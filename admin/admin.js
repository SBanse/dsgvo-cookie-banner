jQuery(function ($) {
    var __ = function (key) {
        return (DCBAdmin.i18n && DCBAdmin.i18n[key]) ? DCBAdmin.i18n[key] : key;
    };

    /* ── Settings Tabs ── */
    $('.dcb-tab').on('click', function () {
        $('.dcb-tab').removeClass('active');
        $('.dcb-tab-content').removeClass('active');
        $(this).addClass('active');
        $('#tab-' + $(this).data('tab')).addClass('active');
    });

    /* ── Cookie Scan ── */
    /* ── Cookie-Scan (zweiphasig: Server + Browser) ── */
    var dcbScanOrigin = window.location.origin;
    var dcbBrowserCookies = [];
    var dcbBrowserDone    = false;

    function dcbSetStep(id, state, text) {
        var $el = $('#' + id);
        $el.removeClass('active done error');
        if (state === 'active') $el.addClass('active').html('🔄 ' + (text || $el.text().replace(/^[^ ]+ /, '')));
        if (state === 'done')   $el.addClass('done').html('✅ ' + (text || $el.text().replace(/^[^ ]+ /, '')));
        if (state === 'error')  $el.addClass('error').html('⚠️ ' + (text || $el.text().replace(/^[^ ]+ /, '')));
    }
    function dcbProgress(pct) {
        $('#dcb-progress-bar').css('width', pct + '%');
    }

    // postMessage-Listener: empfängt Cookies vom Scan-iframe
    window.addEventListener('message', function(e) {
        if (!e.data || !e.data.dcb_scan) return;
        // Alle gemeldeten Cookies sammeln (3 Meldungen: 0.5s, 5s, 8s)
        if (Array.isArray(e.data.cookies)) {
            e.data.cookies.forEach(function(c) {
                if (dcbBrowserCookies.indexOf(c) === -1) dcbBrowserCookies.push(c);
            });
        }
    });

    $('#dcb-run-scan').on('click', function () {
        var $btn    = $(this);
        var $status = $('#dcb-scan-status');
        $btn.prop('disabled', true);
        dcbBrowserCookies = [];
        dcbBrowserDone    = false;

        $('#dcb-scan-progress').show();
        $status.css('color','').text('');
        dcbProgress(0);
        ['step-server','step-browser','step-wait','step-match'].forEach(function(id) {
            $('#'+id).removeClass('active done error');
        });

        // ─ Phase 1: Server-Scan ─────────────────────────────────────────────
        dcbSetStep('step-server', 'active', 'Server-Scan läuft (Plugins, Dateien, Options)…');
        dcbProgress(10);

        $.ajax({
            url:     DCBAdmin.ajax_url,
            type:    'POST',
            timeout: 25000,
            data:    { action: 'dcb_scan', nonce: DCBAdmin.nonce },
            success: function(raw) {
                var res;
                try { res = typeof raw === 'object' ? raw : JSON.parse(raw); }
                catch(e) {
                    var idx = typeof raw === 'string' ? raw.indexOf('{"') : -1;
                    if (idx >= 0) try { res = JSON.parse(raw.substring(idx)); } catch(e2) {}
                }
                if (res && res.success) {
                    dcbSetStep('step-server', 'done', 'Server-Scan: ' + res.data.count + ' Einträge');
                } else {
                    dcbSetStep('step-server', 'error', 'Server-Scan fehlgeschlagen');
                }
                dcbProgress(25);
                startBrowserScan($btn, $status);
            },
            error: function(xhr, status) {
                dcbSetStep('step-server', 'error', 'Server-Scan: ' + status);
                dcbProgress(25);
                startBrowserScan($btn, $status); // trotzdem weiter
            }
        });
    });

    // ─ Phase 2: Browser-Scan (alle öffentlichen Seiten) ───────────────────────
    function startBrowserScan($btn, $status) {
        dcbSetStep('step-browser', 'active', 'Browser-Scan: URLs werden ermittelt…');
        dcbProgress(30);

        $.ajax({
            url:     DCBAdmin.ajax_url,
            type:    'POST',
            timeout: 10000,
            data:    { action: 'dcb_get_scan_url', nonce: DCBAdmin.nonce },
            success: function(res) {
                if (!res || !res.success || !res.data.urls || !res.data.urls.length) {
                    dcbSetStep('step-browser', 'error', 'Scan-URLs nicht verfügbar');
                    dcbProgress(100);
                    finishScan($btn, $status, false);
                    return;
                }
                var urls    = res.data.urls;   // [{url, label}, ...]
                var total   = urls.length;
                var current = 0;
                var $frame  = $('#dcb-scan-frame');
                var DWELL   = 5000; // ms pro Seite — genug für Drittanbieter-Scripts

                dcbSetStep('step-browser', 'active',
                    'Seite ' + (current + 1) + '/' + total + ' wird geladen…');
                dcbProgress(35);

                function loadNext() {
                    if (current >= total) {
                        // Alle Seiten abgearbeitet
                        $frame.attr('src', 'about:blank');
                        dcbSetStep('step-browser', 'done',
                            'Browser-Scan abgeschlossen (' + total + ' Seiten, ' + dcbBrowserCookies.length + ' Cookies)');
                        dcbSetStep('step-wait', 'done', 'Wartezeit abgeschlossen');
                        dcbProgress(82);
                        submitBrowserCookies($btn, $status);
                        return;
                    }

                    var entry = urls[current];
                    dcbSetStep('step-browser', 'active',
                        'Seite ' + (current + 1) + '/' + total + ': ' + entry.label);
                    dcbSetStep('step-wait', 'active',
                        'Scripts laden… <span id="dcb-countdown">' + Math.round(DWELL/1000) + '</span>s');
                    dcbProgress(35 + Math.round(current / total * 45));

                    $frame.attr('src', entry.url);

                    // Countdown für diese Seite
                    var secs = Math.round(DWELL / 1000);
                    var iv = setInterval(function() {
                        secs--;
                        $('#dcb-countdown').text(Math.max(0, secs));
                        if (secs <= 0) clearInterval(iv);
                    }, 1000);

                    setTimeout(function() {
                        clearInterval(iv);
                        $frame.attr('src', 'about:blank');
                        current++;
                        // Kurze Pause zwischen Seiten damit der iframe sich entlädt
                        setTimeout(loadNext, 400);
                    }, DWELL + 200);
                }

                loadNext();
            },
            error: function() {
                dcbSetStep('step-browser', 'error', 'Browser-Scan: Verbindungsfehler');
                dcbProgress(100);
                finishScan($btn, $status, false);
            }
        });
    }

    // ─ Phase 3: Browser-Cookies ans Backend schicken ─────────────────────────
    function submitBrowserCookies($btn, $status) {
        dcbSetStep('step-match', 'active', 'Cookies werden ausgewertet…');
        dcbProgress(88);

        $.ajax({
            url:     DCBAdmin.ajax_url,
            type:    'POST',
            timeout: 15000,
            data:    {
                action:  'dcb_browser_scan',
                nonce:   DCBAdmin.nonce,
                cookies: dcbBrowserCookies.join(','),
                ls_keys: ''
            },
            success: function(res) {
                if (res && res.success) {
                    var d = res.data;
                    var details = d.matched + ' erkannt, ' + d.unknown + ' unbekannt';
                    dcbSetStep('step-match', 'done', 'Ergebnis: ' + d.total + ' Cookies (' + details + ')');
                    dcbProgress(100);
                    $status.css('color', 'green').html(
                        '✅ Scan abgeschlossen: <strong>' + d.total + ' Cookies</strong> gefunden' +
                        (d.unknown > 0 ? ' (' + d.unknown + ' unbekannte bitte prüfen)' : '')
                    );
                    setTimeout(function() { location.reload(); }, 2200);
                } else {
                    dcbSetStep('step-match', 'error', 'Auswertung fehlgeschlagen');
                    dcbProgress(100);
                    finishScan($btn, $status, false);
                }
            },
            error: function() {
                dcbSetStep('step-match', 'error', 'Verbindungsfehler bei Auswertung');
                dcbProgress(100);
                finishScan($btn, $status, false);
            }
        });
    }

    function finishScan($btn, $status, success) {
        if (!success) {
            $status.css('color', 'orange').text('⚠️ Scan teilweise abgeschlossen. Seite wird neu geladen…');
            setTimeout(function() { location.reload(); }, 2000);
        }
        $btn.prop('disabled', false);
    }


    /* ── Inline Edit: Open ── */
    $(document).on('click', '.dcb-edit-btn', function () {
        var $row = $(this).closest('tr');
        $('.dcb-cookie-row.dcb-editing').each(function () {
            closeEditRow($(this), false);
        });
        openEditRow($row);
    });

    /* ── Inline Edit: Cancel ── */
    $(document).on('click', '.dcb-cancel-btn', function () {
        closeEditRow($(this).closest('tr'), false);
    });

    /* ── Inline Edit: Save ── */
    $(document).on('click', '.dcb-save-btn', function () {
        var $row = $(this).closest('tr');
        var key  = $row.data('key');
        var data = {
            action:     'dcb_update_cookie',
            nonce:      DCBAdmin.nonce,
            cookie_key: key,
            name:       $row.find('.dcb-in-name').val().trim(),
            provider:   $row.find('.dcb-in-provider').val().trim(),
            purpose:    $row.find('.dcb-in-purpose').val().trim(),
            duration:   $row.find('.dcb-in-duration').val().trim(),
            category:   $row.find('.dcb-in-category').val(),
        };

        if (!data.name) {
            alert(__('name_required'));
            return;
        }

        var $btn = $(this).prop('disabled', true).text(__('btn_saving'));

        $.post(DCBAdmin.ajax_url, data, function (res) {
            if (res.success) {
                closeEditRow($row, res.data.cookie);
            } else {
                alert(__('save_error'));
                $btn.prop('disabled', false).text(__('btn_save'));
            }
        }).fail(function () {
            alert(__('save_connection_error'));
            $btn.prop('disabled', false).text(__('btn_save'));
        });
    });

    /* ── Delete (einzelner Cookie) ── */
    $(document).on('click', '.dcb-delete-cookie', function () {
        if (!confirm(__('delete_confirm'))) return;
        var $btn = $(this);
        var key  = $btn.data('key');
        $btn.prop('disabled', true).text('…');

        $.ajax({
            url:      DCBAdmin.ajax_url,
            type:     'POST',
            dataType: 'json',
            data:     { action: 'dcb_delete_cookie', nonce: DCBAdmin.nonce, cookie_key: key },
            success: function (res) {
                if (res && res.success) {
                    var $row     = $btn.closest('tr');
                    var $section = $row.closest('.dcb-cat-section');
                    $row.fadeOut(250, function () {
                        $(this).remove();
                        if ($section.find('.dcb-cookie-row').length === 0) {
                            $section.fadeOut(200, function () { $(this).remove(); });
                        } else {
                            var n = $section.find('.dcb-cookie-row').length;
                            $section.find('.dcb-cat-count').text(n + ' Cookie' + (n !== 1 ? 's' : ''));
                        }
                        updateCounts();
                    });
                } else {
                    $btn.prop('disabled', false).text(__('btn_delete'));
                    var msg = (res && res.data && res.data.message) ? res.data.message : 'Unbekannter Fehler';
                    alert('Fehler: ' + msg);
                }
            },
            error: function (xhr) {
                // Trotz HTTP-Fehler kann valides JSON im Body stecken (z.B. durch Plugin-Output-Kontamination)
                var res = null;
                try { res = JSON.parse(xhr.responseText); } catch(e) {
                    var idx = (xhr.responseText || '').indexOf('{"');
                    if (idx >= 0) try { res = JSON.parse(xhr.responseText.substring(idx)); } catch(e2) {}
                }
                if (res && res.success) {
                    // Trotz HTTP-Fehlercode hat der Server erfolgreich gelöscht
                    var $row     = $btn.closest('tr');
                    var $section = $row.closest('.dcb-cat-section');
                    $row.fadeOut(250, function () {
                        $(this).remove();
                        if ($section.find('.dcb-cookie-row').length === 0) {
                            $section.fadeOut(200, function () { $(this).remove(); });
                        } else {
                            var n = $section.find('.dcb-cookie-row').length;
                            $section.find('.dcb-cat-count').text(n + ' Cookie' + (n !== 1 ? 's' : ''));
                        }
                        updateCounts();
                    });
                    return;
                }
                $btn.prop('disabled', false).text(__('btn_delete'));
                var errMsg = (res && res.data && res.data.message)
                    ? res.data.message
                    : 'HTTP ' + xhr.status + ' – bitte Seite neu laden.';
                alert('Fehler beim Löschen: ' + errMsg);
                console.error('[DCB delete]', xhr.status, xhr.responseText ? xhr.responseText.substring(0, 300) : '');
            }
        });
    });

    /* ── Reset (gesamte Scanner-Liste leeren) ── */
    $('#dcb-reset-scan').on('click', function () {
        if (!confirm('Möchten Sie wirklich alle automatisch erkannten Cookies aus der Liste entfernen? Manuell hinzugefügte Cookies bleiben erhalten.')) return;
        var $btn = $(this).prop('disabled', true).text('Wird zurückgesetzt…');

        $.post(DCBAdmin.ajax_url, { action: 'dcb_reset_scan', nonce: DCBAdmin.nonce }, function (res) {
            if (res && res.success) {
                location.reload();
            } else {
                $btn.prop('disabled', false).text('Scanner-Liste zurücksetzen');
                alert('Zurücksetzen fehlgeschlagen.');
            }
        }).fail(function () {
            $btn.prop('disabled', false).text('Scanner-Liste zurücksetzen');
            alert('Verbindungsfehler.');
        });
    });

    /* ── Add cookie form toggle ── */
    $('#dcb-add-row-btn').on('click', function () {
        $('#dcb-add-row-form').slideDown(200);
        $('html, body').animate({ scrollTop: $('#dcb-add-row-form').offset().top - 60 }, 300);
        $('#mc-name').focus();
    });

    $('#dcb-cancel-add').on('click', function () {
        $('#dcb-add-row-form').slideUp(200);
        clearAddForm();
    });

    /* ── Add cookie: Save ── */
    $('#dcb-add-manual').on('click', function () {
        var name = $('#mc-name').val().trim();
        if (!name) { alert(__('name_required')); $('#mc-name').focus(); return; }

        var $btn = $(this).prop('disabled', true).text(__('btn_saving'));
        $('#dcb-manual-status').text('');

        $.post(DCBAdmin.ajax_url, {
            action:          'dcb_save_manual_cookie',
            nonce:           DCBAdmin.nonce,
            cookie_name:     name,
            cookie_category: $('#mc-category').val(),
            cookie_provider: $('#mc-provider').val().trim(),
            cookie_purpose:  $('#mc-purpose').val().trim(),
            cookie_duration: $('#mc-duration').val().trim(),
        }, function (res) {
            if (res.success) {
                $('#dcb-manual-status').css('color', 'green').text(__('save_success'));
                setTimeout(function () { location.reload(); }, 1000);
            } else {
                $('#dcb-manual-status').css('color', 'red').text(__('save_error'));
                $btn.prop('disabled', false).text(__('btn_save_cookie'));
            }
        }).fail(function () {
            $('#dcb-manual-status').css('color', 'red').text(__('save_connection_error'));
            $btn.prop('disabled', false).text(__('btn_save_cookie'));
        });
    });

    /* ── Language card highlight ── */
    $(document).on('change', 'input[name$="[plugin_language]"]', function () {
        $('.dcb-lang-card').removeClass('dcb-lang-active');
        $(this).closest('.dcb-lang-card').addClass('dcb-lang-active');
    });

    /* ── Helpers ── */
    function openEditRow($row) {
        $row.addClass('dcb-editing');
        $row.find('.dcb-view').hide();
        $row.find('.dcb-edit').show();
        $row.find('.dcb-in-name').focus();
    }

    function closeEditRow($row, updated) {
        $row.removeClass('dcb-editing');
        if (updated) {
            var catLabel = updated.category;
            if (DCBAdmin.i18n && DCBAdmin.i18n['cat_' + updated.category + '_label']) {
                catLabel = DCBAdmin.i18n['cat_' + updated.category + '_label'];
            }
            $row.find('.dcb-view-name').html('<code>' + esc(updated.name) + '</code>');
            $row.find('.dcb-view-provider').text(updated.provider);
            $row.find('.dcb-view-purpose').text(updated.purpose);
            $row.find('.dcb-view-duration').text(updated.duration);
            $row.find('.dcb-view-category').html(
                '<span class="dcb-cat-badge dcb-badge-' + esc(updated.category) + '">' + esc(catLabel) + '</span>'
            );
            $row.find('.dcb-view').css('background', '#d4edda').show();
            setTimeout(function () { $row.find('.dcb-view').css('background', ''); }, 1400);
        } else {
            $row.find('.dcb-view').show();
        }
        $row.find('.dcb-edit').hide();
        $row.find('.dcb-save-btn').prop('disabled', false).text(__('btn_save'));
    }

    function clearAddForm() {
        $('#mc-name, #mc-provider, #mc-duration').val('');
        $('#mc-purpose').val('');
        $('#mc-category').prop('selectedIndex', 0);
        $('#dcb-manual-status').text('');
        $('#dcb-add-manual').prop('disabled', false).text(__('btn_save_cookie'));
    }

    function updateCounts() {
        // Gesamt-Zähler
        var total = $('.dcb-cookie-row').length;
        $('.dcb-table-header h2').text(
            __('cookie_list_title') + ' (' + total + ' ' + __('cookie_list_entries') + ')'
        );
        // Kategorie-Zähler
        $('.dcb-cat-section').each(function () {
            var n = $(this).find('.dcb-cookie-row').length;
            $(this).find('.dcb-cat-count').text(n + ' Cookie' + (n !== 1 ? 's' : ''));
        });
    }

    function esc(str) {
        return String(str || '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /* ── Category key live preview ── */
    $(document).on('input', '.dcb-cat-key-group input[name*="[shortcode_key]"]', function () {
        var val = $(this).val().replace(/[^a-z0-9_-]/gi, '').toLowerCase() || '…';
        $(this).closest('.dcb-cat-key-group').find('.dcb-key-preview-sc').text(val);
    });

    $(document).on('input', '.dcb-cat-key-group input[name*="[block_key]"]', function () {
        var val = $(this).val().replace(/[^a-z0-9_-]/gi, '').toLowerCase() || '…';
        $(this).closest('.dcb-cat-key-group').find('.dcb-key-preview-bk').text(val);
    });

    $(document).on('blur', '.dcb-key-input:not([readonly])', function () {
        var clean = $(this).val().replace(/[^a-z0-9_-]/gi, '').toLowerCase();
        if (!clean) { clean = $(this).data('original') || ''; }
        $(this).val(clean);
    });

    $(document).on('focus', '.dcb-key-input:not([readonly])', function () {
        if (!$(this).data('original')) { $(this).data('original', $(this).val()); }
    });

});

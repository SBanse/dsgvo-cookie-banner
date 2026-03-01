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
    $('#dcb-run-scan').on('click', function () {
        var $btn = $(this);
        $('#dcb-scan-status').text(__('scan_running'));
        $btn.prop('disabled', true);

        $.ajax({
            url:  DCBAdmin.ajax_url,
            type: 'POST',
            data: { action: 'dcb_scan', nonce: DCBAdmin.nonce },
            success: function (raw) {
                var res;
                try { res = (typeof raw === 'object') ? raw : JSON.parse(raw); }
                catch(e) {
                    // Manchmal steht PHP-Output vor dem JSON – JSON-Teil extrahieren
                    var jsonStart = typeof raw === 'string' ? raw.indexOf('{"') : -1;
                    if (jsonStart > 0) {
                        try { res = JSON.parse(raw.substring(jsonStart)); } catch(e2) {}
                    }
                    if (!res) {
                        $('#dcb-scan-status').css('color','red').text('❌ Ungültige Server-Antwort');
                        console.error('[DCB Scan] Keine JSON-Antwort:', typeof raw === 'string' ? raw.substring(0,300) : raw);
                        $btn.prop('disabled', false); return;
                    }
                }
                if (res && res.success) {
                    var msg = __('scan_done').replace('%d', res.data.count);
                    $('#dcb-scan-status').css('color','green').text(msg);
                    setTimeout(function () { location.reload(); }, 1200);
                } else {
                    var errMsg = (res && res.data && res.data.message) ? res.data.message : __('scan_error');
                    $('#dcb-scan-status').css('color','red').text('❌ ' + errMsg);
                    console.error('[DCB Scan] Server-Fehler:', res);
                    $btn.prop('disabled', false);
                }
            },
            error: function (xhr, status, err) {
                // Prüfen ob trotz Fehler-Status valides JSON in der Antwort steckt
                var raw = xhr.responseText || '';
                var res = null;
                var jsonStart = raw.indexOf('{"');
                if (jsonStart >= 0) {
                    try { res = JSON.parse(raw.substring(jsonStart)); } catch(e) {}
                }
                if (res && res.success) {
                    // Scan hat funktioniert – der "Fehler" war nur schmutziger Output davor
                    var msg = __('scan_done').replace('%d', res.data.count);
                    $('#dcb-scan-status').css('color','green').text(msg);
                    setTimeout(function () { location.reload(); }, 1200);
                    return;
                }
                var errMsg = res && res.data && res.data.message ? res.data.message : ('Verbindungsfehler (' + status + ')');
                $('#dcb-scan-status').css('color','red').text('❌ ' + errMsg);
                console.error('[DCB Scan] AJAX-Fehler:', status, err);
                console.error('[DCB Scan] Server-Antwort:', raw.substring(0, 300));
                $btn.prop('disabled', false);
            }
        });
    });

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

    /* ── Delete ── */
    $(document).on('click', '.dcb-delete-cookie', function () {
        if (!confirm(__('delete_confirm'))) return;
        var $btn = $(this);
        var key  = $btn.data('key');
        $.post(DCBAdmin.ajax_url, { action: 'dcb_delete_cookie', nonce: DCBAdmin.nonce, cookie_key: key }, function (res) {
            if (res.success) {
                $btn.closest('tr').fadeOut(300, function () { $(this).remove(); updateCounts(); });
            }
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
        var total = $('.dcb-cookie-row').length;
        $('.dcb-table-header h2').text(
            __('cookie_list_title') + ' (' + total + ' ' + __('cookie_list_entries') + ')'
        );
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

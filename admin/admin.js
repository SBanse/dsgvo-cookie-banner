jQuery(function ($) {

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
        $('#dcb-scan-status').text('⏳ Scanne…');
        $btn.prop('disabled', true);
        $.post(DCBAdmin.ajax_url, { action: 'dcb_scan', nonce: DCBAdmin.nonce }, function (res) {
            if (res.success) {
                $('#dcb-scan-status').text('✅ ' + res.data.count + ' Cookies gefunden. Seite wird neu geladen…');
                setTimeout(function () { location.reload(); }, 1200);
            } else {
                $('#dcb-scan-status').text('❌ Fehler beim Scan.');
                $btn.prop('disabled', false);
            }
        }).fail(function () {
            $('#dcb-scan-status').text('❌ Verbindungsfehler.');
            $btn.prop('disabled', false);
        });
    });

    /* ── Inline Edit: Open ── */
    $(document).on('click', '.dcb-edit-btn', function () {
        var $row = $(this).closest('tr');
        // Close any other open row first
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
            action:   'dcb_update_cookie',
            nonce:    DCBAdmin.nonce,
            cookie_key: key,
            name:     $row.find('.dcb-in-name').val().trim(),
            provider: $row.find('.dcb-in-provider').val().trim(),
            purpose:  $row.find('.dcb-in-purpose').val().trim(),
            duration: $row.find('.dcb-in-duration').val().trim(),
            category: $row.find('.dcb-in-category').val(),
        };

        if (!data.name) {
            alert('Bitte einen Cookie-Namen angeben.');
            return;
        }

        var $btn = $(this).prop('disabled', true).text('⏳ Speichern…');

        $.post(DCBAdmin.ajax_url, data, function (res) {
            if (res.success) {
                closeEditRow($row, res.data.cookie);
            } else {
                alert('Fehler beim Speichern.');
                $btn.prop('disabled', false).text('💾 Speichern');
            }
        }).fail(function () {
            alert('Verbindungsfehler.');
            $btn.prop('disabled', false).text('💾 Speichern');
        });
    });

    /* ── Delete ── */
    $(document).on('click', '.dcb-delete-cookie', function () {
        if (!confirm('Diesen Cookie-Eintrag wirklich löschen?')) return;
        var $btn = $(this);
        var key  = $btn.data('key');
        $.post(DCBAdmin.ajax_url, { action: 'dcb_delete_cookie', nonce: DCBAdmin.nonce, cookie_key: key }, function (res) {
            if (res.success) {
                $btn.closest('tr').fadeOut(300, function () { $(this).remove(); updateCounts(); });
            }
        });
    });

    /* ── Add new cookie form toggle ── */
    $('#dcb-add-row-btn').on('click', function () {
        $('#dcb-add-row-form').slideDown(200);
        $('html, body').animate({ scrollTop: $('#dcb-add-row-form').offset().top - 60 }, 300);
        $('#mc-name').focus();
    });

    $('#dcb-cancel-add').on('click', function () {
        $('#dcb-add-row-form').slideUp(200);
        clearAddForm();
    });

    /* ── Add new cookie: Save ── */
    $('#dcb-add-manual').on('click', function () {
        var name = $('#mc-name').val().trim();
        if (!name) { alert('Bitte einen Cookie-Namen angeben.'); $('#mc-name').focus(); return; }

        var $btn = $(this).prop('disabled', true).text('⏳ Speichern…');
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
                $('#dcb-manual-status').text('✅ Gespeichert! Seite wird neu geladen…');
                setTimeout(function () { location.reload(); }, 1000);
            } else {
                $('#dcb-manual-status').css('color', 'red').text('❌ Fehler beim Speichern.');
                $btn.prop('disabled', false).text('✅ Cookie speichern');
            }
        }).fail(function () {
            $('#dcb-manual-status').css('color', 'red').text('❌ Verbindungsfehler.');
            $btn.prop('disabled', false).text('✅ Cookie speichern');
        });
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
            // Update view cells with saved data
            var catLabel = updated.category;
            if (typeof DCBCategories !== 'undefined' && DCBCategories[updated.category]) {
                catLabel = DCBCategories[updated.category];
            }
            $row.find('.dcb-view-name').html('<code>' + esc(updated.name) + '</code>');
            $row.find('.dcb-view-provider').text(updated.provider);
            $row.find('.dcb-view-purpose').text(updated.purpose);
            $row.find('.dcb-view-duration').text(updated.duration);
            $row.find('.dcb-view-category').html('<span class="dcb-cat-badge dcb-badge-' + esc(updated.category) + '">' + esc(catLabel) + '</span>');
            // Mark as manually edited
            $row.find('.dcb-view:nth-child(6)').html('<span title="Manuell / bearbeitet">✏️</span>');
            // Flash feedback
            $row.find('.dcb-view').css('background', '#d4edda').show();
            setTimeout(function () { $row.find('.dcb-view').css('background', ''); }, 1200);
        } else {
            $row.find('.dcb-view').show();
        }
        $row.find('.dcb-edit').hide();
        $row.find('.dcb-save-btn').prop('disabled', false).text('💾 Speichern');
    }

    function clearAddForm() {
        $('#mc-name, #mc-provider, #mc-duration').val('');
        $('#mc-purpose').val('');
        $('#mc-category').prop('selectedIndex', 0);
        $('#dcb-manual-status').text('');
        $('#dcb-add-manual').prop('disabled', false).text('✅ Cookie speichern');
    }

    function updateCounts() {
        // Update header count after deletion
        var total = $('.dcb-cookie-row').length;
        $('.dcb-table-header h2').text('Cookie-Liste (' + total + ' Einträge)');
    }

    function esc(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
});

jQuery(function($) {
    // Tabs
    $('.dcb-tab').on('click', function() {
        $('.dcb-tab').removeClass('active');
        $('.dcb-tab-content').removeClass('active');
        $(this).addClass('active');
        $('#tab-' + $(this).data('tab')).addClass('active');
    });

    // Scan
    $('#dcb-run-scan').on('click', function() {
        $('#dcb-scan-status').text('Scanne…');
        $(this).prop('disabled', true);
        $.post(DCBAdmin.ajax_url, { action: 'dcb_scan', nonce: DCBAdmin.nonce }, function(res) {
            if (res.success) {
                $('#dcb-scan-status').text('✅ ' + res.data.count + ' Cookies gefunden. Seite wird neu geladen…');
                setTimeout(() => location.reload(), 1500);
            }
        }).always(() => $('#dcb-run-scan').prop('disabled', false));
    });

    // Manuell hinzufügen
    $('#dcb-add-manual').on('click', function() {
        const name = $('#mc-name').val().trim();
        if (!name) { alert('Bitte Cookie-Name angeben.'); return; }
        $.post(DCBAdmin.ajax_url, {
            action: 'dcb_save_manual_cookie',
            nonce: DCBAdmin.nonce,
            cookie_name: name,
            cookie_category: $('#mc-category').val(),
            cookie_provider: $('#mc-provider').val(),
            cookie_purpose: $('#mc-purpose').val(),
            cookie_duration: $('#mc-duration').val(),
        }, function(res) {
            if (res.success) {
                $('#dcb-manual-status').text('✅ Gespeichert');
                setTimeout(() => location.reload(), 1000);
            }
        });
    });

    // Löschen
    $(document).on('click', '.dcb-delete-cookie', function() {
        if (!confirm('Cookie-Eintrag löschen?')) return;
        const key = $(this).data('key');
        $.post(DCBAdmin.ajax_url, { action: 'dcb_delete_cookie', nonce: DCBAdmin.nonce, cookie_key: key }, function(res) {
            if (res.success) location.reload();
        });
    });
});

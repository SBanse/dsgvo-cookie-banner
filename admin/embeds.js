/* DCB Embeds – Admin JS */
jQuery(function ($) {

    var lang = typeof DCBAdmin !== 'undefined' && DCBAdmin.i18n ? DCBAdmin.i18n : {};
    var de = (document.documentElement.lang || '').startsWith('de') || (DCBAdmin && DCBAdmin.i18n && DCBAdmin.i18n['btn_save'] === 'Speichern');

    function t(key, fallback) { return lang[key] || fallback; }
    function msg_saving()  { return de ? '⏳ Speichern…' : '⏳ Saving…'; }
    function msg_saved()   { return de ? '✅ Gespeichert' : '✅ Saved'; }
    function msg_error()   { return de ? '❌ Fehler'       : '❌ Error'; }

    /* ── Toggle enabled/disabled ── */
    $(document).on('click', '.dcb-embed-toggle', function () {
        var $btn   = $(this);
        var id     = $btn.data('id');
        var enable = $btn.data('enabled') === '1' ? 0 : 1;

        $.post(DCBAdmin.ajax_url, {
            action:   'dcb_embed_toggle',
            nonce:    DCBAdmin.nonce,
            embed_id: id,
            enabled:  enable
        }, function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert(msg_error());
            }
        });
    });

    /* ── Edit form open/close ── */
    $(document).on('click', '.dcb-embed-edit-btn', function () {
        var id      = $(this).data('id');
        var $form   = $('#dcb-embed-form-' + id);
        var $others = $('.dcb-embed-edit-form').not($form);

        $others.slideUp(150);
        $form.slideToggle(200, function () {
            if ($form.is(':visible')) {
                $('html,body').animate({ scrollTop: $form.offset().top - 80 }, 200);
            }
        });
    });

    $(document).on('click', '.dcb-embed-cancel-btn', function () {
        $('#dcb-embed-form-' + $(this).data('id')).slideUp(150);
    });

    /* ── Live preview inside edit form ── */
    function updatePreview($form) {
        var lang_suffix = '_' + (DCBAdmin.lang || 'de');
        var title   = $form.find('.ef-title' + lang_suffix).val() || $form.find('.ef-title-de').val();
        var text    = $form.find('.ef-text'  + lang_suffix).val() || $form.find('.ef-text-de').val();
        var btn     = $form.find('.ef-btn'   + lang_suffix).val() || $form.find('.ef-btn-de').val();
        var always  = $form.find('.ef-always'+ lang_suffix).val() || $form.find('.ef-always-de').val();
        var icon    = $form.find('.ef-icon').val();
        var bg      = $form.find('.ef-bg-color').val();
        var accent  = $form.find('.ef-accent-color').val();
        var tc      = $form.find('.ef-text-color').val();

        var $prev = $form.find('.dcb-embed-placeholder');
        $prev.css('--dcb-embed-bg', bg);
        $prev.css('--dcb-embed-accent', accent);
        $prev.css('--dcb-embed-tc', tc);
        // Set inline styles for CSS vars (jQuery can't set custom props directly)
        $prev[0].style.setProperty('--dcb-embed-bg', bg);
        $prev[0].style.setProperty('--dcb-embed-accent', accent);
        $prev[0].style.setProperty('--dcb-embed-tc', tc);

        $form.find('.dcb-prev-icon').text(icon);
        $form.find('.dcb-prev-title').text(title);
        $form.find('.dcb-prev-text').text(text);
        $form.find('.dcb-prev-btn').text(btn);
        $form.find('.dcb-prev-always').text(always);

        // Update card preview strip
        var $card = $form.closest('.dcb-embed-card');
        var $strip = $card.find('.dcb-embed-preview-strip');
        $strip[0].style.setProperty('--dcb-embed-bg', bg);
        $strip[0].style.setProperty('--dcb-embed-accent', accent);
        $strip[0].style.setProperty('--dcb-embed-tc', tc);
        $strip.find('.dcb-embed-prev-icon').text(icon);
        $strip.find('.dcb-embed-prev-title').text(title);
        $strip.find('.dcb-embed-prev-btn').text(btn);
    }

    $(document).on('input change', '.dcb-embed-edit-form input, .dcb-embed-edit-form textarea, .dcb-embed-edit-form select', function () {
        updatePreview($(this).closest('.dcb-embed-edit-form'));
    });

    /* ── Save embed ── */
    $(document).on('click', '.dcb-embed-save-btn', function () {
        var $btn  = $(this);
        var id    = $btn.data('id');
        var $form = $('#dcb-embed-form-' + id);
        var $status = $form.find('.dcb-embed-save-status');

        $btn.prop('disabled', true).text(msg_saving());
        $status.text('');

        var data = {
            action:               'dcb_embed_save',
            nonce:                DCBAdmin.nonce,
            embed_id:             id,
            label:                $form.find('.ef-label').val(),
            category:             $form.find('.ef-category').val(),
            icon:                 $form.find('.ef-icon').val(),
            bg_color:             $form.find('.ef-bg-color').val(),
            accent_color:         $form.find('.ef-accent-color').val(),
            text_color:           $form.find('.ef-text-color').val(),
            placeholder_title_de: $form.find('.ef-title-de').val(),
            placeholder_title_en: $form.find('.ef-title-en').val(),
            placeholder_text_de:  $form.find('.ef-text-de').val(),
            placeholder_text_en:  $form.find('.ef-text-en').val(),
            btn_text_de:          $form.find('.ef-btn-de').val(),
            btn_text_en:          $form.find('.ef-btn-en').val(),
            always_text_de:       $form.find('.ef-always-de').val(),
            always_text_en:       $form.find('.ef-always-en').val(),
        };

        $.post(DCBAdmin.ajax_url, data, function (res) {
            if (res.success) {
                $status.css('color', 'green').text(msg_saved());
                // Update card title
                $btn.closest('.dcb-embed-card').find('.dcb-embed-card-title strong').text(data.label);
                $btn.prop('disabled', false).text(de ? '💾 Speichern' : '💾 Save');
                setTimeout(function() { $status.text(''); }, 3000);
            } else {
                $status.css('color', 'red').text(msg_error());
                $btn.prop('disabled', false).text(de ? '💾 Speichern' : '💾 Save');
            }
        }).fail(function () {
            $status.css('color', 'red').text(msg_error());
            $btn.prop('disabled', false).text(de ? '💾 Speichern' : '💾 Save');
        });
    });

    /* ── Reset to default ── */
    $(document).on('click', '.dcb-embed-reset-btn', function () {
        var id  = $(this).data('id');
        var msg = de ? 'Wirklich auf Standard zurücksetzen?' : 'Reset to default?';
        if (!confirm(msg)) return;

        $.post(DCBAdmin.ajax_url, {
            action:   'dcb_embed_reset',
            nonce:    DCBAdmin.nonce,
            embed_id: id,
        }, function (res) {
            if (res.success) location.reload();
            else alert(msg_error());
        });
    });

    /* ── Delete custom embed ── */
    $(document).on('click', '.dcb-embed-delete', function () {
        var id  = $(this).data('id');
        var msg = de ? 'Diesen Einbettungs-Typ wirklich löschen?' : 'Really delete this embed type?';
        if (!confirm(msg)) return;

        $.post(DCBAdmin.ajax_url, {
            action:   'dcb_embed_delete',
            nonce:    DCBAdmin.nonce,
            embed_id: id,
        }, function (res) {
            if (res.success) {
                $('#dcb-embed-card-' + id).fadeOut(300, function () { $(this).remove(); });
            } else {
                alert(msg_error());
            }
        });
    });

    /* ── Add new type ── */
    $('#dcb-embed-add-btn').on('click', function () {
        $('#dcb-embed-add-form').slideDown(200);
        $(this).hide();
        $('#new-embed-id').focus();
    });

    $('#dcb-embed-add-cancel').on('click', function () {
        $('#dcb-embed-add-form').slideUp(200);
        $('#dcb-embed-add-btn').show();
        clearAddForm();
    });

    $('#dcb-embed-create-btn').on('click', function () {
        var id    = $('#new-embed-id').val().trim().replace(/[^a-z0-9_]/gi, '').toLowerCase();
        var label = $('#new-embed-label').val().trim();
        var $status = $('#dcb-embed-add-status');

        if (!id || !label) {
            $status.css('color','red').text(de ? 'Bitte ID und Bezeichnung angeben.' : 'Please enter ID and label.');
            return;
        }

        var $btn = $(this).prop('disabled', true).text(msg_saving());

        $.post(DCBAdmin.ajax_url, {
            action:   'dcb_embed_create',
            nonce:    DCBAdmin.nonce,
            embed_id: id,
            label:    label,
            category: $('#new-embed-category').val(),
            icon:     $('#new-embed-icon').val() || '▶',
        }, function (res) {
            if (res.success) {
                $status.css('color','green').text(de ? '✅ Erstellt! Seite wird neu geladen…' : '✅ Created! Reloading…');
                setTimeout(function () { location.reload(); }, 1000);
            } else {
                $status.css('color','red').text(res.data && res.data.message ? res.data.message : msg_error());
                $btn.prop('disabled', false).text(de ? '✅ Erstellen' : '✅ Create');
            }
        }).fail(function () {
            $status.css('color','red').text(msg_error());
            $btn.prop('disabled', false).text(de ? '✅ Erstellen' : '✅ Create');
        });
    });

    function clearAddForm() {
        $('#new-embed-id, #new-embed-label').val('');
        $('#new-embed-icon').val('▶');
        $('#dcb-embed-add-status').text('');
        $('#dcb-embed-create-btn').prop('disabled', false);
    }

    /* ── Sanitise ID field live ── */
    $('#new-embed-id').on('input', function () {
        var clean = $(this).val().replace(/[^a-z0-9_]/gi, '').toLowerCase();
        $(this).val(clean);
    });
});

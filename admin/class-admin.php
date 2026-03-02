<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Admin {

    /** Stores the page hook suffixes returned by add_*_page() */
    private $page_hooks = array();

    public function __construct() {
        add_action( 'admin_menu',            array( $this, 'add_menu' ) );
        add_action( 'admin_init',            array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // Cookie AJAX
        add_action( 'wp_ajax_dcb_scan',              array( $this, 'ajax_scan' ) );
        add_action( 'wp_ajax_dcb_browser_scan',      array( $this, 'ajax_browser_scan' ) );
        add_action( 'wp_ajax_dcb_get_scan_url',      array( $this, 'ajax_get_scan_url' ) );
        add_action( 'wp_ajax_dcb_save_manual_cookie', array( $this, 'ajax_save_manual' ) );
        add_action( 'wp_ajax_dcb_update_cookie',     array( $this, 'ajax_update_cookie' ) );
        add_action( 'wp_ajax_dcb_delete_cookie',     array( $this, 'ajax_delete_cookie' ) );
        add_action( 'wp_ajax_dcb_reset_scan',        array( $this, 'ajax_reset_scan' ) );

        // Embed AJAX
        add_action( 'wp_ajax_dcb_embed_save',   array( $this, 'ajax_embed_save' ) );
        add_action( 'wp_ajax_dcb_embed_toggle', array( $this, 'ajax_embed_toggle' ) );
        add_action( 'wp_ajax_dcb_embed_reset',  array( $this, 'ajax_embed_reset' ) );
        add_action( 'wp_ajax_dcb_embed_delete', array( $this, 'ajax_embed_delete' ) );
        add_action( 'wp_ajax_dcb_embed_create', array( $this, 'ajax_embed_create' ) );
        // Debug-Endpunkt (nur für Admins)
        add_action( 'wp_ajax_dcb_debug', array( $this, 'ajax_debug' ) );
    }

    /* ── Temporärer Debug-Handler ──────────────────────────────────────────── */
    public function ajax_debug() {
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
        $stored  = DCB_Cookie_Manager::get_detected_cookies();
        $sources = array();
        foreach ( array_merge( $stored['auto'] ?? array(), $stored['manual'] ?? array() ) as $key => $data ) {
            $sources[ $key ] = array(
                'name'   => $data['name']        ?? $key,
                'source' => $data['_dcb_source'] ?? '(unbekannt)',
            );
        }
        wp_send_json_success( array(
            'page_hooks'     => $this->page_hooks,
            'nonce_valid'    => (bool) wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' ),
            'cookie_sources' => $sources,
        ) );
    }

    /* ── Menu ──────────────────────────────────────────────────────────────── */

    public function add_menu() {
        // Store every hook suffix so enqueue_assets() can match them reliably.
        // WordPress generates hooks like "toplevel_page_dcb-settings" and
        // "cookie-banner_page_dcb-scanner" – NOT the slug itself.
        $this->page_hooks[] = add_menu_page(
            DCB_I18n::t('admin_page_title'),
            DCB_I18n::t('admin_menu_label'),
            'manage_options',
            'dcb-settings',
            array( $this, 'render_settings_page' ),
            'dashicons-privacy',
            85
        );
        $this->page_hooks[] = add_submenu_page( 'dcb-settings', DCB_I18n::t('admin_submenu_settings'), DCB_I18n::t('admin_submenu_settings'), 'manage_options', 'dcb-settings', array( $this, 'render_settings_page' ) );
        $this->page_hooks[] = add_submenu_page( 'dcb-settings', DCB_I18n::t('admin_submenu_scanner'),  DCB_I18n::t('admin_submenu_scanner'),  'manage_options', 'dcb-scanner',  array( $this, 'render_scanner_page' ) );
        $embeds_label = DCB_I18n::get_lang() === 'de' ? '🖼️ Einbettungen' : '🖼️ Embeds';
        $this->page_hooks[] = add_submenu_page( 'dcb-settings', $embeds_label, $embeds_label, 'manage_options', 'dcb-embeds', array( $this, 'render_embeds_page' ) );
        $this->page_hooks[] = add_submenu_page( 'dcb-settings', DCB_I18n::t('admin_submenu_consents'), DCB_I18n::t('admin_submenu_consents'), 'manage_options', 'dcb-consents', array( $this, 'render_consents_page' ) );
    }

    /* ── Settings registration ─────────────────────────────────────────────── */

    public function register_settings() {
        register_setting( 'dcb_options_group', DCB_Cookie_Manager::OPTION_SETTINGS, array(
            'sanitize_callback' => array( $this, 'sanitize_settings' ),
        ) );
    }

    public function sanitize_settings( $input ) {
        $clean = array();

        $allowed_langs = array_keys( DCB_I18n::available_languages() );
        $clean['plugin_language'] = in_array( $input['plugin_language'] ?? '', $allowed_langs, true )
            ? $input['plugin_language']
            : 'de';

        $text_fields = array( 'banner_title', 'banner_text', 'accept_all_text', 'accept_necessary_text', 'customize_text', 'save_settings_text', 'banner_position', 'banner_layout' );
        foreach ( $text_fields as $f ) {
            $clean[ $f ] = sanitize_text_field( $input[ $f ] ?? '' );
        }
        $clean['primary_color']  = sanitize_hex_color( $input['primary_color'] ?? '#0073aa' );
        $clean['text_color']     = sanitize_hex_color( $input['text_color']    ?? '#333333' );
        $clean['bg_color']       = sanitize_hex_color( $input['bg_color']      ?? '#ffffff' );
        $clean['cookie_lifetime']= absint( $input['cookie_lifetime'] ?? 365 );
        $clean['privacy_page_id']= absint( $input['privacy_page_id'] ?? 0 );
        $clean['imprint_page_id']= absint( $input['imprint_page_id'] ?? 0 );
        $clean['auto_block_scripts'] = ! empty( $input['auto_block_scripts'] );
        $clean['log_consents']       = ! empty( $input['log_consents'] );

        $prev      = DCB_Cookie_Manager::get_settings();
        $prev_lang = $prev['plugin_language'] ?? 'de';
        $new_lang  = $clean['plugin_language'];

        if ( $prev_lang !== $new_lang ) {
            $new_defaults = DCB_Cookie_Manager::default_categories( $new_lang );
            $prev_cats    = $prev['categories'] ?? array();
            foreach ( $new_defaults as $key => $cat ) {
                if ( isset( $prev_cats[ $key ] ) ) {
                    $new_defaults[ $key ]['shortcode_key'] = $prev_cats[ $key ]['shortcode_key'] ?? $key;
                    $new_defaults[ $key ]['block_key']     = $prev_cats[ $key ]['block_key']     ?? $key;
                }
            }
            $clean['categories'] = $new_defaults;

            $old_defaults  = DCB_Cookie_Manager::default_settings_for_lang( $prev_lang );
            $lang_defaults = DCB_Cookie_Manager::default_settings_for_lang( $new_lang );
            foreach ( array( 'banner_title', 'banner_text', 'accept_all_text', 'accept_necessary_text', 'customize_text', 'save_settings_text' ) as $f ) {
                if ( $clean[ $f ] === $old_defaults[ $f ] || $clean[ $f ] === $prev[ $f ] ) {
                    $clean[ $f ] = $lang_defaults[ $f ];
                }
            }
        } else {
            $defaults       = DCB_Cookie_Manager::default_categories( $new_lang );
            $submitted_cats = $input['categories'] ?? array();
            $clean_cats     = array();

            foreach ( $defaults as $key => $default_cat ) {
                $sub = $submitted_cats[ $key ] ?? array();
                $clean_cats[ $key ] = array(
                    'label'         => sanitize_text_field(      $sub['label']         ?? $default_cat['label'] ),
                    'description'   => sanitize_textarea_field(  $sub['description']   ?? $default_cat['description'] ),
                    'required'      => (bool)( $default_cat['required'] ),
                    'shortcode_key' => sanitize_key(             $sub['shortcode_key'] ?? $key ),
                    'block_key'     => sanitize_key(             $sub['block_key']     ?? $key ),
                );
                if ( empty( $clean_cats[ $key ]['shortcode_key'] ) ) $clean_cats[ $key ]['shortcode_key'] = $key;
                if ( empty( $clean_cats[ $key ]['block_key'] ) )     $clean_cats[ $key ]['block_key']     = $key;
            }
            $clean['categories'] = $clean_cats;
        }

        return $clean;
    }

    /* ── Assets ────────────────────────────────────────────────────────────── */

    public function enqueue_assets( $hook ) {
        // Robustester Weg: gegen die gespeicherten Hook-Suffixe prüfen.
        // $this->page_hooks enthält genau die Strings, die WordPress für unsere
        // Seiten zurückgibt (z.B. "cookie-banner_page_dcb-scanner").
        // Zusätzlich: Fallback über get_current_screen() für ältere WP-Versionen.
        $is_our_page = in_array( $hook, $this->page_hooks, true );

        if ( ! $is_our_page ) {
            $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
            if ( $screen ) {
                $is_our_page = strpos( $screen->id, 'dcb-' ) !== false
                    || strpos( $screen->base, 'dcb-' ) !== false;
            }
        }

        if ( ! $is_our_page ) return;

        // Base admin assets (alle Plugin-Seiten)
        wp_enqueue_style(  'dcb-admin', DCB_PLUGIN_URL . 'admin/admin.css', array(), DCB_VERSION );
        wp_enqueue_script( 'dcb-admin', DCB_PLUGIN_URL . 'admin/admin.js',  array( 'jquery' ), DCB_VERSION, true );
        wp_localize_script( 'dcb-admin', 'DCBAdmin', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'dcb_admin_nonce' ),
            'lang'     => DCB_I18n::get_lang(),
            'i18n'     => DCB_I18n::all(),
        ) );

        // Embeds-Seite: zusätzliche Assets
        if ( strpos( $hook, 'dcb-embeds' ) !== false ) {
            wp_enqueue_style(  'dcb-embeds-admin', DCB_PLUGIN_URL . 'admin/embeds.css', array(), DCB_VERSION );
            wp_enqueue_style(  'dcb-embeds-front', DCB_PLUGIN_URL . 'public/css/embeds.css', array(), DCB_VERSION );
            wp_enqueue_script( 'dcb-embeds-admin', DCB_PLUGIN_URL . 'admin/embeds.js', array( 'jquery', 'dcb-admin' ), DCB_VERSION, true );
        }
    }

    /* ── Cookie AJAX handlers ──────────────────────────────────────────────── */

    public function ajax_scan() {
        // Alle vorherigen Output-Buffer leeren (PHP-Warnings anderer Plugins etc.)
        while ( ob_get_level() > 0 ) {
            ob_end_clean();
        }

        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => 'Nonce ungültig – bitte Seite neu laden.' ) );
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Keine Berechtigung.' ) );
            return;
        }

        try {
            $before  = count( DCB_Cookie_Manager::get_detected_cookies()['auto'] ?? array() );
            $cookies = DCB_Cookie_Scanner::scan();
            $after   = count( $cookies );
            $new     = max( 0, $after - $before );

            wp_send_json_success( array(
                'count'   => $after,
                'new'     => $new,
                'cookies' => array_values( $cookies ),
            ) );
        } catch ( \Throwable $e ) {
            wp_send_json_error( array( 'message' => 'Scanner-Fehler: ' . $e->getMessage() ) );
        }
    }

    /**
     * Erzeugt ein einmalig gültiges Token und gibt die Scan-URL zurück.
     * Das Token ist 60 Sekunden gültig (Transient).
     */
    public function ajax_get_scan_url() {
        while ( ob_get_level() > 0 ) { ob_end_clean(); }
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' ) || ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Nonce ungültig.' ) ); return;
        }

        $token = wp_generate_password( 32, false );
        set_transient( 'dcb_scan_token_' . $token, 1, 60 ); // 60s gültig

        $url = add_query_arg( 'dcb_browser_scan', $token, home_url( '/' ) );
        wp_send_json_success( array(
            'url'   => $url,
            'token' => $token,
        ) );
    }

    /**
     * Empfängt die vom Browser gemeldeten Cookie-Namen (via postMessage → AJAX),
     * gleicht sie gegen die Datenbank ab und speichert Treffer + Unbekannte.
     */
    public function ajax_browser_scan() {
        while ( ob_get_level() > 0 ) { ob_end_clean(); }
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' ) || ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Nonce ungültig.' ) ); return;
        }

        $raw_cookies = $_POST['cookies'] ?? '';
        $raw_ls      = $_POST['ls_keys'] ?? '';

        // Komma-getrennte Liste bereinigen
        $cookie_names = array_filter( array_map( 'sanitize_text_field', explode( ',', $raw_cookies ) ) );
        $ls_keys      = array_filter( array_map( 'sanitize_text_field', explode( ',', $raw_ls ) ) );

        if ( empty( $cookie_names ) ) {
            wp_send_json_error( array( 'message' => 'Keine Cookie-Namen empfangen.' ) ); return;
        }

        $known   = DCB_Cookie_Scanner::known_cookies();
        $stored  = DCB_Cookie_Manager::get_detected_cookies();
        $manual  = $stored['manual'] ?? array();
        $auto    = $stored['auto']   ?? array();
        $manual_keys = array_keys( $manual );

        $matched   = array(); // Cookies aus Datenbank erkannt
        $unknown   = array(); // Unbekannte Cookies — trotzdem eintragen

        foreach ( $cookie_names as $cn ) {
            $found_key = false;

            // Exakter Treffer
            foreach ( $known as $key => $data ) {
                if ( in_array( $key, $manual_keys, true ) ) continue;

                $match = $data['match'] ?? '';
                $match_hit = false;

                if ( $match === '' ) {
                    // Nur exakter Treffer — kein Prefix-Vergleich, da sonst
                    // z.B. '_gat' auf '_gat_UA-XXXXXXXX' matcht.
                    $match_hit = ( $cn === $data['name'] );
                } elseif ( strpos( $match, 'prefix:' ) === 0 ) {
                    $prefix = substr( $match, 7 );
                    $match_hit = ( strpos( $cn, $prefix ) === 0 );
                }

                if ( $match_hit ) {
                    if ( ! isset( $auto[ $key ] ) && ! isset( $matched[ $key ] ) ) {
                        $matched[ $key ] = $data;
                    }
                    $found_key = true;
                    break;
                }
            }

            // Unbekannter Cookie → als "Unbekannt / Notwendig" eintragen
            // damit der Admin es sehen und kategorisieren kann
            if ( ! $found_key ) {
                // Interne/Browser/WordPress-Cookies überspringen
                $skip_prefixes = array( 'wordpress_', 'wp-', 'dcb_', '__utmz_', 'PHPSESSID', '_wpnonce', 'comment_' );
                $skip = false;
                foreach ( $skip_prefixes as $sp ) {
                    if ( strpos( $cn, $sp ) === 0 ) { $skip = true; break; }
                }
                if ( ! $skip && strlen( $cn ) > 1 && strlen( $cn ) < 80 ) {
                    $unknown_key = 'unknown_' . sanitize_key( $cn );
                    if ( ! isset( $auto[ $unknown_key ] ) && ! isset( $manual[ $unknown_key ] ) ) {
                        $unknown[ $unknown_key ] = array(
                            'name'     => $cn,
                            'category' => 'necessary',
                            'provider' => '?',
                            'purpose'  => '(Browser-Scan – bitte prüfen)',
                            'duration' => '?',
                        );
                    }
                }
            }
        }

        // Zusammenführen
        $new_auto = array_merge( $auto, $matched, $unknown );
        DCB_Cookie_Manager::save_detected_cookies( array(
            'auto'      => $new_auto,
            'manual'    => $manual,
            'last_scan' => current_time( 'mysql' ),
        ) );

        wp_send_json_success( array(
            'matched'  => count( $matched ),
            'unknown'  => count( $unknown ),
            'total'    => count( $new_auto ),
            'new'      => count( $matched ) + count( $unknown ),
        ) );
    }

    public function ajax_save_manual() {
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' ) || ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Nonce ungültig.' ), 403 ); return;
        }
        $name = sanitize_text_field( $_POST['cookie_name'] ?? '' );
        if ( empty( $name ) ) { wp_send_json_error( array( 'message' => 'Kein Cookie-Name.' ) ); return; }

        $key  = sanitize_key( $name );
        $data = array(
            'name'     => $name,
            'category' => $_POST['cookie_category'] ?? 'necessary',
            'provider' => $_POST['cookie_provider']  ?? '',
            'purpose'  => $_POST['cookie_purpose']   ?? '',
            'duration' => $_POST['cookie_duration']  ?? '',
        );

        DCB_Cookie_Manager::update_cookie_entry( $key, $data )
            ? wp_send_json_success()
            : wp_send_json_error( array( 'message' => 'Speichern fehlgeschlagen.' ) );
    }

    public function ajax_update_cookie() {
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' ) || ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Nonce ungültig.' ), 403 ); return;
        }
        $key = sanitize_key( $_POST['cookie_key'] ?? '' );
        if ( empty( $key ) ) { wp_send_json_error( array( 'message' => 'Kein Schlüssel.' ) ); return; }

        $data = array(
            'name'     => $_POST['name']     ?? '',
            'category' => $_POST['category'] ?? 'necessary',
            'provider' => $_POST['provider'] ?? '',
            'purpose'  => $_POST['purpose']  ?? '',
            'duration' => $_POST['duration'] ?? '',
        );

        if ( DCB_Cookie_Manager::update_cookie_entry( $key, $data ) ) {
            $stored  = DCB_Cookie_Manager::get_detected_cookies();
            $updated = $stored['manual'][ $key ] ?? $data;
            wp_send_json_success( array( 'cookie' => $updated ) );
        } else {
            wp_send_json_error( array( 'message' => 'Speichern fehlgeschlagen.' ) );
        }
    }

    public function ajax_delete_cookie() {
        while ( ob_get_level() > 0 ) { ob_end_clean(); }
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => 'Nonce ungueltig - bitte Seite neu laden.' ) );
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Keine Berechtigung.' ) );
            return;
        }
        $key = sanitize_key( $_POST['cookie_key'] ?? '' );
        if ( empty( $key ) ) {
            wp_send_json_error( array( 'message' => 'Kein Cookie-Schluessel uebergeben.' ) );
            return;
        }
        // delete_cookie_entry gibt false zurueck wenn der Key nicht existiert -
        // das ist kein Fehler (idempotent), daher immer success zurueckgeben.
        DCB_Cookie_Manager::delete_cookie_entry( $key );
        wp_send_json_success();
    }

    /**
     * Setzt die automatisch erkannte Cookie-Liste zurück.
     * Manuell hinzugefügte Cookies (manual) bleiben erhalten.
     */
    public function ajax_reset_scan() {
        while ( ob_get_level() > 0 ) { ob_end_clean(); }
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' ) || ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Nonce ungueltig.' ) ); return;
        }

        $stored = DCB_Cookie_Manager::get_detected_cookies();
        $stored['auto'] = array();
        // last_scan bewusst behalten damit der Zeitstempel sichtbar bleibt
        DCB_Cookie_Manager::save_detected_cookies( $stored );

        wp_send_json_success( array( 'message' => 'Auto-Liste geleert.' ) );
    }

    /* ── Embed AJAX handlers ───────────────────────────────────────────────── */

    private function verify_nonce(): bool {
        return wp_verify_nonce( $_POST['nonce'] ?? '', 'dcb_admin_nonce' )
            && current_user_can( 'manage_options' );
    }

    public function ajax_embed_save() {
        if ( ! $this->verify_nonce() ) { wp_send_json_error( array( 'message' => 'Nonce ungültig.' ), 403 ); return; }

        $id = sanitize_key( $_POST['embed_id'] ?? '' );
        if ( ! $id ) { wp_send_json_error( array( 'message' => 'Keine Embed-ID.' ) ); return; }

        $current = DCB_Embeds::get_embed( $id ) ?? array();
        $data = DCB_Embeds::sanitise_embed( array_merge( $current, $_POST, array( 'id' => $id ) ) );
        $data['enabled']      = $current['enabled']      ?? true;
        $data['preview_type'] = $current['preview_type'] ?? 'color';

        DCB_Embeds::save_embed( $id, $data );
        wp_send_json_success( array( 'embed' => $data ) );
    }

    public function ajax_embed_toggle() {
        if ( ! $this->verify_nonce() ) { wp_send_json_error( array( 'message' => 'Nonce ungültig.' ), 403 ); return; }

        $id      = sanitize_key( $_POST['embed_id'] ?? '' );
        $enabled = ! empty( $_POST['enabled'] );

        $current = DCB_Embeds::get_embed( $id );
        if ( ! $current ) { wp_send_json_error( array( 'message' => 'Unbekannter Embed-Typ.' ) ); return; }

        $current['enabled'] = $enabled;
        DCB_Embeds::save_embed( $id, $current );
        wp_send_json_success();
    }

    public function ajax_embed_reset() {
        if ( ! $this->verify_nonce() ) { wp_send_json_error( array( 'message' => 'Nonce ungültig.' ), 403 ); return; }
        $id = sanitize_key( $_POST['embed_id'] ?? '' );
        DCB_Embeds::delete_embed( $id );
        wp_send_json_success();
    }

    public function ajax_embed_delete() {
        if ( ! $this->verify_nonce() ) { wp_send_json_error( array( 'message' => 'Nonce ungültig.' ), 403 ); return; }

        $id       = sanitize_key( $_POST['embed_id'] ?? '' );
        $defaults = DCB_Embeds::default_embed_types();

        if ( isset( $defaults[ $id ] ) ) {
            wp_send_json_error( array( 'message' => 'Standard-Typen können nicht gelöscht werden.' ) ); return;
        }

        DCB_Embeds::delete_embed( $id );
        wp_send_json_success();
    }

    public function ajax_embed_create() {
        if ( ! $this->verify_nonce() ) { wp_send_json_error( array( 'message' => 'Nonce ungültig.' ), 403 ); return; }

        $id    = sanitize_key( $_POST['embed_id'] ?? '' );
        $label = sanitize_text_field( $_POST['label']    ?? '' );
        $cat   = sanitize_text_field( $_POST['category'] ?? 'marketing' );
        $icon  = sanitize_text_field( $_POST['icon']     ?? '▶' );

        if ( ! $id || ! $label ) {
            wp_send_json_error( array( 'message' => 'ID und Bezeichnung sind erforderlich.' ) ); return;
        }

        $existing = DCB_Embeds::get_embeds();
        if ( isset( $existing[ $id ] ) ) {
            wp_send_json_error( array( 'message' => 'Ein Embed mit dieser ID existiert bereits.' ) ); return;
        }

        $new_embed = array(
            'id'                   => $id,
            'label'                => $label,
            'category'             => $cat,
            'icon'                 => $icon,
            'preview_type'         => 'color',
            'placeholder_title_de' => $label,
            'placeholder_title_en' => $label,
            'placeholder_text_de'  => 'Dieser Inhalt wird von ' . $label . ' bereitgestellt. Mit dem Laden stimmen Sie der Datenschutzerklärung zu.',
            'placeholder_text_en'  => 'This content is provided by ' . $label . '. By loading it, you agree to their privacy policy.',
            'btn_text_de'          => 'Inhalt laden',
            'btn_text_en'          => 'Load content',
            'always_text_de'       => 'Immer für ' . $label . ' erlauben',
            'always_text_en'       => 'Always allow ' . $label,
            'bg_color'             => '#1a1a1a',
            'accent_color'         => '#0073aa',
            'text_color'           => '#ffffff',
            'enabled'              => true,
        );

        DCB_Embeds::save_embed( $id, $new_embed );
        wp_send_json_success( array( 'embed' => $new_embed ) );
    }

    /* ── Page renderers ────────────────────────────────────────────────────── */

    public function render_settings_page() {
        $settings = DCB_Cookie_Manager::get_settings();
        $pages    = get_pages();
        include DCB_PLUGIN_DIR . 'admin/views/settings.php';
    }

    public function render_scanner_page() {
        $stored = DCB_Cookie_Manager::get_detected_cookies();
        include DCB_PLUGIN_DIR . 'admin/views/scanner.php';
    }

    public function render_embeds_page() {
        $embeds = DCB_Embeds::get_embeds();
        include DCB_PLUGIN_DIR . 'admin/views/embeds.php';
    }

    public function render_consents_page() {
        $consents = DCB_Cookie_Manager::get_consents( 100 );
        include DCB_PLUGIN_DIR . 'admin/views/consents.php';
    }
}

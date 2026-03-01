<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Admin {

    public function __construct() {
        add_action( 'admin_menu',            array( $this, 'add_menu' ) );
        add_action( 'admin_init',            array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_ajax_dcb_scan',         array( $this, 'ajax_scan' ) );
        add_action( 'wp_ajax_dcb_save_manual_cookie', array( $this, 'ajax_save_manual' ) );
        add_action( 'wp_ajax_dcb_update_cookie', array( $this, 'ajax_update_cookie' ) );
        add_action( 'wp_ajax_dcb_delete_cookie', array( $this, 'ajax_delete_cookie' ) );
    }

    public function add_menu() {
        add_menu_page(
            DCB_I18n::t('admin_page_title'),
            DCB_I18n::t('admin_menu_label'),
            'manage_options',
            'dcb-settings',
            array( $this, 'render_settings_page' ),
            'dashicons-privacy',
            85
        );
        add_submenu_page( 'dcb-settings', DCB_I18n::t('admin_submenu_settings'), DCB_I18n::t('admin_submenu_settings'), 'manage_options', 'dcb-settings',  array( $this, 'render_settings_page' ) );
        add_submenu_page( 'dcb-settings', DCB_I18n::t('admin_submenu_scanner'),  DCB_I18n::t('admin_submenu_scanner'),  'manage_options', 'dcb-scanner',   array( $this, 'render_scanner_page' ) );
        add_submenu_page( 'dcb-settings', DCB_I18n::t('admin_submenu_consents'), DCB_I18n::t('admin_submenu_consents'), 'manage_options', 'dcb-consents',  array( $this, 'render_consents_page' ) );
    }

    public function register_settings() {
        register_setting( 'dcb_options_group', DCB_Cookie_Manager::OPTION_SETTINGS, array(
            'sanitize_callback' => array( $this, 'sanitize_settings' ),
        ) );
    }

    public function sanitize_settings( $input ) {
        $clean = array();

        // Language (must be processed first so category labels can be updated)
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
        $clean['log_consents']   = ! empty( $input['log_consents'] );

        // When language changes, update the category labels/descriptions to match
        // but keep the required flag unchanged
        $prev       = DCB_Cookie_Manager::get_settings();
        $prev_lang  = $prev['plugin_language'] ?? 'de';
        $new_lang   = $clean['plugin_language'];

        $prev       = DCB_Cookie_Manager::get_settings();
        $prev_lang  = $prev['plugin_language'] ?? 'de';
        $new_lang   = $clean['plugin_language'];

        if ( $prev_lang !== $new_lang ) {
            // Language switched: rebuild categories in new language
            // But preserve any custom shortcode_key / block_key the user set
            $new_defaults = DCB_Cookie_Manager::default_categories( $new_lang );
            $prev_cats    = $prev['categories'] ?? array();
            foreach ( $new_defaults as $key => $cat ) {
                if ( isset( $prev_cats[ $key ] ) ) {
                    $new_defaults[ $key ]['shortcode_key'] = $prev_cats[ $key ]['shortcode_key'] ?? $key;
                    $new_defaults[ $key ]['block_key']     = $prev_cats[ $key ]['block_key']     ?? $key;
                }
            }
            $clean['categories'] = $new_defaults;

            // Also reset banner texts if unchanged
            $old_defaults = DCB_Cookie_Manager::default_settings_for_lang( $prev_lang );
            $lang_defaults = DCB_Cookie_Manager::default_settings_for_lang( $new_lang );
            foreach ( array( 'banner_title', 'banner_text', 'accept_all_text', 'accept_necessary_text', 'customize_text', 'save_settings_text' ) as $f ) {
                if ( $clean[ $f ] === $old_defaults[ $f ] || $clean[ $f ] === $prev[ $f ] ) {
                    $clean[ $f ] = $lang_defaults[ $f ];
                }
            }
        } else {
            // Same language – save user-submitted category edits
            $defaults = DCB_Cookie_Manager::default_categories( $new_lang );
            $submitted_cats = $input['categories'] ?? array();
            $clean_cats = array();

            foreach ( $defaults as $key => $default_cat ) {
                $sub = $submitted_cats[ $key ] ?? array();
                $clean_cats[ $key ] = array(
                    'label'         => sanitize_text_field( $sub['label']         ?? $default_cat['label'] ),
                    'description'   => sanitize_textarea_field( $sub['description'] ?? $default_cat['description'] ),
                    'required'      => (bool) ( $default_cat['required'] ),   // required is never user-changeable
                    'shortcode_key' => sanitize_key( $sub['shortcode_key'] ?? $key ),
                    'block_key'     => sanitize_key( $sub['block_key']     ?? $key ),
                );
                // Fallback: keys must not be empty
                if ( empty( $clean_cats[ $key ]['shortcode_key'] ) ) $clean_cats[ $key ]['shortcode_key'] = $key;
                if ( empty( $clean_cats[ $key ]['block_key'] ) )     $clean_cats[ $key ]['block_key']     = $key;
            }
            $clean['categories'] = $clean_cats;
        }

        return $clean;
    }

    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'dcb-' ) === false ) return;
        wp_enqueue_style(  'dcb-admin', DCB_PLUGIN_URL . 'admin/admin.css', array(), DCB_VERSION );
        wp_enqueue_script( 'dcb-admin', DCB_PLUGIN_URL . 'admin/admin.js',  array( 'jquery' ), DCB_VERSION, true );
        wp_localize_script( 'dcb-admin', 'DCBAdmin', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'dcb_admin_nonce' ),
            'i18n'     => DCB_I18n::all(),
        ) );
    }

    public function ajax_scan() {
        check_ajax_referer( 'dcb_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
        $cookies = DCB_Cookie_Scanner::scan();
        wp_send_json_success( array( 'count' => count( $cookies ), 'cookies' => array_values( $cookies ) ) );
    }

    public function ajax_save_manual() {
        check_ajax_referer( 'dcb_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

        $name = sanitize_text_field( $_POST['cookie_name'] ?? '' );
        if ( empty( $name ) ) {
            wp_send_json_error( array( 'message' => 'Kein Cookie-Name angegeben.' ) );
        }

        // Schlüssel = bereinigter Name (gleiche Logik wie beim Update)
        $key  = sanitize_key( $name );

        $data = array(
            'name'     => $name,
            'category' => $_POST['cookie_category'] ?? 'necessary',
            'provider' => $_POST['cookie_provider']  ?? '',
            'purpose'  => $_POST['cookie_purpose']   ?? '',
            'duration' => $_POST['cookie_duration']  ?? '',
        );

        $ok = DCB_Cookie_Manager::update_cookie_entry( $key, $data );
        if ( $ok ) {
            wp_send_json_success();
        } else {
            wp_send_json_error( array( 'message' => 'Speichern fehlgeschlagen.' ) );
        }
    }

    public function ajax_update_cookie() {
        check_ajax_referer( 'dcb_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

        $key  = sanitize_key( $_POST['cookie_key'] ?? '' );
        if ( empty( $key ) ) {
            wp_send_json_error( array( 'message' => 'Kein Schlüssel angegeben.' ) );
        }

        $data = array(
            'name'     => $_POST['name']     ?? '',
            'category' => $_POST['category'] ?? 'necessary',
            'provider' => $_POST['provider'] ?? '',
            'purpose'  => $_POST['purpose']  ?? '',
            'duration' => $_POST['duration'] ?? '',
        );

        $ok = DCB_Cookie_Manager::update_cookie_entry( $key, $data );

        if ( $ok ) {
            // Gespeicherten Eintrag zurückgeben (sanitized)
            $stored  = DCB_Cookie_Manager::get_detected_cookies();
            $updated = $stored['manual'][ $key ] ?? $data;
            wp_send_json_success( array( 'cookie' => $updated ) );
        } else {
            wp_send_json_error( array( 'message' => 'Speichern fehlgeschlagen. Bitte Cookie-Name prüfen.' ) );
        }
    }

    public function ajax_delete_cookie() {
        check_ajax_referer( 'dcb_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

        $key = sanitize_key( $_POST['cookie_key'] ?? '' );
        if ( empty( $key ) ) {
            wp_send_json_error( array( 'message' => 'Kein Schlüssel angegeben.' ) );
        }

        DCB_Cookie_Manager::delete_cookie_entry( $key );
        wp_send_json_success();
    }

    public function render_settings_page() {
        $settings = DCB_Cookie_Manager::get_settings();
        $pages    = get_pages();
        include DCB_PLUGIN_DIR . 'admin/views/settings.php';
    }

    public function render_scanner_page() {
        $stored = DCB_Cookie_Manager::get_detected_cookies();
        include DCB_PLUGIN_DIR . 'admin/views/scanner.php';
    }

    public function render_consents_page() {
        $consents = DCB_Cookie_Manager::get_consents( 100 );
        include DCB_PLUGIN_DIR . 'admin/views/consents.php';
    }
}

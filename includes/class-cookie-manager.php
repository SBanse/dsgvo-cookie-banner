<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Cookie_Manager {

    const OPTION_SETTINGS = 'dcb_settings';
    const OPTION_COOKIES  = 'dcb_detected_cookies';

    public static function activate() {
        global $wpdb;
        $table_name      = $wpdb->prefix . 'dcb_consents';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id           BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            consent_id   VARCHAR(64)  NOT NULL,
            ip_hash      VARCHAR(64)  NOT NULL,
            consent_data LONGTEXT     NOT NULL,
            created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY consent_id (consent_id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        // Default-Einstellungen
        if ( ! get_option( self::OPTION_SETTINGS ) ) {
            update_option( self::OPTION_SETTINGS, self::default_settings() );
        }
    }

    public static function deactivate() {
        // Tabelle bleibt erhalten (Datenschutz-Log)
    }

    public static function default_settings() {
        return array(
            'banner_title'         => 'Wir verwenden Cookies',
            'banner_text'          => 'Wir verwenden Cookies und ähnliche Technologien, um unsere Website zu verbessern und Ihnen die bestmögliche Erfahrung zu bieten. Bitte wählen Sie, welche Cookies Sie akzeptieren möchten.',
            'accept_all_text'      => 'Alle akzeptieren',
            'accept_necessary_text'=> 'Nur notwendige',
            'customize_text'       => 'Einstellungen',
            'save_settings_text'   => 'Einstellungen speichern',
            'banner_position'      => 'bottom',
            'banner_layout'        => 'bar',
            'primary_color'        => '#0073aa',
            'text_color'           => '#333333',
            'bg_color'             => '#ffffff',
            'cookie_lifetime'      => 365,
            'categories'           => self::default_categories(),
            'privacy_page_id'      => 0,
            'imprint_page_id'      => 0,
            'auto_block_scripts'   => true,
            'log_consents'         => true,
        );
    }

    public static function default_categories() {
        return array(
            'necessary' => array(
                'label'       => 'Notwendig',
                'description' => 'Diese Cookies sind für den Betrieb der Website unbedingt erforderlich und können nicht deaktiviert werden.',
                'required'    => true,
            ),
            'statistics' => array(
                'label'       => 'Statistik',
                'description' => 'Diese Cookies helfen uns zu verstehen, wie Besucher mit der Website interagieren (z. B. Google Analytics).',
                'required'    => false,
            ),
            'marketing' => array(
                'label'       => 'Marketing',
                'description' => 'Diese Cookies werden verwendet, um Werbeanzeigen relevanter für Sie zu gestalten.',
                'required'    => false,
            ),
            'preferences' => array(
                'label'       => 'Präferenzen',
                'description' => 'Diese Cookies ermöglichen der Website, Informationen zu speichern, die Ihr Verhalten oder Ihr Aussehen beeinflussen.',
                'required'    => false,
            ),
        );
    }

    public static function get_settings() {
        $settings = get_option( self::OPTION_SETTINGS, array() );
        return wp_parse_args( $settings, self::default_settings() );
    }

    public static function get_detected_cookies() {
        return get_option( self::OPTION_COOKIES, array() );
    }

    public static function save_detected_cookies( $cookies ) {
        update_option( self::OPTION_COOKIES, $cookies );
    }

    public static function log_consent( $consent_id, $data ) {
        $settings = self::get_settings();
        if ( empty( $settings['log_consents'] ) ) return;

        global $wpdb;
        $table = $wpdb->prefix . 'dcb_consents';
        $wpdb->insert( $table, array(
            'consent_id'   => sanitize_text_field( $consent_id ),
            'ip_hash'      => hash( 'sha256', $_SERVER['REMOTE_ADDR'] ?? '' ),
            'consent_data' => wp_json_encode( $data ),
            'created_at'   => current_time( 'mysql' ),
        ) );
    }

    public static function get_consents( $limit = 50 ) {
        global $wpdb;
        $table = $wpdb->prefix . 'dcb_consents';
        return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table ORDER BY created_at DESC LIMIT %d", $limit ) );
    }
}

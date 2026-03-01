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
        // Detect locale for initial language default
        $locale   = get_locale();
        $lang     = str_starts_with( $locale, 'de' ) ? 'de' : 'en';

        // Translated defaults – i18n may not be initialised yet at activation,
        // so we inline a tiny lookup here.
        $titles = array(
            'de' => array(
                'banner_title'          => 'Wir verwenden Cookies',
                'banner_text'           => 'Wir verwenden Cookies und ähnliche Technologien, um unsere Website zu verbessern und Ihnen die bestmögliche Erfahrung zu bieten. Bitte wählen Sie, welche Cookies Sie akzeptieren möchten.',
                'accept_all_text'       => 'Alle akzeptieren',
                'accept_necessary_text' => 'Nur notwendige',
                'customize_text'        => 'Einstellungen',
                'save_settings_text'    => 'Einstellungen speichern',
            ),
            'en' => array(
                'banner_title'          => 'We use cookies',
                'banner_text'           => 'We use cookies and similar technologies to improve our website and provide you with the best possible experience. Please choose which cookies you would like to accept.',
                'accept_all_text'       => 'Accept all',
                'accept_necessary_text' => 'Necessary only',
                'customize_text'        => 'Settings',
                'save_settings_text'    => 'Save settings',
            ),
        );

        $t = $titles[ $lang ];

        return array(
            'plugin_language'      => $lang,
            'banner_title'         => $t['banner_title'],
            'banner_text'          => $t['banner_text'],
            'accept_all_text'      => $t['accept_all_text'],
            'accept_necessary_text'=> $t['accept_necessary_text'],
            'customize_text'       => $t['customize_text'],
            'save_settings_text'   => $t['save_settings_text'],
            'banner_position'      => 'bottom',
            'banner_layout'        => 'bar',
            'primary_color'        => '#0073aa',
            'text_color'           => '#333333',
            'bg_color'             => '#ffffff',
            'cookie_lifetime'      => 365,
            'categories'           => self::default_categories( $lang ),
            'privacy_page_id'      => 0,
            'imprint_page_id'      => 0,
            'auto_block_scripts'   => true,
            'log_consents'         => true,
        );
    }

    /**
     * Returns default categories translated for the given language.
     * Falls back to DE if i18n not yet loaded.
     */
    public static function default_categories( string $lang = '' ): array {
        // If i18n is already loaded, use it; otherwise inline strings
        if ( class_exists( 'DCB_I18n' ) && DCB_I18n::get_lang() ) {
            $l = $lang ?: DCB_I18n::get_lang();
        } else {
            $l = $lang ?: 'de';
        }

        $cats = array(
            'de' => array(
                'necessary'   => array( 'label' => 'Notwendig',   'description' => 'Diese Cookies sind für den Betrieb der Website unbedingt erforderlich und können nicht deaktiviert werden.', 'required' => true,  'shortcode_key' => 'necessary',   'block_key' => 'necessary' ),
                'statistics'  => array( 'label' => 'Statistik',   'description' => 'Diese Cookies helfen uns zu verstehen, wie Besucher mit der Website interagieren (z. B. Google Analytics).', 'required' => false, 'shortcode_key' => 'statistics',  'block_key' => 'statistics' ),
                'marketing'   => array( 'label' => 'Marketing',   'description' => 'Diese Cookies werden verwendet, um Werbeanzeigen relevanter für Sie zu gestalten.', 'required' => false, 'shortcode_key' => 'marketing',   'block_key' => 'marketing' ),
                'preferences' => array( 'label' => 'Präferenzen', 'description' => 'Diese Cookies ermöglichen der Website, Informationen zu speichern, die Ihr Verhalten oder Ihr Aussehen beeinflussen.', 'required' => false, 'shortcode_key' => 'preferences', 'block_key' => 'preferences' ),
            ),
            'en' => array(
                'necessary'   => array( 'label' => 'Necessary',   'description' => 'These cookies are strictly required for the operation of the website and cannot be disabled.', 'required' => true,  'shortcode_key' => 'necessary',   'block_key' => 'necessary' ),
                'statistics'  => array( 'label' => 'Statistics',  'description' => 'These cookies help us understand how visitors interact with our website (e.g. Google Analytics).', 'required' => false, 'shortcode_key' => 'statistics',  'block_key' => 'statistics' ),
                'marketing'   => array( 'label' => 'Marketing',   'description' => 'These cookies are used to make advertisements more relevant to you.', 'required' => false, 'shortcode_key' => 'marketing',   'block_key' => 'marketing' ),
                'preferences' => array( 'label' => 'Preferences', 'description' => 'These cookies allow the website to remember information that changes how the site behaves or looks.', 'required' => false, 'shortcode_key' => 'preferences', 'block_key' => 'preferences' ),
            ),
        );

        return $cats[ $l ] ?? $cats['de'];
    }

    public static function get_settings() {
        $settings = get_option( self::OPTION_SETTINGS, array() );
        return wp_parse_args( $settings, self::default_settings() );
    }

    public static function get_detected_cookies() {
        return get_option( self::OPTION_COOKIES, array() );
    }

    /**
     * Returns just the translatable text defaults for a specific language
     * (used when detecting whether the user customised the texts).
     */
    public static function default_settings_for_lang( string $lang ): array {
        $map = array(
            'de' => array(
                'banner_title'          => 'Wir verwenden Cookies',
                'banner_text'           => 'Wir verwenden Cookies und ähnliche Technologien, um unsere Website zu verbessern und Ihnen die bestmögliche Erfahrung zu bieten. Bitte wählen Sie, welche Cookies Sie akzeptieren möchten.',
                'accept_all_text'       => 'Alle akzeptieren',
                'accept_necessary_text' => 'Nur notwendige',
                'customize_text'        => 'Einstellungen',
                'save_settings_text'    => 'Einstellungen speichern',
            ),
            'en' => array(
                'banner_title'          => 'We use cookies',
                'banner_text'           => 'We use cookies and similar technologies to improve our website and provide you with the best possible experience. Please choose which cookies you would like to accept.',
                'accept_all_text'       => 'Accept all',
                'accept_necessary_text' => 'Necessary only',
                'customize_text'        => 'Settings',
                'save_settings_text'    => 'Save settings',
            ),
        );
        return $map[ $lang ] ?? $map['de'];
    }

    public static function save_detected_cookies( $cookies ) {
        update_option( self::OPTION_COOKIES, $cookies );
    }

    /**
     * Aktualisiert einen einzelnen Cookie-Eintrag atomar in der Datenbank.
     * Schreibt den Eintrag immer in 'manual', damit er beim nächsten Scan
     * nicht durch den Auto-Scanner überschrieben wird.
     *
     * @param string $key     Interner Schlüssel des Eintrags
     * @param array  $data    Neue Felder (name, category, provider, purpose, duration)
     * @return bool           true wenn gespeichert, false bei Fehler
     */
    public static function update_cookie_entry( string $key, array $data ): bool {
        // Felder bereinigen
        $clean = array(
            'name'     => sanitize_text_field( $data['name']     ?? '' ),
            'category' => sanitize_text_field( $data['category'] ?? 'necessary' ),
            'provider' => sanitize_text_field( $data['provider'] ?? '' ),
            'purpose'  => sanitize_textarea_field( $data['purpose']  ?? '' ),
            'duration' => sanitize_text_field( $data['duration'] ?? '' ),
        );

        if ( empty( $clean['name'] ) ) {
            return false;
        }

        $stored = self::get_detected_cookies();

        // Aus auto entfernen – der manuelle Eintrag hat Vorrang
        if ( isset( $stored['auto'][ $key ] ) ) {
            unset( $stored['auto'][ $key ] );
        }

        // In manual schreiben (global persistent)
        $stored['manual'][ $key ] = $clean;

        return update_option( self::OPTION_COOKIES, $stored );
    }

    /**
     * Löscht einen Cookie-Eintrag aus auto UND manual.
     *
     * @param string $key  Interner Schlüssel
     * @return bool
     */
    public static function delete_cookie_entry( string $key ): bool {
        $stored = self::get_detected_cookies();
        $changed = false;

        if ( isset( $stored['auto'][ $key ] ) ) {
            unset( $stored['auto'][ $key ] );
            $changed = true;
        }
        if ( isset( $stored['manual'][ $key ] ) ) {
            unset( $stored['manual'][ $key ] );
            $changed = true;
        }

        return $changed ? update_option( self::OPTION_COOKIES, $stored ) : false;
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

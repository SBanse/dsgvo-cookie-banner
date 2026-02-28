<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Cookie_Scanner {

    /**
     * Bekannte Cookies mit Metadaten (erweiterbare Datenbank)
     */
    public static function known_cookies() {
        return array(
            // Notwendig
            '_wpnonce'          => array( 'name' => '_wpnonce',          'category' => 'necessary',  'provider' => 'WordPress',        'purpose' => 'Sicherheits-Token zur Formularvalidierung',        'duration' => 'Session' ),
            'wordpress_logged_in' => array( 'name' => 'wordpress_logged_in_*', 'category' => 'necessary', 'provider' => 'WordPress', 'purpose' => 'WordPress Login-Status',                          'duration' => '14 Tage' ),
            'wp-settings'       => array( 'name' => 'wp-settings-*',    'category' => 'necessary',  'provider' => 'WordPress',        'purpose' => 'WordPress Nutzereinstellungen',                   'duration' => '1 Jahr' ),
            'PHPSESSID'         => array( 'name' => 'PHPSESSID',        'category' => 'necessary',  'provider' => 'PHP',              'purpose' => 'PHP Session-Identifikator',                       'duration' => 'Session' ),
            'dcb_consent'       => array( 'name' => 'dcb_consent',      'category' => 'necessary',  'provider' => 'Diese Website',    'purpose' => 'Speichert Ihre Cookie-Einwilligung',              'duration' => '1 Jahr' ),
            // WooCommerce
            'woocommerce_cart_hash'    => array( 'name' => 'woocommerce_cart_hash',    'category' => 'necessary', 'provider' => 'WooCommerce', 'purpose' => 'Speichert Warenkorb-Hash',            'duration' => 'Session' ),
            'woocommerce_items_in_cart'=> array( 'name' => 'woocommerce_items_in_cart','category' => 'necessary', 'provider' => 'WooCommerce', 'purpose' => 'Speichert Warenkorb-Status',          'duration' => 'Session' ),
            'wp_woocommerce_session'   => array( 'name' => 'wp_woocommerce_session_*', 'category' => 'necessary', 'provider' => 'WooCommerce', 'purpose' => 'WooCommerce Session-Daten',           'duration' => '2 Tage' ),
            // Google Analytics
            '_ga'               => array( 'name' => '_ga',              'category' => 'statistics', 'provider' => 'Google Analytics', 'purpose' => 'Unterscheidet Nutzer und Sitzungen',              'duration' => '2 Jahre' ),
            '_gid'              => array( 'name' => '_gid',             'category' => 'statistics', 'provider' => 'Google Analytics', 'purpose' => 'Unterscheidet Nutzer',                            'duration' => '24 Stunden' ),
            '_gat'              => array( 'name' => '_gat',             'category' => 'statistics', 'provider' => 'Google Analytics', 'purpose' => 'Drosselung der Anfragerate',                     'duration' => '1 Minute' ),
            '_gac_'             => array( 'name' => '_gac_*',           'category' => 'marketing',  'provider' => 'Google Ads',       'purpose' => 'Enthält Kampagneninformationen',                  'duration' => '90 Tage' ),
            // Matomo
            '_pk_id'            => array( 'name' => '_pk_id.*',         'category' => 'statistics', 'provider' => 'Matomo',           'purpose' => 'Eindeutige Besucher-ID',                         'duration' => '13 Monate' ),
            '_pk_ses'           => array( 'name' => '_pk_ses.*',        'category' => 'statistics', 'provider' => 'Matomo',           'purpose' => 'Aktive Session',                                 'duration' => '30 Minuten' ),
            // Facebook
            '_fbp'              => array( 'name' => '_fbp',             'category' => 'marketing',  'provider' => 'Facebook / Meta',  'purpose' => 'Facebook-Tracking-Pixel',                        'duration' => '3 Monate' ),
            'fr'                => array( 'name' => 'fr',               'category' => 'marketing',  'provider' => 'Facebook / Meta',  'purpose' => 'Zielgruppenbasierte Werbung',                    'duration' => '3 Monate' ),
            // Google Tag Manager
            '_gcl_au'           => array( 'name' => '_gcl_au',          'category' => 'marketing',  'provider' => 'Google',           'purpose' => 'Conversion-Tracking',                            'duration' => '3 Monate' ),
            // Cloudflare
            '__cfduid'          => array( 'name' => '__cfduid',         'category' => 'necessary',  'provider' => 'Cloudflare',       'purpose' => 'Sicherheits-Cookie von Cloudflare',              'duration' => '30 Tage' ),
            '__cf_bm'           => array( 'name' => '__cf_bm',          'category' => 'necessary',  'provider' => 'Cloudflare',       'purpose' => 'Bot-Schutz',                                     'duration' => '30 Minuten' ),
            // Hotjar
            '_hj'               => array( 'name' => '_hj*',             'category' => 'statistics', 'provider' => 'Hotjar',           'purpose' => 'Nutzerverhalten-Analyse',                        'duration' => '1 Jahr' ),
            // LinkedIn
            'li_gc'             => array( 'name' => 'li_gc',            'category' => 'marketing',  'provider' => 'LinkedIn',         'purpose' => 'Cookie-Einwilligung LinkedIn',                   'duration' => '2 Jahre' ),
            'lidc'              => array( 'name' => 'lidc',             'category' => 'marketing',  'provider' => 'LinkedIn',         'purpose' => 'LinkedIn Routing',                               'duration' => '1 Tag' ),
            // Twitter/X
            '_twitter_sess'     => array( 'name' => '_twitter_sess',    'category' => 'marketing',  'provider' => 'Twitter / X',      'purpose' => 'Twitter-Session',                               'duration' => 'Session' ),
            'guest_id'          => array( 'name' => 'guest_id',         'category' => 'marketing',  'provider' => 'Twitter / X',      'purpose' => 'Eindeutige Besucher-ID',                         'duration' => '2 Jahre' ),
            // YouTube
            'YSC'               => array( 'name' => 'YSC',              'category' => 'marketing',  'provider' => 'YouTube (Google)', 'purpose' => 'Registriert eine eindeutige ID für Videos',      'duration' => 'Session' ),
            'VISITOR_INFO1_LIVE'=> array( 'name' => 'VISITOR_INFO1_LIVE','category' => 'marketing', 'provider' => 'YouTube (Google)', 'purpose' => 'Schätzt die Bandbreite des Nutzers',             'duration' => '180 Tage' ),
            // Stripe
            '__stripe_mid'      => array( 'name' => '__stripe_mid',     'category' => 'necessary',  'provider' => 'Stripe',           'purpose' => 'Betrugsprävention',                              'duration' => '1 Jahr' ),
            '__stripe_sid'      => array( 'name' => '__stripe_sid',     'category' => 'necessary',  'provider' => 'Stripe',           'purpose' => 'Session-ID',                                     'duration' => '30 Minuten' ),
        );
    }

    /**
     * Scannt die WordPress-Installation auf verwendete Cookies
     */
    public static function scan() {
        $found   = array();
        $known   = self::known_cookies();
        $plugins = get_option( 'active_plugins', array() );

        // WordPress-Kern-Cookies immer hinzufügen
        $core_cookies = array( '_wpnonce', 'wordpress_logged_in', 'wp-settings', 'PHPSESSID', 'dcb_consent' );
        foreach ( $core_cookies as $key ) {
            if ( isset( $known[ $key ] ) ) {
                $found[ $key ] = $known[ $key ];
            }
        }

        // Plugin-basiertes Scannen
        $plugin_map = array(
            'woocommerce'    => array( 'woocommerce_cart_hash', 'woocommerce_items_in_cart', 'wp_woocommerce_session' ),
            'google-analytics-for-wordpress' => array( '_ga', '_gid', '_gat' ),
            'analytify'      => array( '_ga', '_gid', '_gat' ),
            'wp-piwik'       => array( '_pk_id', '_pk_ses' ),
            'matomo'         => array( '_pk_id', '_pk_ses' ),
            'facebook-for-woocommerce' => array( '_fbp', 'fr' ),
            'wordfence'      => array( 'wfwaf-authcookie', 'wf_loginalerted' ),
        );

        foreach ( $plugins as $plugin ) {
            $slug = explode( '/', $plugin )[0];
            foreach ( $plugin_map as $key => $cookie_keys ) {
                if ( strpos( $slug, $key ) !== false ) {
                    foreach ( $cookie_keys as $ck ) {
                        if ( isset( $known[ $ck ] ) ) {
                            $found[ $ck ] = $known[ $ck ];
                        }
                    }
                }
            }
        }

        // Themes scannen (einfache Schlagwort-Suche in functions.php)
        $theme_dir = get_stylesheet_directory() . '/functions.php';
        if ( file_exists( $theme_dir ) ) {
            $content = file_get_contents( $theme_dir );
            $scan_keywords = array(
                'setcookie'         => array(),
                'google-analytics'  => array( '_ga', '_gid', '_gat' ),
                'gtag'              => array( '_ga', '_gid', '_gat' ),
                'fbq('              => array( '_fbp', 'fr' ),
                'hotjar'            => array( '_hj' ),
                'youtube.com/embed' => array( 'YSC', 'VISITOR_INFO1_LIVE' ),
                'stripe.js'         => array( '__stripe_mid', '__stripe_sid' ),
            );
            foreach ( $scan_keywords as $keyword => $cookie_keys ) {
                if ( stripos( $content, $keyword ) !== false ) {
                    foreach ( $cookie_keys as $ck ) {
                        if ( isset( $known[ $ck ] ) ) {
                            $found[ $ck ] = $known[ $ck ];
                        }
                    }
                }
            }
        }

        // Gespeicherte manuelle Cookies hinzufügen
        $stored = DCB_Cookie_Manager::get_detected_cookies();
        if ( isset( $stored['manual'] ) ) {
            foreach ( $stored['manual'] as $mk => $mc ) {
                $found[ 'manual_' . $mk ] = $mc;
            }
        }

        DCB_Cookie_Manager::save_detected_cookies( array(
            'auto'       => $found,
            'manual'     => $stored['manual'] ?? array(),
            'last_scan'  => current_time( 'mysql' ),
        ) );

        return $found;
    }
}

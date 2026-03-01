<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Cookie_Scanner {

    /**
     * Interne Cookie-Datenbank (Schlüssel = eindeutiger Bezeichner, nie der Cookie-Name direkt,
     * damit Wildcards wie _ga_* nicht zu Mehrfacheinträgen führen).
     */
    public static function known_cookies() {
        return array(
            // WordPress / PHP
            '_wpnonce'               => array( 'name' => '_wpnonce',               'category' => 'necessary',  'provider' => 'WordPress',        'purpose' => 'Sicherheits-Token zur Formularvalidierung',   'duration' => 'Session' ),
            'wordpress_logged_in'    => array( 'name' => 'wordpress_logged_in_*',  'category' => 'necessary',  'provider' => 'WordPress',        'purpose' => 'WordPress Login-Status',                      'duration' => '14 Tage' ),
            'wp-settings'            => array( 'name' => 'wp-settings-*',          'category' => 'necessary',  'provider' => 'WordPress',        'purpose' => 'WordPress Nutzereinstellungen',               'duration' => '1 Jahr' ),
            'PHPSESSID'              => array( 'name' => 'PHPSESSID',              'category' => 'necessary',  'provider' => 'PHP',              'purpose' => 'PHP Session-Identifikator',                   'duration' => 'Session' ),
            'dcb_consent'            => array( 'name' => 'dcb_consent',            'category' => 'necessary',  'provider' => 'Diese Website',    'purpose' => 'Speichert Ihre Cookie-Einwilligung',          'duration' => '1 Jahr' ),
            // WooCommerce
            'woocommerce_cart_hash'    => array( 'name' => 'woocommerce_cart_hash',    'category' => 'necessary', 'provider' => 'WooCommerce', 'purpose' => 'Warenkorb-Hash',       'duration' => 'Session' ),
            'woocommerce_items_in_cart'=> array( 'name' => 'woocommerce_items_in_cart','category' => 'necessary', 'provider' => 'WooCommerce', 'purpose' => 'Warenkorb-Status',     'duration' => 'Session' ),
            'wp_woocommerce_session'   => array( 'name' => 'wp_woocommerce_session_*', 'category' => 'necessary', 'provider' => 'WooCommerce', 'purpose' => 'WooCommerce Session',  'duration' => '2 Tage' ),
            // Google Analytics
            '_ga'                    => array( 'name' => '_ga',                    'category' => 'statistics', 'provider' => 'Google Analytics', 'purpose' => 'Unterscheidet Nutzer und Sitzungen',         'duration' => '2 Jahre' ),
            '_gid'                   => array( 'name' => '_gid',                   'category' => 'statistics', 'provider' => 'Google Analytics', 'purpose' => 'Unterscheidet Nutzer (24h)',                  'duration' => '24 Stunden' ),
            '_gat'                   => array( 'name' => '_gat',                   'category' => 'statistics', 'provider' => 'Google Analytics', 'purpose' => 'Drosselung der Anfragerate',                 'duration' => '1 Minute' ),
            '_gac'                   => array( 'name' => '_gac_*',                 'category' => 'marketing',  'provider' => 'Google Ads',       'purpose' => 'Kampagneninformationen',                     'duration' => '90 Tage' ),
            // Matomo
            '_pk_id'                 => array( 'name' => '_pk_id.*',               'category' => 'statistics', 'provider' => 'Matomo',           'purpose' => 'Eindeutige Besucher-ID',                     'duration' => '13 Monate' ),
            '_pk_ses'                => array( 'name' => '_pk_ses.*',              'category' => 'statistics', 'provider' => 'Matomo',           'purpose' => 'Aktive Session',                             'duration' => '30 Minuten' ),
            // Facebook / Meta
            '_fbp'                   => array( 'name' => '_fbp',                   'category' => 'marketing',  'provider' => 'Facebook / Meta',  'purpose' => 'Facebook-Tracking-Pixel',                    'duration' => '3 Monate' ),
            'fr'                     => array( 'name' => 'fr',                     'category' => 'marketing',  'provider' => 'Facebook / Meta',  'purpose' => 'Zielgruppenbasierte Werbung',                'duration' => '3 Monate' ),
            // Google Tag Manager / Ads
            '_gcl_au'                => array( 'name' => '_gcl_au',                'category' => 'marketing',  'provider' => 'Google',           'purpose' => 'Conversion-Tracking',                        'duration' => '3 Monate' ),
            // Cloudflare
            '__cfduid'               => array( 'name' => '__cfduid',               'category' => 'necessary',  'provider' => 'Cloudflare',       'purpose' => 'Sicherheits-Cookie',                         'duration' => '30 Tage' ),
            '__cf_bm'                => array( 'name' => '__cf_bm',                'category' => 'necessary',  'provider' => 'Cloudflare',       'purpose' => 'Bot-Schutz',                                 'duration' => '30 Minuten' ),
            // Hotjar
            '_hj'                    => array( 'name' => '_hj*',                   'category' => 'statistics', 'provider' => 'Hotjar',           'purpose' => 'Nutzerverhalten-Analyse',                    'duration' => '1 Jahr' ),
            // LinkedIn
            'li_gc'                  => array( 'name' => 'li_gc',                  'category' => 'marketing',  'provider' => 'LinkedIn',         'purpose' => 'Cookie-Einwilligung LinkedIn',               'duration' => '2 Jahre' ),
            'lidc'                   => array( 'name' => 'lidc',                   'category' => 'marketing',  'provider' => 'LinkedIn',         'purpose' => 'LinkedIn Routing',                           'duration' => '1 Tag' ),
            // Twitter / X
            '_twitter_sess'          => array( 'name' => '_twitter_sess',          'category' => 'marketing',  'provider' => 'Twitter / X',      'purpose' => 'Twitter-Session',                           'duration' => 'Session' ),
            'guest_id'               => array( 'name' => 'guest_id',               'category' => 'marketing',  'provider' => 'Twitter / X',      'purpose' => 'Eindeutige Besucher-ID',                     'duration' => '2 Jahre' ),
            // YouTube
            'YSC'                    => array( 'name' => 'YSC',                    'category' => 'marketing',  'provider' => 'YouTube (Google)', 'purpose' => 'Eindeutige Video-ID',                        'duration' => 'Session' ),
            'VISITOR_INFO1_LIVE'     => array( 'name' => 'VISITOR_INFO1_LIVE',     'category' => 'marketing',  'provider' => 'YouTube (Google)', 'purpose' => 'Bandbreiten-Schätzung',                      'duration' => '180 Tage' ),
            // Stripe
            '__stripe_mid'           => array( 'name' => '__stripe_mid',           'category' => 'necessary',  'provider' => 'Stripe',           'purpose' => 'Betrugsprävention',                          'duration' => '1 Jahr' ),
            '__stripe_sid'           => array( 'name' => '__stripe_sid',           'category' => 'necessary',  'provider' => 'Stripe',           'purpose' => 'Session-ID',                                 'duration' => '30 Minuten' ),
        );
    }

    /**
     * Scannt die WordPress-Installation und gibt nur neue, noch nicht vorhandene Cookies zurück.
     *
     * Kernprinzip der Deduplizierung:
     *  - Manuell bearbeitete Einträge (in $stored['manual']) werden NIEMALS überschrieben.
     *  - Auto-Einträge werden nur hinzugefügt, wenn derselbe Schlüssel nicht bereits in
     *    'manual' ODER 'auto' vorhanden ist (kein Duplikat innerhalb eines Scans).
     */
    public static function scan() {
        $known   = self::known_cookies();
        $plugins = get_option( 'active_plugins', array() );
        $stored  = DCB_Cookie_Manager::get_detected_cookies();

        // Bereits manuell verwaltete Schlüssel – diese werden nie vom Scan überschrieben
        $manual      = $stored['manual'] ?? array();
        $manual_keys = array_keys( $manual );

        // Bisher automatisch erkannte Cookies als Basis (nur die Schlüssel merken)
        $prev_auto_keys = array_keys( $stored['auto'] ?? array() );

        // Sammelt alle Schlüssel die in diesem Scan gefunden werden (Deduplizierung)
        $scan_found = array();

        // Hilfsfunktion: Cookie nur hinzufügen wenn Schlüssel noch nicht bekannt ist
        $add = function ( $key ) use ( $known, $manual_keys, &$scan_found ) {
            if ( ! isset( $known[ $key ] ) )             return; // unbekannter Schlüssel
            if ( in_array( $key, $manual_keys, true ) )  return; // manuell verwaltet → nicht anfassen
            if ( isset( $scan_found[ $key ] ) )          return; // bereits in diesem Scan gefunden
            $scan_found[ $key ] = $known[ $key ];
        };

        // ── 1. WordPress-Kern-Cookies ────────────────────────────────────────
        foreach ( array( '_wpnonce', 'wordpress_logged_in', 'wp-settings', 'PHPSESSID', 'dcb_consent' ) as $key ) {
            $add( $key );
        }

        // ── 2. Plugin-basiertes Scannen ──────────────────────────────────────
        $plugin_map = array(
            'woocommerce'                    => array( 'woocommerce_cart_hash', 'woocommerce_items_in_cart', 'wp_woocommerce_session' ),
            'google-analytics-for-wordpress' => array( '_ga', '_gid', '_gat' ),
            'analytify'                      => array( '_ga', '_gid', '_gat' ),
            'google-site-kit'                => array( '_ga', '_gid', '_gat', '_gac' ),
            'wp-piwik'                       => array( '_pk_id', '_pk_ses' ),
            'matomo'                         => array( '_pk_id', '_pk_ses' ),
            'facebook-for-woocommerce'       => array( '_fbp', 'fr' ),
            'pixel-caffeine'                 => array( '_fbp', 'fr' ),
            'wordfence'                      => array( ),
            'all-in-one-seo-pack'            => array( ),
        );

        foreach ( $plugins as $plugin ) {
            $slug = explode( '/', $plugin )[0];
            foreach ( $plugin_map as $match => $cookie_keys ) {
                if ( strpos( $slug, $match ) !== false ) {
                    foreach ( $cookie_keys as $ck ) {
                        $add( $ck );
                    }
                }
            }
        }

        // ── 3. Theme functions.php scannen ───────────────────────────────────
        $theme_file = get_stylesheet_directory() . '/functions.php';
        if ( file_exists( $theme_file ) ) {
            $content = @file_get_contents( $theme_file );
            if ( $content !== false ) {
                $keyword_map = array(
                    'google-analytics'  => array( '_ga', '_gid', '_gat' ),
                    'gtag('             => array( '_ga', '_gid', '_gat' ),
                    'GoogleAnalytics'   => array( '_ga', '_gid', '_gat' ),
                    'fbq('              => array( '_fbp', 'fr' ),
                    'facebook-pixel'    => array( '_fbp', 'fr' ),
                    'hotjar'            => array( '_hj' ),
                    'youtube.com/embed' => array( 'YSC', 'VISITOR_INFO1_LIVE' ),
                    'stripe.js'         => array( '__stripe_mid', '__stripe_sid' ),
                    'stripe.com/v3'     => array( '__stripe_mid', '__stripe_sid' ),
                    'linkedin'          => array( 'li_gc', 'lidc' ),
                    'twitter'           => array( '_twitter_sess', 'guest_id' ),
                );
                foreach ( $keyword_map as $keyword => $cookie_keys ) {
                    if ( stripos( $content, $keyword ) !== false ) {
                        foreach ( $cookie_keys as $ck ) {
                            $add( $ck );
                        }
                    }
                }
            }
        }

        // ── 4. Zusammenführen ────────────────────────────────────────────────
        // Bestehende Auto-Einträge behalten, neue ergänzen (aber nie manuelle berühren)
        $merged_auto = array();
        // Vorherige Auto-Einträge, die nicht manuell überschrieben wurden, übernehmen
        foreach ( $stored['auto'] ?? array() as $k => $v ) {
            if ( ! in_array( $k, $manual_keys, true ) ) {
                $merged_auto[ $k ] = $v;
            }
        }
        // Neu gefundene Einträge hinzufügen (keine Duplikate dank $scan_found-Logik oben)
        foreach ( $scan_found as $k => $v ) {
            $merged_auto[ $k ] = $v;
        }

        $result = array(
            'auto'      => $merged_auto,
            'manual'    => $manual,
            'last_scan' => current_time( 'mysql' ),
        );

        DCB_Cookie_Manager::save_detected_cookies( $result );

        return $merged_auto;
    }
}

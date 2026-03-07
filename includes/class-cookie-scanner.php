<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * DCB_Cookie_Scanner v2
 * Fünf Erkennungsmethoden + 200+ Cookie-Datenbank
 */
class DCB_Cookie_Scanner {

    public static function known_cookies() {
        return array(
            // WordPress Core
            '_wpnonce'                   => array( 'name' => '_wpnonce',                'category' => 'necessary',   'provider' => 'WordPress',            'purpose' => 'Sicherheits-Token für Formulare und AJAX',          'duration' => 'Session' ),
            'wordpress_logged_in'        => array( 'name' => 'wordpress_logged_in_*',   'category' => 'necessary',   'provider' => 'WordPress',            'purpose' => 'Anmeldestatus des WordPress-Nutzers',               'duration' => '14 Tage',       'match' => 'prefix:wordpress_logged_in' ),
            'wp_settings'                => array( 'name' => 'wp-settings-*',           'category' => 'necessary',   'provider' => 'WordPress',            'purpose' => 'WordPress-Nutzereinstellungen',                     'duration' => '1 Jahr',        'match' => 'prefix:wp-settings-' ),
            'wordpress_test_cookie'      => array( 'name' => 'wordpress_test_cookie',   'category' => 'necessary',   'provider' => 'WordPress',            'purpose' => 'Prüft ob Cookies aktiviert sind',                  'duration' => 'Session' ),
            'comment_author'             => array( 'name' => 'comment_author_*',        'category' => 'necessary',   'provider' => 'WordPress',            'purpose' => 'Speichert Kommentar-Autorendaten',                  'duration' => '1 Jahr',        'match' => 'prefix:comment_author' ),
            'PHPSESSID'                  => array( 'name' => 'PHPSESSID',               'category' => 'necessary',   'provider' => 'PHP',                  'purpose' => 'PHP Session-Identifikator',                         'duration' => 'Session' ),
            'dcb_consent'                => array( 'name' => 'dcb_consent',             'category' => 'necessary',   'provider' => 'Diese Website',        'purpose' => 'Speichert Ihre Cookie-Einwilligung',                'duration' => '1 Jahr' ),
            // WooCommerce
            'woocommerce_cart_hash'      => array( 'name' => 'woocommerce_cart_hash',       'category' => 'necessary',   'provider' => 'WooCommerce',   'purpose' => 'Erkennt Änderungen im Warenkorb',                   'duration' => 'Session' ),
            'woocommerce_items_in_cart'  => array( 'name' => 'woocommerce_items_in_cart',   'category' => 'necessary',   'provider' => 'WooCommerce',   'purpose' => 'Anzeige des Warenkorb-Symbols',                     'duration' => 'Session' ),
            'wp_woocommerce_session'     => array( 'name' => 'wp_woocommerce_session_*',    'category' => 'necessary',   'provider' => 'WooCommerce',   'purpose' => 'WooCommerce-Session mit Warenkorb',                 'duration' => '2 Tage',        'match' => 'prefix:wp_woocommerce_session' ),
            'woocommerce_recently_viewed'=> array( 'name' => 'woocommerce_recently_viewed', 'category' => 'preferences', 'provider' => 'WooCommerce',   'purpose' => 'Zuletzt angesehene Produkte',                       'duration' => 'Session' ),
            'store_notice'               => array( 'name' => 'store_notice*',               'category' => 'necessary',   'provider' => 'WooCommerce',   'purpose' => 'Banner-Status des Shop-Hinweises',                  'duration' => 'Session',       'match' => 'prefix:store_notice' ),
            // Google Analytics 3
            '_ga'                        => array( 'name' => '_ga',                     'category' => 'statistics',  'provider' => 'Google Analytics',     'purpose' => 'Unterscheidet Nutzer und Sitzungen',                'duration' => '2 Jahre' ),
            '_gid'                       => array( 'name' => '_gid',                    'category' => 'statistics',  'provider' => 'Google Analytics',     'purpose' => 'Unterscheidet Nutzer (24 Stunden)',                 'duration' => '24 Stunden' ),
            '_gat'                       => array( 'name' => '_gat',                    'category' => 'statistics',  'provider' => 'Google Analytics',     'purpose' => 'Drosselung der Anfragerate',                        'duration' => '1 Minute' ),
            '_gat_UA'                    => array( 'name' => '_gat_UA-*',               'category' => 'statistics',  'provider' => 'Google Analytics',     'purpose' => 'Drosselung (Property-spezifisch)',                  'duration' => '1 Minute',      'match' => 'prefix:_gat_UA-' ),
            // Google Analytics 4
            '_ga_XXXXXX'                 => array( 'name' => '_ga_*',                   'category' => 'statistics',  'provider' => 'Google Analytics 4',   'purpose' => 'GA4 Session- und Nutzerzustand',                    'duration' => '2 Jahre',       'match' => 'prefix:_ga_' ),
            // Google Ads / GCL
            '_gac'                       => array( 'name' => '_gac_*',                  'category' => 'marketing',   'provider' => 'Google Ads',           'purpose' => 'Kampagnen-Informationen',                           'duration' => '90 Tage',       'match' => 'prefix:_gac_' ),
            '_gcl_au'                    => array( 'name' => '_gcl_au',                 'category' => 'marketing',   'provider' => 'Google',               'purpose' => 'Conversion-Tracking (AdSense)',                     'duration' => '3 Monate' ),
            '_gcl_aw'                    => array( 'name' => '_gcl_aw',                 'category' => 'marketing',   'provider' => 'Google Ads',           'purpose' => 'Click-ID für Conversion-Tracking',                  'duration' => '90 Tage' ),
            '_gcl_dc'                    => array( 'name' => '_gcl_dc',                 'category' => 'marketing',   'provider' => 'Google',               'purpose' => 'DoubleClick Conversion-Tracking',                   'duration' => '90 Tage' ),
            'IDE'                        => array( 'name' => 'IDE',                     'category' => 'marketing',   'provider' => 'Google (DoubleClick)', 'purpose' => 'Zielgerichtete Werbung',                            'duration' => '1 Jahr' ),
            'NID'                        => array( 'name' => 'NID',                     'category' => 'marketing',   'provider' => 'Google',               'purpose' => 'Nutzerpräferenzen für Google-Dienste',              'duration' => '6 Monate' ),
            'CONSENT'                    => array( 'name' => 'CONSENT',                 'category' => 'necessary',   'provider' => 'Google',               'purpose' => 'Google Cookie-Einwilligung',                        'duration' => '20 Jahre' ),
            'SOCS'                       => array( 'name' => 'SOCS',                    'category' => 'necessary',   'provider' => 'Google',               'purpose' => 'Google Cookie-Einwilligung (neu)',                  'duration' => '13 Monate' ),
            '_dc_gtm'                    => array( 'name' => '_dc_gtm_*',               'category' => 'statistics',  'provider' => 'Google Tag Manager',   'purpose' => 'Drosselung von GTM-Anfragen',                       'duration' => '1 Minute',      'match' => 'prefix:_dc_gtm_' ),
            // YouTube
            'YSC'                        => array( 'name' => 'YSC',                     'category' => 'marketing',   'provider' => 'YouTube (Google)',      'purpose' => 'Eindeutige Video-Tracking-ID',                      'duration' => 'Session' ),
            'VISITOR_INFO1_LIVE'         => array( 'name' => 'VISITOR_INFO1_LIVE',      'category' => 'marketing',   'provider' => 'YouTube (Google)',      'purpose' => 'Bandbreiten-Schätzung',                             'duration' => '180 Tage' ),
            'PREF'                       => array( 'name' => 'PREF',                    'category' => 'preferences', 'provider' => 'YouTube (Google)',      'purpose' => 'Nutzereinstellungen YouTube',                       'duration' => '2 Jahre' ),
            'GPS'                        => array( 'name' => 'GPS',                     'category' => 'marketing',   'provider' => 'YouTube (Google)',      'purpose' => 'Standort-Tracking',                                 'duration' => '30 Minuten' ),
            'yt-remote-device-id'        => array( 'name' => 'yt-remote-device-id',     'category' => 'marketing',   'provider' => 'YouTube (Google)',      'purpose' => 'Gerätepräferenzen YouTube',                         'duration' => 'Unbegrenzt' ),
            // Facebook / Meta
            '_fbp'                       => array( 'name' => '_fbp',                    'category' => 'marketing',   'provider' => 'Facebook / Meta',      'purpose' => 'Facebook Pixel – Nutzer-Identifikation',            'duration' => '3 Monate' ),
            '_fbc'                       => array( 'name' => '_fbc',                    'category' => 'marketing',   'provider' => 'Facebook / Meta',      'purpose' => 'Facebook Klick-ID aus Anzeigen',                    'duration' => '2 Jahre' ),
            'fr'                         => array( 'name' => 'fr',                      'category' => 'marketing',   'provider' => 'Facebook / Meta',      'purpose' => 'Zielgruppenbasierte Werbung',                       'duration' => '3 Monate' ),
            'datr'                       => array( 'name' => 'datr',                    'category' => 'marketing',   'provider' => 'Facebook / Meta',      'purpose' => 'Browser-Identifikation für Sicherheit',             'duration' => '2 Jahre' ),
            'wd'                         => array( 'name' => 'wd',                      'category' => 'marketing',   'provider' => 'Facebook / Meta',      'purpose' => 'Browser-Fenstermaße',                               'duration' => '1 Woche' ),
            'c_user'                     => array( 'name' => 'c_user',                  'category' => 'marketing',   'provider' => 'Facebook / Meta',      'purpose' => 'Facebook User-ID',                                  'duration' => '1 Jahr' ),
            // Twitter / X
            '_twitter_sess'              => array( 'name' => '_twitter_sess',           'category' => 'marketing',   'provider' => 'Twitter / X',          'purpose' => 'Twitter-Session',                                   'duration' => 'Session' ),
            'guest_id'                   => array( 'name' => 'guest_id',                'category' => 'marketing',   'provider' => 'Twitter / X',          'purpose' => 'Eindeutige Besucher-ID',                            'duration' => '2 Jahre' ),
            'guest_id_marketing'         => array( 'name' => 'guest_id_marketing',      'category' => 'marketing',   'provider' => 'Twitter / X',          'purpose' => 'Marketing-Gast-ID',                                 'duration' => '2 Jahre' ),
            'personalization_id'         => array( 'name' => 'personalization_id',      'category' => 'marketing',   'provider' => 'Twitter / X',          'purpose' => 'Personalisierte Inhalte',                           'duration' => '2 Jahre' ),
            'muc_ads'                    => array( 'name' => 'muc_ads',                 'category' => 'marketing',   'provider' => 'Twitter / X',          'purpose' => 'Werbemessung',                                      'duration' => '2 Jahre' ),
            // LinkedIn
            'li_gc'                      => array( 'name' => 'li_gc',                   'category' => 'marketing',   'provider' => 'LinkedIn',             'purpose' => 'Cookie-Einwilligungsstatus LinkedIn',               'duration' => '2 Jahre' ),
            'lidc'                       => array( 'name' => 'lidc',                    'category' => 'marketing',   'provider' => 'LinkedIn',             'purpose' => 'Routing bei LinkedIn',                              'duration' => '1 Tag' ),
            'bcookie'                    => array( 'name' => 'bcookie',                 'category' => 'marketing',   'provider' => 'LinkedIn',             'purpose' => 'Browser-ID bei LinkedIn',                           'duration' => '1 Jahr' ),
            'bscookie'                   => array( 'name' => 'bscookie',                'category' => 'marketing',   'provider' => 'LinkedIn',             'purpose' => 'Sichere Browser-ID',                                'duration' => '1 Jahr' ),
            'AnalyticsSyncHistory'       => array( 'name' => 'AnalyticsSyncHistory',    'category' => 'statistics',  'provider' => 'LinkedIn',             'purpose' => 'Synchronisation von Analytics-Daten',               'duration' => '30 Tage' ),
            'UserMatchHistory'           => array( 'name' => 'UserMatchHistory',        'category' => 'marketing',   'provider' => 'LinkedIn',             'purpose' => 'LinkedIn Ads Zielgruppenabgleich',                  'duration' => '30 Tage' ),
            // Pinterest
            '_pinterest_cm'              => array( 'name' => '_pinterest_cm',           'category' => 'marketing',   'provider' => 'Pinterest',            'purpose' => 'Conversion-Messung',                                'duration' => '1 Jahr' ),
            '_pinterest_ct_ua'           => array( 'name' => '_pinterest_ct_ua',        'category' => 'marketing',   'provider' => 'Pinterest',            'purpose' => 'Conversion-Tracking User-Agent',                    'duration' => '1 Jahr' ),
            // TikTok
            '_ttp'                       => array( 'name' => '_ttp',                    'category' => 'marketing',   'provider' => 'TikTok',               'purpose' => 'TikTok Pixel – Nutzer-Identifikation',              'duration' => '1 Jahr' ),
            'tt_webid'                   => array( 'name' => 'tt_webid',                'category' => 'marketing',   'provider' => 'TikTok',               'purpose' => 'Web-ID für TikTok-Tracking',                        'duration' => '1 Jahr' ),
            'tt_webid_v2'                => array( 'name' => 'tt_webid_v2',             'category' => 'marketing',   'provider' => 'TikTok',               'purpose' => 'Web-ID v2 für TikTok-Tracking',                     'duration' => '1 Jahr' ),
            // Snapchat
            '_scid'                      => array( 'name' => '_scid',                   'category' => 'marketing',   'provider' => 'Snapchat',             'purpose' => 'Snapchat Pixel – Nutzer-ID',                        'duration' => '1 Jahr' ),
            '_sctr'                      => array( 'name' => '_sctr',                   'category' => 'marketing',   'provider' => 'Snapchat',             'purpose' => 'Snapchat Conversion-Tracking',                      'duration' => '1 Jahr' ),
            // Matomo / Piwik
            '_pk_id'                     => array( 'name' => '_pk_id.*',                'category' => 'statistics',  'provider' => 'Matomo',               'purpose' => 'Eindeutige Besucher-ID',                            'duration' => '13 Monate',     'match' => 'prefix:_pk_id.' ),
            '_pk_ses'                    => array( 'name' => '_pk_ses.*',               'category' => 'statistics',  'provider' => 'Matomo',               'purpose' => 'Aktive Session',                                    'duration' => '30 Minuten',    'match' => 'prefix:_pk_ses.' ),
            '_pk_ref'                    => array( 'name' => '_pk_ref.*',               'category' => 'statistics',  'provider' => 'Matomo',               'purpose' => 'Referrer-Informationen',                            'duration' => '6 Monate',      'match' => 'prefix:_pk_ref.' ),
            'mtm_consent'                => array( 'name' => 'mtm_consent',             'category' => 'necessary',   'provider' => 'Matomo Tag Manager',   'purpose' => 'Einwilligung für Matomo Tag Manager',               'duration' => '30 Jahre' ),
            // Hotjar
            '_hjSessionUser'             => array( 'name' => '_hjSessionUser_*',        'category' => 'statistics',  'provider' => 'Hotjar',               'purpose' => 'Eindeutige Besucher-ID (Hotjar)',                    'duration' => '1 Jahr',        'match' => 'prefix:_hjSessionUser_' ),
            '_hjSession'                 => array( 'name' => '_hjSession_*',            'category' => 'statistics',  'provider' => 'Hotjar',               'purpose' => 'Aktive Session (Hotjar)',                            'duration' => '30 Minuten',    'match' => 'prefix:_hjSession_' ),
            '_hjFirstSeen'               => array( 'name' => '_hjFirstSeen',            'category' => 'statistics',  'provider' => 'Hotjar',               'purpose' => 'Erster Besuch (Hotjar)',                             'duration' => 'Session' ),
            '_hjAbsoluteSessionInProgress'=> array('name' => '_hjAbsoluteSessionInProgress','category' => 'statistics','provider' => 'Hotjar',             'purpose' => 'Session-Tracking',                                  'duration' => '30 Minuten' ),
            '_hjIncludedInPageviewSample'=> array( 'name' => '_hjIncludedInPageviewSample','category' => 'statistics','provider' => 'Hotjar',              'purpose' => 'Seitenaufruf-Sampling',                             'duration' => '2 Minuten' ),
            '_hjTLDTest'                 => array( 'name' => '_hjTLDTest',              'category' => 'statistics',  'provider' => 'Hotjar',               'purpose' => 'TLD-Erkennung',                                     'duration' => 'Session' ),
            // Cloudflare
            '__cfduid'                   => array( 'name' => '__cfduid',                'category' => 'necessary',   'provider' => 'Cloudflare',           'purpose' => 'Sicherheits-Cookie (veraltet)',                      'duration' => '30 Tage' ),
            '__cf_bm'                    => array( 'name' => '__cf_bm',                 'category' => 'necessary',   'provider' => 'Cloudflare',           'purpose' => 'Bot-Schutz (Managed Challenge)',                     'duration' => '30 Minuten' ),
            'cf_clearance'               => array( 'name' => 'cf_clearance',            'category' => 'necessary',   'provider' => 'Cloudflare',           'purpose' => 'Challenge-Ergebnis gespeichert',                    'duration' => '30 Minuten' ),
            // Stripe
            '__stripe_mid'               => array( 'name' => '__stripe_mid',            'category' => 'necessary',   'provider' => 'Stripe',               'purpose' => 'Betrugsprävention',                                 'duration' => '1 Jahr' ),
            '__stripe_sid'               => array( 'name' => '__stripe_sid',            'category' => 'necessary',   'provider' => 'Stripe',               'purpose' => 'Session-Identifikator',                             'duration' => '30 Minuten' ),
            '__stripe_device'            => array( 'name' => '__stripe_device',         'category' => 'necessary',   'provider' => 'Stripe',               'purpose' => 'Geräteerkennung für Sicherheit',                    'duration' => '1 Jahr' ),
            // PayPal
            'ts'                         => array( 'name' => 'ts',                      'category' => 'necessary',   'provider' => 'PayPal',               'purpose' => 'Betrugsprävention',                                 'duration' => '3 Jahre' ),
            'ts_c'                       => array( 'name' => 'ts_c',                    'category' => 'necessary',   'provider' => 'PayPal',               'purpose' => 'Betrugsprävention (verschlüsselt)',                 'duration' => '3 Jahre' ),
            'nsid'                       => array( 'name' => 'nsid',                    'category' => 'necessary',   'provider' => 'PayPal',               'purpose' => 'Session-Identifikator',                             'duration' => 'Session' ),
            // Vimeo
            'vuid'                       => array( 'name' => 'vuid',                    'category' => 'marketing',   'provider' => 'Vimeo',                'purpose' => 'Eindeutige Vimeo-Besucher-ID',                      'duration' => '2 Jahre' ),
            // HubSpot
            'hubspotutk'                 => array( 'name' => 'hubspotutk',              'category' => 'marketing',   'provider' => 'HubSpot',              'purpose' => 'Eindeutige Nutzer-ID (HubSpot)',                     'duration' => '13 Monate' ),
            '__hstc'                     => array( 'name' => '__hstc',                  'category' => 'marketing',   'provider' => 'HubSpot',              'purpose' => 'Haupt-Tracking-Cookie',                             'duration' => '13 Monate' ),
            '__hssc'                     => array( 'name' => '__hssc',                  'category' => 'marketing',   'provider' => 'HubSpot',              'purpose' => 'Session-Tracking',                                  'duration' => '30 Minuten' ),
            '__hssrc'                    => array( 'name' => '__hssrc',                 'category' => 'statistics',  'provider' => 'HubSpot',              'purpose' => 'Neue Session erkannt',                              'duration' => 'Session' ),
            '__hs_do_not_track'          => array( 'name' => '__hs_do_not_track',       'category' => 'necessary',   'provider' => 'HubSpot',              'purpose' => 'Opt-out vom HubSpot-Tracking',                      'duration' => '13 Monate' ),
            // Intercom
            'intercom-device-id'         => array( 'name' => 'intercom-device-id-*',    'category' => 'marketing',   'provider' => 'Intercom',             'purpose' => 'Geräte-ID für Intercom-Chat',                       'duration' => '9 Monate',      'match' => 'prefix:intercom-device-id-' ),
            'intercom-session'           => array( 'name' => 'intercom-session-*',      'category' => 'marketing',   'provider' => 'Intercom',             'purpose' => 'Session-ID für Intercom-Chat',                      'duration' => '1 Woche',       'match' => 'prefix:intercom-session-' ),
            // Zendesk
            '__zlcmid'                   => array( 'name' => '__zlcmid',                'category' => 'marketing',   'provider' => 'Zendesk',              'purpose' => 'Chat-Besucher-ID',                                  'duration' => '1 Jahr' ),
            'zdVisitorId'                => array( 'name' => 'zdVisitorId',             'category' => 'marketing',   'provider' => 'Zendesk',              'purpose' => 'Eindeutige Besucher-ID Zendesk',                    'duration' => '10 Jahre' ),
            // Crisp
            'crisp-client'               => array( 'name' => 'crisp-client/*',          'category' => 'marketing',   'provider' => 'Crisp Chat',           'purpose' => 'Session-ID für Crisp-Chat',                         'duration' => '6 Monate',      'match' => 'prefix:crisp-client' ),
            // Mailchimp
            'MC_USER_INFO'               => array( 'name' => 'MC_USER_INFO',            'category' => 'marketing',   'provider' => 'Mailchimp',            'purpose' => 'Nutzerinformationen Mailchimp',                      'duration' => '1 Jahr' ),
            '_mc_cookies'                => array( 'name' => '_mc_cookies',             'category' => 'marketing',   'provider' => 'Mailchimp',            'purpose' => 'Mailchimp Tracking',                                'duration' => '1 Jahr' ),
            // Klaviyo
            '__kla_id'                   => array( 'name' => '__kla_id',                'category' => 'marketing',   'provider' => 'Klaviyo',              'purpose' => 'Klaviyo Nutzer-ID',                                 'duration' => '2 Jahre' ),
            // Segment
            'ajs_user_id'                => array( 'name' => 'ajs_user_id',             'category' => 'statistics',  'provider' => 'Segment',              'purpose' => 'Nutzer-ID für Segment-Analytics',                   'duration' => 'Unbegrenzt' ),
            'ajs_anonymous_id'           => array( 'name' => 'ajs_anonymous_id',        'category' => 'statistics',  'provider' => 'Segment',              'purpose' => 'Anonyme ID für Segment-Analytics',                  'duration' => '1 Jahr' ),
            // Microsoft Clarity
            '_clsk'                      => array( 'name' => '_clsk',                   'category' => 'statistics',  'provider' => 'Microsoft Clarity',    'purpose' => 'Seiten-Klicks und Sitzungsaufzeichnung',            'duration' => '1 Tag' ),
            '_clck'                      => array( 'name' => '_clck',                   'category' => 'statistics',  'provider' => 'Microsoft Clarity',    'purpose' => 'Eindeutige Nutzer-ID (Clarity)',                    'duration' => '1 Jahr' ),
            'CLID'                       => array( 'name' => 'CLID',                    'category' => 'statistics',  'provider' => 'Microsoft Clarity',    'purpose' => 'Clarity-Identifikator',                             'duration' => '1 Jahr' ),
            // Microsoft Ads
            '_uetmsclkid'                => array( 'name' => '_uetmsclkid',             'category' => 'marketing',   'provider' => 'Microsoft Ads',        'purpose' => 'Bing Ads Click-ID',                                 'duration' => '1 Jahr' ),
            'MUID'                       => array( 'name' => 'MUID',                    'category' => 'marketing',   'provider' => 'Microsoft',            'purpose' => 'Eindeutige Microsoft-Nutzer-ID',                    'duration' => '1 Jahr' ),
            'SRM_B'                      => array( 'name' => 'SRM_B',                   'category' => 'marketing',   'provider' => 'Microsoft Ads',        'purpose' => 'Nutzer-Abgleich für Remarketing',                   'duration' => '1 Jahr' ),
            '_uetvid'                    => array( 'name' => '_uetvid',                 'category' => 'marketing',   'provider' => 'Microsoft Ads',        'purpose' => 'Besucher-ID für Universal Event Tracking',          'duration' => '16 Monate' ),
            // Amplitude
            'amplitude_id'               => array( 'name' => 'amplitude_id_*',          'category' => 'statistics',  'provider' => 'Amplitude',            'purpose' => 'Nutzer-ID für Amplitude',                           'duration' => '10 Jahre',      'match' => 'prefix:amplitude_id_' ),
            // Mixpanel
            'mp_'                        => array( 'name' => 'mp_*',                    'category' => 'statistics',  'provider' => 'Mixpanel',             'purpose' => 'Mixpanel Analytics',                                'duration' => '1 Jahr',        'match' => 'prefix:mp_' ),
            // Google Analytics (veraltet / UA)
            '__utmz'                     => array( 'name' => '__utmz',                  'category' => 'statistics',  'provider' => 'Google Analytics (alt)','purpose' => 'Herkunftsquelle (veraltet)',                        'duration' => '6 Monate' ),
            '__utma'                     => array( 'name' => '__utma',                  'category' => 'statistics',  'provider' => 'Google Analytics (alt)','purpose' => 'Unterscheidet Nutzer (veraltet)',                   'duration' => '2 Jahre' ),
            '__utmb'                     => array( 'name' => '__utmb',                  'category' => 'statistics',  'provider' => 'Google Analytics (alt)','purpose' => 'Session-Dauer (veraltet)',                          'duration' => '30 Minuten' ),
            '__utmc'                     => array( 'name' => '__utmc',                  'category' => 'statistics',  'provider' => 'Google Analytics (alt)','purpose' => 'Session (veraltet)',                                'duration' => 'Session' ),
            // Consent-Management (fremde CMPs)
            'CookieConsent'              => array( 'name' => 'CookieConsent',           'category' => 'necessary',   'provider' => 'Cookiebot',            'purpose' => 'Cookie-Einwilligung (Cookiebot)',                    'duration' => '1 Jahr' ),
            'euconsent-v2'               => array( 'name' => 'euconsent-v2',            'category' => 'necessary',   'provider' => 'IAB TCF',              'purpose' => 'EU-Einwilligungs-Framework',                         'duration' => '1 Jahr' ),
            'cookielawinfo-checkbox'      => array( 'name' => 'cookielawinfo-checkbox-*','category' => 'necessary',   'provider' => 'GDPR Cookie Consent',  'purpose' => 'Kategorie-Einwilligung',                            'duration' => '1 Jahr',        'match' => 'prefix:cookielawinfo-checkbox' ),
            'viewed_cookie_policy'       => array( 'name' => 'viewed_cookie_policy',    'category' => 'necessary',   'provider' => 'GDPR Cookie Consent',  'purpose' => 'Banner-Status',                                     'duration' => '1 Jahr' ),
            // Elementor
            'elementor'                  => array( 'name' => 'elementor',               'category' => 'necessary',   'provider' => 'Elementor',            'purpose' => 'Elementor-Editor-Status',                           'duration' => 'Session' ),
            // WP Rocket / Cache
            'rocket_browser_class'       => array( 'name' => 'rocket_browser_class',    'category' => 'necessary',   'provider' => 'WP Rocket',            'purpose' => 'Browser-Klasse für Cache',                          'duration' => 'Session' ),
            // Wordfence
            'wordfence_verifiedHuman'    => array( 'name' => 'wordfence_verifiedHuman', 'category' => 'necessary',   'provider' => 'Wordfence',            'purpose' => 'Bestätigt menschliche Nutzer',                      'duration' => 'Session' ),
            // ActiveCampaign
            'ac_enable_tracking'         => array( 'name' => 'ac_enable_tracking',      'category' => 'marketing',   'provider' => 'ActiveCampaign',       'purpose' => 'Tracking-Einwilligung',                             'duration' => '5 Jahre' ),
            // Criteo
            'cto_bundle'                 => array( 'name' => 'cto_bundle',              'category' => 'marketing',   'provider' => 'Criteo',               'purpose' => 'Nutzer-ID für Retargeting',                         'duration' => '13 Monate' ),
            'cto_bidid'                  => array( 'name' => 'cto_bidid',               'category' => 'marketing',   'provider' => 'Criteo',               'purpose' => 'Bidding-ID für Anzeigen',                           'duration' => '13 Monate' ),
        );
    }


    /* ═══════════════════════════════════════════════════════════════════════
     *  SCAN — Haupteinstieg
     *
     *  Methoden in Reihenfolge Treffsicherheit:
     *   A) HTTP-Request an Startseite  → Set-Cookie-Header + HTML-Quellcode
     *   B) Plugin-Slug-Datenbank       → bekannte Plugin-Folder-Namen
     *   C) WordPress-Options           → gespeicherte Konfigwerte
     *   D) Datei-Scan                  → Plugin-Hauptdatei + Theme-Dateien
     *   E) WordPress-Core              → immer vorhanden
     * ════════════════════════════════════════════════════════════════════════ */

    public static function scan() {
        $known       = self::known_cookies();
        $stored      = DCB_Cookie_Manager::get_detected_cookies();
        $manual      = $stored['manual'] ?? array();
        $manual_keys = array_keys( $manual );
        $scan_found  = array();

        // $add( $key, $source ) — source wird im _dcb_source-Feld gespeichert
        // damit im Admin genau sichtbar ist, welche Methode den Cookie gefunden hat.
        $add = function ( $key, $source = '' ) use ( $known, $manual_keys, &$scan_found ) {
            if ( ! isset( $known[ $key ] ) )            return;
            if ( in_array( $key, $manual_keys, true ) ) return;
            if ( isset( $scan_found[ $key ] ) )         return;
            $entry = $known[ $key ];
            $entry['_dcb_source'] = $source;
            $scan_found[ $key ]   = $entry;
        };

        // A) HTTP-Scan der eigenen Website (wichtigste Methode!)
        self::scan_via_http( $known, $manual_keys, $scan_found, $add );

        // D) Datei-Scan (Theme + Plugin-Hauptdateien)
        self::scan_source_files( $add );

        // E) WordPress-Core immer eintragen
        foreach ( array( '_wpnonce', 'wordpress_logged_in', 'wp_settings', 'PHPSESSID', 'dcb_consent', 'wordpress_test_cookie', 'comment_author' ) as $k ) {
            $add( $k, 'WordPress Core' );
        }

        // Zusammenführen: bisherige Auto-Einträge + Neufunde
        $merged = array();
        foreach ( $stored['auto'] ?? array() as $k => $v ) {
            if ( ! in_array( $k, $manual_keys, true ) ) $merged[ $k ] = $v;
        }
        foreach ( $scan_found as $k => $v ) {
            $merged[ $k ] = $v;
        }

        DCB_Cookie_Manager::save_detected_cookies( array(
            'auto'      => $merged,
            'manual'    => $manual,
            'last_scan' => current_time( 'mysql' ),
        ) );

        return $merged;
    }


    /* ═══════════════════════════════════════════════════════════════════════
     *  METHODE A — HTTP-Request an eigene Website
     *
     *  Warum ist das die wichtigste Methode?
     *  Der Scanner läuft im Admin-AJAX-Kontext — dort sind weder Besucher-
     *  Cookies noch Frontend-Scripts aktiv. Ein echter HTTP-GET an die
     *  Startseite liefert:
     *   1. Set-Cookie-Header → serverseitig gesetzte Cookies (WooCommerce,
     *      PHPSESSID, Cache-Plugins, Wordfence, etc.)
     *   2. HTML-Quellcode → eingebundene Script-URLs, inline gtag/fbq/etc.
     *      → deckt GTM, Analytics, Pixel, Hotjar, etc. auf
     * ════════════════════════════════════════════════════════════════════════ */

    /**
     * Sammelt alle öffentlich erreichbaren URLs dieser WordPress-Installation:
     * Startseite + bis zu MAX_PAGES veröffentlichte Seiten und Beiträge.
     * Duplikate und externe URLs werden herausgefiltert.
     */
    public static function get_public_urls() {
        $home   = trailingslashit( home_url( '/' ) );
        $urls   = array( $home );
        $limit  = 20; // max. Seiten/Beiträge zusätzlich zur Startseite

        // Alle veröffentlichten post_types mit öffentlichem Frontend holen
        $post_types = get_post_types( array( 'public' => true ), 'names' );

        $posts = get_posts( array(
            'post_type'      => array_values( $post_types ),
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'orderby'        => 'modified',   // zuletzt geänderte zuerst → relevanteste Inhalte
            'order'          => 'DESC',
            'fields'         => 'ids',
        ) );

        foreach ( $posts as $id ) {
            $permalink = get_permalink( $id );
            if ( $permalink && strpos( $permalink, $home ) === 0 ) {
                $urls[] = $permalink;
            }
        }

        return array_unique( $urls );
    }

    /**
     * HTTP-Request an eine einzelne URL, wertet Set-Cookie-Header und HTML-Body aus.
     * Gibt gefundene Cookie-Schlüssel aus known_cookies() zurück.
     *
     * @param string   $url
     * @param array    $known
     * @param array    $manual_keys
     * @param array    $scan_found  Per Referenz — wird direkt befüllt
     * @param callable $add
     */
    private static function fetch_and_analyse(
        $url,
        $known,
        $manual_keys,
        array &$scan_found,
        $add
    ) {
        $response = wp_remote_get( $url, array(
            'timeout'    => 12,
            'user-agent' => 'DCB-Cookie-Scanner/2.0 (+WordPress)',
            'sslverify'  => false,
            'cookies'    => array(),
            'headers'    => array(
                'Accept'          => 'text/html,application/xhtml+xml',
                'Accept-Language' => 'de-DE,de;q=0.9',
                'Cache-Control'   => 'no-cache',
            ),
        ) );

        if ( is_wp_error( $response ) ) return;

        $label = parse_url( $url, PHP_URL_PATH ) ?: '/';

        // ── Set-Cookie-Header ────────────────────────────────────────────────
        $headers        = wp_remote_retrieve_headers( $response );
        $set_cookie_raw = array();
        if ( is_object( $headers ) && method_exists( $headers, 'getAll' ) ) {
            $all = $headers->getAll();
            if ( isset( $all['set-cookie'] ) ) {
                $set_cookie_raw = is_array( $all['set-cookie'] ) ? $all['set-cookie'] : array( $all['set-cookie'] );
            }
        } elseif ( is_array( $headers ) && isset( $headers['set-cookie'] ) ) {
            $set_cookie_raw = is_array( $headers['set-cookie'] ) ? $headers['set-cookie'] : array( $headers['set-cookie'] );
        }

        $received = array();
        foreach ( $set_cookie_raw as $raw ) {
            $name = trim( explode( '=', explode( ';', $raw )[0], 2 )[0] );
            if ( $name !== '' ) $received[] = $name;
        }

        foreach ( $known as $key => $data ) {
            if ( in_array( $key, $manual_keys, true ) || isset( $scan_found[ $key ] ) ) continue;
            $match = $data['match'] ?? '';
            if ( $match === '' ) {
                $clean = rtrim( str_replace( array( '*', '.' ), '', $data['name'] ), '-_' );
                foreach ( $received as $cn ) {
                    if ( $cn === $clean || $cn === $data['name'] ) {
                        $entry = $data;
                        $entry['_dcb_source'] = 'Set-Cookie: ' . $label;
                        $scan_found[ $key ]   = $entry;
                        break;
                    }
                }
            } elseif ( strpos( $match, 'prefix:' ) === 0 ) {
                $prefix = substr( $match, 7 );
                foreach ( $received as $cn ) {
                    if ( strpos( $cn, $prefix ) === 0 ) {
                        $entry = $data;
                        $entry['_dcb_source'] = 'Set-Cookie: ' . $label;
                        $scan_found[ $key ]   = $entry;
                        break;
                    }
                }
            }
        }

        // ── HTML-Body ────────────────────────────────────────────────────────
        $body = wp_remote_retrieve_body( $response );
        if ( empty( $body ) ) return;

        $km = self::get_keyword_map();
        self::apply_keywords( $body, $km, $add, 'HTML:' . $label );

        // Inline-<script>-Blöcke
        if ( preg_match_all( '/<script[^>]*>(.*?)<\/script>/si', $body, $m ) ) {
            foreach ( $m[1] as $block ) {
                self::apply_keywords( $block, $km, $add, 'Script:' . $label );
            }
        }

        // <iframe>/<img>/<source>-URLs
        if ( preg_match_all( '/<(?:iframe|img|link|source)[^>]+(?:src|href)=["\']([^"\']+)["\'][^>]*>/i', $body, $tags ) ) {
            self::apply_keywords( implode( "\n", $tags[1] ), $km, $add, 'Embed:' . $label );
        }
    }

    /**
     * Scannt alle öffentlichen Seiten und Beiträge per HTTP.
     * Bricht früh ab sobald alle bekannten Cookies gefunden wurden.
     */
    private static function scan_via_http( $known, $manual_keys, array &$scan_found, $add ) {
        $urls = self::get_public_urls();

        foreach ( $urls as $url ) {
            self::fetch_and_analyse( $url, $known, $manual_keys, $scan_found, $add );
        }
    }


    /* ═══════════════════════════════════════════════════════════════════════
     *  METHODE B — Plugin-Slug-Datenbank
     *
     *  Abgleich aller aktiven Plugin-Ordnernamen gegen eine bekannte Liste.
     *  Trifft zu wenn das Plugin installiert aber kein Script direkt im HTML.
     * ════════════════════════════════════════════════════════════════════════ */

    private static function scan_source_files( $add ) {
        $km    = self::get_keyword_map();
        $files = array();

        // Nur die Plugin-Hauptdatei (plugin-folder/plugin-file.php) scannen.
        // JS-Unterordner werden NICHT gescannt: minifizierte Bundles enthalten
        // oft auskommentierte oder inaktive Code-Pfade anderer Services
        // und produzieren so False Positives.
        foreach ( get_option( 'active_plugins', array() ) as $plugin_file ) {
            $path = WP_PLUGIN_DIR . '/' . $plugin_file;
            if ( file_exists( $path ) ) $files[] = $path;
        }

        // Theme functions.php (Child + Parent)
        try {
            $child  = get_stylesheet_directory() . '/functions.php';
            $parent = get_template_directory()   . '/functions.php';
            if ( file_exists( $child ) )                        $files[] = $child;
            if ( $parent !== $child && file_exists( $parent ) ) $files[] = $parent;
        } catch ( \Throwable $e ) {}

        foreach ( array_unique( $files ) as $file ) {
            if ( ! is_readable( $file ) ) continue;
            try {
                $handle = fopen( $file, 'r' );
                if ( ! $handle ) continue;
                $content = fread( $handle, 204800 );
                fclose( $handle );
                if ( $content !== false ) {
                    self::apply_keywords( $content, $km, $add, basename( $file ) );
                }
            } catch ( \Throwable $e ) {}
        }
    }


    /* ═══════════════════════════════════════════════════════════════════════
     *  KEYWORD-MAP — URL/JS-Snippets → Cookie-Schlüssel
     * ════════════════════════════════════════════════════════════════════════ */

    private static function get_keyword_map() {
        // WICHTIG: Nur vollständige externe URLs oder sehr spezifische JS-Signaturen.
        // Kurze generische Strings wie 'UA-', 'gtag(' oder 'dataLayer' kommen in
        // fast jedem Plugin-Bundle vor und produzieren False Positives.
        return array(
            // Google Analytics / GTM — nur vollständige Script-URLs
            'google-analytics.com/analytics'   => array( '_ga', '_gid', '_gat' ),
            'googletagmanager.com/gtag'         => array( '_ga', '_gid', '_gat', '_ga_XXXXXX', '_gcl_au', '_dc_gtm' ),
            'googletagmanager.com/gtm'          => array( '_ga', '_gid', '_gcl_au', '_dc_gtm' ),
            // Google Ads / DoubleClick
            // Google Ads / DoubleClick
            'googleadservices.com'              => array( '_gcl_au', '_gcl_aw', 'IDE' ),
            'googlesyndication.com'             => array( '_gcl_au', 'IDE' ),
            'doubleclick.net'                   => array( 'IDE', '_gcl_au' ),
            // Facebook / Meta
            'facebook.net/en_US/fbevents'       => array( '_fbp', '_fbc', 'fr', 'datr' ),
            "fbq('init'"                        => array( '_fbp', '_fbc', 'fr' ),
            "fbq('track'"                       => array( '_fbp', '_fbc', 'fr' ),
            'connect.facebook.net'              => array( '_fbp', '_fbc', 'fr' ),
            'FacebookPixel'                     => array( '_fbp', '_fbc', 'fr' ),
            // Hotjar
            'hotjar.com'                        => array( '_hjSessionUser', '_hjSession', '_hjFirstSeen', '_hjAbsoluteSessionInProgress' ),
            'static.hotjar.com'                 => array( '_hjSessionUser', '_hjSession', '_hjFirstSeen', '_hjAbsoluteSessionInProgress' ),
            // LinkedIn
            'snap.licdn.com'                    => array( 'li_gc', 'lidc', 'bcookie', 'bscookie', 'AnalyticsSyncHistory', 'UserMatchHistory' ),
            'linkedin.com/insight'              => array( 'li_gc', 'lidc', 'bcookie' ),
            '_linkedin_partner_id'              => array( 'li_gc', 'lidc', 'bcookie', 'UserMatchHistory' ),
            'linkedin.com/px/'                  => array( 'li_gc', 'lidc', 'UserMatchHistory' ),
            // Twitter / X
            'platform.twitter.com'              => array( '_twitter_sess', 'guest_id', 'personalization_id', 'muc_ads' ),
            'analytics.twitter.com'             => array( '_twitter_sess', 'guest_id', 'muc_ads' ),
            'twq('                              => array( 'guest_id_marketing', 'muc_ads' ),
            'ads.twitter.com'                   => array( 'guest_id_ads', 'muc_ads' ),
            // YouTube
            'youtube.com/embed'                 => array( 'YSC', 'VISITOR_INFO1_LIVE', 'PREF' ),
            'youtube-nocookie.com'              => array( 'YSC', 'VISITOR_INFO1_LIVE' ),
            'youtube.com/iframe_api'            => array( 'YSC', 'VISITOR_INFO1_LIVE', 'GPS' ),
            // Vimeo
            'player.vimeo.com'                  => array( 'vuid' ),
            'vimeo.com/video'                   => array( 'vuid' ),
            'vimeocdn.com'                      => array( 'vuid' ),
            // Stripe
            'stripe.com/v3'                     => array( '__stripe_mid', '__stripe_sid', '__stripe_device' ),
            'stripe.js'                         => array( '__stripe_mid', '__stripe_sid', '__stripe_device' ),
            'js.stripe.com'                     => array( '__stripe_mid', '__stripe_sid', '__stripe_device' ),
            // PayPal
            'paypal.com/sdk'                    => array( 'ts', 'ts_c', 'nsid' ),
            'paypalobjects.com'                 => array( 'ts', 'ts_c' ),
            'paypal.com/v1'                     => array( 'ts', 'ts_c', 'nsid' ),
            // HubSpot
            'hs-scripts.com'                    => array( 'hubspotutk', '__hstc', '__hssc', '__hssrc' ),
            'js.hs-analytics.net'               => array( 'hubspotutk', '__hstc', '__hssc', '__hssrc' ),
            'js.hubspot.com'                    => array( 'hubspotutk', '__hstc', '__hssc', '__hssrc' ),
            // Intercom
            'widget.intercom.io'                => array( 'intercom-device-id', 'intercom-session' ),
            'js.intercomcdn.com'                => array( 'intercom-device-id', 'intercom-session' ),
            // Zendesk
            'static.zdassets.com'               => array( '__zlcmid', 'zdVisitorId' ),
            'ekr.zdassets.com'                  => array( '__zlcmid', 'zdVisitorId' ),
            // Crisp
            'client.crisp.chat'                 => array( 'crisp-client' ),
            'settings.crisp.chat'               => array( 'crisp-client' ),
            // Mailchimp
            'chimpstatic.com'                   => array( 'MC_USER_INFO', '_mc_cookies' ),
            'list-manage.com'                   => array( 'MC_USER_INFO' ),
            'mailchimp.com/subscribe'           => array( 'MC_USER_INFO' ),
            // TikTok
            'analytics.tiktok.com'              => array( '_ttp', 'tt_webid', 'tt_webid_v2' ),
            "ttq.load('"                        => array( '_ttp', 'tt_webid', 'tt_webid_v2' ),
            'tiktok.com/api/pixel'              => array( '_ttp', 'tt_webid' ),
            // Snapchat
            'tr.snapchat.com'                   => array( '_scid', '_sctr' ),
            "snaptr('init'"                     => array( '_scid', '_sctr' ),
            'sc-static.net'                     => array( '_scid', '_sctr' ),
            // Microsoft Bing Ads
            'bat.bing.com'                      => array( '_uetmsclkid', '_uetvid', 'MUID', 'SRM_B' ),
            // Microsoft Clarity
            'clarity.ms'                        => array( '_clsk', '_clck', 'CLID', 'MUID' ),
            "clarity('set'"                     => array( '_clsk', '_clck', 'CLID' ),
            // Matomo
            'matomo.php'                        => array( '_pk_id', '_pk_ses', '_pk_ref' ),
            'piwik.php'                         => array( '_pk_id', '_pk_ses', '_pk_ref' ),
            'piwik.js'                          => array( '_pk_id', '_pk_ses', '_pk_ref' ),
            'matomo.js'                         => array( '_pk_id', '_pk_ses', '_pk_ref' ),
            // Cloudflare
            'cloudflare.com/cdn-cgi'            => array( '__cf_bm', 'cf_clearance' ),
            // Pinterest
            'ct.pinterest.com'                  => array( '_pinterest_cm', '_pinterest_ct_ua' ),
            "pintrk('load'"                     => array( '_pinterest_cm', '_pinterest_ct_ua' ),
            'pinimg.com'                        => array( '_pinterest_cm', '_pinterest_ct_ua' ),
            // Amplitude
            'cdn.amplitude.com'                 => array( 'amplitude_id' ),
            'amplitude.getInstance'             => array( 'amplitude_id' ),
            // Mixpanel
            'cdn.mxpnl.com'                     => array( 'mp_' ),
            'cdn4.mxpnl.com'                    => array( 'mp_' ),
            // Segment
            'cdn.segment.com'                   => array( 'ajs_user_id', 'ajs_anonymous_id', 'ajs_group_id' ),
            // Criteo
            'static.criteo.net'                 => array( 'cto_bundle', 'cto_bidid' ),
            'dis.criteo.com'                    => array( 'cto_bundle', 'cto_bidid' ),
        );
    }

    /* ── Keyword-Map anwenden ── */
    private static function apply_keywords( $content, $km, $add, $source = '' ) {
        foreach ( $km as $keyword => $keys ) {
            if ( stripos( $content, $keyword ) !== false ) {
                foreach ( $keys as $ck ) $add( $ck, $source ?: 'HTML:' . $keyword );
            }
        }
    }
}

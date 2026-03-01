<?php
/**
 * Internationalisierung / Übersetzungssystem
 *
 * Statt dem Standard-WordPress-Gettext-System (das .mo-Dateien benötigt)
 * verwendet dieses Plugin ein eigenes Array-basiertes System, das:
 *  - keine externe Toolchain (msgfmt etc.) benötigt
 *  - im Backend über eine Einstellung umschaltbar ist
 *  - einfach um weitere Sprachen erweiterbar ist
 *  - dennoch vollständig mit wp_localize_script() kompatibel ist
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_I18n {

    /** Aktiv geladene Sprache */
    private static string $lang = 'de';

    /** Cache der geladenen Strings */
    private static array $strings = array();

    /** Verfügbare Sprachen */
    public static function available_languages(): array {
        return array(
            'de' => 'Deutsch',
            'en' => 'English',
        );
    }

    /**
     * Sprache initialisieren – muss früh aufgerufen werden (plugins_loaded).
     */
    public static function init(): void {
        $settings  = get_option( DCB_Cookie_Manager::OPTION_SETTINGS, array() );
        $saved     = $settings['plugin_language'] ?? '';

        if ( $saved && array_key_exists( $saved, self::available_languages() ) ) {
            self::$lang = $saved;
        } else {
            // WordPress-Locale als Fallback
            $locale = get_locale();
            self::$lang = str_starts_with( $locale, 'de' ) ? 'de' : 'en';
        }

        self::$strings = self::load( self::$lang );
    }

    public static function get_lang(): string {
        return self::$lang;
    }

    /**
     * Übersetzung abrufen.
     * @param string $key   Schlüssel aus dem Spracharray
     * @param string $default Fallback wenn Schlüssel fehlt
     */
    public static function t( string $key, string $default = '' ): string {
        return self::$strings[ $key ] ?? ( $default ?: $key );
    }

    /**
     * Gibt alle Strings als Array zurück (für wp_localize_script).
     */
    public static function all(): array {
        return self::$strings;
    }

    /**
     * Lädt die Strings für eine bestimmte Sprache.
     */
    private static function load( string $lang ): array {
        $strings_de = array(
            // ── Banner (Frontend) ────────────────────────────────────────────
            'banner_title'              => 'Wir verwenden Cookies',
            'banner_text'               => 'Wir verwenden Cookies und ähnliche Technologien, um unsere Website zu verbessern und Ihnen die bestmögliche Erfahrung zu bieten. Bitte wählen Sie, welche Cookies Sie akzeptieren möchten.',
            'accept_all'                => 'Alle akzeptieren',
            'accept_necessary'          => 'Nur notwendige',
            'customize'                 => 'Einstellungen',
            'save_settings'             => 'Einstellungen speichern',
            'privacy_link'              => 'Datenschutz',
            'imprint_link'              => 'Impressum',
            'always_active'             => 'Immer aktiv',
            'accept_all_modal'          => 'Alle akzeptieren',
            'necessary_only_modal'      => 'Nur notwendige',

            // ── Cookie-Kategorien ────────────────────────────────────────────
            'cat_necessary_label'       => 'Notwendig',
            'cat_necessary_desc'        => 'Diese Cookies sind für den Betrieb der Website unbedingt erforderlich und können nicht deaktiviert werden.',
            'cat_statistics_label'      => 'Statistik',
            'cat_statistics_desc'       => 'Diese Cookies helfen uns zu verstehen, wie Besucher mit der Website interagieren (z. B. Google Analytics).',
            'cat_marketing_label'       => 'Marketing',
            'cat_marketing_desc'        => 'Diese Cookies werden verwendet, um Werbeanzeigen relevanter für Sie zu gestalten.',
            'cat_preferences_label'     => 'Präferenzen',
            'cat_preferences_desc'      => 'Diese Cookies ermöglichen der Website, Informationen zu speichern, die Ihr Verhalten oder Ihr Aussehen beeinflussen.',

            // ── Shortcode-Ausgabe ────────────────────────────────────────────
            'no_cookies_found'          => 'Keine Cookies erkannt. Bitte führen Sie einen Scan im WordPress-Backend durch.',
            'last_scanned'              => 'Zuletzt gescannt:',
            'cookie_col_name'           => 'Name',
            'cookie_col_provider'       => 'Anbieter',
            'cookie_col_purpose'        => 'Zweck',
            'cookie_col_duration'       => 'Laufzeit',
            'open_settings_btn'         => 'Cookie-Einstellungen',
            'change_settings_btn'       => 'Cookie-Einstellungen ändern',

            // ── Admin: Allgemein ─────────────────────────────────────────────
            'admin_page_title'          => '🍪 DSGVO Cookie Banner',
            'admin_menu_label'          => 'Cookie Banner',
            'admin_settings_title'      => '🍪 DSGVO Cookie Banner – Einstellungen',
            'admin_scanner_title'       => '🔍 Cookie-Verwaltung',
            'admin_consents_title'      => '📋 Einwilligungsprotokoll',
            'admin_submenu_settings'    => 'Einstellungen',
            'admin_submenu_scanner'     => 'Cookie-Verwaltung',
            'admin_submenu_consents'    => 'Einwilligungen',
            'save_changes'              => 'Änderungen speichern',

            // ── Admin: Einstellungs-Tabs ─────────────────────────────────────
            'tab_banner'                => 'Banner-Text',
            'tab_design'                => 'Design',
            'tab_advanced'              => 'Erweitert',
            'tab_categories'            => 'Kategorien',
            'tab_language'              => 'Sprache',
            'tab_help'                  => 'Hilfe',

            // ── Admin: Kategorien Tab ────────────────────────────────────────
            'categories_tab_intro'      => 'Passen Sie hier die Cookie-Kategorien an. Der Shortcode-Schlüssel wird in [dcb_cookie_list] und der Blockierungs-Schlüssel für data-dcb-category verwendet.',
            'cat_label_field'           => 'Bezeichnung',
            'cat_description_field'     => 'Beschreibung',
            'cat_shortcode_key_field'   => 'Shortcode-Schlüssel',
            'cat_shortcode_key_desc'    => 'Verwendung: [dcb_cookie_list category="…"]',
            'cat_block_key_field'       => 'Blockierungs-Schlüssel',
            'cat_block_key_desc'        => 'Verwendung: data-dcb-category="…"',
            'cat_required_field'        => 'Immer aktiv (nicht deaktivierbar)',
            'cat_required_locked'       => '🔒 Gesperrt – notwendige Cookies können nicht deaktiviert werden',
            'cat_shortcode_example'     => 'Shortcode-Beispiel:',
            'cat_block_example'         => 'Script-Blockierung:',

            // ── Admin: Banner-Text Tab ───────────────────────────────────────
            'field_banner_title'        => 'Banner-Titel',
            'field_banner_text'         => 'Banner-Text',
            'field_accept_all'          => '„Alle akzeptieren"',
            'field_accept_necessary'    => '„Nur notwendige"',
            'field_customize'           => '„Einstellungen"',
            'field_save_settings'       => '„Einstellungen speichern"',

            // ── Admin: Design Tab ────────────────────────────────────────────
            'field_position'            => 'Position',
            'field_position_bottom'     => 'Unten',
            'field_position_top'        => 'Oben',
            'field_position_center'     => 'Mitte (Modal)',
            'field_layout'              => 'Layout',
            'field_layout_bar'          => 'Leiste',
            'field_layout_box'          => 'Box',
            'field_primary_color'       => 'Primärfarbe',
            'field_text_color'          => 'Textfarbe',
            'field_bg_color'            => 'Hintergrundfarbe',

            // ── Admin: Erweitert Tab ─────────────────────────────────────────
            'field_privacy_page'        => 'Datenschutzseite',
            'field_imprint_page'        => 'Impressum-Seite',
            'field_please_select'       => '-- bitte wählen --',
            'field_cookie_lifetime'     => 'Cookie-Laufzeit',
            'field_lifetime_days'       => 'Tage',
            'field_auto_block'          => 'Scripts automatisch blockieren',
            'field_auto_block_desc'     => 'Aktivieren (blockiert Scripts bis zur Einwilligung)',
            'field_log_consents'        => 'Einwilligungen protokollieren',
            'field_log_consents_desc'   => 'Aktivieren (DSGVO-Nachweispflicht)',

            // ── Admin: Sprache Tab ───────────────────────────────────────────
            'field_plugin_language'     => 'Plugin-Sprache',
            'lang_desc'                 => 'Wählen Sie die Sprache für das Frontend-Banner und die Admin-Oberfläche.',
            'lang_note'                 => 'Hinweis: Die Standardtexte des Banners werden beim Wechsel automatisch auf die gewählte Sprache umgestellt. Manuell angepasste Texte bleiben unverändert.',
            'lang_de'                   => 'Deutsch',
            'lang_en'                   => 'English',

            // ── Admin: Hilfe Tab ─────────────────────────────────────────────
            'help_shortcodes_title'     => 'Shortcodes',
            'help_shortcode_list_desc'  => 'Vollständige Cookie-Tabelle ausgeben (für Datenschutzerklärung / Impressum)',
            'help_shortcode_cat_desc'   => 'Nur Statistik-Cookies anzeigen',
            'help_shortcode_btn_desc'   => 'Button zum Öffnen der Cookie-Einstellungen',
            'help_privacy_title'        => 'Einbindung in Datenschutzerklärung',
            'help_privacy_text'         => 'Fügen Sie auf Ihrer Datenschutzseite den Shortcode [dcb_cookie_list] ein. Die Liste wird automatisch aus dem letzten Scan befüllt.',
            'help_compliance_title'     => 'DSGVO-Compliance Checkliste',
            'help_check_1'              => 'Kein Pre-Ticking (kein Standard-Häkchen außer „Notwendig")',
            'help_check_2'              => 'Granulare Kategorien (nicht nur „Alle akzeptieren")',
            'help_check_3'              => 'Einwilligungsprotokoll mit IP-Hash und Zeitstempel',
            'help_check_4'              => 'Widerruf jederzeit möglich via [dcb_privacy_settings]',
            'help_check_5'              => 'Link zur Datenschutzerklärung im Banner',
            'help_check_6'              => 'Keine Ablehnung erschwert (gleichwertige Buttons)',

            // ── Admin: Scanner ───────────────────────────────────────────────
            'scanner_intro'             => 'Scannt Ihre WordPress-Installation auf verwendete Cookies. Alle Felder können direkt in der Tabelle bearbeitet werden – klicken Sie dazu auf den ✏️-Button.',
            'scan_start_btn'            => '🔍 Scan starten',
            'scan_running'              => '⏳ Scanne…',
            'scan_done'                 => '✅ %d Cookies gefunden. Seite wird neu geladen…',
            'scan_error'                => '❌ Fehler beim Scan.',
            'scan_connection_error'     => '❌ Verbindungsfehler.',
            'last_scan'                 => 'Letzter Scan:',
            'cookie_list_title'         => 'Cookie-Liste',
            'cookie_list_entries'       => 'Einträge',
            'add_cookie_btn'            => '+ Cookie hinzufügen',
            'no_cookies_yet'            => 'Noch keine Cookies vorhanden. Starten Sie einen Scan oder fügen Sie Cookies manuell hinzu.',
            'col_cookie_name'           => 'Cookie-Name',
            'col_provider'              => 'Anbieter',
            'col_purpose'               => 'Zweck / Beschreibung',
            'col_duration'              => 'Laufzeit',
            'col_category'              => 'Kategorie',
            'col_source'                => 'Quelle',
            'col_actions'               => 'Aktionen',
            'source_manual'             => 'Manuell / bearbeitet',
            'source_auto'               => 'Automatisch erkannt',
            'btn_edit'                  => '✏️ Bearbeiten',
            'btn_save'                  => '💾 Speichern',
            'btn_saving'                => '⏳ Speichern…',
            'btn_cancel'                => 'Abbrechen',
            'btn_delete'                => '🗑️',
            'delete_confirm'            => 'Diesen Cookie-Eintrag wirklich löschen?',
            'save_success'              => '✅ Gespeichert! Seite wird neu geladen…',
            'save_error'                => '❌ Fehler beim Speichern.',
            'save_connection_error'     => '❌ Verbindungsfehler.',
            'add_cookie_title'          => 'Neuen Cookie hinzufügen',
            'field_cookie_name'         => 'Cookie-Name *',
            'field_cookie_name_ph'      => 'z.B. _my_cookie',
            'field_cookie_name_desc'    => 'Exakter Name des Cookies im Browser.',
            'field_category'            => 'Kategorie *',
            'field_provider'            => 'Anbieter',
            'field_provider_ph'         => 'z.B. Google LLC',
            'field_purpose'             => 'Zweck / Beschreibung',
            'field_purpose_ph'          => 'Wofür wird dieser Cookie verwendet?',
            'field_duration'            => 'Laufzeit',
            'field_duration_ph'         => 'z.B. 1 Jahr, Session, 30 Tage',
            'btn_save_cookie'           => '✅ Cookie speichern',
            'name_required'             => 'Bitte einen Cookie-Namen angeben.',
            'shortcode_hint'            => '💡 Shortcode: Fügen Sie [dcb_cookie_list] in Ihre Datenschutzerklärung ein, um diese Liste automatisch anzuzeigen.',

            // ── Admin: Einwilligungen ────────────────────────────────────────
            'consents_intro'            => 'Protokollierte Nutzer-Einwilligungen (IP-Adressen werden als Hash gespeichert – DSGVO-konform).',
            'consents_none'             => 'Noch keine Einwilligungen protokolliert.',
            'col_consent_id'            => 'Einwilligungs-ID',
            'col_ip_hash'               => 'IP-Hash',
            'col_categories'            => 'Kategorien',
            'col_timestamp'             => 'Zeitpunkt',
        );

        $strings_en = array(
            // ── Banner (Frontend) ────────────────────────────────────────────
            'banner_title'              => 'We use cookies',
            'banner_text'               => 'We use cookies and similar technologies to improve our website and provide you with the best possible experience. Please choose which cookies you would like to accept.',
            'accept_all'                => 'Accept all',
            'accept_necessary'          => 'Necessary only',
            'customize'                 => 'Settings',
            'save_settings'             => 'Save settings',
            'privacy_link'              => 'Privacy Policy',
            'imprint_link'              => 'Imprint',
            'always_active'             => 'Always active',
            'accept_all_modal'          => 'Accept all',
            'necessary_only_modal'      => 'Necessary only',

            // ── Cookie categories ────────────────────────────────────────────
            'cat_necessary_label'       => 'Necessary',
            'cat_necessary_desc'        => 'These cookies are strictly required for the operation of the website and cannot be disabled.',
            'cat_statistics_label'      => 'Statistics',
            'cat_statistics_desc'       => 'These cookies help us understand how visitors interact with our website (e.g. Google Analytics).',
            'cat_marketing_label'       => 'Marketing',
            'cat_marketing_desc'        => 'These cookies are used to make advertisements more relevant to you.',
            'cat_preferences_label'     => 'Preferences',
            'cat_preferences_desc'      => 'These cookies allow the website to remember information that changes how the site behaves or looks.',

            // ── Shortcode output ─────────────────────────────────────────────
            'no_cookies_found'          => 'No cookies detected. Please run a scan in the WordPress backend.',
            'last_scanned'              => 'Last scanned:',
            'cookie_col_name'           => 'Name',
            'cookie_col_provider'       => 'Provider',
            'cookie_col_purpose'        => 'Purpose',
            'cookie_col_duration'       => 'Duration',
            'open_settings_btn'         => 'Cookie Settings',
            'change_settings_btn'       => 'Manage Cookie Settings',

            // ── Admin: General ───────────────────────────────────────────────
            'admin_page_title'          => '🍪 GDPR Cookie Banner',
            'admin_menu_label'          => 'Cookie Banner',
            'admin_settings_title'      => '🍪 GDPR Cookie Banner – Settings',
            'admin_scanner_title'       => '🔍 Cookie Management',
            'admin_consents_title'      => '📋 Consent Log',
            'admin_submenu_settings'    => 'Settings',
            'admin_submenu_scanner'     => 'Cookie Management',
            'admin_submenu_consents'    => 'Consents',
            'save_changes'              => 'Save Changes',

            // ── Admin: Settings tabs ─────────────────────────────────────────
            'tab_banner'                => 'Banner Text',
            'tab_design'                => 'Design',
            'tab_advanced'              => 'Advanced',
            'tab_categories'            => 'Categories',
            'tab_language'              => 'Language',
            'tab_help'                  => 'Help',

            // ── Admin: Categories tab ────────────────────────────────────────
            'categories_tab_intro'      => 'Customise the cookie categories here. The shortcode key is used in [dcb_cookie_list] and the block key for data-dcb-category.',
            'cat_label_field'           => 'Label',
            'cat_description_field'     => 'Description',
            'cat_shortcode_key_field'   => 'Shortcode Key',
            'cat_shortcode_key_desc'    => 'Usage: [dcb_cookie_list category="…"]',
            'cat_block_key_field'       => 'Block Key',
            'cat_block_key_desc'        => 'Usage: data-dcb-category="…"',
            'cat_required_field'        => 'Always active (cannot be disabled)',
            'cat_required_locked'       => '🔒 Locked – necessary cookies cannot be disabled',
            'cat_shortcode_example'     => 'Shortcode example:',
            'cat_block_example'         => 'Script blocking:',

            // ── Admin: Banner Text tab ───────────────────────────────────────
            'field_banner_title'        => 'Banner Title',
            'field_banner_text'         => 'Banner Text',
            'field_accept_all'          => '"Accept All"',
            'field_accept_necessary'    => '"Necessary Only"',
            'field_customize'           => '"Settings"',
            'field_save_settings'       => '"Save Settings"',

            // ── Admin: Design tab ────────────────────────────────────────────
            'field_position'            => 'Position',
            'field_position_bottom'     => 'Bottom',
            'field_position_top'        => 'Top',
            'field_position_center'     => 'Center (Modal)',
            'field_layout'              => 'Layout',
            'field_layout_bar'          => 'Bar',
            'field_layout_box'          => 'Box',
            'field_primary_color'       => 'Primary Color',
            'field_text_color'          => 'Text Color',
            'field_bg_color'            => 'Background Color',

            // ── Admin: Advanced tab ──────────────────────────────────────────
            'field_privacy_page'        => 'Privacy Policy Page',
            'field_imprint_page'        => 'Imprint Page',
            'field_please_select'       => '-- please select --',
            'field_cookie_lifetime'     => 'Cookie Lifetime',
            'field_lifetime_days'       => 'days',
            'field_auto_block'          => 'Auto-block scripts',
            'field_auto_block_desc'     => 'Enable (blocks scripts until consent is given)',
            'field_log_consents'        => 'Log consents',
            'field_log_consents_desc'   => 'Enable (required for GDPR documentation)',

            // ── Admin: Language tab ──────────────────────────────────────────
            'field_plugin_language'     => 'Plugin Language',
            'lang_desc'                 => 'Choose the language for the frontend banner and admin interface.',
            'lang_note'                 => 'Note: The default banner texts will be automatically updated to the selected language. Manually customised texts remain unchanged.',
            'lang_de'                   => 'Deutsch',
            'lang_en'                   => 'English',

            // ── Admin: Help tab ──────────────────────────────────────────────
            'help_shortcodes_title'     => 'Shortcodes',
            'help_shortcode_list_desc'  => 'Output full cookie table (for Privacy Policy / Imprint)',
            'help_shortcode_cat_desc'   => 'Show only statistics cookies',
            'help_shortcode_btn_desc'   => 'Button to reopen cookie settings',
            'help_privacy_title'        => 'Adding to Privacy Policy',
            'help_privacy_text'         => 'Add the shortcode [dcb_cookie_list] to your Privacy Policy page. The list is automatically populated from the last scan.',
            'help_compliance_title'     => 'GDPR Compliance Checklist',
            'help_check_1'              => 'No pre-ticking (no default checkmarks except "Necessary")',
            'help_check_2'              => 'Granular categories (not just "Accept All")',
            'help_check_3'              => 'Consent log with IP hash and timestamp',
            'help_check_4'              => 'Revocable at any time via [dcb_privacy_settings]',
            'help_check_5'              => 'Link to Privacy Policy in banner',
            'help_check_6'              => 'Declining not made harder (equal-weight buttons)',

            // ── Admin: Scanner ───────────────────────────────────────────────
            'scanner_intro'             => 'Scans your WordPress installation for cookies in use. All fields can be edited directly in the table – click the ✏️ button in the relevant row.',
            'scan_start_btn'            => '🔍 Start Scan',
            'scan_running'              => '⏳ Scanning…',
            'scan_done'                 => '✅ %d cookies found. Reloading page…',
            'scan_error'                => '❌ Scan failed.',
            'scan_connection_error'     => '❌ Connection error.',
            'last_scan'                 => 'Last scan:',
            'cookie_list_title'         => 'Cookie List',
            'cookie_list_entries'       => 'entries',
            'add_cookie_btn'            => '+ Add Cookie',
            'no_cookies_yet'            => 'No cookies yet. Start a scan or add cookies manually.',
            'col_cookie_name'           => 'Cookie Name',
            'col_provider'              => 'Provider',
            'col_purpose'               => 'Purpose / Description',
            'col_duration'              => 'Duration',
            'col_category'              => 'Category',
            'col_source'                => 'Source',
            'col_actions'               => 'Actions',
            'source_manual'             => 'Manual / edited',
            'source_auto'               => 'Auto-detected',
            'btn_edit'                  => '✏️ Edit',
            'btn_save'                  => '💾 Save',
            'btn_saving'                => '⏳ Saving…',
            'btn_cancel'                => 'Cancel',
            'btn_delete'                => '🗑️',
            'delete_confirm'            => 'Really delete this cookie entry?',
            'save_success'              => '✅ Saved! Reloading page…',
            'save_error'                => '❌ Save failed.',
            'save_connection_error'     => '❌ Connection error.',
            'add_cookie_title'          => 'Add New Cookie',
            'field_cookie_name'         => 'Cookie Name *',
            'field_cookie_name_ph'      => 'e.g. _my_cookie',
            'field_cookie_name_desc'    => 'Exact name of the cookie as set in the browser.',
            'field_category'            => 'Category *',
            'field_provider'            => 'Provider',
            'field_provider_ph'         => 'e.g. Google LLC',
            'field_purpose'             => 'Purpose / Description',
            'field_purpose_ph'          => 'What is this cookie used for?',
            'field_duration'            => 'Duration',
            'field_duration_ph'         => 'e.g. 1 year, Session, 30 days',
            'btn_save_cookie'           => '✅ Save Cookie',
            'name_required'             => 'Please enter a cookie name.',
            'shortcode_hint'            => '💡 Shortcode: Add [dcb_cookie_list] to your Privacy Policy page to display this list automatically.',

            // ── Admin: Consents ──────────────────────────────────────────────
            'consents_intro'            => 'Logged user consents (IP addresses are stored as a hash – GDPR compliant).',
            'consents_none'             => 'No consents logged yet.',
            'col_consent_id'            => 'Consent ID',
            'col_ip_hash'               => 'IP Hash',
            'col_categories'            => 'Categories',
            'col_timestamp'             => 'Timestamp',
        );

        return $lang === 'en' ? $strings_en : $strings_de;
    }
}
// This file is auto-appended – no closing ?> needed above

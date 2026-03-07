<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Frontend {

    private $settings;

    public function __construct() {
        add_action( 'wp_enqueue_scripts',          array( $this, 'enqueue_assets' ) );
        add_action( 'wp_footer',                   array( $this, 'render_banner' ), 1 );
        add_action( 'wp_footer',                   array( $this, 'maybe_inject_scan_reporter' ), 999 );
        add_action( 'wp_ajax_nopriv_dcb_save_consent', array( $this, 'ajax_save_consent' ) );
        add_action( 'wp_ajax_dcb_save_consent',        array( $this, 'ajax_save_consent' ) );
    }

    public function enqueue_assets() {
        $this->settings = DCB_Cookie_Manager::get_settings();

        wp_enqueue_style(  'dcb-frontend', DCB_PLUGIN_URL . 'public/css/frontend.css', array(), DCB_VERSION );
        wp_enqueue_script( 'dcb-frontend', DCB_PLUGIN_URL . 'public/js/frontend.js',   array(), DCB_VERSION, true );

        // Embed placeholder assets (loaded only if embed shortcodes are used on this page)
        wp_enqueue_style(  'dcb-embeds', DCB_PLUGIN_URL . 'public/css/embeds.css', array(), DCB_VERSION );
        wp_enqueue_script( 'dcb-embeds', DCB_PLUGIN_URL . 'public/js/embeds.js',   array( 'dcb-frontend' ), DCB_VERSION, true );

        $privacy_url = $this->settings['privacy_page_id'] ? get_permalink( $this->settings['privacy_page_id'] ) : '#';
        $imprint_url = $this->settings['imprint_page_id'] ? get_permalink( $this->settings['imprint_page_id'] ) : '#';

        // Build categories config for JS using current i18n labels
        $cats = $this->settings['categories'];

        wp_localize_script( 'dcb-frontend', 'DCBConfig', array(
            'ajax_url'    => admin_url( 'admin-ajax.php' ),
            'nonce'       => wp_create_nonce( 'dcb_consent_nonce' ),
            'cookie_name' => 'dcb_consent',
            'lifetime'    => (int) $this->settings['cookie_lifetime'],
            'privacy_url' => $privacy_url,
            'imprint_url' => $imprint_url,
            'settings'    => array(
                'title'            => $this->settings['banner_title'],
                'text'             => $this->settings['banner_text'],
                'accept_all'       => $this->settings['accept_all_text'],
                'accept_necessary' => $this->settings['accept_necessary_text'],
                'customize'        => $this->settings['customize_text'],
                'save_settings'    => $this->settings['save_settings_text'],
                'position'         => $this->settings['banner_position'],
                'layout'           => $this->settings['banner_layout'],
                'primary_color'    => $this->settings['primary_color'],
                'text_color'       => $this->settings['text_color'],
                'bg_color'         => $this->settings['bg_color'],
                'categories'       => $cats,
            ),
            // Pass UI strings from i18n for JS-rendered elements
            'i18n' => array(
                'always_active'      => DCB_I18n::t('always_active'),
                'accept_all'         => DCB_I18n::t('accept_all_modal'),
                'necessary_only'     => DCB_I18n::t('necessary_only_modal'),
                'privacy_link'       => DCB_I18n::t('privacy_link'),
                'imprint_link'       => DCB_I18n::t('imprint_link'),
                'aria_dialog_label'  => DCB_I18n::t('aria_dialog_label'),
                'aria_close'         => DCB_I18n::t('aria_close'),
                'aria_embed_ph'      => DCB_I18n::t('aria_embed_placeholder'),
            ),
        ) );
    }

    public function render_banner() {
        echo '<div id="dcb-banner-root" aria-live="polite" aria-label="' . esc_attr( DCB_I18n::t('aria_dialog_label') ) . '"></div>';'
    }

    /**
     * Browser-Scan-Reporter
     *
     * Wenn die Seite mit ?dcb_browser_scan=TOKEN aufgerufen wird,
     * injiziert dieser Code ein Script, das nach DELAY_MS alle
     * document.cookie-Werte per postMessage an das Parent-Window sendet.
     *
     * Das Token wird serverseitig gegen einen Transient validiert.
     * So kann kein Dritter diese Funktion missbrauchen.
     */
    public function maybe_inject_scan_reporter(): void {
        $token = sanitize_text_field( $_GET['dcb_browser_scan'] ?? '' );
        if ( empty( $token ) ) return;

        // Transient-Validierung: Token muss vom Admin angelegt worden sein
        $valid = get_transient( 'dcb_scan_token_' . $token );
        if ( ! $valid ) return;

        // Token einmalig — nach Aufruf löschen
        delete_transient( 'dcb_scan_token_' . $token );

        $origin  = esc_js( home_url() );
        $delay   = 5000; // 5 Sekunden — genug für Drittanbieter-Scripts
        ?>
        <script id="dcb-scan-reporter">
        (function() {
            var ORIGIN = '<?php echo $origin; ?>';
            var DELAY  = <?php echo $delay; ?>;

            function collectAndReport() {
                var raw    = document.cookie || '';
                var names  = [];
                if (raw) {
                    raw.split(';').forEach(function(part) {
                        var name = part.split('=')[0].trim();
                        if (name) names.push(name);
                    });
                }
                // LocalStorage-Keys ebenfalls melden (manche "Cookies" sind eigentlich localStorage)
                var lsKeys = [];
                try {
                    for (var i = 0; i < localStorage.length; i++) {
                        lsKeys.push(localStorage.key(i));
                    }
                } catch(e) {}

                if (window.parent && window.parent !== window) {
                    window.parent.postMessage({
                        dcb_scan: true,
                        cookies:  names,
                        ls_keys:  lsKeys
                    }, ORIGIN);
                }
            }

            // Sofort melden (Cookies die synchron gesetzt werden)
            setTimeout(collectAndReport, 500);
            // Nochmals nach DELAY (Drittanbieter-Scripts brauchen Zeit)
            setTimeout(collectAndReport, DELAY);
            // Und nochmals nach 8s für langsame CDNs
            setTimeout(collectAndReport, 8000);
        })();
        </script>
        <?php
    }

    public function ajax_save_consent() {
        check_ajax_referer( 'dcb_consent_nonce', 'nonce' );
        $raw        = $_POST['consent'] ?? '{}';
        $consent    = json_decode( stripslashes( $raw ), true );
        $consent_id = wp_generate_uuid4();

        DCB_Cookie_Manager::log_consent( $consent_id, $consent );

        wp_send_json_success( array( 'consent_id' => $consent_id ) );
    }
}

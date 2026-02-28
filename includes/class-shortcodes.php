<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Shortcodes {

    public function __construct() {
        add_shortcode( 'dcb_cookie_list',   array( $this, 'cookie_list' ) );
        add_shortcode( 'dcb_cookie_banner', array( $this, 'manual_banner' ) );
        add_shortcode( 'dcb_privacy_settings', array( $this, 'privacy_settings_link' ) );
    }

    /**
     * [dcb_cookie_list] – Gibt die Cookie-Tabelle für Impressum/Datenschutz aus
     */
    public function cookie_list( $atts ) {
        $atts = shortcode_atts( array(
            'category' => '',
            'style'    => 'table',
        ), $atts );

        $stored     = DCB_Cookie_Manager::get_detected_cookies();
        $cookies    = array_merge( $stored['auto'] ?? array(), $stored['manual'] ?? array() );
        $settings   = DCB_Cookie_Manager::get_settings();
        $categories = $settings['categories'];

        if ( empty( $cookies ) ) {
            return '<p>' . esc_html__( 'Keine Cookies erkannt. Bitte führen Sie einen Scan im WordPress-Backend durch.', 'dsgvo-cookie-banner' ) . '</p>';
        }

        // Nach Kategorie filtern
        if ( $atts['category'] ) {
            $cookies = array_filter( $cookies, function( $c ) use ( $atts ) {
                return $c['category'] === $atts['category'];
            });
        }

        // Nach Kategorien gruppieren
        $grouped = array();
        foreach ( $cookies as $cookie ) {
            $grouped[ $cookie['category'] ][] = $cookie;
        }

        ob_start();
        if ( isset( $stored['last_scan'] ) ) {
            echo '<p class="dcb-last-scan"><small>' . sprintf( esc_html__( 'Zuletzt gescannt: %s', 'dsgvo-cookie-banner' ), esc_html( $stored['last_scan'] ) ) . '</small></p>';
        }

        foreach ( $grouped as $cat_key => $cat_cookies ) {
            $cat_label = $categories[ $cat_key ]['label'] ?? ucfirst( $cat_key );
            $cat_desc  = $categories[ $cat_key ]['description'] ?? '';
            ?>
            <div class="dcb-cookie-category">
                <h3 class="dcb-cat-title"><?php echo esc_html( $cat_label ); ?></h3>
                <?php if ( $cat_desc ) : ?>
                    <p class="dcb-cat-desc"><?php echo esc_html( $cat_desc ); ?></p>
                <?php endif; ?>
                <table class="dcb-cookie-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Name', 'dsgvo-cookie-banner' ); ?></th>
                            <th><?php esc_html_e( 'Anbieter', 'dsgvo-cookie-banner' ); ?></th>
                            <th><?php esc_html_e( 'Zweck', 'dsgvo-cookie-banner' ); ?></th>
                            <th><?php esc_html_e( 'Laufzeit', 'dsgvo-cookie-banner' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $cat_cookies as $cookie ) : ?>
                        <tr>
                            <td><code><?php echo esc_html( $cookie['name'] ); ?></code></td>
                            <td><?php echo esc_html( $cookie['provider'] ); ?></td>
                            <td><?php echo esc_html( $cookie['purpose'] ); ?></td>
                            <td><?php echo esc_html( $cookie['duration'] ); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
        }

        return ob_get_clean();
    }

    /**
     * [dcb_privacy_settings] – Link zum Öffnen der Cookie-Einstellungen
     */
    public function privacy_settings_link( $atts ) {
        $atts = shortcode_atts( array( 'text' => 'Cookie-Einstellungen ändern' ), $atts );
        return '<button type="button" class="dcb-reopen-banner" onclick="DCB.openBanner()">' . esc_html( $atts['text'] ) . '</button>';
    }

    public function manual_banner( $atts ) {
        return '<button type="button" class="dcb-open-btn" onclick="DCB.openBanner()">' . esc_html__( 'Cookie-Einstellungen', 'dsgvo-cookie-banner' ) . '</button>';
    }
}

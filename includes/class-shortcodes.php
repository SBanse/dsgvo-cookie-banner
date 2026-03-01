<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Shortcodes {

    public function __construct() {
        add_shortcode( 'dcb_cookie_list',      array( $this, 'cookie_list' ) );
        add_shortcode( 'dcb_cookie_banner',    array( $this, 'manual_banner' ) );
        add_shortcode( 'dcb_privacy_settings', array( $this, 'privacy_settings_link' ) );
    }

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
            return '<p>' . esc_html( DCB_I18n::t('no_cookies_found') ) . '</p>';
        }

        if ( $atts['category'] ) {
            $cookies = array_filter( $cookies, function ( $c ) use ( $atts ) {
                return $c['category'] === $atts['category'];
            } );
        }

        $grouped = array();
        foreach ( $cookies as $cookie ) {
            $grouped[ $cookie['category'] ][] = $cookie;
        }

        ob_start();

        if ( isset( $stored['last_scan'] ) ) {
            echo '<p class="dcb-last-scan"><small>'
                . esc_html( DCB_I18n::t('last_scanned') ) . ' '
                . esc_html( $stored['last_scan'] )
                . '</small></p>';
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
                            <th><?php echo esc_html( DCB_I18n::t('cookie_col_name') ); ?></th>
                            <th><?php echo esc_html( DCB_I18n::t('cookie_col_provider') ); ?></th>
                            <th><?php echo esc_html( DCB_I18n::t('cookie_col_purpose') ); ?></th>
                            <th><?php echo esc_html( DCB_I18n::t('cookie_col_duration') ); ?></th>
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

    public function privacy_settings_link( $atts ) {
        $atts = shortcode_atts( array(
            'text' => DCB_I18n::t('change_settings_btn'),
        ), $atts );
        return '<button type="button" class="dcb-reopen-banner" onclick="DCB.openSettings()">'
            . esc_html( $atts['text'] ) . '</button>';
    }

    public function manual_banner( $atts ) {
        return '<button type="button" class="dcb-open-btn" onclick="DCB.openBanner()">'
            . esc_html( DCB_I18n::t('open_settings_btn') ) . '</button>';
    }
}

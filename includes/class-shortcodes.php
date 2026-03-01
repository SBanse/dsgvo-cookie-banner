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
            'category' => '',   // matches against shortcode_key OR internal key
            'style'    => 'table',
        ), $atts );

        $stored     = DCB_Cookie_Manager::get_detected_cookies();
        $cookies    = array_merge( $stored['auto'] ?? array(), $stored['manual'] ?? array() );
        $settings   = DCB_Cookie_Manager::get_settings();
        $categories = $settings['categories'];

        if ( empty( $cookies ) ) {
            return '<p>' . esc_html( DCB_I18n::t('no_cookies_found') ) . '</p>';
        }

        // Build lookup: shortcode_key → internal_key
        // Also accept: internal_key directly (backwards compat)
        $shortcode_map = array(); // shortcode_key => internal_key
        foreach ( $categories as $internal_key => $cat ) {
            $sk = $cat['shortcode_key'] ?? $internal_key;
            $shortcode_map[ $sk ] = $internal_key;
            // also map internal key directly so old shortcodes keep working
            $shortcode_map[ $internal_key ] = $internal_key;
        }

        // Filter by category if requested
        if ( $atts['category'] !== '' ) {
            $target_internal = $shortcode_map[ $atts['category'] ] ?? $atts['category'];
            $cookies = array_filter( $cookies, function ( $c ) use ( $target_internal ) {
                return ( $c['category'] ?? '' ) === $target_internal;
            } );
        }

        // Group cookies by internal category key
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
            $cat       = $categories[ $cat_key ] ?? array();
            $cat_label = $cat['label']       ?? ucfirst( $cat_key );
            $cat_desc  = $cat['description'] ?? '';
            $bk        = $cat['block_key']   ?? $cat_key;
            $sk        = $cat['shortcode_key'] ?? $cat_key;
            ?>
            <div class="dcb-cookie-category" data-category="<?php echo esc_attr( $cat_key ); ?>">
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
                <?php if ( $bk !== 'necessary' ) : ?>
                <p class="dcb-block-hint">
                    <small><?php
                        $label = DCB_I18n::get_lang() === 'de'
                            ? 'Script-Blockierung: '
                            : 'Script blocking: ';
                        echo esc_html( $label );
                    ?><code>data-dcb-category="<?php echo esc_html( $bk ); ?>"</code></small>
                </p>
                <?php endif; ?>
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

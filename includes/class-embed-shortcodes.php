<?php
/**
 * DCB_Embed_Shortcodes – Shortcodes für datenschutzkonforme Einbettungen
 *
 * Shortcodes:
 *   [dcb_youtube id="dQw4w9WgXcQ" width="560" height="315"]
 *   [dcb_googlemaps src="https://maps.google.com/maps?..." width="100%" height="450"]
 *   [dcb_vimeo id="123456789"]
 *   [dcb_instagram url="https://www.instagram.com/p/..."]
 *   [dcb_twitter url="https://twitter.com/.../status/..."]
 *   [dcb_facebook url="https://www.facebook.com/.../posts/..."]
 *   [dcb_openstreetmap lat="52.52" lng="13.40" zoom="14" width="100%" height="450"]
 *   [dcb_embed type="youtube" src="..." width="..." height="..."]  (generic)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Embed_Shortcodes {

    public function __construct() {
        $embeds = DCB_Embeds::get_embeds();

        // Register per-type shortcodes for all enabled types
        foreach ( $embeds as $id => $embed ) {
            if ( ! empty( $embed['enabled'] ) ) {
                add_shortcode( 'dcb_' . $id, array( $this, 'render_embed_shortcode' ) );
            }
        }
        // Generic shortcode
        add_shortcode( 'dcb_embed', array( $this, 'render_embed_shortcode' ) );
    }

    /* ── Generic dispatcher ────────────────────────────────────────────────── */

    public function render_embed_shortcode( $atts, $content, $tag ) {
        // Determine embed type from shortcode tag or explicit 'type' attribute
        // Tag format: dcb_youtube → type = youtube
        $type = ( strpos( $tag, 'dcb_' ) === 0 ) ? substr( $tag, 4 ) : $tag;
        if ( $tag === 'dcb_embed' ) {
            $type = sanitize_key( $atts['type'] ?? '' );
        }

        $embed = DCB_Embeds::get_embed( $type );
        if ( ! $embed || empty( $embed['enabled'] ) ) {
            return '<!-- dcb_embed: unknown or disabled type: ' . esc_html( $type ) . ' -->';
        }

        $atts = shortcode_atts( array(
            'type'        => $type,
            'id'          => '',       // YouTube/Vimeo video ID
            'src'         => '',       // iFrame src (generic)
            'url'         => '',       // Social embed URL
            'lat'         => '',       // OSM latitude
            'lng'         => '',       // OSM longitude
            'zoom'        => '14',     // OSM zoom
            'width'       => '100%',
            'height'      => '315',
            'title'       => '',       // Custom title override
            'thumbnail'   => '',       // Custom thumbnail URL (YouTube)
            'class'       => '',       // Extra CSS classes
        ), $atts, $tag );

        $iframe_src = $this->build_iframe_src( $type, $atts );
        if ( ! $iframe_src ) {
            return '<!-- dcb_embed: could not build src for ' . esc_html( $type ) . ' -->';
        }

        return $this->render_placeholder( $embed, $atts, $iframe_src );
    }

    /* ── Build the iframe src per type ────────────────────────────────────── */

    private function build_iframe_src( string $type, array $atts ): string {
        $id  = sanitize_text_field( $atts['id']  ?? '' );
        $src = esc_url_raw(         $atts['src'] ?? '' );
        $url = esc_url_raw(         $atts['url'] ?? '' );

        switch ( $type ) {
            case 'youtube':
                if ( ! $id ) {
                    // Try extracting from url
                    if ( preg_match( '/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $src ?: $url, $m ) ) {
                        $id = $m[1];
                    }
                }
                return $id ? 'https://www.youtube-nocookie.com/embed/' . $id . '?rel=0' : '';

            case 'vimeo':
                if ( ! $id && preg_match( '/vimeo\.com\/(\d+)/', $src ?: $url, $m ) ) {
                    $id = $m[1];
                }
                return $id ? 'https://player.vimeo.com/video/' . $id : '';

            case 'googlemaps':
            case 'googlemaps_iframe':
                if ( $src ) return $src;
                if ( $url ) return $url;
                return '';

            case 'openstreetmap':
                $lat  = floatval( $atts['lat']  ?? 52.52 );
                $lng  = floatval( $atts['lng']  ?? 13.40 );
                $zoom = intval(   $atts['zoom'] ?? 14 );
                $bbox_offset = 360 / pow( 2, $zoom ) * 0.25;
                return sprintf(
                    'https://www.openstreetmap.org/export/embed.html?bbox=%s%%2C%s%%2C%s%%2C%s&layer=mapnik&marker=%s%%2C%s',
                    $lng - $bbox_offset, $lat - $bbox_offset / 2,
                    $lng + $bbox_offset, $lat + $bbox_offset / 2,
                    $lat, $lng
                );

            case 'instagram':
                if ( ! $url ) return '';
                // Instagram embed needs blockquote + script – handled specially
                return '__instagram__' . $url;

            case 'twitter':
                if ( ! $url ) return '';
                return '__twitter__' . $url;

            case 'facebook':
                if ( ! $url ) return '';
                $encoded = urlencode( $url );
                return 'https://www.facebook.com/plugins/post.php?href=' . $encoded . '&show_text=true&width=500';

            default:
                return $src ?: $url;
        }
    }

    /* ── Render the placeholder HTML ──────────────────────────────────────── */

    private function render_placeholder( array $embed, array $atts, string $iframe_src ): string {
        $lang  = DCB_I18n::get_lang();
        $id    = $embed['id'];

        $title      = $atts['title'] ?: DCB_Embeds::get_text( $embed, 'placeholder_title' );
        $text       = DCB_Embeds::get_text( $embed, 'placeholder_text' );
        $btn_text   = DCB_Embeds::get_text( $embed, 'btn_text' );
        $always_txt = DCB_Embeds::get_text( $embed, 'always_text' );

        $width  = esc_attr( $atts['width']  ?? '100%' );
        $height = esc_attr( $atts['height'] ?? '315' );

        // Height: if pure number, add px
        $height_css = is_numeric( $height ) ? $height . 'px' : $height;
        $width_css  = is_numeric( $width )  ? $width  . 'px' : $width;

        $extra_class = sanitize_html_class( $atts['class'] ?? '' );

        $bg     = esc_attr( $embed['bg_color']     ?? '#1a1a1a' );
        $accent = esc_attr( $embed['accent_color'] ?? '#ff0000' );
        $tc     = esc_attr( $embed['text_color']   ?? '#ffffff' );
        $icon   = esc_html( $embed['icon']         ?? '▶' );

        // Thumbnail for YouTube
        $thumbnail_html = '';
        if ( $embed['preview_type'] === 'thumbnail' && $id === 'youtube' ) {
            $vid_id = '';
            if ( preg_match( '/embed\/([a-zA-Z0-9_-]{11})/', $iframe_src, $m ) ) {
                $vid_id = $m[1];
            }
            $thumb_url = $atts['thumbnail'] ?: ( $vid_id
                ? 'https://img.youtube.com/vi/' . $vid_id . '/hqdefault.jpg'
                : '' );
            if ( $thumb_url ) {
                $thumbnail_html = '<div class="dcb-embed-thumb" style="background-image:url(' . esc_url( $thumb_url ) . ')"></div>';
            }
        } elseif ( $embed['preview_type'] === 'image' && ! empty( $embed['custom_image_url'] ) ) {
            $thumbnail_html = '<div class="dcb-embed-thumb" style="background-image:url(' . esc_url( $embed['custom_image_url'] ) . ')"></div>';
        }

        // Determine category's block_key for the "always allow" button
        $settings  = DCB_Cookie_Manager::get_settings();
        $cat_key   = $embed['category'] ?? 'marketing';
        $cat_conf  = $settings['categories'][ $cat_key ] ?? array();
        $block_key = $cat_conf['block_key'] ?? $cat_key;

        // Cookie name for "always allow" = dcb_always_{type}
        $always_cookie = 'dcb_always_' . $id;

        // Check if user already said "always allow"
        $always_set = isset( $_COOKIE[ $always_cookie ] );

        // Build data attributes
        $data = array(
            'data-dcb-embed-type'   => esc_attr( $id ),
            'data-dcb-embed-src'    => esc_attr( $iframe_src ),
            'data-dcb-embed-width'  => esc_attr( $width ),
            'data-dcb-embed-height' => esc_attr( $height ),
            'data-dcb-category'     => esc_attr( $block_key ),
            'data-dcb-always-cookie'=> esc_attr( $always_cookie ),
        );
        $data_str = implode( ' ', array_map(
            function( $k, $v ) { return $k . '="' . $v . '"'; },
            array_keys($data), $data
        ) );

        // If already consented via "always allow" cookie, render directly
        if ( $always_set ) {
            return $this->render_iframe( $iframe_src, $width, $height, $title, $id, $extra_class );
        }

        ob_start();
        ?>
        <div class="dcb-embed-wrap dcb-embed-<?php echo esc_attr( $id ); ?> <?php echo $extra_class; ?>"
             style="width:<?php echo $width_css; ?>;max-width:100%;position:relative;"
             <?php echo $data_str; ?>>

            <?php echo $thumbnail_html; ?>

            <div class="dcb-embed-placeholder"
                 role="region"
                 aria-label="<?php echo esc_attr( DCB_I18n::t('aria_embed_placeholder') . ': ' . $title ); ?>"
                 style="--dcb-embed-bg:<?php echo $bg; ?>;--dcb-embed-accent:<?php echo $accent; ?>;--dcb-embed-tc:<?php echo $tc; ?>;min-height:<?php echo $height_css; ?>;">

                <div class="dcb-embed-icon" aria-hidden="true"><?php echo $icon; ?></div>

                <div class="dcb-embed-info">
                    <strong class="dcb-embed-title"><?php echo esc_html( $title ); ?></strong>
                    <p class="dcb-embed-text"><?php echo esc_html( $text ); ?></p>
                </div>

                <div class="dcb-embed-actions">
                    <button type="button" class="dcb-embed-load-btn dcb-embed-load-once">
                        <?php echo esc_html( $btn_text ); ?>
                    </button>
                    <button type="button" class="dcb-embed-load-btn dcb-embed-load-always">
                        <?php echo esc_html( $always_txt ); ?>
                    </button>
                    <a href="<?php echo esc_url( get_permalink( $settings['privacy_page_id'] ?? 0 ) ?: '#' ); ?>"
                       class="dcb-embed-privacy-link">
                        <?php echo esc_html( DCB_I18n::t('privacy_link') ); ?>
                    </a>
                </div>
            </div>

            <div class="dcb-embed-frame-container" style="display:none;width:<?php echo $width_css; ?>;height:<?php echo $height_css; ?>;"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    /* ── Render a live iframe ──────────────────────────────────────────────── */

    private function render_iframe( string $src, string $width, string $height, string $title, string $type, string $extra_class = '' ): string {
        $width_css  = is_numeric( $width )  ? $width  . 'px' : $width;
        $height_css = is_numeric( $height ) ? $height . 'px' : $height;

        // Special: Instagram/Twitter use their own embed scripts
        if ( strpos( $src, '__instagram__' ) === 0 ) {
            $url = substr( $src, 13 );
            return '<blockquote class="instagram-media" data-instgrm-permalink="' . esc_url( $url ) . '"></blockquote>'
                 . '<script async src="//www.instagram.com/embed.js"></script>';
        }
        if ( strpos( $src, '__twitter__' ) === 0 ) {
            $url = substr( $src, 11 );
            return '<blockquote class="twitter-tweet"><a href="' . esc_url( $url ) . '"></a></blockquote>'
                 . '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
        }

        return '<iframe src="' . esc_url( $src ) . '"'
             . ' width="' . esc_attr( $width ) . '"'
             . ' height="' . esc_attr( $height ) . '"'
             . ' style="width:' . $width_css . ';height:' . $height_css . ';border:0;display:block;"'
             . ' title="' . esc_attr( $title ) . '"'
             . ' allowfullscreen loading="lazy"'
             . ' class="dcb-embed-iframe dcb-embed-' . esc_attr( $type ) . ' ' . esc_attr( $extra_class ) . '"'
             . '></iframe>';
    }
}

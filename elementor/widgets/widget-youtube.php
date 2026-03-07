<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Widget_YouTube extends DCB_Widget_Embed_Base {

    public function get_name()  { return 'dcb_youtube'; }
    public function get_title() { return '▶ YouTube'; }
    public function get_icon()  { return 'eicon-youtube'; }
    public function get_keywords() {
        return array_merge( parent::get_keywords(), array( 'youtube', 'video' ) );
    }

    protected function get_embed_type() { return 'youtube'; }

    protected function register_source_controls() {
        $de = DCB_I18n::get_lang() === 'de';

        $this->add_control( 'video_id', array(
            'label'       => $de ? 'Video-ID oder URL' : 'Video ID or URL',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'dQw4w9WgXcQ  oder  https://youtu.be/dQw4w9WgXcQ',
            'label_block' => true,
            'description' => $de
                ? 'Die 11-stellige Video-ID oder die vollständige YouTube-URL.'
                : 'The 11-character video ID or the full YouTube URL.',
        ) );

        $this->add_control( 'custom_thumbnail', array(
            'label'       => $de ? 'Eigenes Vorschaubild (optional)' : 'Custom Thumbnail (optional)',
            'type'        => \Elementor\Controls_Manager::MEDIA,
            'description' => $de
                ? 'Leer lassen, um das automatische YouTube-Thumbnail zu verwenden.'
                : 'Leave empty to use the automatic YouTube thumbnail.',
        ) );
    }

    protected function build_shortcode( $s ) {
        $id        = ! empty( $s['video_id'] )           ? ' id="'        . esc_attr( $s['video_id'] ) . '"'          : '';
        $thumb     = ! empty( $s['custom_thumbnail']['url'] ) ? ' thumbnail="' . esc_attr( $s['custom_thumbnail']['url'] ) . '"' : '';
        $width     = $this->size_string( $s['embed_width']  ?? array(), '100%' );
        $height    = $this->size_string( $s['embed_height'] ?? array(), '400px' );
        return "[dcb_youtube{$id}{$thumb} width=\"{$width}\" height=\"{$height}\"]";
    }
}

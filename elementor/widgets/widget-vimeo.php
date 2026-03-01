<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Widget_Vimeo extends DCB_Widget_Embed_Base {

    public function get_name()  { return 'dcb_vimeo'; }
    public function get_title() { return '▶ Vimeo'; }
    public function get_icon()  { return 'eicon-vimeo'; }
    public function get_keywords() {
        return array_merge( parent::get_keywords(), array( 'vimeo', 'video' ) );
    }

    protected function get_embed_type(): string { return 'vimeo'; }

    protected function register_source_controls(): void {
        $de = DCB_I18n::get_lang() === 'de';

        $this->add_control( 'video_id', array(
            'label'       => $de ? 'Video-ID oder URL' : 'Video ID or URL',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => '123456789  oder  https://vimeo.com/123456789',
            'label_block' => true,
            'description' => $de
                ? 'Die numerische Vimeo-Video-ID oder die vollständige Vimeo-URL.'
                : 'The numeric Vimeo video ID or the full Vimeo URL.',
        ) );
    }

    protected function build_shortcode( array $s ): string {
        $id     = ! empty( $s['video_id'] ) ? ' id="' . esc_attr( $s['video_id'] ) . '"' : '';
        $width  = $this->size_string( $s['embed_width']  ?? array(), '100%' );
        $height = $this->size_string( $s['embed_height'] ?? array(), '400px' );
        return "[dcb_vimeo{$id} width=\"{$width}\" height=\"{$height}\"]";
    }
}

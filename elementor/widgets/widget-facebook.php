<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Widget_Facebook extends DCB_Widget_Embed_Base {

    public function get_name()  { return 'dcb_facebook'; }
    public function get_title() { return 'f Facebook'; }
    public function get_icon()  { return 'eicon-facebook'; }
    public function get_keywords() {
        return array_merge( parent::get_keywords(), array( 'facebook', 'fb', 'social', 'post' ) );
    }

    protected function get_embed_type(): string { return 'facebook'; }

    protected function register_source_controls(): void {
        $de = DCB_I18n::get_lang() === 'de';

        $this->add_control( 'post_url', array(
            'label'       => $de ? 'Beitrags-URL' : 'Post URL',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'https://www.facebook.com/username/posts/123456789',
            'label_block' => true,
            'description' => $de
                ? 'Die vollständige URL des Facebook-Beitrags oder der Seite.'
                : 'The full URL of the Facebook post or page.',
        ) );
    }

    protected function build_shortcode( array $s ): string {
        $url    = ! empty( $s['post_url'] )  ? ' url="'    . esc_attr( $s['post_url'] )  . '"' : '';
        $width  = $this->size_string( $s['embed_width']  ?? array(), '100%' );
        $height = $this->size_string( $s['embed_height'] ?? array(), '400px' );
        return "[dcb_facebook{$url} width=\"{$width}\" height=\"{$height}\"]";
    }
}

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Widget_Instagram extends DCB_Widget_Embed_Base {

    public function get_name()  { return 'dcb_instagram'; }
    public function get_title() { return '📷 Instagram'; }
    public function get_icon()  { return 'eicon-instagram-gallery'; }
    public function get_keywords() {
        return array_merge( parent::get_keywords(), array( 'instagram', 'social', 'photo' ) );
    }

    protected function get_embed_type() { return 'instagram'; }

    protected function register_source_controls() {
        $de = DCB_I18n::get_lang() === 'de';

        $this->add_control( 'post_url', array(
            'label'       => $de ? 'Beitrags-URL' : 'Post URL',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'https://www.instagram.com/p/ABC123xyz/',
            'label_block' => true,
            'description' => $de
                ? 'Die vollständige URL des Instagram-Beitrags.'
                : 'The full URL of the Instagram post.',
        ) );

        $this->add_control( 'instagram_note', array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => $de
                ? '<small>ℹ️ Instagram-Einbettungen laden <code>instagram.com/embed.js</code> nach der Einwilligung.</small>'
                : '<small>ℹ️ Instagram embeds load <code>instagram.com/embed.js</code> after consent.</small>',
            'content_classes' => 'elementor-descriptor',
        ) );
    }

    protected function build_shortcode( $s ) {
        $url = ! empty( $s['post_url'] ) ? ' url="' . esc_attr( $s['post_url'] ) . '"' : '';
        return "[dcb_instagram{$url}]";
    }
}

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Widget_Twitter extends DCB_Widget_Embed_Base {

    public function get_name()  { return 'dcb_twitter'; }
    public function get_title() { return '𝕏 X / Twitter'; }
    public function get_icon()  { return 'eicon-twitter'; }
    public function get_keywords() {
        return array_merge( parent::get_keywords(), array( 'twitter', 'x', 'tweet', 'social' ) );
    }

    protected function get_embed_type(): string { return 'twitter'; }

    protected function register_source_controls(): void {
        $de = DCB_I18n::get_lang() === 'de';

        $this->add_control( 'tweet_url', array(
            'label'       => $de ? 'Tweet-URL' : 'Tweet URL',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'https://twitter.com/user/status/1234567890',
            'label_block' => true,
            'description' => $de
                ? 'Die vollständige URL des Tweets (twitter.com oder x.com).'
                : 'The full URL of the tweet (twitter.com or x.com).',
        ) );

        $this->add_control( 'twitter_note', array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => $de
                ? '<small>ℹ️ Twitter/X-Einbettungen laden <code>platform.twitter.com/widgets.js</code> nach der Einwilligung.</small>'
                : '<small>ℹ️ Twitter/X embeds load <code>platform.twitter.com/widgets.js</code> after consent.</small>',
            'content_classes' => 'elementor-descriptor',
        ) );
    }

    protected function build_shortcode( array $s ): string {
        $url = ! empty( $s['tweet_url'] ) ? ' url="' . esc_attr( $s['tweet_url'] ) . '"' : '';
        return "[dcb_twitter{$url}]";
    }
}

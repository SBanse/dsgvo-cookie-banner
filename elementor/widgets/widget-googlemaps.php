<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Widget_GoogleMaps extends DCB_Widget_Embed_Base {

    public function get_name()  { return 'dcb_googlemaps'; }
    public function get_title() { return '📍 Google Maps'; }
    public function get_icon()  { return 'eicon-google-maps'; }
    public function get_keywords() {
        return array_merge( parent::get_keywords(), array( 'google', 'maps', 'karte', 'map' ) );
    }

    protected function get_embed_type(): string { return 'googlemaps'; }

    protected function register_source_controls(): void {
        $de = DCB_I18n::get_lang() === 'de';

        $this->add_control( 'map_src', array(
            'label'       => $de ? 'Einbettungs-URL' : 'Embed URL',
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'rows'        => 3,
            'placeholder' => 'https://maps.google.com/maps?q=Berlin&output=embed',
            'label_block' => true,
            'description' => $de
                ? 'Öffnen Sie Google Maps → Teilen → Karte einbetten → die src="…"-URL kopieren.'
                : 'Open Google Maps → Share → Embed a map → copy the src="…" URL.',
        ) );
    }

    protected function build_shortcode( array $s ): string {
        $src    = ! empty( $s['map_src'] ) ? ' src="' . esc_attr( $s['map_src'] ) . '"' : '';
        $width  = $this->size_string( $s['embed_width']  ?? array(), '100%' );
        $height = $this->size_string( $s['embed_height'] ?? array(), '450px' );
        return "[dcb_googlemaps{$src} width=\"{$width}\" height=\"{$height}\"]";
    }
}

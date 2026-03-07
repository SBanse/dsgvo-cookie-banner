<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Widget_OpenStreetMap extends DCB_Widget_Embed_Base {

    public function get_name()  { return 'dcb_openstreetmap'; }
    public function get_title() { return '🗺 OpenStreetMap'; }
    public function get_icon()  { return 'eicon-map-pin'; }
    public function get_keywords() {
        return array_merge( parent::get_keywords(), array( 'openstreetmap', 'osm', 'map', 'karte', 'datenschutz' ) );
    }

    protected function get_embed_type() { return 'openstreetmap'; }

    protected function register_source_controls() {
        $de = DCB_I18n::get_lang() === 'de';

        $this->add_control( 'lat', array(
            'label'       => $de ? 'Breitengrad (Latitude)' : 'Latitude',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => '52.52',
            'description' => $de ? 'z.B. 52.5200 für Berlin' : 'e.g. 51.5074 for London',
        ) );

        $this->add_control( 'lng', array(
            'label'       => $de ? 'Längengrad (Longitude)' : 'Longitude',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => '13.405',
            'description' => $de ? 'z.B. 13.4050 für Berlin' : 'e.g. -0.1278 for London',
        ) );

        $this->add_control( 'zoom', array(
            'label'   => $de ? 'Zoom-Stufe' : 'Zoom Level',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'range'   => array( 'px' => array( 'min' => 1, 'max' => 19 ) ),
            'default' => array( 'size' => 14, 'unit' => 'px' ),
        ) );

        $this->add_control( 'osm_hint', array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => $de
                ? '<small>💡 Tipp: Koordinaten finden unter <a href="https://www.openstreetmap.org/" target="_blank">openstreetmap.org</a> → Rechtsklick → Adresse nachschlagen</small>'
                : '<small>💡 Tip: Find coordinates at <a href="https://www.openstreetmap.org/" target="_blank">openstreetmap.org</a> → Right-click → Show address</small>',
            'content_classes' => 'elementor-descriptor',
        ) );
    }

    protected function build_shortcode( $s ) {
        $lat    = ! empty( $s['lat'] )  ? ' lat="'  . esc_attr( $s['lat'] )  . '"' : '';
        $lng    = ! empty( $s['lng'] )  ? ' lng="'  . esc_attr( $s['lng'] )  . '"' : '';
        $zoom   = ! empty( $s['zoom']['size'] ) ? ' zoom="' . intval( $s['zoom']['size'] ) . '"' : ' zoom="14"';
        $width  = $this->size_string( $s['embed_width']  ?? array(), '100%' );
        $height = $this->size_string( $s['embed_height'] ?? array(), '400px' );
        return "[dcb_openstreetmap{$lat}{$lng}{$zoom} width=\"{$width}\" height=\"{$height}\"]";
    }
}

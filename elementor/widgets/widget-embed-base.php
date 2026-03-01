<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * DCB_Widget_Embed_Base
 *
 * Abstrakte Basisklasse für alle Einbettungs-Platzhalter-Widgets.
 * Stellt gemeinsame Größen- und Stil-Controls bereit.
 */
abstract class DCB_Widget_Embed_Base extends \Elementor\Widget_Base {

    public function get_categories() { return array( DCB_Elementor_Integration::CATEGORY ); }
    public function get_keywords()   { return array( 'dsgvo', 'gdpr', 'embed', 'datenschutz', 'privacy', 'placeholder' ); }
    public function get_style_depends()  { return array( 'dcb-embeds' ); }
    public function get_script_depends() { return array( 'dcb-embeds' ); }

    /** Kindklassen geben den Embed-Typ zurück (z.B. 'youtube') */
    abstract protected function get_embed_type(): string;

    /** Kindklassen registrieren Quell-Controls (id, src, url, lat/lng…) */
    abstract protected function register_source_controls(): void;

    /** Kindklassen bauen den Shortcode-String zusammen */
    abstract protected function build_shortcode( array $s ): string;

    /* ── Gemeinsame Controls ── */

    protected function register_controls(): void {
        $de = DCB_I18n::get_lang() === 'de';

        // Quell-Sektion (von Kindklasse befüllt)
        $this->start_controls_section( 'section_source', array(
            'label' => $de ? 'Quelle' : 'Source',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ) );
        $this->register_source_controls();
        $this->end_controls_section();

        // Größe
        $this->start_controls_section( 'section_size', array(
            'label' => $de ? 'Größe' : 'Size',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ) );

        $this->add_responsive_control( 'embed_width', array(
            'label'      => $de ? 'Breite' : 'Width',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => array( 'px', '%', 'vw' ),
            'range'      => array(
                'px' => array( 'min' => 100, 'max' => 2000 ),
                '%'  => array( 'min' => 10,  'max' => 100  ),
            ),
            'default'    => array( 'unit' => '%', 'size' => 100 ),
        ) );

        $this->add_responsive_control( 'embed_height', array(
            'label'      => $de ? 'Höhe' : 'Height',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => array( 'px', 'vh' ),
            'range'      => array( 'px' => array( 'min' => 100, 'max' => 1200 ) ),
            'default'    => array( 'unit' => 'px', 'size' => 400 ),
        ) );

        $this->end_controls_section();

        // Platzhalter-Stil
        $this->start_controls_section( 'section_placeholder_style', array(
            'label' => $de ? 'Platzhalter-Stil' : 'Placeholder Style',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ) );

        // Hole Standard-Farben aus dem gespeicherten Embed-Typ
        $embed    = DCB_Embeds::get_embed( $this->get_embed_type() );
        $def_bg   = $embed['bg_color']     ?? '#1a1a1a';
        $def_acc  = $embed['accent_color'] ?? '#ffffff';
        $def_tc   = $embed['text_color']   ?? '#ffffff';

        $this->add_control( 'placeholder_bg', array(
            'label'     => $de ? 'Hintergrundfarbe' : 'Background Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => $def_bg,
            'selectors' => array( '{{WRAPPER}} .dcb-embed-placeholder' => '--dcb-embed-bg: {{VALUE}};' ),
        ) );

        $this->add_control( 'placeholder_accent', array(
            'label'     => $de ? 'Akzentfarbe (Button)' : 'Accent Color (Button)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => $def_acc,
            'selectors' => array( '{{WRAPPER}} .dcb-embed-placeholder' => '--dcb-embed-accent: {{VALUE}};' ),
        ) );

        $this->add_control( 'placeholder_text_color', array(
            'label'     => $de ? 'Textfarbe' : 'Text Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => $def_tc,
            'selectors' => array( '{{WRAPPER}} .dcb-embed-placeholder' => '--dcb-embed-tc: {{VALUE}};' ),
        ) );

        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), array(
            'name'     => 'placeholder_border',
            'selector' => '{{WRAPPER}} .dcb-embed-placeholder',
        ) );

        $this->add_control( 'placeholder_border_radius', array(
            'label'      => $de ? 'Rahmen-Radius' : 'Border Radius',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array( '{{WRAPPER}} .dcb-embed-placeholder' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
        ) );

        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        echo do_shortcode( $this->build_shortcode( $s ) );
    }

    /* ── Hilfs-Methode: Breite/Höhe aus Elementor-Slider → String ── */
    protected function size_string( $control_value, string $default ): string {
        if ( empty( $control_value['size'] ) ) return $default;
        return $control_value['size'] . $control_value['unit'];
    }
}

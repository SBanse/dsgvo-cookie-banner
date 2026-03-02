<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Widget: Datenschutz-Einstellungen-Button
 * Shortcode: [dcb_privacy_settings]
 */
class DCB_Widget_Privacy_Settings extends \Elementor\Widget_Base {

    public function get_name()  { return 'dcb_privacy_settings'; }
    public function get_title() { return DCB_I18n::get_lang() === 'de' ? '⚙️ Cookie-Einstellungen' : '⚙️ Cookie Settings'; }
    public function get_icon()  { return 'eicon-toggle'; }
    public function get_categories() { return array( DCB_Elementor_Integration::CATEGORY ); }
    public function get_keywords()   { return array( 'cookie', 'dsgvo', 'gdpr', 'settings', 'einstellungen', 'datenschutz' ); }

    protected function register_controls(): void {
        $de = DCB_I18n::get_lang() === 'de';

        /* ── Inhalt ── */
        $this->start_controls_section( 'section_content', array(
            'label' => $de ? 'Inhalt' : 'Content',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ) );

        $this->add_control( 'button_text', array(
            'label'       => $de ? 'Button-Text' : 'Button Text',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => $de ? 'Cookie-Einstellungen ändern' : 'Change Cookie Settings',
            'placeholder' => $de ? 'Cookie-Einstellungen' : 'Cookie Settings',
        ) );

        $this->add_control( 'button_type', array(
            'label'   => $de ? 'Button-Typ' : 'Button Type',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'settings',
            'options' => array(
                'settings' => $de ? 'Einstellungen öffnen' : 'Open Settings',
                'banner'   => $de ? 'Banner öffnen' : 'Open Banner',
            ),
        ) );

        $this->end_controls_section();

        /* ── Style ── */
        $this->start_controls_section( 'section_style', array(
            'label' => $de ? 'Stil' : 'Style',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ) );

        $this->add_control( 'btn_color', array(
            'label'     => $de ? 'Textfarbe' : 'Text Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => array( '{{WRAPPER}} .dcb-reopen-banner, {{WRAPPER}} .dcb-open-btn' => 'color: {{VALUE}};' ),
        ) );

        $this->add_control( 'btn_bg', array(
            'label'     => $de ? 'Hintergrundfarbe' : 'Background Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => array( '{{WRAPPER}} .dcb-reopen-banner, {{WRAPPER}} .dcb-open-btn' => 'background-color: {{VALUE}};' ),
        ) );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
            'name'     => 'btn_typography',
            'selector' => '{{WRAPPER}} .dcb-reopen-banner, {{WRAPPER}} .dcb-open-btn',
        ) );

        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), array(
            'name'     => 'btn_border',
            'selector' => '{{WRAPPER}} .dcb-reopen-banner, {{WRAPPER}} .dcb-open-btn',
        ) );

        $this->add_control( 'btn_border_radius', array(
            'label'      => $de ? 'Rahmen-Radius' : 'Border Radius',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array( '{{WRAPPER}} .dcb-reopen-banner, {{WRAPPER}} .dcb-open-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
        ) );

        $this->add_responsive_control( 'btn_padding', array(
            'label'      => $de ? 'Innenabstand' : 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em' ),
            'selectors'  => array( '{{WRAPPER}} .dcb-reopen-banner, {{WRAPPER}} .dcb-open-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
        ) );

        $this->end_controls_section();
    }

    protected function render(): void {
        $s    = $this->get_settings_for_display();
        $text = ! empty( $s['button_text'] ) ? esc_attr( $s['button_text'] ) : '';
        if ( $s['button_type'] === 'banner' ) {
            echo do_shortcode( '[dcb_cookie_banner]' );
        } else {
            echo do_shortcode( '[dcb_privacy_settings text="' . $text . '"]' );
        }
    }

    public function get_script_depends() { return array( 'dcb-frontend' ); }
}

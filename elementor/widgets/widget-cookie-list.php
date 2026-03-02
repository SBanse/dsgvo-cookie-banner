<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Widget: Cookie-Liste
 * Shortcode: [dcb_cookie_list]
 */
class DCB_Widget_Cookie_List extends \Elementor\Widget_Base {

    public function get_name()  { return 'dcb_cookie_list'; }
    public function get_title() { return DCB_I18n::get_lang() === 'de' ? '🍪 Cookie-Liste' : '🍪 Cookie List'; }
    public function get_icon()  { return 'eicon-table'; }
    public function get_categories() { return array( DCB_Elementor_Integration::CATEGORY ); }
    public function get_keywords()   { return array( 'cookie', 'dsgvo', 'gdpr', 'datenschutz', 'privacy' ); }

    protected function register_controls(): void {
        $lang = DCB_I18n::get_lang();
        $de   = $lang === 'de';

        /* ── Inhalt ── */
        $this->start_controls_section( 'section_content', array(
            'label' => $de ? 'Inhalt' : 'Content',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ) );

        // Kategorie-Optionen dynamisch aufbauen
        $cat_options = array( '' => $de ? 'Alle Kategorien' : 'All categories' );
        $settings = DCB_Cookie_Manager::get_settings();
        foreach ( $settings['categories'] ?? array() as $key => $cat ) {
            $sk = $cat['shortcode_key'] ?? $key;
            $cat_options[ $sk ] = $cat['label'];
        }

        $this->add_control( 'category', array(
            'label'   => $de ? 'Kategorie filtern' : 'Filter category',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '',
            'options' => $cat_options,
        ) );

        $this->add_control( 'show_scan_date', array(
            'label'        => $de ? 'Scan-Datum anzeigen' : 'Show scan date',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => $de ? 'Ja' : 'Yes',
            'label_off'    => 'Nein',
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        $cat_attr = ! empty( $settings['category'] ) ? ' category="' . esc_attr( $settings['category'] ) . '"' : '';
        echo do_shortcode( '[dcb_cookie_list' . $cat_attr . ']' );
    }

    public function get_style_depends() { return array( 'dcb-frontend' ); }
}

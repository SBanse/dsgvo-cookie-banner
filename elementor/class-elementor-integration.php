<?php
/**
 * DCB_Elementor_Integration
 *
 * Lädt alle Elementor-Widgets des DSGVO Cookie Banner Plugins.
 * Wird nur initialisiert wenn Elementor aktiv ist.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class DCB_Elementor_Integration {

    /** Widget-Kategorien-Slug */
    const CATEGORY = 'dcb-widgets';

    public static function init(): void {
        // Warten bis Elementor bereit ist
        add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widgets' ) );
        add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'register_category' ) );
    }

    /**
     * Eigene Widget-Kategorie „DSGVO Cookie Banner" im Elementor-Panel registrieren.
     *
     * @param \Elementor\Elements_Manager $manager
     */
    public static function register_category( $manager ): void {
        $manager->add_category( self::CATEGORY, array(
            'title' => '🍪 DSGVO Cookie Banner',
            'icon'  => 'fa fa-shield',
        ) );
    }

    /**
     * Alle Widget-Dateien einbinden und Widgets registrieren.
     *
     * @param \Elementor\Widgets_Manager $manager
     */
    public static function register_widgets( $manager ): void {
        require_once __DIR__ . '/widgets/widget-embed-base.php';
        require_once __DIR__ . '/widgets/widget-cookie-list.php';
        require_once __DIR__ . '/widgets/widget-privacy-settings.php';
        require_once __DIR__ . '/widgets/widget-youtube.php';
        require_once __DIR__ . '/widgets/widget-vimeo.php';
        require_once __DIR__ . '/widgets/widget-googlemaps.php';
        require_once __DIR__ . '/widgets/widget-openstreetmap.php';
        require_once __DIR__ . '/widgets/widget-instagram.php';
        require_once __DIR__ . '/widgets/widget-twitter.php';
        require_once __DIR__ . '/widgets/widget-facebook.php';

        $manager->register( new DCB_Widget_Cookie_List() );
        $manager->register( new DCB_Widget_Privacy_Settings() );
        $manager->register( new DCB_Widget_YouTube() );
        $manager->register( new DCB_Widget_Vimeo() );
        $manager->register( new DCB_Widget_GoogleMaps() );
        $manager->register( new DCB_Widget_OpenStreetMap() );
        $manager->register( new DCB_Widget_Instagram() );
        $manager->register( new DCB_Widget_Twitter() );
        $manager->register( new DCB_Widget_Facebook() );
    }
}

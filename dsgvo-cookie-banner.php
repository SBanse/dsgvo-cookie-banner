<?php
/**
 * Plugin Name: DSGVO Cookie Banner
 * Plugin URI:  https://github.com/sbanse/dsgvo-cookie-banner
 * Description: DSGVO/GDPR-konformer Cookie-Banner mit Scanner, Inline-Bearbeitung, Mehrsprachigkeit (DE/EN) und datenschutzkonformen Einbettungs-Platzhaltern für YouTube, Maps & Social Media.
 * Version:     1.2.0
 * Author:      sbanse
 * License:     GPL-2.0+
 * Text Domain: dsgvo-cookie-banner
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'DCB_VERSION',     '1.2.0' );
define( 'DCB_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'DCB_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'DCB_PLUGIN_FILE', __FILE__ );

require_once DCB_PLUGIN_DIR . 'includes/class-cookie-manager.php';
require_once DCB_PLUGIN_DIR . 'includes/class-i18n.php';
require_once DCB_PLUGIN_DIR . 'includes/class-cookie-scanner.php';
require_once DCB_PLUGIN_DIR . 'includes/class-shortcodes.php';
require_once DCB_PLUGIN_DIR . 'includes/class-embeds.php';
require_once DCB_PLUGIN_DIR . 'includes/class-embed-shortcodes.php';
require_once DCB_PLUGIN_DIR . 'admin/class-admin.php';
require_once DCB_PLUGIN_DIR . 'public/class-frontend.php';

register_activation_hook( __FILE__,   array( 'DCB_Cookie_Manager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'DCB_Cookie_Manager', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'DCB_I18n', 'init' ), 5 );

add_action( 'plugins_loaded', function () {
    new DCB_Admin();
    new DCB_Frontend();
    new DCB_Shortcodes();
    new DCB_Embed_Shortcodes();
}, 10 );

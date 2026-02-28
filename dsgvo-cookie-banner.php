<?php
/**
 * Plugin Name: DSGVO Cookie Banner
 * Plugin URI:  https://example.com
 * Description: Datenschutzkonformer Cookie-Banner nach europäischem Standard (DSGVO/GDPR) mit Cookie-Scanner und Impressum-Shortcode.
 * Version:     1.0.0
 * Author:      Ihr Name
 * License:     GPL-2.0+
 * Text Domain: dsgvo-cookie-banner
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DCB_VERSION',     '1.0.0' );
define( 'DCB_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'DCB_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'DCB_PLUGIN_FILE', __FILE__ );

require_once DCB_PLUGIN_DIR . 'includes/class-cookie-scanner.php';
require_once DCB_PLUGIN_DIR . 'includes/class-cookie-manager.php';
require_once DCB_PLUGIN_DIR . 'includes/class-shortcodes.php';
require_once DCB_PLUGIN_DIR . 'admin/class-admin.php';
require_once DCB_PLUGIN_DIR . 'public/class-frontend.php';

register_activation_hook( __FILE__,   array( 'DCB_Cookie_Manager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'DCB_Cookie_Manager', 'deactivate' ) );

new DCB_Admin();
new DCB_Frontend();
new DCB_Shortcodes();

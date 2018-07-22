<?php
/**
 * Plugin Name: WooCommerce Multilevel Referral Plugin
 * Plugin URI: http://referral.prismitworks.com/
 * Description: The WooCommerce Multilevel Plugin is a WooCommerce Add-On Plugin. 
This plugin is best suited to WooCommerce store owners who wish to increase their sales through a referral chain. 
Users can earn credit points whilst their followers buy products from the existing online store. 
The credit points then can be redeemed at the time of personal purchase.
 * Version: 1.4.7
 * Author: Prism I.T. Systems
 * Author URI: http://www.prismitsystems.com
 * Developer: Prism I.T. Systems
 * Developer URI: http://www.prismitsystems.com
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 * Copyright: &copy; 2009-2018 PRISM IT SYSTEMS.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}
require_once( ABSPATH. 'wp-includes/pluggable.php');

define( 'WMC_NAME','WooCommerce Multilevel Referral Plugin' );
define( 'WMC_REQUIRED_PHP_VERSION', '5.3' );                          // because of get_called_class()
define( 'WMC_REQUIRED_WP_VERSION',  '3.1' );                          // because of esc_textarea()
define( 'WMC_DIR', plugin_dir_path( __FILE__ ) );
define( 'WMC_URL', plugin_dir_url( __FILE__ ) );

add_action('init', 'plugin_init'); 
function plugin_init() {
    load_plugin_textdomain( 'wmc', false, dirname(plugin_basename(__FILE__)).'/languages/' );    
}
/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
if( !function_exists('wmc_requirements_check') ){
	function wmc_requirements_check() {
		global $wp_version;		
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );		// to get is_plugin_active() early	
		if ( version_compare( PHP_VERSION, WMC_REQUIRED_PHP_VERSION, '<' ) ) {
			return false;
		}	
		if ( version_compare( $wp_version, WMC_REQUIRED_WP_VERSION, '<' ) ) {
			return false;
		}		
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return false;
		}		
		return true;
	}
}
/**
 * Prints an error that the system requirements weren't met.
 */
function wmc_requirements_error() {
	global $wp_version;
	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
}


/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if ( wmc_requirements_check() ) {    
	require_once( __DIR__ . '/classes/wmc-module.php' );	
    if(is_admin()){
        require_once( __DIR__ . '/classes/admin/table-users.php' );
        require_once( __DIR__ . '/classes/admin/table-credit_logs.php' );
        require_once( __DIR__ . '/classes/admin/table-orderwise_credits.php' );
        require_once( __DIR__ . '/classes/admin/settings-general.php' );
        require_once( __DIR__ . '/classes/admin/users.php' );
        require_once( __DIR__ . '/classes/admin/referral.php' );
        require_once( __DIR__ . '/classes/admin/metabox-product.php' );
    }
	require_once( __DIR__ . '/classes/woocommerce-multilevel-referral.php' );
	//require_once( __DIR__ . '/classes/referral-mail.php' );	
	require_once( __DIR__ . '/classes/referral-program.php' );
	require_once( __DIR__ . '/classes/referral-users.php' );	
	require_once( __DIR__ . '/classes/woocommerce-order.php' );    
	if ( class_exists( 'WooCommerce_Multilevel_Referal' ) ) {
		$GLOBALS['wpps'] = WooCommerce_Multilevel_Referal::get_instance();
		register_activation_hook(   __FILE__, array( $GLOBALS['wpps'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['wpps'], 'deactivate' ) );
	}	
} else {
	add_action( 'admin_notices', 'wmc_requirements_error' );
}
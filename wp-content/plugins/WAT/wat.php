<?php
/*
Plugin Name: WAT
Plugin URI: http://codecanyon.net/item/wordpress-admin-theme/10822456
Description: WAT wordpress plugin to customize the WordPress Admin theme and elements as your wish.
Version: 4.0
Author: KannanC
Author URI: http://acmeedesign.com
*/


/*
*	WAT Constants
*/
define( 'WAT_VERSION' , '4.0' );    
define( 'WAT_PATH' , dirname(__FILE__) . "/"); 
define( 'WAT_DIR_URI' , plugin_dir_url(__FILE__) );

/*
*	Enabling Global Customization for Multi-site installation
*      
*/
if(is_multisite())
	define('NETWORK_ADMIN_CONTROL', true);
// Delete the above two lines to enable customization per blog

if ( ! empty( $_GET['action'] ) && ! empty( $_GET['plugin'] ) ) {
    if ( $_GET['action'] == 'activate' ) {
        return;
    }
}
// Check if the framework plugin is activated
$useEmbeddedFramework = true;
$activePlugins = get_option('active_plugins');
if ( is_array( $activePlugins ) ) {
    foreach ( $activePlugins as $plugin ) {
        if ( is_string( $plugin ) ) {
            if ( stripos( $plugin, '/titan-framework.php' ) !== false ) {
                $useEmbeddedFramework = false;
                break;
            }
        }
    }
}
// Use the embedded Titan Framework
if ( $useEmbeddedFramework ) {
    require_once( WAT_PATH . 'includes/titan-framework/titan-framework.php' );
}


include_once WAT_PATH . 'includes/watthemes.class.php';
include_once WAT_PATH . 'includes/fa-icons.class.php';
include_once WAT_PATH . 'includes/wat.class.php';

register_deactivation_hook( __FILE__, array( 'WAT', 'deleteOptions') );
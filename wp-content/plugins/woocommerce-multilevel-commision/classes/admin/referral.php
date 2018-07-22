<?php
/**
 * WooCommerce Multilevel Referral
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WMR_Referal_Settings' ) ) :

/**
 * WMR_Referal_Settings.
 */
class WMR_Referal_Settings extends WMC_Module {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_hook_callbacks();
	}

	/**
	 * Register callbacks for actions and filters
	 *
	 * @mvc Controller
	 */
	public function register_hook_callbacks() {
        $ref_=get_option('wmc_sutats');  
        if($ref_=='9f7d0ee82b6a6ca7ddeae841f3253059'){
		    add_action( 'admin_menu', __CLASS__.'::_add_referal_menu_callback', 99 );
        }
	}
	
	public static function _add_referal_menu_callback(){
		add_submenu_page( 'woocommerce', __( 'Referral', 'wmc' ),  __( 'Referral', 'wmc' ) , 'manage_woocommerce', 'wc_referral', __CLASS__.'::referal_program' );
	}
	
	public static function referal_program(){
		    $template = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'referral-users';
		    $option = 'per_page';
		    $args   = [
			    'label'   => 'Orders',
			    'default' => 5,
			    'option'  => 'orders_per_page'
		    ];
		    add_screen_option( $option, $args );
		    
		    WMR_Referal_Settings::_save_referal_templates_callback();
		    
		    $obj_referal_users	=	new Referal_Users();
		    $obj_referal_program=	new Referal_Program();
		    
		    $users				=	count_users();
		    $total_referrals	=	$obj_referal_users->record_count();
		    $total_credits		=	get_woocommerce_currency_symbol() . $obj_referal_program->total_statistic( 'credits' );
		    $total_redeems		=	get_woocommerce_currency_symbol() . $obj_referal_program->total_statistic( 'redeems' );
		    
		    $data = array(
						    'total_users'	=>	$users['total_users'],
						    'total_referrals'=>	$total_referrals,
						    'total_credites'	=>	$total_credits,
						    'total_redeems'	=>	$total_redeems
					      );
		    
		    echo self::render_template( 'admin/referral-header.php', array('data' => $data ));
		    echo self::render_template( 'admin/'.$template.'.php');
    
	}
	
	/*
	 *	Save email templates
	 */
	public static function _save_referal_templates_callback(){
		if(isset($_POST['save_template'])){
			update_option('joining_mail_template', $_POST['joining_mail_template'] );
			update_option('referral_user_template', $_POST['referral_user_template'] );
			update_option('expire_notification_template', $_POST['expire_notification_template'] );
		}
	}
	
	public function activate( $network_wide ){
		
	}

	/**
	 * Rolls back activation procedures when de-activating the plugin
	 *
	 * @mvc Controller
	 */
	public function deactivate(){
		
	}

	/**
	 * Initializes variables
	 *
	 * @mvc Controller
	 */
	public function init(){
		
	}

	/**
	 * Checks if the plugin was recently updated and upgrades if necessary
	 *
	 * @mvc Controller
	 *
	 * @param string $db_version
	 */
	public function upgrade( $db_version = 0 ){
		
	}

	/**
	 * Checks that the object is in a correct state
	 *
	 * @mvc Model
	 *
	 * @param string $property An individual property to check, or 'all' to check all of them
	 * @return bool
	 */
	public function is_valid($valid = "all"){
		return true;
	}

}
 new WMR_Referal_Settings();
endif;
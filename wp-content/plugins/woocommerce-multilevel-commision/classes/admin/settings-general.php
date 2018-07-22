<?php
/**
 * WooCommerce Multilevel Referral General Settings
 *
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
if ( ! class_exists( 'WMR_Settings_General' )) :

/**
 * WC_Admin_Settings_General.
 */
class WMR_Settings_General extends WMC_Module  {

	public $panel_id;
	

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->panel_id = 'wmr_general';
		$this->register_hook_callbacks();
        //delete_option('wmc_sutats');
	}

	public function register_hook_callbacks(){
		//$this->label = __( 'Referral', 'wmc' );		
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab' , 30 );
		add_action( 'woocommerce_settings_tabs_' . $this->panel_id,  __CLASS__. '::settings_tab' );
		add_action( 'woocommerce_update_options_' . $this->panel_id,  __CLASS__. '::save_settings' );		
		add_action( 'woocommerce_settings_' . $this->panel_id,	__CLASS__.'::start_panel' );
		add_action( 'woocommerce_settings_' . $this->panel_id . '_end',	__CLASS__.'::end_panel' );	
		add_action( 'wmc_validation_notices', __CLASS__.'::wmc_validation_error' ); 
        add_action( 'woocommerce_product_options_general_product_data', __CLASS__. '::wmc_add_custom_general_fields' );
        add_action( 'woocommerce_process_product_meta', __CLASS__. '::wmc_add_custom_general_fields_save' );  
        add_action( 'product_cat_add_form_fields', __CLASS__. '::wmc_add_product_cat_fields' );  
        add_action( 'product_cat_edit_form_fields', __CLASS__. '::wmc_edit_product_cat_fields' );          
        add_action( 'edit_product_cat', __CLASS__. '::wmc_product_cat_fields_save' , 10, 2);  
        add_action( 'create_product_cat', __CLASS__. '::wmc_product_cat_fields_save', 10, 2 );  
        //add_action( 'woocommerce_product_write_panel_tabs', __CLASS__. '::wmc_add_custom_admin_product_tab' );
        
        $c=get_option('wmc_purchase_code','');        
        $last_checked=get_option('wmc_last_checked',strtotime(date('Y-m-d',strtotime("-1 days"))));        
        $current_time=strtotime(date('Y-m-d'));               
        $datediff = floor($current_time/(60*60*24)) - floor($last_checked/(60*60*24));
        if($c!='' && $datediff>0){
            $response=self::fnValidateTheCopy($c);
            if($response!='invalid' && is_array($response)){                  
                update_option('wmc_sutats','9f7d0ee82b6a6ca7ddeae841f3253059');
                update_option('wmc_last_checked',strtotime(date('Y-m-d')));                
            }else{
                update_option('wmc_sutats','fedb2d84cafe20862cb4399751a8a7e3');
            }
        }      
       
	}		

	public static function wmc_validation_error($error){
		echo '<div class="wmc_error notice notice-error"><p>'.$error.'</p></div>';
	}

	public static function start_panel(){
		echo '<div id="wmr_general_setting_panel">';	             
	}	 
	public static function end_panel(){
		echo '</div>';	
	}	 
	/*
	 *	Add setting to 
	 */
	public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['wmr_general'] = __( 'Referral', 'wmc' );
        return $settings_tabs;
    }
	
	public static function settings_tab(){
		woocommerce_admin_fields( self::get_settings() );
	}
	
	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public static function get_settings() {
        $arrExcludeProducts=get_option('wmc_exclude_products');
        $json_ids    = array();
        if($arrExcludeProducts && is_array($arrExcludeProducts)){
		    $product_ids = array_filter( array_map( 'absint',  get_option('wmc_exclude_products')));
		    foreach ( $product_ids as $product_id ) {
			    $product = wc_get_product( $product_id );
			    if ( is_object( $product ) ) {
				    $json_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
			    }
		    }
        }
        $arrPages=array(0=>__( 'Select Page', 'wmc' ));
        $pages = get_pages(); 
        foreach($pages as $page){
            $arrPages[$page->ID]=__($page->post_title, 'wmc' );
        }
        $ref_=get_option('wmc_sutats');        
        if(!$ref_ || $ref_=='fedb2d84cafe20862cb4399751a8a7e3'){
		    $settings = apply_filters( 'woo_referal_general_settings', array(
            array( 'title' => __( 'Activate your Copy', 'woocommerce' ), 'type' => 'title', 'desc' => '', 'id' =>  'wmr_general_setting_panel', 'class' => 'referral_option_title' ),        
            array(
                'title'    => __( 'Purchase Code', 'wmc' ),
                'desc'     => __( 'Enter Purchase Code.', 'wmc' ),
                'id'       => 'wmc_purchase_code',
                'css'      => 'width:400px;',
                'desc_tip' =>  true,
                'type'     => 'text'
            ),
             array( 'type' => 'sectionend', 'id' => 'wmr_license_setting_panel')
    ));  
       }else if($ref_=='9f7d0ee82b6a6ca7ddeae841f3253059'){
           $arrSettings=array(
           array( 'title' => __( 'Referral Options', 'wmc' ), 'type' => 'title', 'desc' => '', 'id' =>  'wmr_general_setting_panel', 'class' => 'referral_option_title' ),
            array(
                'title'    => __( 'Global Store Credit (%)', 'wmc' ),
                'desc'     => '<br>'.__( '1. The defined credit points will be deposited in affiliate users account.<br>2. For more information about "How credit system works?" visit <a href="http://referral.prismitworks.com/#ffs-tabbed-13" target="_blank">here</a>', 'wmc' ),
                'id'       => 'wmc_store_credit',
                'css'      => 'width: 100px;',
                'type'     => 'text',
                'desc_tip' =>  false
            ),
            array(
                'title'    => __( 'Welcome Credit', 'wmc' ),
                'desc'     => '<br>'.__( '1. All Users : All users including the existing ones will be presented with Welcome Credits on their first purchase.', 'wmc' ).'<br>'.__('2. New Users : Only the newly registered users will be presented with Welcome Credits on their first purchase. Existing users are not entitled for this benefit.','wmc'),
                'id'       => 'wmc_welcome_credit_for',                
                'type'     => 'select',
                'class'    => 'wc-enhanced-select',
                'css'      => 'min-width: 100px;',
                'desc_tip' =>  false,
                'options'  =>  array(
                    'all'    => __( 'All Users', 'wmc' ),        
                    'new'   => __( 'New Users', 'wmc' )
                )
            ), 
            array(
                'title'    => __( 'Credit validity by period', 'wmc' ),
                'desc'     => '<br>'.__( 'This sets the number of months/years for expire credits.', 'wmc' ),
                'id'       => 'wmc_credit_validity_number',
                'css'      => 'width:50px;',
                'desc_tip' =>  false,
                'type'     => 'number',
            ),
            
            array(
                'title'    => '',
                'id'       => 'wmc_credit_validity_period',
                'default'  => '',
                'type'     => 'select',
                'class'    => 'wc-enhanced-select set_position',
                'css'      => 'width: 100px;',
                'desc_tip' =>  false,
                'options'  => array(
                    ''            => __( 'Select expiry', 'wmc' ),        
                    'month'       => __( 'Month', 'wmc' ),        
                    'year'        => __( 'Year', 'wmc' ),        
                )
            ),

            array(
                'title'    => __( 'Notification Mail Time', 'wmc' ),
                'desc'     => __( 'This sets the number of days for send notification mail for expire credits.', 'wmc' ),
                'id'       => 'wmc_notification_mail_time',
                'css'      => 'width:50px;',
                'desc_tip' =>  true,
                'type'     => 'number',
            ),

            array(
                'title'    => __( 'Monthly max credit limit','wmc').'('.get_woocommerce_currency_symbol().')',
                'desc'     => __( 'The credit points will not be credited more than defined limit in the period of one month', 'wmc' ),
                'id'       => 'wmc_max_credit_limit',
                'css'      => 'width:100px;',
                'desc_tip' =>  true,
                'type'     => 'number',
            ),
            
            array(
                'title'    => __( 'Max Redemption (%)', 'wmc' ),
                'desc'     => __( 'You can define the limit for redemption. If you set 50% then user can not be redeem points more than 50% of product price.', 'wmc' ),
                'id'       => 'wmc_max_redumption',
                'css'      => 'width:100px;',
                'desc_tip' =>  true,
                'type'     => 'number',
            ),
            
            array(
                'title'    => __( 'Exclude products', 'wmc' ),
                'desc'     => __( 'Select the product which you want to be exclude from this referral program', 'wmc' ),
                'id'       => 'wmc_exclude_products',                
                'css'      => 'width:100%;',
                'desc_tip' =>  true,
                'type'     => 'multiselect',                
                'class'        =>   'wc-product-search',                
                'options'   =>  $json_ids,
                'placeholder'    =>    __('Exclude products', 'wmc'),
                'custom_attributes'    =>    array(                    
                    'data-action'    =>    'woocommerce_json_search_products',
                    'data-multiple'    =>    'true'
                )
            ),
            array(
                'title'    => __( 'Terms And Conditions Page', 'wmc' ),
                'desc'     => __( 'Select the terms and condition page', 'wmc' ),
                'id'       => 'wmc_terms_and_conditions',                
                'type'     => 'select',
                'class'    => 'wc-enhanced-select',
                'css'      => 'min-width: 100px;',
                'desc_tip' =>  true,
                'options'  =>  $arrPages
            ),
            array(
                'title'    => __( 'Auto Join', 'wmc' ),
                'desc'     => __( 'Select "Yes" if you want to register users automatically to referral program', 'wmc' ),
                'id'       => 'wmc_auto_register',                
                'type'     => 'select',
                'class'    => 'wc-enhanced-select',
                'css'      => 'min-width: 100px;',
                'desc_tip' =>  true,
                'options'  =>  array(
                    'no'            => __( 'No', 'wmc' ),        
                    'yes'            => __( 'Yes', 'wmc' )
                )
            ), 
            array(
                'title'    => __( 'Category Credit Preference', 'wmc' ),
                'desc'     => '<br>'.__( 'In case of multiple category selected for product, this setting will decide which credit percentage should be used. If "Highest" selected then highest percentage between all the categories will be considered, if "Lowest" selected lowest percentage will be considered', 'wmc' ),
                'id'       => 'wmc_cat_pref',                
                'type'     => 'select',
                'class'    => 'wc-enhanced-select',
                'css'      => 'min-width: 100px;',
                'desc_tip' =>  false,
                'options'  =>  array(
                    'lowest'    => __( 'Lowest', 'wmc' ),        
                    'highest'   => __( 'Highest', 'wmc' )
                )
            ) );
           $addSettings=apply_filters('wmc_additional_settings',$arrSettings);
           array_push($addSettings, array( 'type' => 'sectionend', 'id' => 'wmr_general_setting_panel'));
           $settings = apply_filters( 'woo_referal_general_settings', $addSettings); 
        }        
		return apply_filters( 'woocommerce_get_settings_wmc', $settings );
	}
    function wmc_additional_settings($arrSettings){
        $new= array_push($arrSettings,array());
            return $new;
    }
    /* Category add credit input field */
    static function wmc_add_product_cat_fields(){
        ?>
            <div class="form-field">
                <label for="term_meta[wmc_cat_credit]"><?php _e( 'Affiliate Credit (%)', 'wmc' ); ?></label>
                <input type="text" placeholder ="<?php echo get_option('wmc_store_credit');?>" name="term_meta[wmc_cat_credit]" id="term_meta[wmc_cat_credit]" value="">
                <p class="description"><?php _e( 'Enter a credit percentage, this percentage will apply for all the products in this category','wmc' ); ?></p>
            </div>
        <?php
    }
    static function wmc_edit_product_cat_fields($term){
        $t_id = $term->term_id;
        $term_meta = get_option( "product_cat_$t_id" );
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[wmc_cat_credit]"><?php _e( 'Affiliate Credit (%)', 'wmc' ); ?></label></th>
            <td>
                <input type="text" placeholder ="<?php echo get_option('wmc_store_credit');?>" name="term_meta[wmc_cat_credit]" id="term_meta[wmc_cat_credit]" value="<?php echo __( $term_meta['wmc_cat_credit'] ) ? __( $term_meta['wmc_cat_credit'] ) : ''; ?>">
                <p class="description"><?php _e( 'Enter a credit percentage, this percentage will apply for all the products in this category','wmc' ); ?></p>
            </td>
        </tr>           
        <?php
    }
    static function wmc_product_cat_fields_save($term_id){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "product_cat_$t_id" );
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    if($key=='wmc_cat_credit'){
                        $_POST['term_meta'][$key]=floatval($_POST['term_meta'][$key]);
                    }
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            // Save the option array.
            update_option( "product_cat_$t_id", $term_meta );
        }
    }
    /* end */ 

	/**
	 * Save settings.
	 */
	public static function save_settings() {
        if(isset($_POST['wmc_purchase_code'])){
		    try{             
                if($_POST['wmc_purchase_code'] !=''){
                     $response=self::fnValidateTheCopy(sanitize_text_field($_POST['wmc_purchase_code']));
                     if($response!='invalid' && is_array($response)){
                          woocommerce_update_options(self::get_settings());
                          update_option('wmc_sutats','9f7d0ee82b6a6ca7ddeae841f3253059');
                     }else{
                         throw new Exception( __(base64_decode('SW52YWxpZCBQdXJjaGFzZSBjb2Rl'), 'wmc' ) );
                     }
                }else{
                    throw new Exception( __(base64_decode("UGxlYXNlIGFkZCBQdXJjaGFzZSBjb2RlIGFuZCB0aGVuIHRyeSBhZ2Fpbg=="), 'wmc' ) );
                }
		    }catch( Exception $e ){			
			    do_action('wmc_validation_notices',$e->getMessage());
		    }
        }else{
            woocommerce_update_options(self::get_settings());
        }
	}
    static function wmc_add_custom_admin_product_tab() {
    ?>
        <li class="referral_tab"><a href="#referral_tab_data"><?php _e('Multilevel Referral', 'wmc'); ?></a></li>
    <?php
    }
	static function wmc_add_custom_general_fields(){
        global $woocommerce, $post;
        echo '<div class="options_group"><h4 style="padding-left:10px;">'.__('Multilevel Referral Plugin Settings','wmc').'</h4>';
        woocommerce_wp_text_input( 
            array( 
                'id'          => 'wmc_credits', 
                'label'       => __( 'Affiliate Credit (%)', 'wmc' ), 
                'placeholder' => get_option('wmc_store_credit'),
                'desc_tip'    => true,
                'description' => __( '1. The defined credit points will be deposited in affiliate users account, when user purchase this product.<br>2. For more information about "How credit system works?" visit <a href="http://referral.prismitworks.com/#ffs-tabbed-13" target="_blank">here</a>', 'wmc' ) 
            )
        );
        echo '</div>';
    }
    
    static function wmc_add_custom_general_fields_save( $post_id){
      //  echo $int.'='.$int2.'<pre>';
      $woocommerce_text_field = sanitize_text_field($_POST['wmc_credits']); 
      if($woocommerce_text_field!=''){
          $woocommerce_text_field=floatval($woocommerce_text_field);
      }
      update_post_meta( $post_id, 'wmc_credits', $woocommerce_text_field);                
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
    static function fnValidateTheCopy($code){
        $result = false; // have we got a valid purchase code?
        $id = 16993804; // check if they've bought this item id.
        $uname = 'prismitsystems'; // authors username
        $aKey = 'zyoao0dxm5h6if6tr5o7bijdrpu29rrk'; // api key from my account area        
        //$strLiveURL=base64_decode('aHR0cDovL21hcmtldHBsYWNlLmVudmF0by5jb20vYXBpL2VkZ2Uv');
        $strLiveURL=base64_decode('aHR0cDovL21hcmtldHBsYWNlLmVudmF0by5jb20vYXBpL2VkZ2U=');
        $strLiveURL .= "/$uname/$aKey/verify-purchase:$code.json";           
       // $strLocalURL=base64_decode('aHR0cDovL3d3dy5zYWFnYXIuY29tL2FwaS5waHA=');
        //$strLocalURL.="?u=$uname&a=$aKey&c=$code";   
        $ch = curl_init($strLiveURL);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $agent = 'REFFERAL-AGENT';
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $json_res = curl_exec($ch);
        $data = json_decode($json_res,true);         
        $purchases = $data['verify-purchase'];
        if(isset($purchases['buyer'])){
           // format single purchases same as multi purchases
           $purchases=array($purchases); 
        }
        $purchase_details = array();
        if(is_array($purchases) && count($purchases)>0){
            foreach($purchases as $purchase){
            $purchase=(array)$purchase; // json issues
            if((int)$purchase['item_id']==(int)$id){
                // we have a winner!
                $result = true;
                $purchase_details = $purchase;
            }
        }
        }
        // do something with the users purchase details, 
        // eg: check which license they've bought, save their username something
        if($result){
            return $purchase_details;            
        }else{
            return 'invalid';
        }        
    }    
}
endif;
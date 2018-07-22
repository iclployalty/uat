<?php

    if ( ! class_exists( 'Referal_Users' ) ) {
        /**
        * Main / front controller class
        *
        */
        class Referal_Users extends WooCommerce_Multilevel_Referal {
            public $table_name;		
            public function __construct(){
                global $wpdb;
                $this->table_name = $wpdb->prefix . 'referal_users';
                $this->register_hook_callbacks();
            }

            public function register_hook_callbacks() {          
                add_action( 'init', 							array( $this, 'join_referral_program' ) );
                add_action( 'init', 							array( $this, 'send_invitation' ) );			
                add_action( 'woocommerce_register_form_start', 	array( $this, 'referral_register_start_fields' ) );
                add_action( 'woocommerce_register_form', 		array( $this, 'referral_register_fields' ) );
                add_action( 'woocommerce_register_post', 		array( $this, 'referral_registration_validation' ), 1, 3  );
                add_action( 'woocommerce_created_customer', 	array( $this, 'referral_customer_save_data' ) );

                add_action( 'delete_user',						array( $this, 'delete_user_callback' ) );
                add_shortcode( 'referral_link', 				array( $this, 'referral_link_callback' ) );		
                add_shortcode( 'wmc_invite_friends', array($this,'referral_user_invite_friends'));            
                add_shortcode( 'wmc_show_credit_info', array($this,'referral_user_credit_info'));                        
                add_shortcode( 'wmc_show_affiliate_info', array($this,'wmcShowMyAffiliates'));


                add_action( 'init', array($this, 'init_hook') );
            }

            /*
            *	Delete user from referral program
            *
            *	@param int Deleted user id
            *
            *	@return void
            */
            public function delete_user_callback( $customer_id ){
                global $wpdb;

                $this->change_referral_user( $customer_id );
                $this->delete( $customer_id );

                $parent_user_id = get_user_meta( $customer_id, 'meta_value', true );

                $query = 'UPDATE '.$wpdb->usermeta.' SET meta_value = "'.$parent_user_id.'" WHERE meta_key = "referral_parent" AND user_id IN ( SELECT * from ( SELECT user_id FROM '.$wpdb->usermeta.' WHERE `meta_key` LIKE "referral_parent" AND `meta_value` LIKE "'.$customer_id.'" ) as a)';
                $wpdb->query( $query );
            }

            /*
            * Call of referral_link shortcode
            *
            * @param $atts Attributes of shortcode
            *
            * @return string Link of referral program.
            */
            public function referral_link_callback( $atts ){
                global $customer_id, $referral_code;
                //$text_link = 'Click here';
                $pull_quote_atts = shortcode_atts( array(
                    'text' => 'Click here'
                    ), $atts );			
                $link = add_query_arg('ru', $referral_code, get_the_permalink( get_option('woocommerce_myaccount_page_id') ) );			
                return '<a href="'. $link .'" target="_blank">'.$pull_quote_atts['text'].'</a>';
            }

            /*
            * Static methods
            */
            public function create_table(){
                global $wpdb;			
                $wpdb->query('DROP FUNCTION IF EXISTS `followers_count`');			
                $sql = "
                CREATE FUNCTION `followers_count`(`parent_id` INT, `return_value` VARCHAR(1024)) 
                RETURNS VARCHAR(1024)
                BEGIN
                DECLARE rv,q,queue,queue_children2 VARCHAR(1024);
                DECLARE queue_length,front_id,pos INT;
                DECLARE no_of_followers INT;

                SET rv = parent_id;
                SET queue = parent_id;
                SET queue_length = 1;
                SET no_of_followers = 0;

                WHILE queue_length > 0 DO

                SET front_id = FORMAT(queue,0);
                IF queue_length = 1 THEN
                SET queue = '';
                ELSE
                SET pos = LOCATE(',',queue) + 1;
                SET q = SUBSTR(queue,pos);
                SET queue = q;
                END IF;
                SET queue_length = queue_length - 1;

                SELECT IFNULL(qc,'') INTO queue_children2
                FROM (SELECT GROUP_CONCAT(user_id) qc
                FROM " . $this->table_name . " WHERE referral_parent IN (front_id)) A;

                IF LENGTH(queue_children2) = 0 THEN
                IF LENGTH(queue) = 0 THEN
                SET queue_length = 0;
                END IF;
                ELSE
                IF LENGTH(rv) = 0 THEN
                SET rv = queue_children2;
                ELSE
                SET rv = CONCAT(rv,',',queue_children2);
                END IF;
                IF LENGTH(queue) = 0 THEN
                SET queue = queue_children2;
                ELSE
                SET queue = CONCAT(queue,',',queue_children2);
                END IF;
                SET queue_length = LENGTH(queue) - LENGTH(REPLACE(queue,',','')) + 1;
                END IF;
                END WHILE;

                IF(return_value = 'count') THEN
                SELECT count(*) into no_of_followers  FROM " . $this->table_name . " WHERE active = 1 AND FIND_IN_SET(referral_parent, rv );

                RETURN no_of_followers;
                ELSE
                RETURN rv;
                END IF;
                END";

                $wpdb->query( $sql );


                $sql = "CREATE TABLE " . $this->table_name . " (
                id int(11) NOT NULL AUTO_INCREMENT,
                user_id int(11)  NOT NULL,
                referral_parent  int(11)  NOT NULL,
                active  TINYINT(1) NOT NULL DEFAULT 1,
                referral_code VARCHAR(5) NOT NULL,
                referal_benefits  TINYINT(1) NOT NULL DEFAULT 0,
                referral_email VARCHAR(50) NOT NULL,
                join_date  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                update_date  TIMESTAMP NOT NULL DEFAULT 0,
                PRIMARY KEY  (id),
                INDEX `referral_users` (`referral_parent`, `user_id`)
                );";

                // we do not execute sql directly
                // we are calling dbDelta which cant migrate database
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);

            }

            /**
            * Insert record
            *
            * @mvc Controller
            */
            public function insert($data) {
                global $wpdb;
                $wpdb->insert(
                    $this->table_name,
                    $data
                );
            }

            public function delete($user_id){
                global $wpdb;
                $wpdb->delete(
                    $this->table_name,
                    array(
                        'user_id'	=>	$user_id
                    )
                );
            }

            public function update($user_id, $referral_parent, $status = 1){
                global $wpdb;
                $wpdb->update(
                    $this->table_name,
                    array(
                        'active'	=>	$status,
                        'update_date'	=>	date("Y-m-d H:i:s"),
                        'referral_parent'	=>	$referral_parent
                    ),
                    array(
                        'user_id'	=>	$user_id
                    )
                );
            }

            public function updateAll( $data, $user_id){
                global $wpdb;
                $wpdb->update(
                    $this->table_name,
                    $data,
                    array(
                        'user_id'	=>	$user_id
                    )
                );
            }

            /*
            *
            */
            public function get_referral_user( $user_id ){
                global $wpdb;

                $sql = 'SELECT referral_code, join_date, referal_benefits, followers_count(user_id, \'count\') as followers FROM '.$this->table_name. ' WHERE user_id = '.$user_id;
                return $wpdb->get_row( $sql, ARRAY_A );
            }

            public function referral_user($user_field, $where, $user_id){
                global $wpdb;			
                return $wpdb->get_var(
                    'SELECT '.$user_field.' FROM '.$this->table_name.' WHERE '.$where.' = "'. $user_id. '"'
                );
            }

            public function change_referral_user($user_id){
                global $wpdb;

                $parent_referral_user = $wpdb->get_var(
                    'SELECT referral_parent FROM '.$this->table_name.' WHERE user_id = '. $user_id
                );

                if( $parent_referral_user ){
                    $this->update( $user_id, $parent_referral_user, 0 );
                    $query = 'UPDATE '.$this->table_name.' SET referral_parent = '.$parent_referral_user.' WHERE referral_parent = '.$user_id;
                    $wpdb->query( $query );
                }
                return $parent_referral_user;
            }

            public function active_referral_user( $user_id ){
                global $wpdb, $inactive_user_array;

                $parent_referral_user = $wpdb->get_var(
                    'SELECT referral_parent FROM '.$this->table_name.' WHERE user_id = '. $user_id
                );
                $this->update( $user_id, $parent_referral_user, 1 );

                $query = 'SELECT um.user_id FROM '.$wpdb->usermeta.' AS um JOIN '.$this->table_name.' AS ru ON ru.user_id = um.user_id WHERE ru.active = 1 AND um.meta_value = "'.$user_id.'" AND um.`meta_key` = "referral_parent"';

                $active_user_list = $wpdb->get_col( $query);
                if( count( $active_user_list ) ){
                    $query = 'UPDATE '.$this->table_name.' SET referral_parent = '.$user_id.', update_date = "'.date("Y-m-d H:i:s") .'"  WHERE active = 1 AND user_id IN ('.implode(',', $active_user_list ).')';
                    $wpdb->query( $query );
                }

                $this->check_child_deactive_referral_user( $user_id );
                if(count($inactive_user_array) > 0){
                    $query = 'UPDATE '.$this->table_name.' SET referral_parent = '.$user_id.', update_date = "'.date("Y-m-d H:i:s") .'" WHERE active = 0 AND user_id IN ('.implode(',', $inactive_user_array ).')';
                    $wpdb->query( $query );
                }
                echo admin_url('admin.php?page=wc_referral&user_status=0&uid='.$user_id);
                die();
            }

            public function check_child_deactive_referral_user( $user_id ){
                global $wpdb, $inactive_user_array;
                $query = 'SELECT um.user_id FROM '.$wpdb->usermeta.' AS um JOIN '.$this->table_name.' AS ru ON ru.user_id = um.user_id WHERE ru.active = 0 AND um.meta_value = "'.$user_id.'" AND um.`meta_key` = "referral_parent"';
                $deactive_user_list = $wpdb->get_col( $query);
                if( count( $deactive_user_list ) ){
                    foreach( $deactive_user_list as $deactive_user ){
                        $inactive_user_array[] = $deactive_user;
                        $this->check_child_deactive_referral_user( $deactive_user );
                    }
                }
            }

            /**
            * Add new register fields for WooCommerce registration.
            *
            * @return string Register fields HTML.
            */
            public function referral_register_start_fields(){
                if( isset( $_GET['ru'] ) && !isset( $_POST['referral_code'] ) && $_GET['ru'] != '' ){
                    $referral_email = $this->referral_user( 'referral_email', 'referral_code', sanitize_text_field($_GET['ru']) );
                    if( $referral_email ){
                        $_POST['email'] = $referral_email;
                    }
                }
                echo self::render_template( 'front/register_form_start_fields.php' );
            }

            /*
            *	Add referral program form to register form
            */
            public function referral_register_fields(){
                //print_r($_POST);

                $data = array(
                    'join_referral_program'	=> isset( $_POST['join_referral_program'] ) ? sanitize_text_field($_POST['join_referral_program']) : ( isset( $_GET['ru'] ) && !isset( $_POST['join_referral_program'] ) ? 2 : 0 ),
                    'referral_email'		=> isset( $_POST['referral_email'] ) ? sanitize_text_field( $_POST['referral_email'] ) : '',
                    'referral_code'			=> isset( $_POST['referral_code'] ) ? sanitize_text_field( $_POST['referral_code'] ) : ( isset( $_GET['ru'] ) && !isset( $_POST['referral_code'] ) ? sanitize_text_field( $_GET['ru'] ) : '' ),
                );
                //print_r($data);
                echo self::render_template( 'front/register_form_end_fields.php', array('data' => $data ) );
            }

            /*
            * Referral Program Dashboard
            *
            * @return void
            */
            /* public function referral_user_account_panel_old(){
            global $invitation_error;
            $check_user = $this->referral_user( 'user_id', 'user_id', get_current_user_id() );
            if( $check_user ){				
            $current_user_id = $check_user;				
            $obj_referal_program = new Referal_Program();
            $data = array(
            'referral_code' 	=> 	$this->referral_user( 'referral_code', 'user_id', $current_user_id ),
            'total_points' 		=> 	$obj_referal_program->available_credits( $current_user_id ),
            'total_followers'	=> 	$obj_referal_program->no_of_followers( $current_user_id ),
            'records'			=>	$obj_referal_program->select_all( 0, 1, $current_user_id ),
            //'invitation_status'	=>	(isset ($_POST['emails'])) ? '' : 'hide',
            'emails'			=>	isset( $_POST['emails'] ) ? sanitize_text_field($_POST['emails']) : ''
            );
            echo self::render_template( 'front/myaccount.php', array('data' => $data ) );		
            }else{
            $data = array(
            'join_referral_program'	=> isset( $_POST['join_referral_program'] ) ? sanitize_text_field($_POST['join_referral_program']) : 1,
            'referral_email'		=> isset( $_POST['referral_email'] ) ? sanitize_text_field( $_POST['referral_email'] ) : '',
            'referral_code'			=> isset( $_POST['referral_code'] ) ? sanitize_text_field( $_POST['referral_code'] ) : '',
            'nonce'					=>	wp_create_nonce('referral_program')
            );

            echo self::render_template( 'front/join-form.php', array('data' => $data ) );		
            }

            } */
            function referral_user_account_panel(){
                if(is_user_logged_in()){
                    $check_user = $this->referral_user( 'user_id', 'user_id', get_current_user_id() );
                    if( $check_user ){ 
                        echo do_shortcode('[wmc_invite_friends][wmc_show_affiliate_info][wmc_show_credit_info]', true);                  
                    }else{
                        $data = array(
                            'join_referral_program'    => isset( $_POST['join_referral_program'] ) ? sanitize_text_field($_POST['join_referral_program']) : 1,
                            'referral_email'        => isset( $_POST['referral_email'] ) ? sanitize_email( $_POST['referral_email'] ) : '',
                            'referral_code'            => isset( $_POST['referral_code'] ) ? sanitize_text_field( $_POST['referral_code'] ) : '',
                            'nonce'                    =>    wp_create_nonce('referral_program')
                        );                
                        echo self::render_template( 'front/join-form.php', array('data' => $data ) );
                    }
                }           
            }
            // Newly added checkout fields 19-01-2018 
            function wmc_override_checkout_fields($wmcFields){
                $wmcFields['account']['join_referral_program']=array(
                    'type' => 'select',
                    'label' => __('Join Referral Program', 'wmc'),
                    'placeholder' => _x('Join Referral Program', 'placeholder', 'wmc'),
                    'class' => array(''),
                    'label_class' => array('hidden')
                );
                $wmcFields['account']['join_referral_program']['options']=array(
                  '1' => __( 'I have the referral code and want to join referral program.', 'wmc' ),
                  '2' => __( 'I don\'t have referral code or I lost it. But I wish to join referral program.', 'wmc' ),
                  '3' => __( 'No, I don\'t want to be a part of referral program at this time.', 'wmc' )
                );
                $wmcFields['account']['referral_code']=array(
                    'type' => 'text',
                    'label' => __('Referral Code', 'wmc'),
                    'placeholder' => _x('Referral Code', 'placeholder', 'wmc'),
                    'class' => array(''),
                    'label_class' => array('hidden')
                );
                $wmcFields['account']['termsandconditions']=array(
                    'type' => 'checkbox',
                    'label' => __('I\'ve read and agree to the referral program&nbsp;', 'wmc').'<a href="'.esc_url( get_permalink(get_option('wmc_terms_and_conditions',0)) ).'" target="_blank">'.__( 'terms and conditions', 'wmc' ).'</a>',
                    'class' => array('validate-required'),
                    'label_class' => array('hidden')
                );
                return $wmcFields;                                            
            }
            function wmc_custom_checkout_field_process(){
                if(isset($_POST['createaccount']) && isset($_POST['join_referral_program'])){
                    if($_POST['join_referral_program']==1){ 
                        if(isset($_POST['referral_code']) && $_POST['referral_code']==''){
                            wc_add_notice( __( 'The Referral code is required field.','wmc' ), 'error' );
                        }
                        if(!isset($_POST['termsandconditions'])){
                            wc_add_notice( __( 'Please accept terms and conditions to join referral program.','wmc' ), 'error' );
                        }
                    }
                    if($_POST['join_referral_program']==2){ 
                         if(!isset($_POST['termsandconditions'])){
                            wc_add_notice( __( 'Please accept terms and conditions to join referral program.','wmc' ), 'error' );
                        }
                    }
                }                
            }
            
            /* Shortcode to display Invite friends form*/
            public function referral_user_invite_friends(){
                if(is_user_logged_in()){
                    global $invitation_error;
                    $check_user = $this->referral_user( 'user_id', 'user_id', get_current_user_id() );
                    $wmc_html='<div class="wmc-invite-friends">';                
                    if( $check_user ){ 
                        $email=isset( $_POST['emails'] ) ? sanitize_text_field($_POST['emails']) : '';
                        $wmc_html.='<p class="hide">
                        <a href="#" class="button btn-invite-friends">'.__('Invite Friends','wmc').'</a>
                        </p>
                        <div id="dialog-invitation-form">
                        <h2>'.__('Invite your friends', 'wmc' ).'</h2>       
                        <span><small>'.__('You can earn more credits by inviting more people to join this referral program. You can add comma separated list of emails below ...','wmc').'</small></span>
                        <form method="post">
                        <table class="shop_table shop_table_responsive">
                        <tr>
                        <td>
                        <input type="text" name="emails"  class="input-text" value="'.$email.'" placeholder="Ex. test@demo.com, test2@demo.com" />
                        </td>
                        <td width="105px">    
                        <input type="submit" class="button btn-send-invitation" value="'.__('Invite','wmc').'" />
                        <input type="hidden" name="action" value="send_invitations" />
                        </td>
                        </tr>
                        </table>
                        </form>
                        </div>';                    
                    }
                    $wmc_html.='</div>';                
                    return $wmc_html;
                }    
                return;        
            }
            function wmcShowBanners(){
                $allBanners=get_posts(array('post_type'=>'wmc-banner','numberposts'=>-1));
                $i=0;
                $firstBanner=$firstBannerPath=$firstTitle=$firstDesc=$firstUrl='';
                $wmc_html='<div id="wmc-social-media">
                <h2>'.__('Share on Social Media', 'wmc' ).'</h2>
                <h4>'.__('Select promotional banner from the following to share on social media', 'wmc' ).'</h4>
                <div class="wmc-banners">
                <div class="wmc-banner-list">
                <select>';
                foreach($allBanners as $banner){
                    $checked='';
                    $banner_thumbnail_id = get_post_thumbnail_id($banner->ID);
                    $banner_thumbnail_url = wp_get_attachment_url( $banner_thumbnail_id );
                    if($i<1){
                        $firstBanner=$banner_thumbnail_url;
                        $firstBannerPath=get_attached_file($banner_thumbnail_id);
                        $checked='checked="checked"';
                        $firstTitle=$banner->post_title;
                        $firstDesc=$banner->post_excerpt;                                    
                        $firstUrl=get_permalink($banner->ID);
                    }
                    $wmc_html.='<option value="'.$banner->ID.'" data-url="'.get_permalink($banner->ID).'" data-title="'.$banner->post_title.'" data-desc="'.$banner->post_excerpt.'" data-image="'.$banner_thumbnail_url.'">'.$banner->post_title.'</option>';
                    $i++;
                }
                $referralCode=__('Referral Code : ', 'wmc');
                $check_user = $this->referral_user( 'user_id', 'user_id', get_current_user_id() );
                if( $check_user ){
                    $current_user_id = $check_user;  
                    $referralCode .= $this->referral_user( 'referral_code', 'user_id', $current_user_id );
                }
                $siteUrl=__('Visit','wmc').' : '.site_url();
                $wmc_html.='</select></div>
                <div class="wmc-banner-preview"><div class="top-strip">'.$referralCode.'</div><div class="bottom-strip">'.$siteUrl.'</div>';
                if($firstBanner!=''){
                    $wmc_html.='<img width="100%" alt="Banner" src="'.$firstBanner.'"/>';
                }
                $wmc_html.='</div>
                </div>
                <div class="share42init" data-url="'.$firstUrl.'" data-title="'.$firstTitle.'" data-image="'.$firstBanner.'" data-description="'.$banner->post_excerpt.'"></div>';
                return $wmc_html.='</div>';
                // $this->writeTextonImage($referralCode,$firstBannerPath);
            }
            function writeTextonImage($code,$path){
                $img = imagecreatefromjpeg($path); // image.jpg is the image on which we are going to write text ,you can replace this iamge name with your
                if(!$img) die("Unable to load image");
                $color = imagecolorallocate($img, 255, 255, 255);
                $green = imagecolorallocate($img, 0, 255, 0);
                $width = 500;// it will store width of image 
                $height = 300; //it will store height of image
                $fontsize = 20; // size of font
                //$text = "Referral Code : ".$code; // Define the text
                $pos = ( $width - (imagefontwidth($fontsize)*STRLEN($code)/2) );// calculate the left position of the text:
                $font = 'C:\Windows\Fonts\ARLRDBD.TTF';
                imagettftext($img, $fontsize, 0, 120, 30, $color, $font, $code);
                //  imagestring($img, $fontsize, $pos, 15, $code, $red);
                imagejpeg($img, 'E:\wamp64\www\wordpress\wp-content\plugins\woocommerce-multilevel-commision\simpletext.jpg',100);
                imagedestroy($img);
            } 
            /* Shortcode to display Credit points info */

            /* Show the logged in users affiliate user list */
            function wmcShowMyAffiliates(){
                $wmcHtml='';
                $arrBreadCrumb=array();            
                $check_user = $this->referral_user( 'user_id', 'user_id', get_current_user_id() );                       
                if($check_user){                                
                    // print_r($referral_users);                    
                    $wmcHtml.='<div class="wmc-show-affiliates">'; 
                    $wmcHtml.='<h2>'.__('My Affiliates','wmc').'</h2>'; 
                    $wmcHtml.='<table class="shop_table shop_table_responsive">';                
                    $wmcHtml.='<tr><th align="center">'.__('Show/Hide','wmc').'</th><th align="center">'.__('Referral Code','wmc').'</th><th align="center">'.__('Name','wmc').'</th><th align="right">'.__('Affiliates','wmc').'</th><th align="center">'.__('Join Date','wmc').'</th></tr>'; 
                    $returnHtml=$this->wmcGetAffliateUsersList($check_user);
                    $wmcHtml.=$returnHtml;
                    if($returnHtml==''){
                        $wmcHtml.='<tr><td colspan="5"><p class="help">'.__('Could not find any affiliate users. Please invite more friends and colleagues to start earning credit points.','wmc').'</p></td></tr>';
                    }else{
                        $wmcHtml.='<tr><td colspan="5"><p class="help"><Strong>'.__('Affiliates : ','wmc').'</strong>'.__('This particular column shows the number of Affiliates for the corresponding affiliate member.','wmc').'</p></td></tr>';
                    }                  
                    $wmcHtml.='</table>';                    
                    $wmcHtml.='</div>';
                }
                return $wmcHtml;
            }
            function wmcGetAffliateUsersList($parentID,$arrClass=array(),$backColor='',$rHTML=''){
                $obj_referal_program = new Referal_Program();
                $referral_users = $obj_referal_program->get_referral_user_list($parentID );            
                if(is_array($referral_users) && count($referral_users)>0){                
                    foreach($referral_users as $key=>$affiliate){
                        $className='';                   
                        if($parentID!=get_current_user_id() && strpos($className,'wmc-child ')===false){
                            $className='wmc-child';
                        }
                        if(!in_array($parentID,$arrClass)){                    
                            array_push($arrClass,$parentID);
                        }
                        $opacity=(1/count($arrClass));
                        if($parentID==get_current_user_id()){
                            if($key%2!=0){
                                $backColor='230,230,230';
                            }else{
                                $backColor='178,229,255';
                                //$backColor='255,255,255';
                            }
                            $opacity=1;
                            //$arrClass=array();
                        }                   
                        $wmcFinder=implode('-',$arrClass);
                        $className.=' wmc-child-'.$wmcFinder;
                        $user_info = get_userdata($affiliate->user_id);
                        //170,213,255 18,194,227
                        $rHTML.='<tr class="'.$className.'" style="background-color:rgba('.$backColor.','.$opacity.');">';
                        if(intval($affiliate->followers)>0){
                            $rHTML.='<td align="center" class="view_hierarchie"><a href="javascript:void(0)" data-finder="'.$wmcFinder.'-'.$affiliate->user_id.'" class="view_hierarchie">'.__('View Hirarchy','wmc').'  </a></td>'; 
                        }else{
                            $rHTML.='<td align="center">-</td>';
                        }
                        $rHTML.='<td  align="center">'.$this->referral_user( 'referral_code', 'user_id', $affiliate->user_id ).'</td><td>'.$affiliate->first_name.'&nbsp'.$affiliate->last_name.'</td><td align="right">'.$affiliate->followers.'</td><td align="right">'.$user_info->data->user_registered.'</td>'; 

                        $rHTML.='</tr>';
                        if(intval($affiliate->followers)>0){
                            $rHTML.=$this->wmcGetAffliateUsersList($affiliate->user_id,$arrClass,$backColor);
                        }
                    }
                }             
                return $rHTML;
            }

            /* End */ 
            public function referral_user_credit_info(){
                if(is_user_logged_in()){
                    global $invitation_error;
                    $check_user = $this->referral_user( 'user_id', 'user_id', get_current_user_id() );
                    $wmc_html_credit='<div class="wmc-show-credits">';  
                    if( $check_user ){
                        $current_user_id = $check_user;                
                        $obj_referal_program = new Referal_Program();
                        $data = array(
                            'referral_code'=>$this->referral_user( 'referral_code', 'user_id', $current_user_id ),
                            'total_points'=>$obj_referal_program->available_credits( $current_user_id ),
                            'total_followers'=>$obj_referal_program->no_of_followers( $current_user_id ),
                            'records'=>$obj_referal_program->select_all( 0, 1, $current_user_id ),
                            //'invitation_status'    =>    (isset ($_POST['emails'])) ? '' : 'hide',
                            'emails'=>isset( $_POST['emails'] ) ? sanitize_text_field($_POST['emails']) : ''
                        );
                        $wmc_html_credit.='<h2>'.__('Referral Program Details', 'wmc' ).'</h2>
                        <table class="shop_table shop_table_responsive my_account_orders">
                        <tr>
                        <th>'.__('Your Referral Code', 'wmc').'</th>    
                        <th>'.__('Store Credits', 'wmc').'</th>    
                        <th>'.__('Total Followers', 'wmc').'</th>    
                        </tr>
                        <tr>
                        <td>'.$data['referral_code'].'</td>
                        <td>'.wc_price( $data['total_points'] ).'</td>
                        <td>'.__($data['total_followers']).'</td>
                        </tr>
                        </table>';

                        if( count($data['records']) > 0 ){                    
                            $wmc_html_credit.='<table class="shop_table shop_table_responsive my_account_orders">
                            <tr>
                            <!--th>'.__( 'Order', 'wmc' ).'</th-->
                            <th>'.__( 'Date', 'wmc' ).'</th>
                            <th>'.__( 'Note', 'wmc' ).'</th>
                            </tr>';                        
                            foreach( $data['records'] as $row ){
                                $note = '';
                                $order = new WC_Order( $row['order_id'] );                                
                                if( $row['credits'] > 0 ){
                                    $credits = wc_price( $row['credits'] );                                   
                                    if( $order->user_id == $row['user_id'] ){
                                        if( $order->get_status() == 'cancelled' || $order->get_status() == 'refunded' || $order->get_status() == 'failed' ){
                                            $note =  sprintf( __( '%s Store credit is refund for order %s.', 'wmc' ) ,$credits, '#'.$row['order_id'] );
                                        }else{
                                            $note =  sprintf( __( '%s Store credit is earned from order %s.', 'wmc' ) ,$credits, '#'.$row['order_id'] );
                                        }
                                    }else{
                                        $note = sprintf( __( '%s Store credit is earned through referral user ( %s order %s )  ', 'wmc' ) ,$credits, get_user_meta( $order->user_id, 'first_name', true) .' '. get_user_meta( $order->user_id, 'last_name', true), '#'.$row['order_id'] );    
                                    }
                                }
                                if( $row['redeems'] > 0 ){
                                    $redeems = wc_price( $row['redeems'] );
                                    if( $order->get_status() == 'cancelled' || $order->get_status() == 'refunded' || $order->get_status() == 'failed' ){
                                        $note =  sprintf( __( '%s Store credit is refund for order %s.', 'wmc' ) ,$redeems, '#'.$row['order_id'] );
                                    }else{
                                        if( $row['order_id'] ){
                                            $note = sprintf( __( '%s Store credit is used in order %s.', 'wmc' ), $redeems, '#'.$row['order_id'] ); 
                                        }else{
                                            $note = sprintf( __( '%s Store credit is expired.', 'wmc' ), $redeems ); 
                                        }
                                    }
                                }
                                $wmc_html_credit.='<tr>
                                <!--td><a htref="">#'.$row['order_id'].'</a></td-->
                                <td>'. date_i18n( 'M d, Y', strtotime( $row['date'] ) ) .'</td>
                                <td>'.$note.'</td>
                                </tr>';
                            }
                            $wmc_html_credit.='</table>';                    
                        }
                    }    
                    $wmc_html_credit.='</div>';  
                    return $wmc_html_credit;        
                }

                return;
            }            
            /**
            *	Send invation to others to join Referral Program
            *
            *	@return string status
            **/
            public function send_invitation( ){
                global $customer_id, $referral_code, $invitation_error;
                try{
                    // WP Validation
                    $validation_errors = new WP_Error();
                    $invitation_error	=	false;
                    if( isset( $_POST['action'] ) && $_POST['action'] == 'send_invitations' ){
                        unset( $_POST['action'] );
                        if( empty( $_POST['emails'] ) ){
                            throw new Exception( __('Please enter a valid E-mail address.', 'wmc') );
                        }

                        $email_array = explode(',', sanitize_text_field($_POST['emails']));
                        $customer_id = get_current_user_id();

                        WC()->mailer();

                        $current_user 	=	wp_get_current_user();
                        $email 			=	$current_user->user_email;	 
                        $first_name 	= 	$current_user->user_firstname;
                        $last_name 		= 	$current_user->user_lastname;
                        $referral_code	=	$this->referral_user( 'referral_code', 'user_id', $customer_id );

                        $invalid_arrray = array();
                        $exist_email_array=	array();
                        $success_mail	=	false;
                        foreach( $email_array as $email ){
                            // Referral user mail
                            if( $email != '' ){
                                if( filter_var($email, FILTER_VALIDATE_EMAIL) && email_exists($email) ){
                                    $exist_email_array[]	=	$email;
                                }elseif( filter_var($email, FILTER_VALIDATE_EMAIL) ){
                                    $success_mail	=	true;
                                    do_action( 'wmc_joining_user_notification', $email, $first_name, $last_name, $referral_code, 'referral_user', 	$customer_id );
                                }else{
                                    $invalid_arrray[] = $email;
                                }
                            }
                        }
                        if( count( $exist_email_array ) > 0 ){
                            $email_list = '<ul><li>'.implode('</li><li>', $exist_email_array ).'</li></ul>';
                            $messagewmc1 = __('The user is already part of our referral program, please try with different E-mail address.', 'wmc');
                            throw new Exception( $messagewmc1.$email_list );						
                        }
                        if( !$success_mail ){
                            $messagewmc2=__('E-mail address is invalid.', 'wmc');
                            throw new Exception($messagewmc2);
                        }
                        if( count($invalid_arrray) > 0 ){
                            $email_list = '<ul><li>'.implode('</li><li>', $invalid_arrray ).'</li></ul>';
                            $messagewmc3 = __('We can not send invitation to below listed E-mail addresses.', 'wmc');
                            throw new Exception( $messagewmc2.$email_list );
                        }
                        wc_add_notice( __('Your invitations are sent succesfully!', 'wmc') );
                    }	
                }catch( Exception $e ){
                    $invitation_error	=	true;
                    wc_add_notice( '<strong>' . __( 'Error', 'wmc' ) . ':</strong> ' . $e->getMessage(), 'error' );
                }
            }

            /**
            * Hander for late join Referral Program
            *
            * @return void
            **/
            public function join_referral_program(){
                try{
                    // WP Validation
                    $validation_errors = new WP_Error();
                    if( isset( $_POST['join_referral_program'] ) && isset($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'] , 'referral_program' ) ){
                        $validation_errors = $this->referral_registration_validation( null, null, $validation_errors );
                        if ( $validation_errors->get_error_code() ){
                            unset( $_POST['_wpnonce'] );
                            throw new Exception( $validation_errors->get_error_message() );
                        }
                        $this->referral_customer_save_data( get_current_user_id() );
                        wc_add_notice( __( 'Thanks for joining the referral program', 'wmc' ) );
                        unset( $_POST['_wpnonce'] );
                    }	
                }catch( Exception $e ){
                    wc_add_notice( '<strong>' . __( 'Error', 'wmc' ) . ':</strong> ' . $e->getMessage(), 'error' );
                }
            }
            /**
            * Validate the extra register fields.
            *
            * @param  string $username          Current username.
            * @param  string $email             Current email.
            * @param  object $validation_errors WP_Error object.
            *
            * @return void
            */
            public function referral_registration_validation( $username, $email, $validation_errors ){            
                $autoJoin=get_option('wmc_auto_register','no');
                if ( isset($_POST['billing_first_name']) && $_POST['billing_first_name'] == '' ) {
                    $validation_errors->add( 'empty required fields', __( 'Please enter the First name.', 'wmc' ) );
                }
                if ( isset($_POST['billing_last_name']) && $_POST['billing_last_name'] == '' ) {
                    $validation_errors->add( 'empty required fields', __( 'Please enter the Last name.', 'wmc' ) );
                }			
                if( isset( $_POST['referral_code'] ) && $_POST['referral_code'] == ''
                    && isset($_POST['join_referral_program']) && $_POST['join_referral_program'] == 1 ){
                    if($autoJoin!='yes'){   
                        $validation_errors->add( 'empty required fields', __( 'You must have to add referral code to join referral program.', 'wmc' ) );
                    }
                }
                if(isset($_POST['email']) && !is_email($_POST['email'])){
                    $validation_errors->add( 'invalid fields', __( 'E-mail address is invalid', 'wmc' ) );
                }
                if( isset( $_POST['referral_code'] ) && $_POST['referral_code'] != ''
                    && isset($_POST['join_referral_program']) && $_POST['join_referral_program'] == 1 ){
                    $parent_id = $this->referral_user( 'user_id', 'referral_code', sanitize_text_field($_POST['referral_code']) );

                    if( !$parent_id ){
                        $validation_errors->add( 'empty required fields', __( 'There is no such referral code exist<strong>('. sanitize_text_field($_POST['referral_code']) .')</strong> exist.', 'wmc' ) );
                        $_POST['wrong_referral_code']='yes';
                    }
                }
                if ( isset($_POST['join_referral_program']) && $_POST['join_referral_program'] == 2
                    && isset($_POST['referral_email']) && $_POST['referral_email'] == '' ) {
                    //$validation_errors->add( 'empty required fields', __( 'You have to add referral email to join referral program.', 'wmc' ) );
                }
                //if ( isset($_POST['join_referral_program']) && $_POST['referral_email'] != '' ) {
                if ( isset($_POST['join_referral_program']) && isset($_POST['referral_email'])
                    && $_POST['join_referral_program'] == 2 && $_POST['referral_email'] != '' ) {
                    if( email_exists($_POST['referral_email']) ){
                        $validation_errors->add( 'invalid fields', __( 'This referral E-mail <strong>('. sanitize_text_field($_POST['referral_email']) .')</strong> is already exist.', 'wmc' ) );
                    }
                }
                if ( isset($_POST['join_referral_program']) && $_POST['join_referral_program'] != 3){            
                    if ( !isset($_POST['termsandconditions']) || $_POST['termsandconditions'] != 1) {			
                        $validation_errors->add('Error', __( 'Please accept referral Program terms and conditions', 'wmc' ) );				
                    }
                }
                return $validation_errors;	
            }

            /**
            * Save the extra register fields.
            *
            * @param  int  $customer_id Current customer ID.
            *
            * @return void
            */
            public function referral_customer_save_data( $user_id ){
                global $customer_id, $referral_code;			
                $customer_id = $user_id;
                $parent_id = 0;
                $first_name = '';
                $last_name = '';
                $email = '';

                if ( isset( $_POST['billing_first_name'] ) ) {
                    // WordPress default first name field.
                    update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

                    // WooCommerce billing first name.
                    update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

                    $first_name = $_POST['billing_first_name'];
                }

                if ( isset( $_POST['billing_last_name'] ) ) {
                    // WordPress default last name field.
                    update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
                    // WooCommerce billing last name.
                    update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

                    $last_name = sanitize_text_field($_POST['billing_last_name']);
                }
                $autoJoin=get_option('wmc_auto_register','no');
                if( isset( $_POST['referral_code'] ) && $_POST['referral_code'] != '' ){
                    $parent_id = $this->referral_user( 'user_id', 'referral_code', sanitize_text_field($_POST['referral_code']) );                                
                }else if($autoJoin=='yes'){
                    $_POST['join_referral_program']=2;                
                }
                if ( isset($_POST['termsandconditions']) && $_POST['termsandconditions'] == 1) {				
                    update_user_meta( $customer_id, 'termsandconditions', sanitize_text_field($_POST['termsandconditions']) );
                }
                if( isset( $_POST['join_referral_program'] ) && $_POST['join_referral_program'] < 3 ){				
                    $referral_code = $this->referral_code( $customer_id );		
                    $creditFor=get_option('wmc_welcome_credit_for','new');
                    $benefit=0;
                    if(isset( $_POST['action'] ) && $_POST['action'] == 'join_referreal_program'){
                        if($creditFor=='new'){
                            $benefit=1;
                        }
                    }                
                    if(!$this->referral_user('id', 'user_id', $customer_id) ){
                        $this->insert(
                            array(
                                'user_id'	=>	$customer_id,
                                'referral_parent'=>	$parent_id ? $parent_id : 0,
                                'active'	=>	1,
                                'referral_code'	=> $referral_code,
                                'referral_email'	=>	isset($_POST['referral_email'])?sanitize_text_field($_POST['referral_email']):'',
                                'referal_benefits'	=>	$benefit
                            )
                        );
                    }


                    if( get_current_user_id() ){
                        $current_user 	=	wp_get_current_user();
                        $email 			=	$current_user->user_email;	 
                        $first_name 	= 	$current_user->user_firstname;
                        $last_name 		= 	$current_user->user_lastname;
                    }else{
                        $email = isset($_POST['email'])?sanitize_email($_POST['email']):'';
                    }

                    WC()->mailer();
                    //	Joining mail for new registered user
                    do_action( 'wmc_joining_user_notification', $email, $first_name, $last_name, $referral_code, 'joining_mail', $customer_id );
                    // Referral user mail
                    if( isset( $_POST['referral_email'] ) && $_POST['referral_email'] != ''){
                        do_action( 'wmc_joining_user_notification', sanitize_text_field($_POST['referral_email']), $first_name, $last_name, $referral_code, 'referral_user', 	$customer_id );
                    }
                    //break;
                }

            }

            /**
            * Generate referral code
            *
            * @param int $customer_id Current customer ID.
            *
            * @return Unique Referral Code
            */
            public function referral_code( $customer_id ){
                global $wpdb;

                $temp_cid = md5('R'.$customer_id);
                $referral_code = substr( $temp_cid, 0, 5 );

                $exist_referral_code = $wpdb->get_var( 'SELECT id FROM '.$this->table_name.' WHERE referral_code = "'.$referral_code.'"' );

                if( $exist_referral_code ){
                    $this->referral_code( $referral_code );
                }

                return $referral_code;
            }


            /*
            *	Get number of referral users
            */
            public function record_count() {
                global $wpdb;

                $sql = "SELECT count(*)  FROM ".$this->table_name. " WHERE active = 1";	

                return $wpdb->get_var( $sql );
            }



            public function add_my_account_menu($items)
            {
                $key = array_search('dashboard', array_keys($items));

                if($key !== false)
                {
                    $items = (array_merge(array_splice($items, 0, $key + 1), array('referral' => __('Referral','wmc')), $items));
                }
                else
                {
                    $items['referral'] = __('Referral','wmc');
                }
                return $items;
            }

            public function add_referral_query_var($vars)
            {
                $vars[] = 'referral';
                return $vars;
            }

            public function woocommerce_account_referral_endpoint_hook()
            {
                $this->referral_user_account_panel();
            }

            public function init_hook()
            {
                add_rewrite_endpoint( 'referral', EP_ROOT | EP_PAGES );
                flush_rewrite_rules();

                global $woocommerce;

                if( version_compare( $woocommerce->version, '2.6.0', ">=" ) ) {
                    /* Hooks for myaccount referral endpoint */
                    add_filter( 'woocommerce_account_menu_items', array($this, 'add_my_account_menu'));
                    add_filter( 'query_vars', array($this, 'add_referral_query_var'));
                    add_action( 'woocommerce_account_referral_endpoint', array($this, 'woocommerce_account_referral_endpoint_hook') );
                }
                else
                {
                    add_action( 'woocommerce_before_my_account', array($this, 'referral_user_account_panel' ));
                }
                add_filter( 'woocommerce_checkout_fields' , array($this,'wmc_override_checkout_fields') );
                add_action('woocommerce_checkout_process', array($this,'wmc_custom_checkout_field_process'));
                //add_action('woocommerce_checkout_update_user_meta', array($this,'wmc_custom_checkout_save_data'), 10, 2);                
            }

        } // end Referal_Users

}
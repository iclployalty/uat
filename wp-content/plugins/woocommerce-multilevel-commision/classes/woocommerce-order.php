<?php

if ( ! class_exists( 'WooCommerce_Referral_Order' ) ) {

	/**
	 * Main wocommerce order class handler
	 *
	 */
	class WooCommerce_Referral_Order extends WooCommerce_Multilevel_Referal  {
		
		public function __construct(){
			global $obj_referal_program, $obj_referal_users;			
			$obj_referal_program 	= new Referal_Program();
			$obj_referal_users		= new Referal_Users(); 			
			//add_filter( 'the_content',	array( $this, 'add_store_credits' 	  ) );
			add_action( 'init',									array( $this, 'init' ) );	// Handle post events
			add_action( 'woocommerce_before_cart', 				array( $this, 'store_credits_notice' ) );	// Display available store credits on cart page.
			add_action( 'woocommerce_before_checkout_form',		array( $this, 'store_credits_notice' ) );	// Display available store credits on checkout page.			
			//add_action( 'woocommerce_cart_totals_before_order_total',	array( $this, 'store_credit_info' ) ); // Display used store credits on cart total section.
			//add_action( 'woocommerce_review_order_before_order_total', 	array( $this, 'store_credit_info' ) ); // Display used store credits on checkout total section.
			add_action( 'woocommerce_cart_calculate_fees', 		array( $this, 'store_credit_info') );	//	Display used store credits on cart/checkout total section.			
			add_action( 'woocommerce_checkout_order_processed',	array( $this, 'save_store_credits') );	// Save credits on order
			add_action( 'woocommerce_order_status_completed',	array( $this, 'add_store_credits') );	// Add credits on order
			add_action( 'woocommerce_order_status_cancelled', 	array( $this, 'remove_store_credits' ) );	// Remove previous added credits on order cancellation
			add_action( 'woocommerce_order_status_refunded', 	array( $this, 'remove_store_credits' ) );	// Remove previous added credits on order cancellation
			add_action( 'woocommerce_order_status_failed', 		array( $this, 'remove_store_credits' ) );	// Remove previous added credits on order cancellation
			
			add_filter( 'woocommerce_cart_totals_fee_html',		array( $this, 'remove_link_for_credits' ), 10, 2 ); // Add remove link for Store credit to cart/checkout page.
			
		}
		
		/**
		 *	Handle post events
		 *
		 *	@return void
		 **/
		public function init(){
			$current_user_id = get_current_user_id();
			if( !$current_user_id ){
				return;
			}
			
			try{
				// WP Validation
				$validation_errors = new WP_Error();
				if( isset( $_GET['remove_store_credit'] ) ){
					WC()->session->set( 'store_credit', 0 );
				}
				if( isset( $_POST['action'] ) && $_POST['action'] == 'apply_store_credit' &&  wp_verify_nonce( $_POST['_nonce'], 'apply_store_credit' ) ){
					$user_store_credit		=	round(get_user_meta( $current_user_id, 'wmc_store_credit', true ), 2 );
					$max_store_credit = round( WC()->session->get( 'max_store_credit' ), 2 );
					$appied_credit_amount	=	round(sanitize_text_field($_POST['appied_credit_amount']), 2 );
					
					if( $appied_credit_amount == 0 || ( $appied_credit_amount != $user_store_credit && $appied_credit_amount > $user_store_credit ) || ( $max_store_credit != $appied_credit_amount && $max_store_credit < $appied_credit_amount ) ){
						WC()->session->set( 'store_credit', 0 );
						throw new Exception( __("Please make sure that amount should be equal or less than the maximum limit.", 'wmc' ) );
					}
					
					WC()->session->set( 'store_credit', sanitize_text_field($_POST['appied_credit_amount']) );
					wc_add_notice( __('Store credits successfully applied','wmc') );
				}
			}catch( Exception $e ){
				wc_add_notice( '<strong>' . __( 'Error', 'wmc' ) . ':</strong> ' . $e->getMessage(), 'error' );
			}
		}
		
		/**
		 *	Save credits on order processing
		 *
		 *	@param int Order Id
		 *
		 *	@return void
		 */
		public function save_store_credits( $order_id ){
			global $obj_referal_program, $obj_referal_users;
			
			$order = new WC_Order( $order_id );
		
				// check for guest user
				if ( ! $order->user_id )
					return;
				
			if( WC()->session->get( 'store_credit' ) ){
					$used_store_credit		=	WC()->session->get( 'store_credit' );
					$user_credits		=	get_user_meta( $order->user_id, 'wmc_store_credit', true );
					
					$obj_referal_program->insert(
						array(
								'order_id'	=>	$order_id,
								'user_id'	=>	$order->user_id,
								'redeems'	=>	$used_store_credit,
							  )	
					);
					$user_credits = $user_credits - $used_store_credit;
					
					update_user_meta( $order->user_id, 'wmc_store_credit', $user_credits );
					update_post_meta( $order_id, '_store_credit', $used_store_credit );
					
					WC()->session->set( 'store_credit', 0 );
					WC()->session->set( 'exclude_product_name', '' );
			}
		}
		
		/*
		 *	Add earn credits to user account.
		 *
		 *	@param int Order Id
		 *
		 *	@return void
		 */
		public function add_store_credits( $order_id ){
			global $obj_referal_program, $obj_referal_users;
			//$order_id = 152;
			try{
				// WP Validation
				$validation_errors = new WP_Error();
				$order = new WC_Order( $order_id );
		
				// check for guest user
				if ( ! $order->user_id )
					return;
						
				$objReferalUsers = new Referal_Users();
				if( !$objReferalUsers->referral_user( 'id', 'user_id', $order->user_id ) ){
					return;
				}			
				//$cart_sub_total		=	$order->get_subtotal();
				$cart_sub_total		=	0;
				$total_earn_credits = 	0;                                   
                            
                //$wmc_store_credit   = floatval(get_option('wmc_store_credit'));                                        
				$user_credits		= floatval(get_user_meta( $order->user_id, 'wmc_store_credit', true ));
				$used_store_credit	= floatval(get_post_meta( $order_id, '_store_credit', true ));
				$exclude_products_from_credit = get_option( 'wmc_exclude_products' , array() );
				
				if( !is_array( $exclude_products_from_credit ) ){
					$exclude_products_from_credit = explode( ',', $exclude_products_from_credit );
				}	
				/*if( $used_store_credit ){
					$obj_referal_program->insert(
						array(
								'order_id'	=>	$order_id,
								'user_id'	=>	$order->user_id,
								'redeems'	=>	$used_store_credit,
							  )	
					);
					$user_credits = $user_credits - $used_store_credit;
					
					update_user_meta( $order->user_id, 'wmc_store_credit', $user_credits );
				}*/                
                $total_earn_credits=0; 
                $discount=floatval($order->get_total_discount());               
                $orderTotal= floatval($order->get_subtotal());
                $first_purchase = $obj_referal_users->referral_user( 'referal_benefits', 'user_id', $order->user_id );
                $max_month_earn_limit = get_option( 'wmc_max_credit_limit' , 0 );
                $arrProductCredits=array();
				foreach( $order->get_items() as $item ){
                    $product_price=isset($item['line_subtotal'])?floatval($item['line_subtotal']):0;
					if( !in_array( $item['product_id'], $exclude_products_from_credit ) ){
						$cart_sub_total += $product_price;
					}
                    $wmc_product_credit=$this->fnGetProductFinalCreditPercentage($item['product_id']);                   
                    $rate=($product_price*100)/$orderTotal;
                    $product_discount = ($rate*$discount)/100;               
                    $product_used_credit = ($rate*$used_store_credit)/100;
                    $actual_price= $product_price - ($product_discount + $product_used_credit); 
                    $product_credit=round(($actual_price*$wmc_product_credit)/100,4);             
                    $total_earn_credits+=$product_credit;
                    if(!$first_purchase){
                        $product_credit=round(($product_credit*$wmc_product_credit)/100,4);
                    }
                    array_push($arrProductCredits,array('credit_points'=>$product_credit,'rate'=>$wmc_product_credit));
				}
				
				if( $cart_sub_total ){
					//$basic_amount	=	$cart_sub_total - $discount - $used_store_credit;
					//$total_earn_credits =  round( ( $basic_amount * $wmc_store_credit ) / 100, 4 );
					//$first_purchase	= $obj_referal_users->referral_user( 'referal_benefits', 'user_id', $order->user_id );
					if( !$first_purchase ){
						$obj_referal_program->insert(
							array(
									'order_id'	=>	$order_id,
									'user_id'	=>	$order->user_id,
									'credits'	=>	$total_earn_credits,
								  )	
						);
						$obj_referal_users->updateAll(array('referal_benefits'	=>	1),$order->user_id);
						update_user_meta( $order->user_id, 'wmc_store_credit', $user_credits + $total_earn_credits );
						if( !is_admin() ){
							wc_add_notice( sprintf( __( 'You have earned %s store points.', 'wmc' ) , $total_earn_credits ) );
						}
						//$total_earn_credits =  round( ( $total_earn_credits * $wmc_store_credit ) / 100, 4 );
					}
					//$max_month_earn_limit = get_option( 'wmc_max_credit_limit' , 0 );
					
                    //$this->add_credits_to_parent( $order_id, $order->user_id, $total_earn_credits, $wmc_store_credit, $max_month_earn_limit );
                    $this->add_credits_to_parent_new($order_id,$order->user_id, $arrProductCredits,$max_month_earn_limit);
					
				}
				
			}catch( Exception $e ){
				wc_add_notice( '<strong>' . __( 'Error', 'wmc' ) . ':</strong> ' . $e->getMessage(), 'error' );
			}
		}
        /* Get product credit percentage */
        function fnGetProductFinalCreditPercentage($productId){
            $wmc_store_credit   = floatval(get_option('wmc_store_credit',0));
            $product_credit = floatval(get_post_meta( $productId, 'wmc_credits', true ));
            if($product_credit=='' || $product_credit==0){
               $cat_credit=$this->fnGetCategoryCreditPercentage($productId);
               if($cat_credit!=0){
                    $product_credit=$cat_credit; 
               }else{ 
                    $product_credit=$wmc_store_credit;                        
               }
            } 
            return $product_credit; 
        }
        /* end product credit percentage */
        /* Get category credit percentage */
		function fnGetCategoryCreditPercentage($productId){
            $product_terms = wp_get_post_terms( $productId,'product_cat',array('fields'=>'ids') );              
            $creditPercentage=0;
            if(is_array($product_terms) && count($product_terms)>0){
                $arrCredit=array();
                foreach($product_terms as $term){
                    $term_meta=get_option( "product_cat_$term" );
                    $tR=floatval($term_meta['wmc_cat_credit']);
                    if($tR>0){
                        array_push($arrCredit,floatval($term_meta['wmc_cat_credit']));
                    }
                }
                $pref=get_option('wmc_cat_pref','lowest');       
                if(is_array($arrCredit) && count($arrCredit)>0){
                    $creditPercentage=($pref=='lowest')?min($arrCredit):max($arrCredit);
                }                  
            }
            return $creditPercentage;   
        }
        /* end category credit percentage */
		/*
		 *	Deduct earn points from user account.
		 *
		 *	@param int Order Id
		 *
		 *	@return void
		 */
		public function remove_store_credits( $order_id ){
			global $obj_referal_program;
			
			$used_store_credit	=	get_post_meta( $order_id, '_store_credit', true );
				
			if( $used_store_credit ){
				$order 			=	new WC_Order( $order_id );
				$user_credits	=	get_user_meta( $order->user_id, 'wmc_store_credit', true );
				
				$obj_referal_program->insert(
					array(
							'order_id'	=>	$order_id,
							'user_id'	=>	$order->user_id,
							'credits'	=>	$used_store_credit,
						  )	
				);
				$user_credits	=	$user_credits + $used_store_credit;
				
				update_user_meta( $order->user_id, 'wmc_store_credit', $user_credits );
				delete_post_meta( $order_id, '_store_credit' );
			}else{
				$this->remove_credits_from_parent( $order_id );
			}
		}
		
		/**
		 *
		 *	Remove commesion to referral parent
		 *
		 *	@param int $order_id
		 *
		 *  @return void
		 **/
		public function remove_credits_from_parent( $order_id ){
			global $obj_referal_program;
			
			$user_credit_list = $obj_referal_program->get_credits_by_order( $order_id );
			
			if( count( $user_credit_list ) > 0 ){
				foreach( $user_credit_list as $user_credit ){
					$userId = $user_credit['user_id'];
					$userCredits = $user_credit['credits'];
					
					$user_store_credits	=	get_user_meta( $userId, 'wmc_store_credit', true );
					
					$obj_referal_program->insert(
					array(
							'order_id'	=>	$order_id,
							'user_id'	=>	$userId,
							'redeems'	=>	$userCredits,
						  )
					);
					
					$user_store_credits	=	$user_store_credits - $userCredits;
					
					update_user_meta( $userId, 'wmc_store_credit', $user_store_credits );
					
				}
			}
		}
		
		
		/**
		 * Add commesion to referral parent
		 *
		 * @param int $order_id Current Order ID
		 * @param int $user_id Child user ID
		 * @param float $earn_credits Earn credits by referral
		 * $param int $wmc_store_credit Percentage of earning credits
		 * @param int $max_month_earn_limit Limit of monthly earning
		 * 
		 * @return void
		 **/
         public function add_credits_to_parent_new($order_id, $user_id, $arrProductCredits, $max_month_earn_limit){
             global $obj_referal_program, $obj_referal_users;
             $parent_user= $obj_referal_users->referral_user( 'referral_parent', 'user_id', $user_id );
             $total_new_credits=0;
             $current_credits=0;
             $arrPCredits=array();
             foreach($arrProductCredits as $pCredit){
                 $current_credits+=floatval($pCredit['credit_points']);
                 $product_new_credits = round((floatval($pCredit['credit_points'])*floatval($pCredit['rate']))/ 100, 4);
                 array_push($arrPCredits,array('credit_points'=>$product_new_credits,'rate'=>$pCredit['rate']));
             }                          
             if( $parent_user && $current_credits!=$product_new_credits){
                $current_month_earning=$obj_referal_program->get_current_month_earning( $user_id );
                if( $max_month_earn_limit == 0 || $max_month_earn_limit > $current_month_earning ){
                    try{
                        $user_credits        =    floatval(get_user_meta( $parent_user, 'wmc_store_credit', true ));
                        $obj_referal_program->insert(
                            array(
                                'order_id'   => $order_id,
                                'user_id'    => $parent_user,
                                'credits'    => $current_credits
                            )    
                        );
                        update_user_meta( $parent_user, 'wmc_store_credit', $user_credits + $current_credits );    
                    }catch( Exception $e){
                        wc_add_notice( '<strong>' . __( 'Error', 'wmc' ) . ':</strong> ' . $e->getMessage(), 'error' );
                    }
                }
                $this->add_credits_to_parent_new( $order_id, $parent_user, $arrPCredits, $max_month_earn_limit ); 
             }                        
         }
		public function add_credits_to_parent( $order_id, $user_id, $earn_credits, $wmc_store_credit, $max_month_earn_limit ){
			global $obj_referal_users, $obj_referal_program;			
			$parent_user	=	$obj_referal_users->referral_user( 'referral_parent', 'user_id', $user_id );
			$new_earn_credits = round( ($earn_credits * $wmc_store_credit)/ 100, 4);			
			// Check parent user is exist or not.
			// Add earning while reached to max earning limit
			if( $parent_user && $earn_credits != $new_earn_credits ){
				$current_month_earning	=	$obj_referal_program->get_current_month_earning( $user_id );
				// Check monthly limit is reached or not.
				if( $max_month_earn_limit == 0 || $max_month_earn_limit > $current_month_earning ){
					try{
						$user_credits		=	get_user_meta( $parent_user, 'wmc_store_credit', true );
						$obj_referal_program->insert(
							array(
								'order_id'	=>	$order_id,
								'user_id'	=>	$parent_user,
								'credits'	=>	$earn_credits,
							)	
						);
						update_user_meta( $parent_user, 'wmc_store_credit', $user_credits + $earn_credits );	
					}catch( Exception $e){
						wc_add_notice( '<strong>' . __( 'Error', 'wmc' ) . ':</strong> ' . $e->getMessage(), 'error' );
					}
				}
				$this->add_credits_to_parent( $order_id, $parent_user, $new_earn_credits, $wmc_store_credit, $max_month_earn_limit );
			}
		}
		
		/**
		 *	Display notice when current user has earn points for withdrawl
		 *
		 *	@return void
		 **/
		public function store_credits_notice(){
			if( !WC()->session->get( 'store_credit' ) ){
				$wmc_store_credit	=	get_user_meta( get_current_user_id() , 'wmc_store_credit', true );
				if( $wmc_store_credit ){
					$max_use_credit = $this->get_store_credit();
					$notice = '';
					if( WC()->session->get( 'exclude_product_name' ) ){
						$notice = '<br />'.sprintf( __('You can not use Store credit in following products: %s', 'wmc'), WC()->session->get( 'exclude_product_name' ) );
					}
					echo self::render_template( 'front/store-credits-notice.php',
											   array('data' =>
													array(
														  'store_credit'	=>	wc_price( $wmc_store_credit),
														  'nonce' 			=>	wp_create_nonce('apply_store_credit'),
														  'max_use_credit'	=>	$max_use_credit,
														  'appied_credit_amount'	=>	isset( $_POST['appied_credit_amount'] ) ? sanitize_text_field($_POST['appied_credit_amount']) : $max_use_credit,
														  'notice'			=>	$notice
														  )
													)
											   );
				}
			}
		}
		
		
		/**
		 *	Add Store Credit to cart page.
		 **/
		public function store_credit_info(){
			
			if( WC()->session->get( 'store_credit' ) ){
				
				$applied_store_credit =	$this->get_store_credit();
				if($applied_store_credit > 0){
					WC()->cart->add_fee( __('Store Credit', 'wmc'), -1 * $applied_store_credit );
				}
			}
		}
		
		/**
		 *	Deduct store credits from cart total
		 *
		 *	@param $price Total order price
		 *
		 *	@return float New total price
		 **/
		/*function add_store_credit_to_cart( $price ){
			if( WC()->session->get( 'store_credit' ) ){
				$applied_store_credit =	$this->get_store_credit();
				if($applied_store_credit > 0){
					return wc_price( WC()->cart->total - $applied_store_credit );
				}
			}
			return $price;
		}*/
		
		/**
		 *	Get current user store credit
		 *
		 *	@param float $appiled_credit To check credits on cart.
		 *	
		 *	@return float Return credits
		 **/
		public function get_store_credit(){
			//global $wocommerce;
			$current_user_id = get_current_user_id();
			if( !$current_user_id ){
				return;
			}
			$objReferalUsers = new Referal_Users();
			if( !$objReferalUsers->referral_user( 'id', 'user_id', $current_user_id ) ){
				return;
			}
			$wmc_store_credit		=	0;
			$max_store_credit		=	0;
			$cart_total				=	0;
			$applied_store_credit 	=	WC()->session->get( 'store_credit' );
			//$cart_total				=	WC()->cart->subtotal;
			$cart_discount_total	=	WC()->cart->get_cart_discount_total();
			$exclude_products_from_credit = get_option( 'wmc_exclude_products' , array() );
			$exclude_product_list	=	'';
			$seperator				=	'';
			
			if( !is_array( $exclude_products_from_credit ) ){
				$exclude_products_from_credit = explode( ',', $exclude_products_from_credit );
			}
			foreach ( WC()->cart->get_cart() as $item ) {
				if( !in_array( $item['product_id'], $exclude_products_from_credit ) ){
					$cart_total += ( isset( $item['line_subtotal'] ) ) ? $item['line_subtotal'] : 0;
				}else{
					$exclude_product_list .= $seperator . get_the_title($item['product_id']);
					$seperator	=	', ';
				}
			}
			if( $exclude_product_list ){
				WC()->session->set( 'exclude_product_name', $exclude_product_list );
			}else{
				WC()->session->set( 'exclude_product_name', '' );
			}
			if( $cart_total == $cart_discount_total ){
				if( WC()->session->get( 'store_credit') ){
					WC()->session->set( 'store_credit', 0 );
					wc_add_notice( __( 'Store credits is removed becuase of cart total is same as discount.', 'wmc' ) , 'notice' );
				}
				return 0;
			}
			if( $cart_discount_total ){
				$cart_total -= $cart_discount_total;
			}
			
			if( $applied_store_credit ){
				$wmc_store_credit	=	$applied_store_credit;
			}else{
				$wmc_store_credit	=	get_user_meta( $current_user_id, 'wmc_store_credit', true );
			}
			$max_store_credit = round( ( $cart_total * get_option('wmc_max_redumption' , 0) ) / 100, 2 );
			
			if( $wmc_store_credit > 0 && $cart_total > 0 && $max_store_credit > 0){
				$store_credit = $cart_total > $wmc_store_credit ? $wmc_store_credit : $cart_total;
				$max_store_credit	=	$max_store_credit < $store_credit ? $max_store_credit : $store_credit;
				//$max_store_credit = 
				if( WC()->session->get( 'store_credit') ){ 
					WC()->session->set( 'store_credit', $max_store_credit );
				}
				WC()->session->set( 'max_store_credit', $max_store_credit );
				return $max_store_credit;
			}
			return 0;
		}
		
		/**
		 *	Add remove link for store credit to cart/checkout page.
		 *
		 *	@param string $cart_totals_fee_html HTMl of store credit
		 *	@param int/double $fee Appplied store credit
		 *
		 *	@return string Modified HTML with remove link
		 *	
		 **/
		public function remove_link_for_credits( $cart_totals_fee_html, $fee ){
			
			$link = '<a href="'. add_query_arg( 'remove_store_credit', true, get_the_permalink() ) .'">['. __( 'Remove', 'wmc' ) .']</a>';
			return $cart_totals_fee_html.$link;
		
		}

	} // end WooCommerce_Referral_Order
	
	new WooCommerce_Referral_Order();
}

<?php
require_once('../../../wp-load.php');
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if( !class_exists('wmc_cron') ){	
	class wmc_cron{		
		public function __construct(){		
			$this->check_referral_users();
		}		
		public function check_referral_users(){		
			$credit_validity_period = 	get_option( 'wmc_credit_validity_period' , 0 );
			$credit_validity_number	=	get_option( 'wmc_credit_validity_number', 0 );
			$notification_mail_time	=	get_option( 'wmc_notification_mail_time', 0 );			
			if( $credit_validity_number && $credit_validity_period && $notification_mail_time ){
				$interval_days = 30;
				if( $credit_validity_period == 'year' ){
					$interval_days = 365;
				}
				$interval_days = $interval_days * $credit_validity_number;
				if( ($interval_days - $notification_mail_time)  > 0){
					$this->send_reminder( ($interval_days - $notification_mail_time), $credit_validity_number, $credit_validity_period );	
				}
				$this->redeem_credits( $interval_days );
			}
		}
		
		public function send_reminder( $interval_days, $credit_validity_number, $credit_validity_period ){
			global $wpdb;
			$query = 'SELECT user_id, SUM(credits) AS credit,
						(SELECT sum(credits) FROM '.$wpdb->prefix.'referal_program WHERE date >= CURDATE() - INTERVAL '.$interval_days.' DAY AND user_id = a.user_id GROUP BY user_id) AS available_credits,
						(SELECT sum(redeems) FROM '.$wpdb->prefix.'referal_program WHERE date > CURDATE() - INTERVAL '.$interval_days.' DAY AND user_id = a.user_id GROUP BY user_id) AS redeem,
						DATE_FORMAT(date, "%Y-%m-%d") AS date FROM `'.$wpdb->prefix.'referal_program` as a WHERE DATE_FORMAT(date, "%Y-%m-%d") = CURDATE() - INTERVAL '.$interval_days.' DAY GROUP BY user_id';
			//	die();
			$results = $wpdb->get_results( $query, ARRAY_A );
			WC()->mailer();			
			$today_date = date('Y-m-d');
			$time = strtotime($today_date);
			$time_interval = strtotime('+'.$interval_days.'days', $time);
			$expire_date = date("d/m/Y", $time_interval);
			$expire_month = date('M Y', $time_interval );
			
			foreach( $results as $user ){
				if( $user['credit'] > $user['redeem'] ){
					$author_obj = get_userdata( $user['user_id'] );
					do_action( 'wmc_user_reminder',
							  $author_obj->user_email,
							  $author_obj->first_name,
							  $author_obj->last_name,
							  wc_price( $user['available_credits']),
							  $expire_date,
							  $credit_validity_number.' '.$credit_validity_period,
							  $today_date,
							  $expire_month,
							  wc_price( $user['credit'] - $user['redeem'] ) );
				}
			}
		}
		public function redeem_credits( $interval_days ){			
			global $wpdb;		
			$query = 'SELECT user_id, SUM(credits) AS credit, (SELECT sum(redeems) FROM '.$wpdb->prefix.'referal_program WHERE date > CURDATE() - INTERVAL '.$interval_days.' DAY AND user_id = a.user_id GROUP BY user_id) AS redeem, DATE_FORMAT(date, "%Y-%m-%d") AS date FROM `'.$wpdb->prefix.'referal_program` as a WHERE DATE_FORMAT(date, "%Y-%m-%d") = CURDATE() - INTERVAL '.$interval_days.' DAY GROUP BY user_id';
			$results = $wpdb->get_results( $query, ARRAY_A );
			$obj_referal_program = new Referal_Program();
			foreach( $results as $user ){
				if( $user['credit'] > $user['redeem'] ){
					$obj_referal_program->insert(
													array(
														'order_id'	=>	0,
														'user_id'	=>	$user['user_id'],
														'redeems'	=>	$user['credit'] - $user['redeem'],
													)
												 );
				}
			}
		}
	}
	new wmc_cron();
}
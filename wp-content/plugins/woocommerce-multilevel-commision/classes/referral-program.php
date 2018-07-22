<?php

if ( ! class_exists( 'Referal_Program' ) ) {

	/**
	 * Main / front controller class
	 *
	 */
	class Referal_Program {

		public $table_name;
		
		public function __construct(){
			global $wpdb;
			$this->table_name = $wpdb->prefix . 'referal_program'; 
		}
		
		/*
		 * Static methods
		 */
		public function create_table(){
			global $wpdb;
			
			$checkSQL = "show tables like '".$this->table_name."'";
		
		
		  //if($wpdb->get_var($checkSQL) != $this->table_name)
		  {
			  $sql = "CREATE TABLE " . $this->table_name . " (
				id int(11) NOT NULL AUTO_INCREMENT,
				order_id  int(11),
				user_id  int(11),
				credits  decimal(10,4) DEFAULT 0.0000,
				redeems  decimal(10,4) DEFAULT 0.0000,
				date  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (id)
			  );";
		  
			  // we do not execute sql directly
			  // we are calling dbDelta which cant migrate database
			  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			  dbDelta($sql);
		  }	
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
				array(
					'order_id'	=>	$data['order_id'],
					'user_id'	=>	$data['user_id'],
					'credits'	=>	isset($data['credits']) ? $data['credits'] : 0,
					'redeems'	=>	isset($data['redeems']) ? $data['redeems'] : 0,
				)
			);
		}
		
		
		public function update( $data, $user_id ){
			global $wpdb;
			
			$wpdb->update(
				$this->table_name,
				$data,
				array(
					'user_id'	=>	$user_id
				)
			);
		}
		
		
		public static function delete($order_id){
			global $wpdb;
			$wpdb->delete(
				$this->table_name,
				array(
					'order_id'	=>	$order_id
				)
			);
		}
		
		/*
		 *	Get credit for specific order
		 */
		public function get_credits_by_order( $order_id ){
			global $wpdb;
			
			
			$sql = "SELECT user_id, credits FROM ".$this->table_name." WHERE credits > 0 AND order_id = $order_id";
		  
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		  
			return $result;
			
		}
		
		/*
		 *	Get earn credit list base on order.	
		 */
		public function get_credits( $per_page = 5, $page_number = 1 ) {
		
			global $wpdb;
		  
			$sql = "SELECT min(id), user_id, order_id, sum(credits) as credits FROM ".$this->table_name." WHERE credits > 0 GROUP BY order_id ";
		  
			if ( ! empty( $_REQUEST['orderby'] ) ) {
			  $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			  $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}
		  
			$sql .= " LIMIT $per_page";
		  
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		  
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		  
			return $result;
		}
		
		/*
		 *	Get earn redeem list base on order.	
		 */
		public function get_redeems( $per_page = 5, $page_number = 1 ) {
		
			global $wpdb;
		  
			$sql = "SELECT user_id, order_id, redeems FROM ".$this->table_name." WHERE redeems > 0 GROUP BY order_id ";
		  
			if ( ! empty( $_REQUEST['orderby'] ) ) {
			  $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			  $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}
		  
			$sql .= " LIMIT $per_page";
		  
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		  
		  
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		  
			return $result;
		}

		
	
		/*
		*	Get number of orders
		*/
	   public function record_count($type = 'credits', $all_record = false) {
		   global $wpdb;
		 
			if( $all_record ){
				$sql = "SELECT count(*) FROM ".$this->table_name;	
			}else{
				$sql = "SELECT COUNT(*) FROM (SELECT count(*) FROM ".$this->table_name." WHERE $type > 0 GROUP BY order_id) AS total ";	
			}
			
		   return $wpdb->get_var( $sql );
		}
		
		
		/*
		*	Get total of earning credits
		*/
	   public function total_statistic($type) {
		   global $wpdb;
		 
			$sql = "SELECT SUM($type)  FROM ".$this->table_name;	
			
		   return $this->make_nice_number( $wpdb->get_var( $sql ) );
		}
		
		public function make_nice_number($n) {
        // first strip any formatting;
			$n = (0+str_replace(",","",$n));
		   
			// is this a number?
			if(!is_numeric($n)) return false;
		   
			// now filter it;
			if($n>1000000000000) return round(($n/1000000000000),1).' trillion';
			else if($n>1000000000) return round(($n/1000000000),1).' billion';
			else if($n>1000000) return round(($n/1000000),1).' million';
			else if($n>1000) return round(($n/1000),1).'k';
		   
			return number_format($n);
		}
		
		/*
		 *	Get all records
		 */
		public function select_all( $per_page = 5, $page_number = 1, $where = null){
			
			global $wpdb;
		  
			$sql = "SELECT * FROM ".$this->table_name;
			
			if( $where ){
				$sql .= ' WHERE user_id = '.$where;
			}
		  
			if ( ! empty( $_REQUEST['orderby'] ) ) {
			  $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			  $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}else{
			  $sql .= ' ORDER BY id DESC, order_id DESC';
			}
		  
			if( $per_page > 0 ){
				$sql .= " LIMIT $per_page";
				$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
			}
		  
			
		  
		  
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		  
			return $result;
		}
		
		/*
		 *	Availabel Credits of user
		 */
		public function available_credits($user_id){
			
			global $wpdb;
		 
			$sql = "SELECT IF ( sum(credits) - sum(redeems) , sum(credits) - sum(redeems), 0)  AS total FROM ".$this->table_name." WHERE user_id = $user_id ";	
			
		   return $wpdb->get_var( $sql );	
		}

		/*
		 * Retrieve total number of followers
		 */
		function no_of_followers( $user_id ){
			global $wpdb;
			//return 0;
			$followers = $wpdb->get_var('SELECT followers_count('.$user_id.', \'count\' )');
			return $followers;
		}
		
		
		/*
		 * Get current user's referal details
		 */
		function get_referral_user_list( $user_id ){
			global $wpdb;
			
			$sql = 'SELECT a.user_id, a.meta_value as first_name, b.meta_value as last_name, followers_count(a.user_id, \'count\') as followers, c.active
			FROM '.$wpdb->usermeta.' AS a
			JOIN '.$wpdb->usermeta.' AS b on a.user_id = b.user_id
			JOIN '.$wpdb->prefix . 'referal_users AS c on a.user_id = c.user_id
			WHERE a.meta_key = "first_name" AND b.meta_key = "last_name" AND c.active = 1 AND c.referral_parent = '.$user_id;
			
			$referral_result = $wpdb->get_results( $sql );
			
			return $referral_result;
		}
		
		/*
		 *	Remove referral user
		 */
		function remove_referral_user( $user_id ){
			global $wpdb;
			
			$obj_referal_users = new Referal_Users();
			return $obj_referal_users->change_referral_user($user_id);
		}
		
		/*
		 *	Distrubute credits to user by order
		 */
		function distribute_credit_by_order( $credit_amount ){
			global $wpdb;
			
			
		}
		
		/**
		 *	Get current month earning.
		 *
		 *	@param int $userId Requested user id
		 *
		 *	@return int Return total earning of current month
		 */
		public function get_current_month_earning( $userId ){
			global $wpdb;
			
			return $wpdb->get_var('select if ( sum(credits) , sum(credits) , 0) AS earning from '.$this->table_name.' where MONTH(CURDATE())=MONTH(date) AND user_id = '.$userId);
		}
		
	} // end Referal_Program
	
}

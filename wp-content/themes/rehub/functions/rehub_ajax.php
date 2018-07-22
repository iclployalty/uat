<?php
//mimic the actuall admin-ajax
define('DOING_AJAX', true);
$get_action = '';

if( !empty($_GET['action'])){
	$get_action = $_GET['action'];
} else {
	die( '-1' );
}

ini_set('html_errors', 0);

//make sure we skip most of the loading which we might not need
define('SHORTINIT', true);

//Load WP core
require_once('../../../../wp-load.php');

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

/* Counts Post views */
function rehub_increment_views() {
	if(empty($_GET['postviews_id']))
		return;
	$post_id = intval(esc_html($_GET['postviews_id']));
	
	if($post_id > 0){
		$today_date = current_time( 'm-d-Y' ); 
		list($today_month, $today_day, $today_year) = explode("-", $today_date);
		$curr_date = get_metadata('post', $post_id, '_rehub_views_date', true);
		
		//first running
		if(!$curr_date) {
			$curr_date = array('mon' => $today_month, 'day' => $today_day, 'year' => $today_year);
			update_metadata('post', $post_id, '_rehub_views_date', $curr_date);
		}
		
		$count_for_day = (int)get_metadata('post', $post_id, 'rehub_views_day', true);
		if($today_day == $curr_date['day']){
			$count_for_day++;
			$update_cur_day = false;
		}else{
			$count_for_day = 1;
			$update_cur_day = $today_day;
		}
		update_metadata('post', $post_id, 'rehub_views_day', (int)$count_for_day);

		$count_for_mon = (int)get_metadata('post', $post_id, 'rehub_views_mon', true);
		if($today_month == $curr_date['mon']){
			$count_for_mon++;
			$update_cur_mon = false;
		}else{
			$count_for_mon = 1;
			$update_cur_mon = $today_month;
		}
		update_metadata('post', $post_id, 'rehub_views_mon', (int)$count_for_mon);
		
		$count_for_year = (int)get_metadata('post', $post_id, 'rehub_views_year', true);
		if($today_year == $curr_date['year']){
			$count_for_year++;
			$update_cur_year = false;
		}else{
			$count_for_year = 1;
			$update_cur_year = $today_year;
		}
		update_metadata('post', $post_id, 'rehub_views_year', (int)$count_for_year);
		
		//changes current date elements
		if($update_cur_day || $update_cur_mon || $update_cur_year){
			if($update_cur_day){
				$curr_date['day'] = $update_cur_day;
			}
			if($update_cur_mon){
				$curr_date['mon'] = $update_cur_mon;
			}
			if($update_cur_year){
				$curr_date['year'] = $update_cur_year;
			}
			update_metadata('post', $post_id, '_rehub_views_date', $curr_date);
		}
		
		$count = (int)get_metadata('post', $post_id, 'rehub_views', true);
		$count++;
		update_metadata('post', $post_id, 'rehub_views', (int)$count);
	}

	exit();
}

/* The function which is not available from the theme */
function rehub_get_ip() {
	if (empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ip_address = $_SERVER["REMOTE_ADDR"];
	} else {
		$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	if(strpos($ip_address, ',') !== false) {
		$ip_address = explode(',', $ip_address);
		$ip_address = $ip_address[0];
	}
    if($ip_address){
        substr_replace($ip_address,0,-1);
    }	
	return esc_attr($ip_address);
}

/* Removes or Adds Posts from | to cached user Wishlist */
function rehub_reload_favorites(){

	$current_user = intval($_GET['userid']);
 	
	if ($current_user != '0') {
		$wishlistids = get_user_meta($current_user, "_wished_posts", true);
	} else {
		$ip = rehub_get_ip();
		$wishlistids = get_transient('re_guest_wishes_' . $ip);
	}
		
	$wishlistids = !empty($wishlistids) ? $wishlistids : array();
	wp_send_json(array('wishlistids' => implode(',',$wishlistids), 'wishcounter' => count($wishlistids)));
}

if( $get_action == 'rehubpostviews' ) {
	require( ABSPATH . WPINC . '/formatting.php' );
	require( ABSPATH . WPINC . '/meta.php' );
	rehub_increment_views();
}
elseif( $get_action == 'refreshwishes' ){
	require( ABSPATH . WPINC . '/formatting.php' );
	require( ABSPATH . WPINC . '/user.php' );
	require( ABSPATH . WPINC . '/meta.php' );
	rehub_reload_favorites();
}
else {
    die( '-1' );
}
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

// commentplus scripts
function commentplus_re_scripts() {
	wp_enqueue_script( 'commentplus_re', get_template_directory_uri().'/js/commentplus_re.js', array('jquery'), '1.0', 1 );
	wp_localize_script( 'commentplus_re', 'cplus_var', array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('commre-nonce'),
		)
	);
}
add_action('wp_enqueue_scripts','commentplus_re_scripts', 12);

add_action( 'wp_ajax_nopriv_commentplus', 'commentplus_re' );
add_action( 'wp_ajax_commentplus', 'commentplus_re' );



//add_filter('comment_text', 'getCommentLike_re' );
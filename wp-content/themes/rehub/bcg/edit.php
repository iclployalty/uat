<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php if ( function_exists( 'bp_get_simple_blog_post_form' ) ): ?>
	<?php
	$form = bp_get_simple_blog_post_form( 'bcg_form' );

	$form->show();
	?>

<?php else: ?>
	<?php _e( 'Please Install <a href="http://buddydev.com/plugins/bp-simple-front-end-post/"> BP Simple Front End Post Plugin to make the editing functionality work.', 'buddyblog' );?>
<?php endif; ?>
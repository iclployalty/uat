<?php
/**
 * Footer template part.
 *
 * @package AMP
 */

/**
 * Context.
 *
 * @var AMP_Post_Template $this
 */
?>
<footer class="amp-wp-footer">
	<div>
		<h2><?php echo esc_html( wptexturize( $this->get( 'blog_name' ) ) ); ?></h2>
		<p>
		</p>
		<a href="#top" class="back-to-top"><?php _e( 'Back to top', 'rehub_framework' ); ?></a>
	</div>
</footer>

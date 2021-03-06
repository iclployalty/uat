<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php $search_text = (rehub_option('rehub_search_text')) ? rehub_option('rehub_search_text') : __("Search", "rehub_framework"); ?>
<?php $rehub_ajax_search = rehub_option('rehub_ajax_search'); ?>
<form role="search" method="get" class="search-form product-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" name="s" placeholder="<?php echo esc_attr($search_text); ?>" value="<?php echo get_search_query(); ?>" data-posttype="product" <?php if ($rehub_ajax_search) { ?>class="re-ajax-search" autocomplete="off" data-catid=""<?php } ?> />
	<input type="hidden" name="post_type" value="product" />
	<?php wc_product_dropdown_categories(array('show_count' => 0, 'parent' => 0, 'show_option_none' => __("All categories", "rehub_framework"), 'class'=> 'rh_woo_drop_cat rhhidden rhniceselect')); ?>
	<button type="submit" class="btnsearch"><i class="fal fa-search"></i></button>
</form>
<?php if ($rehub_ajax_search) { ?>
<div class="re-aj-search-wrap"></div>
<?php } ?>
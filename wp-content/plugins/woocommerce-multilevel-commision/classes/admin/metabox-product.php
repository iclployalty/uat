<?php
/**
 * Register product meta box using a class.
 */
class WMC_Product_Meta_Box {
 
    /**
     * Constructor.
     */
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );            
        }
 
    }
 
    /**
     * Meta box initialization.
     */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }
 
    /**
     * Adds the meta box.
     */
    public function add_metabox() {
        add_meta_box(
            'wmc-product-metabox',
            __( 'Exclude Product from Credit', 'wmc' ),
            array( $this, 'render_metabox' ),
            'product',
            'side',
            'high'
        );
 
    }
 
    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
        // Add nonce for security and authentication.
        wp_nonce_field( 'wmc_product_action', 'wmc_product' );
		$product_ids = array_filter( array_map( 'absint',  get_option('wmc_exclude_products')));
		
		echo '<input type="checkbox" '.(in_array( $post->ID, $product_ids ) ? 'checked' : '').' name="exclude_from_credit" value="'.$post->ID.'" />';
    }
 
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['wmc_product'] ) ? $_POST['wmc_product'] : '';
        $nonce_action = 'wmc_product_action';
 
        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
		
		$product_ids = array_filter( array_map( 'absint', get_option('wmc_exclude_products')));
		if(isset($_POST['exclude_from_credit']) && !in_array( $_POST['exclude_from_credit'], $product_ids )){
			$product_ids[] = sanitize_text_field($_POST['exclude_from_credit']);
			update_option( 'wmc_exclude_products',  $product_ids  );
		}
    }
}
 
new WMC_Product_Meta_Box();
<?php
/**
 * Orderwise credits table
 *
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( ! class_exists( 'WMR_Orcer_Credit_List' ) ) :

/**
 * WMR_Referal_Settings.
 */
class WMR_Orcer_Credit_List extends WP_List_Table {


	/**
	 * Constructor.
	 */
	public function __construct() {
		global $obj_referal_program;
		
		parent::__construct( [
			'singular' => __( 'Order', 'wmc' ), //singular name of the listed records
			'plural'   => __( 'Orders', 'wmc' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );

		$obj_referal_program = new Referal_Program();
				
	}
  
	/** Text displayed when no customer data is available */
  public function no_items() {
	_e( 'No orders avaliable.', 'wmc' );
  }
  
	/**
   * Render a column when no column specific method exists.
   *
   * @param array $item
   * @param string $column_name
   *
   * @return mixed
   */
  public function column_default( $item, $column_name ) {
	switch ( $column_name ) {
	  case 'order_id':
		return edit_post_link( '#'.$item[ $column_name ], '', '', $item[ $column_name ]);
		case 'user_id':
			return  ucwords( get_user_meta( $item[$column_name], 'first_name', true ).' '.get_user_meta( $item[$column_name], 'last_name', true ) );
	  case 'credits':
		return wc_price( $item[ $column_name ] );
	  default:
		return print_r( $item, true ); //Show the whole array for troubleshooting purposes
	}
  }
  
	/**
	*  Associative array of columns
	*
	* @return array
	*/
   function get_columns() {
	 $columns = [
	   'order_id'    => __( 'Order', 'wmc' ),
	   'user_id'    => __( 'Customer Name', 'wmc' ),
	   'credits' => __( 'Earned Credits', 'wmc' ),
	   //'update_credites' => '',
	 ];
   
	 return $columns;
   }
   
   /**
	* Columns to make sortable.
	*
	* @return array
	*/
   public function get_sortable_columns() {
	 $sortable_columns = array(
	   'order_id' => array( 'order_id', false ),
	   'credits' => array( 'credits', false )
	 );
   
	 return $sortable_columns;
   }
   
   /**
	* Handles data query and filter, sorting, and pagination.
	*/
   public function prepare_items() {
		global $obj_referal_program;
   
   
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		
		$this->_column_headers = array($columns, $hidden, $sortable);
   
   
	$post_per_page = get_option('posts_per_page');
	 $per_page     = $this->get_items_per_page( 'orders_per_page', $post_per_page );
	 $current_page = $this->get_pagenum();
	 $total_items  = $obj_referal_program->record_count();
   
	 $this->set_pagination_args( [
	   'total_items' => $total_items, //WE have to calculate the total number of items
	   'per_page'    => $per_page //WE have to determine how many items to show on a page
	 ] );
   
   
	 $this->items = $obj_referal_program->get_credits( $per_page, $current_page );
   }
 
	/*
	 *	Update credits form
	 */
	
function column_update_credites($item) {
        return sprintf(
            '<input type="text" name="update_credites[%d]" value="%d" />', $item['order_id'], $item['credits']
        );    
    }
	
}

endif;

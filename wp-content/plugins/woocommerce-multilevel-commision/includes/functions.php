<?php
/*
*   Devloper Name   :
*   Date            :
*   Detail          : Create Table 
*/
function business_directory_install(){
      global $wpdb;
      $table_name = $wpdb->prefix."business_location";      
      $checkSQL = "show tables like '$table_name'";            
        if($wpdb->get_var($checkSQL) != $table_name){
            $create_table = "CREATE TABLE $table_name (
                            post_id INT(11),
                            lat VARCHAR(100),
                            lng VARCHAR(50));
                            ";                                                         
            require_once(ABSPATH . "wp-admin/includes/upgrade.php");
            dbDelta($create_table);          
        }
}
/*
*   Devloper Name   :
*   Date            :
*   Detail          : Add Script To Front End
*/

function _business_script(){    
    wp_register_style( 'wmc-prefix-style', plugins_url('css/style.css',dirname(__FILE__)));
    wp_enqueue_style( 'wmc-prefix-style' );   
    wp_register_script( 'wmc-prefix-script',  plugins_url('js/custom.js',dirname(__FILE__)),array(),'1.0.0',true );
    wp_enqueue_script( 'wmc-prefix-script' );       
}  

/*
*   Devloper Name   :
*   Date            :
*   Detail          : Add Script To Back End
*/

function _business_admin_script(){    
    wp_register_style( 'prefix-style', plugins_url('css/style_admin.css',dirname(__FILE__)));
    wp_enqueue_style( 'prefix-style' );    
    wp_register_script( 'prefix-script',  plugins_url('js/custom_admin.js',dirname(__FILE__)),array(),'1.0.0',true );
    wp_enqueue_script( 'prefix-script' );   
}  
?>

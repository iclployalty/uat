<?php

if (!defined('ABSPATH')) {
    exit;
}

return apply_filters('bb_ex_csv_product_post_columns', array(
    'ID' => 'ID',
    'post_author' => 'post_author',
    'eh_user_email' => 'eh_user_email',
    'eh_user_name' => 'eh_user_name',
    'post_date' => 'post_date',
    'post_date_gmt' => 'post_date_gmt',
    'post_content' => 'post_content',
    'post_title' => 'post_title',
    'post_status' => 'post_status',
    'comment_status' => 'comment_status',
    'ping_status' => 'ping_status',
    'post_password' => 'post_password',
    'post_name' => 'post_name',
    'post_parent' => 'post_parent',
    'guid' => 'guid',
    'menu_order' => 'menu_order',
    'post_type' => 'post_type',
    'post_mime_type' => 'post_nime_type',
    'comment_count' => 'comment_count',
    'post_alter_id' => 'post_alter_id'
        ));

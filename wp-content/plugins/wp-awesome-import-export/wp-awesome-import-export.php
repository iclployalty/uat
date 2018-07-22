<?php

/* Plugin Name: WP Awesome Import & Export
 * Plugin URI: http://demo.digitaldreamstech.com/wp-awesome-import-export-documentation/
 * Description: This plugin allows to import and export of <strong>post, pages, custom posts, categories/tags, comments, users, custom tables, custom taxonomies</strong>. You can also import and export custom plugin data like <strong>woocommerce</strong> or import/export data of <strong>any table</strong> of wordpress database. You can import using csv,excel,xml files and export in <strong>csv,excel,xml,pdf</strong>.
 * Author: ddeveloper
 * Author URI: http://demo.digitaldreamstech.com/wp-awesome-import-export-documentation/
 * Text Domain: wpaie
 * Domain Path: /languages/
 * Version: 2.6.1
 */
if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

error_reporting(0);
define('WPAIE_VERSION', '2.6.1');
define('WPAIE_PATH', dirname(__FILE__));
define('WPAIE_FILE', __FILE__);
define('WPAIE_SQL_ALLOW', true);

if (!defined('WPAIE_PLUGIN_DIR'))
    define('WPAIE_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));

if (!defined('WPAIE_PLUGIN_URL'))
    define('WPAIE_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));

if (!defined('WPAIE_PLUGIN_BASENAME'))
    define('WPAIE_PLUGIN_BASENAME', untrailingslashit(plugin_basename(__FILE__)));

require_once WPAIE_PLUGIN_DIR . '/includes/ACS.php';
require_once WPAIE_PLUGIN_DIR . '/includes/ACS_Model.php';
require_once WPAIE_PLUGIN_DIR . '/includes/ACS_View.php';
require_once WPAIE_PLUGIN_DIR . '/includes/ACS_Helper.php';
require_once WPAIE_PLUGIN_DIR . '/includes/ACS_Actions.php';

add_action('plugins_loaded', 'wpaieLoadPluginTextdomain');

function wpaieLoadPluginTextdomain() {
    load_plugin_textdomain('wpaie', false, WPAIE_PLUGIN_DIR . '/languages');
}

register_activation_hook(__FILE__, 'wpaieInitOptions');

function wpaieInitOptions() {
    global $wpdb;
    $ACS = new ACS();
    $defaultOptions = array(
        'checkFileName' => true,
        'checkFileNameCharacters' => true,
        'rootElement' => 'root',
        'rowTagName' => '',
        'duplicateEntry' => "skip",
        'postFields' => array(
            'post_title',
            'post_content',
            'post_excerpt',
            'post_date',
            'post_name',
            'post_author',
            'post_parent',
            'post_status',
            'post_tag',
            'post_category',
            'featured_image',
        ),
        "postMetaFields" => $ACS->getPostMeta(),
        "customTaxonomiesFields" => $ACS->getCustomTaxonomies(),
        "postStatus" => "draft",
        "postDate" => "currentdate",
        "dateval" => date('Y/m/d g:i:s'),
        "authorDetails" => "authorId",
        "sqlExport" => "yes",
        "termSeparator" => "|",
        "categorySeparator" => "|",
        "csvDelimiter" => ",",
        "fileMailConfrimation" => "no",
        "postContentImg" => "no",
        "categorySetting" => "no",
        "inisetting" => "512",
        "setFeatureImgByDefault" => "no",
        "woocommerceProductMeta" => array(
            "_product_image_gallery",
            "_product_variation",
            "_product_attributes",
            "_visibility",
            "_stock_status",
            "total_sales",
            "_downloadable",
            "_virtual",
            "_regular_price",
            "_sale_price",
            "_purchase_note",
            "_featured",
            "_weight",
            "_length",
            "_width",
            "_height",
            "_sku",
            "_sale_price_dates_from",
            "_sale_price_dates_to",
            "_price",
            "_sold_individually",
            "_stock",
            "_backorders",
            "_manage_stock",
            "post_views_count",
            "on_sale",
            "taxable",
            "_tax_status",
            "_tax_class",
            "_download_limit",
            "_download_expiry",
            "_downloadable_files",
            "_download_type",
            "_button_text",
            "shipping_required",
            "shipping_taxable",
            "product_shipping_class",
            "shipping_class_id",
            "_wc_average_rating",
            "_wc_rating_count",
            "_wc_review_count",
            "related_ids",
            "_upsell_ids",
            "_crosssell_ids"
        )
    );
    update_option('wpaieOptions', $defaultOptions, '', 'no');

    $table_name = $wpdb->prefix . 'wpaie_file_manager';

    $sql = "CREATE TABLE $table_name (
		`file_id` int NOT NULL AUTO_INCREMENT,
		`file_name` tinytext NOT NULL,
                `absolute_path` text DEFAULT '' NOT NULL,
		`file_path` text DEFAULT '' NOT NULL,
                `file_type` text DEFAULT '' NOT NULL,
                `file_info` varchar(200) DEFAULT '' NOT NULL,
                `imported_ids` text DEFAULT '' NOT NULL,
                `upload_time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (`file_id`)
	);";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

if (is_admin())
    new ACS();
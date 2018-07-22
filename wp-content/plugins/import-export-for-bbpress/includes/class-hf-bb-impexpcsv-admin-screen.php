<?php
if (!defined('ABSPATH')) {
    exit;
}

class HF_BB_ImpExpCsv_AJAX_Handler {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_print_styles', array($this, 'admin_scripts'));
        add_action('admin_notices', array($this, 'admin_notices'));

        add_action('bulk_actions-edit-bbcmt', array($this, 'add_bb_bulk_actions'));
        add_action('admin_action_download_to_cmtiew_csv_hf', array($this, 'process_bb_bulk_actions'));

        add_filter('manage_edit-comments_columns', array($this, 'custom_comment_columns'));
        add_filter('manage_comments_custom_column', array($this, 'custom_comment_column_data'), 10, 2);

        if (is_admin()) {
            add_action('wp_ajax_bb_export_to_csv_single', array($this, 'process_ajax_export_single_comment'));
        }
    }

    public function custom_comment_columns($columns) {
        $columns['bbEx_export_to_csv'] = __('Export');
        return $columns;
    }

    public function custom_comment_column_data($column, $comment_ID) {
        if ('bbEx_export_to_csv' == $column) {
            $action_general = 'download_comment_csv';
            $url_general = wp_nonce_url(admin_url('admin-ajax.php?action=bb_export_to_csv_single&comment_ID=' . $comment_ID), 'hf_bb_import_export');
            $name_general = __('Download to CSV', 'hf_bb_import_export');
            printf('<a class="button tips %s" href="%s" data-tip="%s">%s</a>', $action_general, esc_url($url_general), $name_general, $name_general);
        }
    }

    public function process_ajax_export_single_comment() {
        if (!is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'hf_csv_import_export'));
        }

        $comment_ID = !empty($_GET['comment_ID']) ? absint($_GET['comment_ID']) : '';
        if (!$comment_ID) {
            die;
        }
        $comment_IDs = array(0 => $comment_ID);
        include_once( 'exporter/class-hf-bb-impexpcsv-exporter.php' );
        HF_BB_ImpExpCsv_Exporter::do_export($comment_IDs);
    }

    /**
     * Notices in admin
     */
    public function admin_notices() {
        if (!function_exists('mb_detect_encoding')) {
            echo '<div class="error"><p>' . __('Product CSV Import Export requires the function <code>mb_detect_encoding</code> to import and export CSV files. Please ask your hosting provider to enable this function.', 'hf_bb_import_export') . '</p></div>';
        }
    }

    /**
     * Admin Menu
     */
    public function admin_menu() {
          $page = add_menu_page( __('Forums IM-EX', 'hf_bb_import_export'), __('Forums IM-EX', 'hf_bb_import_export'), apply_filters('bb_csv_product_role', 'read'), 'HF_BB_CSV_IM_EX', array($this, 'output'),'dashicons-external',$position='59');
        
    }

    /**
     * Get WC Plugin path without fail on any version
     */
    public static function hf_get_wc_path() {
        if (function_exists('WC')) {
            $wc_path = WC()->plugin_url();
        } else {
            $wc_path = plugins_url() . '/import-export-for-bbpress';
        }
        return $wc_path;
    }

    /**
     * Admin Scripts
     */
    public function admin_scripts() {

        $wc_path = self::hf_get_wc_path();
        wp_enqueue_script('wc-enhanced-select');
        wp_enqueue_style('woocommerce_admin_styles', $wc_path . '/assets/css/admin.css');
        wp_enqueue_style('woocommerce-product-csv-importer1', plugins_url(basename(plugin_dir_path(HF_BB_ImpExpCsv_FILE)) . '/styles/wf-style.css', basename(__FILE__)), '', '1.0.0', 'screen');
        wp_enqueue_style('woocommerce-product-csv-importer3', plugins_url(basename(plugin_dir_path(HF_BB_ImpExpCsv_FILE)) . '/styles/jquery-ui.css', basename(__FILE__)), '', '1.0.0', 'screen');

        wp_enqueue_script('woocommerce-product-csv-importer2', plugins_url(basename(plugin_dir_path(HF_BB_ImpExpCsv_FILE)) . '/js/bb-import-export-javascript.min.js', basename(__FILE__)), '', '1.0.0', 'screen');
        wp_enqueue_script('jquery-ui-datepicker');
    }

    /**
     * Admin Screen output
     */
    public function output() {
        include('market.php');
        $tab = 'import';
        if (!empty($_GET['tab'])) {
            if ($_GET['tab'] == 'export') {
                $tab = 'export';
            } else if ($_GET['tab'] == 'settings') {
                $tab = 'settings';
            }
        }

        include( 'views/html-hf-bb-admin-screen.php' );
    }

    /**
     * bbPress list page bulk export action add to action list
     * 
     */
    public function add_bb_bulk_actions($action) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var $downloadToCSV = $('<option>').val('download_to_cmt_csv_hf').text('<?php _e('Download as CSV', 'hf_bb_import_export') ?>');
                $('select[name^="action"]').append($downloadToCSV);
            });
        </script>
        <?php
        return $action;
    }

    /**
     * Product page bulk export action
     * 
     */
    public function process_bb_bulk_actions() {
        //wp_die( '<pre>' . print_r( $_REQUEST ) . '</pre>' ); 
        $action = $_REQUEST['action'];
        if (!in_array($action, array('download_to_cmtiew_csv_hf')))
            return;

        if (isset($_REQUEST['delete_comments'])) {
            $cmt_ids = array_map('absint', $_REQUEST['delete_comments']);
        }
        if (empty($cmt_ids)) {
            return;
        }
        // give an unlimited timeout if possible
        @set_time_limit(0);

        if ($action == 'download_to_cmtiew_csv_hf') {
            include_once( 'exporter/class-hf-bb-impexpcsv-exporter.php' );
            HF_BB_ImpExpCsv_Exporter::do_export($cmt_ids);
        }
    }

    /**
     * Admin page for importing
     */
    public function admin_import_page() {
        include( 'views/html-hf-bb-getting-started.php' );
        include( 'views/import/html-hf-bb-import-bbpress.php' );
        
        $post_columns = include( 'exporter/data/data-hf-post-columns.php' );
        $post_woo_columns = include('exporter/data/data-hf-woo-post-columns.php');
          include( 'views/export/html-hf-export-WordPress-Comments-normal.php' );
    }

    /**
     * Admin Page for exporting
     */
    public function admin_export_page() {
        $post_columns = include( 'exporter/data/data-hf-post-columns.php' );
        $post_woo_columns = include('exporter/data/data-hf-woo-post-columns.php');
            include( 'views/export/html-hf-export-WordPress-Comments-normal.php' );
    }

    /**
     * Admin Page for settings
     */
    public function admin_settings_page() {
        include( 'views/settings/html-hf-bb-settings-products.php' );
    }

    public function admin_backup_restore() {
        include('views/settings/html-hf-backup.php');
    }

}

new HF_BB_ImpExpCsv_AJAX_Handler();

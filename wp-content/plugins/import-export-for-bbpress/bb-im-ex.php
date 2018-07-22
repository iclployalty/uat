<?php
/*
  Plugin Name: bbPress Import & Export
  Plugin URI: https://wordpress.org/plugins/import-export-for-bbpress/
  Description: Import and Export bbPress Forum, Topics, Replies and Woodiscuzz discussions From and To your Store.
  Author: wpfloor
  Version: 2.0.3
  Author URI: http://www.wpfloor.com/
  Text Domain: hf_bb_import_export
  WC Tested up to: 3.3.1
 */

  if (!defined('ABSPATH') || !is_admin()) {
    return;
}
if (!defined('HF_BB_IMP_EXP_ID')) {
    define("HF_BB_IMP_EXP_ID", "hw_bb_imp_exp");
}
if (!defined('HF_BB_CSV_IM_EX')) {
    define("HF_BB_CSV_IM_EX", "HF_BB_CSV_IM_EX");
}

//print_r(get_option( 'active_plugins' ));
require_once(ABSPATH . "wp-admin/includes/plugin.php");
// Change the Pack IF BASIC  mention switch('BASIC') ELSE mention switch('PREMIUM')
// Enter your plugin unique option name below $option_name variable
if (!in_array('bbpress/bbpress.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', 'eh_wc_bb_admin_notices', 99);
    deactivate_plugins(plugin_basename(__FILE__));

    function eh_wc_bb_admin_notices() {
        is_admin() && add_filter('gettext', function($translated_text, $untranslated_text, $domain) {
            $old = array(
                "Plugin <strong>activated</strong>.",
                "Selected plugins <strong>activated</strong>."
                );
                    //Error Text for Version Identification
            $error_text = "bbPress Import Export Plugin requires bbPress to be installed!";
            $new = "<span style='color:red'>" . $error_text . "</span>";
            if (in_array($untranslated_text, $old, true)) {
                $translated_text = $new;
            }
            return $translated_text;
        }, 99, 3);
    }

    return;
}
if (in_array('bbpress/bbpress.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    register_deactivation_hook(__FILE__, 'bb_deactivate_work');
        // Enter your plugin unique option name below update_option function
    function bb_deactivate_work() {
        update_option('bb_ex_im_option', '');
    }

    if (!class_exists('HF_BB_Import_Export_CSV')) :

            /**
             * Main CSV Import class
             */
        class HF_BB_Import_Export_CSV {

            public $cron;
            public $cron_import;

            public function __construct() {
                define('HF_BB_ImpExpCsv_FILE', __FILE__);
                if (is_admin()) {
                    add_action('admin_notices', array($this, 'hf_bb_ie_admin_notice'), 15);

                }
                add_filter('bb_screen_ids', array($this, 'bb_screen_ids'));
                add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'hf_plugin_action_links'));
                add_action('init', array($this, 'load_plugin_textdomain'));
                add_action('init', array($this, 'catch_export_request'), 20);
                add_action('init', array($this, 'catch_save_settings'), 20);
                add_action('admin_init', array($this, 'register_importers'));

                include_once( 'includes/class-hf-bb-impexpcsv-system-status-tools.php' );
                include_once( 'includes/class-hf-bb-impexpcsv-admin-screen.php' );
                include_once( 'includes/importer/class-hf-bb-impexpcsv-importer.php' );
                require_once( 'includes/class-hf-bb-impexpcsv-cron.php' );

                $this->cron = new HF_BB_ImpExpCsv_Cron();
                register_activation_hook(__FILE__, array($this->cron, 'hf_new_scheduled_bb_export'));
                register_deactivation_hook(__FILE__, array($this->cron, 'clear_hf_scheduled_bb_export'));
                if (defined('DOING_AJAX')) {
                    include_once( 'includes/class-hf-bb-impexpcsv-ajax-handler.php' );
                }
                require_once( 'includes/class-hf-bb-impexpcsv-import-cron.php' );
                $this->cron_import = new HF_BB_ImpExpCsv_ImportCron ();
                register_activation_hook(__FILE__, array($this->cron_import, 'hf_new_scheduled_bb_import'));
                register_deactivation_hook(__FILE__, array($this->cron_import, 'clear_hf_scheduled_bb_import'));
            }

                //Function for Pugin Options in Plugin menu
            public function hf_plugin_action_links($links) {
                $plugin_links = array(
                    '<a href="' . admin_url('admin.php?page=HF_BB_CSV_IM_EX') . '">' . __('Import Export', 'hf_bb_import_export') . '</a>',
                    '<a href="http://www.wpfloor.com/setting-bbpress-forum-import-export-plugin-wordpress/" target="_blank">' . __('Documentation', 'hf_bb_import_export') . '</a>',
                    '<a href="https://wordpress.org/support/plugin/import-export-for-bbpress" target="_blank">' . __('Support', 'hf_bb_import_export') . '</a>',
                    '<a href="http://www.wpfloor.com/" target="_blank">' . __('wpfloor', 'hf_bb_import_export') . '</a>'
                    );
                return array_merge($plugin_links, $links);
            }

            function hf_bb_ie_admin_notice() {
                global $pagenow;
                global $post;

                if (!isset($_GET["hf_bb_ie_msg"]) && empty($_GET["hf_bb_ie_msg"])) {
                    return;
                }

                $wf_bbpress_ie_msg = $_GET["hf_bb_ie_msg"];

                switch ($wf_bbpress_ie_msg) {
                    case "1":
                    echo '<div class="update"><p>' . __('Successfully uploaded via FTP.', 'hf_bb_import_export') . '</p></div>';
                    break;
                    case "2":
                    echo '<div class="error"><p>' . __('Error while uploading via FTP.', 'hf_bb_import_export') . '</p></div>';
                    break;
                    case "3":
                    echo '<div class="error"><p>' . __('Please choose the file in CSV format either using Method 1 or Method 2.', 'hf_bb_import_export') . '</p></div>';
                    break;
                }
            }

                // Add screen ID
            public function bb_screen_ids($ids) {
                    $ids[] = 'admin'; // For import screen
                    return $ids;
                }

                // Handle localisation
                public function load_plugin_textdomain() {
                    load_plugin_textdomain('hf_bb_import_export', false, dirname(plugin_basename(__FILE__)) . '/lang/');
                }

                // Catches an export request and exports the data. This class is only loaded in admin.
                public function catch_export_request() {
                    if (!empty($_GET['action']) && !empty($_GET['page']) && $_GET['page'] == 'HF_BB_CSV_IM_EX') {
                        switch ($_GET['action']) {
                            case "export" :
                            $user_ok = $this->hf_user_permission();
                            if ($user_ok) {
                                include_once( 'includes/exporter/class-hf-bb-impexpcsv-exporter.php' );
                                HF_BB_ImpExpCsv_Exporter::do_export();
                            } else {
                                wp_redirect(wp_login_url());
                            }
                            break;
                        }
                    }
                }

                //settings
                public function catch_save_settings() {
                    if (!empty($_GET['action']) && !empty($_GET['page']) && $_GET['page'] == 'HF_BB_CSV_IM_EX') {
                        switch ($_GET['action']) {
                            case "settings" :
                            include_once( 'includes/settings/class-hf-bb-impexpcsv-settings.php' );
                            HF_BB_ImpExpCsv_Settings::save_settings();
                            break;
                        }
                    }
                }

                /**
                 * Register importers for use
                 */
                public function register_importers() {
                    register_importer('bb_csv', 'bbPress Import Export (CSV)', __('Import <strong>bbPress Forums, Topics and Replys</strong> to your store via a csv file.', 'hf_bb_import_export'), 'HF_BB_ImpExpCsv_Importer::product_importer');
                    register_importer('bb_csv_cron', 'bbPress Import Export (CSV)', __('Cron Import <strong>bbPress Forums, Topics and Replys</strong> to your store via a csv file.', 'hf_bb_import_export'), 'HF_BB_ImpExpCsv_Importer::product_importer');
                }

                private function hf_user_permission() {
                    // Check if user has rights to export
                    $current_user = wp_get_current_user();
                    $user_ok = false;
                    $wf_roles = apply_filters('hf_user_permission_roles', array('administrator', 'shop_manager'));
                    if ($current_user instanceof WP_User) {
                        $can_users = array_intersect($wf_roles, $current_user->roles);
                        if (!empty($can_users)) {
                            $user_ok = true;
                        }
                    }
                    return $user_ok;
                }

            }

            endif;
            new HF_BB_Import_Export_CSV();
        }
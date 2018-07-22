<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class HF_BB_ImpExpCsv_ImportCron {

    public $settings;
    public $file_url;
    public $error_message;

    public function __construct() {
        add_filter('cron_schedules', array($this, 'hf_auto_import_schedule'));
        add_action('init', array($this, 'hf_new_scheduled_bb_import'));
        add_action('HF_BB_CSV_IM_EX_auto_import_products', array($this, 'hf_scheduled_import_products'));
        $this->settings = get_option('woocommerce_' . HF_BB_IMP_EXP_ID . '_settings', null);
        $this->settings_ftp_import = get_option('hf_bb_importer_ftp', null);
        $this->imports_enabled = FALSE;
        if (isset($this->settings['auto_import']) && $this->settings['auto_import'] === 'Enabled')
            $this->imports_enabled = TRUE;
    }

    public function hf_auto_import_schedule($schedules) {
        if ($this->imports_enabled) {
            $import_interval = $this->settings['auto_import_interval'];
            if ($import_interval) {
                $schedules['import_interval'] = array(
                    'interval' => (int) $import_interval * 60,
                    'display' => sprintf(__('Every %d minutes', 'hf_bb_import_export'), (int) $import_interval)
                );
            }
        }
        return $schedules;
    }

    public function hf_new_scheduled_bb_import() {
        if ($this->imports_enabled) {
            if (!wp_next_scheduled('HF_BB_CSV_IM_EX_auto_import_products')) {
                $start_time = $this->settings['auto_import_start_time'];
                $current_time = current_time('timestamp');
                if ($start_time) {
                    if ($current_time > strtotime('today ' . $start_time, $current_time)) {
                        $start_timestamp = strtotime('tomorrow ' . $start_time, $current_time) - ( get_option('gmt_offset') * HOUR_IN_SECONDS );
                    } else {
                        $start_timestamp = strtotime('today ' . $start_time, $current_time) - ( get_option('gmt_offset') * HOUR_IN_SECONDS );
                    }
                } else {
                    $import_interval = $this->settings['auto_import_interval'];
                    $start_timestamp = strtotime("now +{$import_interval} minutes");
                }
                wp_schedule_event($start_timestamp, 'import_interval', 'HF_BB_CSV_IM_EX_auto_import_products');
            }
        }
    }

    public static function load_wp_importer() {
        // Load Importer API
        require_once ABSPATH . 'wp-admin/includes/import.php';

        if (!class_exists('WP_Importer')) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            if (file_exists($class_wp_importer)) {
                require $class_wp_importer;
            }
        }
    }

    public function hf_scheduled_import_products() {

        //error_log("test run by wp-cron" , 3 , ABSPATH . '/wp-content/uploads/wc-logs/my-cron-log.txt');
        define('WP_LOAD_IMPORTERS', true);
        if (class_exists('WC')) {
            if (!class_exists('WooCommerce')) :
                require ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php';
            endif;
        }
        HF_BB_ImpExpCsv_ImportCron ::product_importer();
        //  echo '<pre>';print_r($GLOBALS['HF_BB_CSV_Comments_Import']);exit;
        if ($this->handle_ftp_for_autoimport()) {
            //echo $this->file_url;exit;
            if ($this->settings['auto_import_profile'] !== '') {
                $profile_array = get_option('hf_prod_csv_imp_exp_mapping');
                $mapping = $profile_array[$this->settings['auto_import_profile']][0];
                $eval_field = $profile_array[$this->settings['auto_import_profile']][1];
                $start_pos = 0;
                $end_pos = '';
            } else {
                $this->error_message = 'Please set a mapping profile';
                $GLOBALS['HF_BB_CSV_Comments_Import']->log->add('csv-import', __('Failed processing import. Reason:' . $this->error_message, 'hf_bb_import_export'));
            }
            if ($this->settings['auto_import_merge']) {
                $_GET['merge'] = 1;
            } else {
                $_GET['merge'] = 0;
            }
            if ($this->settings['auto_import_woo']) {
                $_GET['woo_bb'] = 1;
            } else {
                $_GET['woo_bb'] = 0;
            }

            //echo wp_next_scheduled('HF_BB_CSV_IM_EX_auto_import_products').'<br/>';
            //echo date('Y-m-d H:i:s' , wp_next_scheduled('HF_BB_CSV_IM_EX_auto_import_products'));
            //echo $_GET['merge'];exit;

            $GLOBALS['HF_BB_CSV_Comments_Import']->import_start($this->file_url, $mapping, $start_pos, $end_pos, $eval_field);
            $GLOBALS['HF_BB_CSV_Comments_Import']->import();
            $GLOBALS['HF_BB_CSV_Comments_Import']->import_end();

            //do_action('hf_new_scheduled_bb_import');
            //wp_clear_scheduled_hook('HF_BB_CSV_IM_EX_auto_import_products');
            //do_action('hf_new_scheduled_bb_import');

            die();
        } else {
            $GLOBALS['HF_BB_CSV_Comments_Import']->log->add('csv-import', __('Fetching file failed. Reason:' . $this->error_message, 'hf_bb_import_export'));
        }
    }

    public function clear_hf_scheduled_bb_import() {
        wp_clear_scheduled_hook('HF_BB_CSV_IM_EX_auto_import_products');
    }

    private function handle_ftp_for_autoimport() {

        //FTP Settings for Auto Import
        $enable_ftp_ie = $this->settings_ftp_import['enable_ftp_ie'];
        if (!$enable_ftp_ie)
            return false;

        $ftp_server = $this->settings_ftp_import['ftp_server'];
        $ftp_user = $this->settings_ftp_import['ftp_user'];
        $ftp_password = $this->settings_ftp_import['ftp_password'];
        $use_ftps = $this->settings_ftp_import['use_ftps'];
        $ftp_server_path = $this->settings_ftp_import['ftp_server_path'];


        $local_file = 'wp-content/plugins/import-export-for-bbpress/temp-import.csv';
        $server_file = $ftp_server_path;


        $ftp_conn = $use_ftps ? ftp_ssl_connect($ftp_server) : ftp_connect($ftp_server);
        $this->error_message = "";
        $success = false;
        if ($ftp_conn == false) {
            $this->error_message = "There is connection problem\n";
        }

        if (empty($this->error_message)) {
            if (ftp_login($ftp_conn, $ftp_user, $ftp_password) == false) {
                $this->error_message = "Not able to login \n";
            }
        }
        if (empty($this->error_message)) {

            if (ftp_get($ftp_conn, ABSPATH . $local_file, $server_file, FTP_BINARY)) {
                $this->error_message = "";
                $success = true;
            } else {
                $this->error_message = "There was a problem\n";
            }
        }

        ftp_close($ftp_conn);
        if ($success) {
            $this->file_url = ABSPATH . $local_file;
        } else {
            die($this->error_message);
        }

        return true;
    }

    public static function product_importer() {
        if (!defined('WP_LOAD_IMPORTERS')) {
            return;
        }

        self::load_wp_importer();

        // includes
        require_once 'importer/class-hf-bb-impexpcsv-import.php';
        require_once 'importer/class-hf-csv-parser.php';

        if (!class_exists('WC_Logger')) {
            $class_wc_logger = ABSPATH . 'wp-content/plugins/woocommerce/includes/class-wc-logger.php';
            if (file_exists($class_wc_logger)) {
                require $class_wc_logger;
            }
        } else {
            $class_wc_logger = ABSPATH . 'wp-content/plugins/import-export-for-bbpress/includes/WP_Logging.php';
            if (file_exists($class_wc_logger)) {
                require $class_wc_logger;
            }
        }

        $class_wc_logger = ABSPATH . 'wp-includes/pluggable.php';
        require_once($class_wc_logger);
        wp_set_current_user(1); // escape user access check while running cron

        $GLOBALS['HF_BB_CSV_Comments_Import'] = new HF_BB_ImpExpCsv_Import();
        $GLOBALS['HF_BB_CSV_Comments_Import']->import_page = 'bbPress_csv_cron';
        $GLOBALS['HF_BB_CSV_Comments_Import']->delimiter = ','; // need to give option in settingn , if some queries are coming
    }

}

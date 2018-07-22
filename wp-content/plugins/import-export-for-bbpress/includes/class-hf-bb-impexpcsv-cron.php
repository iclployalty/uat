<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class HF_BB_ImpExpCsv_Cron {

    public $settings;

    public function __construct() {
        add_filter('cron_schedules', array($this, 'hf_auto_export_schedule'));
        add_action('init', array($this, 'hf_new_scheduled_bb_export'));
        add_action('HF_BB_CSV_IM_EX_auto_export_products', array($this, 'hf_scheduled_export_products'));
        $this->settings = get_option('woocommerce_' . HF_BB_IMP_EXP_ID . '_settings', null);
        $this->exports_enabled = FALSE;
        if ($this->settings['auto_export'] === 'Enabled')
            $this->exports_enabled = TRUE;
    }

    public function hf_auto_export_schedule($schedules) {
        if ($this->exports_enabled) {
            $export_interval = $this->settings['auto_export_interval'];
            if ($export_interval) {
                $schedules['export_interval'] = array(
                    'interval' => (int) $export_interval * 60,
                    'display' => sprintf(__('Every %d minutes', 'hf_bb_import_export'), (int) $export_interval)
                );
            }
        }
        return $schedules;
    }

    public function hf_new_scheduled_bb_export() {
        if ($this->exports_enabled) {
            if (!wp_next_scheduled('HF_BB_CSV_IM_EX_auto_export_products')) {
                $start_time = $this->settings['auto_export_start_time'];
                $current_time = current_time('timestamp');
                if ($start_time) {
                    if ($current_time > strtotime('today ' . $start_time, $current_time)) {
                        $start_timestamp = strtotime('tomorrow ' . $start_time, $current_time) - ( get_option('gmt_offset') * HOUR_IN_SECONDS );
                    } else {
                        $start_timestamp = strtotime('today ' . $start_time, $current_time) - ( get_option('gmt_offset') * HOUR_IN_SECONDS );
                    }
                } else {
                    $export_interval = $this->settings['auto_export_interval'];
                    $start_timestamp = strtotime("now +{$export_interval} minutes");
                }
                wp_schedule_event($start_timestamp, 'export_interval', 'HF_BB_CSV_IM_EX_auto_export_products');
            }
        }
    }

    public function hf_scheduled_export_products() {
        include_once( 'exporter/class-hf-bb-impexpcsv-exporter.php' );
        HF_BB_ImpExpCsv_Exporter::do_export();
    }

    public function clear_hf_scheduled_bb_export() {
        wp_clear_scheduled_hook('HF_BB_CSV_IM_EX_auto_export_products');
    }

}
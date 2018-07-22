<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class HF_BB_ImpExpCsv_Importer {
      
	/**
	 * bbPress Exporter Tool
	 */
	public static function load_wp_importer() {
		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) ) {
				require $class_wp_importer;
			}
		}
	}

	/**
	 * WordPress bbPress Importer Tool
	 */
	public static function product_importer() {
		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			return;
		}

		self::load_wp_importer();

		// includes
		require_once 'class-hf-bb-impexpcsv-import.php';
		require_once 'class-hf-csv-parser.php';

		// Dispatch
		$GLOBALS['HF_BB_CSV_Comments_Import'] = new HF_BB_ImpExpCsv_Import();
           	$GLOBALS['HF_BB_CSV_Comments_Import'] ->dispatch();
	}	
}

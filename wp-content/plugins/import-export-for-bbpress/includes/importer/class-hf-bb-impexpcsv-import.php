<?php
/**
 * WordPress Importer class for managing the import process of a CSV file
 *
 * @package WordPress
 * @subpackage Importer
 */
if (!class_exists('WP_Importer'))
    return;

class HF_BB_ImpExpCsv_Import extends WP_Importer {

    var $id;
    var $file_url;
    var $delimiter;
    var $profile;
    var $merge_empty_cells;
    var $processed_terms = array();
    var $processed_posts = array();
    var $post_orphans = array();
    var $attachments = array();
    var $upsell_skus = array();
    var $crosssell_skus = array();
    var $parent_data = '';
    var $import_results = array();

    /**
     * Constructor
     */
    public function __construct() {
        if (function_exists('WC')) {
            $this->log = new WC_Logger();
        }

        $this->import_page = 'bb_csv';
        $this->file_url_import_enabled = apply_filters('bb_csv_product_file_url_import_enabled', true);
    }

    /**
     * Registered callback function for the WordPress Importer
     *
     * Manages the three separate stages of the CSV import process
     */
   public function hf_bb_im_ex_StartSession() {
    if(!session_id()) {
        session_start();
    }
}
public function hf_bb_im_ex_myEndSession() {
    session_destroy ();
}
    public function dispatch() {
        global $wpdb;
        if(function_exists('WC'))
        {
        global $woocommerce ;
        }
        add_action('init', array($this,'hf_bb_im_ex_StartSession'), 1);
        
        if (!empty($_POST['delimiter'])) {
            $this->delimiter = stripslashes(trim($_POST['delimiter']));
        } else if (!empty($_GET['delimiter'])) {
            $this->delimiter = stripslashes(trim($_GET['delimiter']));
        }

        if (!$this->delimiter)
            $this->delimiter = ',';

        if (!empty($_POST['profile'])) {
            $this->profile = stripslashes(trim($_POST['profile']));
        } else if (!empty($_GET['profile'])) {
            $this->profile = stripslashes(trim($_GET['profile']));
        }
        if (!$this->profile)
            $this->profile = '';

        $this->bb_clean_before_import = 0;

        $step = empty($_GET['step']) ? 0 : (int) $_GET['step'];

        switch ($step) {
            case 0 :
                $this->header();
                $this->greet();
                break;
            case 1 :
                $this->header();

                check_admin_referer('import-upload');

                if (!empty($_GET['file_url']))
                    $this->file_url = esc_attr($_GET['file_url']);
                if (!empty($_GET['file_id']))
                    $this->id = $_GET['file_id'];

                if (!empty($_GET['clearmapping']) || $this->handle_upload())
                    $this->import_options();
                else
                //_e( 'Error with handle_upload!', 'hf_bb_import_export' );
                    wp_redirect(wp_get_referer() . '&hf_bb_ie_msg=3');
                exit;
                break;
            case 2 :
                $this->header();
                // one woocommerce
                check_admin_referer('import-options');

                $this->id = (int) $_POST['import_id'];

                // get Import options dropdown data and pass to jQuery
                $data1 = (!empty($_POST['woo_stat']) ? $_POST['woo_stat'] : '0');
                $data2 = (!empty($_POST['woo_forum_1']) ? $_POST['woo_forum_1'] : '0');
                $data3 = (!empty($_POST['woo_stat1']) ? $_POST['woo_stat1'] : '0');
                $data4 = (!empty($_POST['woo_date_enable']) ? $_POST['woo_date_enable'] : '0');
                $data5 = (!empty($_POST['woo_selection']) ? $_POST['woo_selection'] : '0');
                $data6 = (!empty($_POST['bb_ex_forum']) ? $_POST['bb_ex_forum'] : '0');
                $data7 = (!empty($_POST['bb_ex_topic']) ? $_POST['bb_ex_topic'] : '0');

                if ($this->file_url_import_enabled)
                    $this->file_url = esc_attr($_POST['import_url']);
                if ($this->id)
                    $file = get_attached_file($this->id);
                else if ($this->file_url_import_enabled)
                    $file = ABSPATH . $this->file_url;

                $file = str_replace("\\", "/", $file);

                if ($file) {
                    ?>
                    <table id="import-progress" class="widefat_importer widefat">     <thead>
                            <tr>
                                <th class="status">&nbsp;</th>
                                <th class="row"><?php _e('Row', 'hf_bb_import_export'); ?></th>
                                <th><?php _e('ID', 'hf_bb_import_export'); ?></th>
                                <th><?php _e('Post Type', 'hf_bb_import_export'); ?></th>             <th class="reason"><?php _e('Status Msg', 'hf_bb_import_export'); ?></th>
                            </tr>     </thead>
                        <tfoot>        
                            <tr class="importer-loading">                                 <td colspan="5"></td>                             </tr>
                        </tfoot>     <tbody></tbody>
                    </table>
                    <script type="text/javascript">     jQuery(document).ready(function($) {
                        if (! window.console) { window.console = function(){}; }

                        var processed_terms = [];
                        var processed_posts = [];
                        var post_orphans = [];
                        var attachments = []; var upsell_skus = []; var crosssell_skus = []; var i = 1;
                        var done_count = 0; function import_rows(start_pos, end_pos) {
                        var data = {
                        action: 	'bb_csv_import_request',
                        file:       '<?php echo addslashes($file); ?>',
                        mapping:    '<?php echo json_encode($_POST['map_from']); ?>',
                        profile:    '<?php echo $this->profile; ?>',
                        eval_field: '<?php echo stripslashes(json_encode(($_POST['eval_field']), JSON_HEX_APOS)) ?>',
                        delimiter:  '<?php echo $this->delimiter; ?>',
                        bb_clean_before_import: '<?php echo $this->bb_clean_before_import; ?>',
                        start_pos:  start_pos, end_pos:    end_pos,
                        };
                        data.eval_field = $.parseJSON(data.eval_field);
                        return $.ajax({     url:        '<?php echo add_query_arg(array('import_page' => $this->import_page, 'step' => '3', 'merge' => !empty($_GET['merge']) ? '1' : '0', 'imp_ex_topc' => !empty($_GET['imp_ex_topc']) ? '1' : '0', 'imp_ex_form' => !empty($_GET['imp_ex_form']) ? '1' : '0', 'woo_bb' => !empty($_GET['woo_bb']) ? '1' : '0', 'woo_forum_1' => $data2, 'woo_stat' => $data1, 'woo_stat1' => $data3, 'woo_conv' => $data5, 'imp_ex_forum' => $data6, 'imp_ex_topic' => $data7), admin_url('admin-ajax.php')); ?>',
                        data:       data,
                        type:       'POST',
                        success:    function(response) {
                        console.log(response);
                        if (response) {

                        try {
                        // Get the valid JSON only from the returned string
                        if (response.indexOf("<!--WC_START-->") >= 0)
                        response = response.split("<!--WC_START-->")[1]; // Strip off before after WC_START 
                        if (response.indexOf("<!--WC_END-->") >= 0)
                        response = response.split("<!--WC_END-->")[0]; // Strip off anything after WC_END

                        // Parse
                        var results = $.parseJSON(response);
                        if (results.error) {

                        $('#import-progress tbody').append('<tr id="row-' + i + '" class="error"><td class="status" colspan="5">' + results.error + '</td></tr>');
                        i++;
                        } else if (results.import_results && $(results.import_results).size() > 0) {

                        $.each(results.processed_terms, function(index, value) {
                        processed_terms.push(value);
                        });
                        $.each(results.processed_posts, function(index, value) {
                        processed_posts.push(value);
                        });
                        $.each(results.post_orphans, function(index, value) {
                        post_orphans.push(value);
                        });
                        $.each(results.attachments, function(index, value) {
                        attachments.push(value);
                        });
                        upsell_skus = jQuery.extend({}, upsell_skus, results.upsell_skus);
                        crosssell_skus = jQuery.extend({}, crosssell_skus, results.crosssell_skus);
                        $(results.import_results).each(function(index, row) {

                        $('#import-progress tbody').append('<tr id="row-' + i + '" class="' + row['status'] + '"><td><mark class="result" title="' + row['status'] + '">' + row['post_id'] + '</mark></td><td class="row">' + i + '</td><td>' + row['post_id'] + '</td><td> <a href="' + row['comment_link'] + '" target="_blank" title="' + row['cmd_title'] + '" > ' + row['cmd_title'] + ' </a>  </td><td class="reason">' + row['reason'] + '</td></tr>');
                        i++;
                        });
                        }

                        } catch (err) {}

                        } else {
                        $('#import-progress tbody').append('<tr class="error"><td class="status" colspan="5">' +                  '<?php _e('AJAX Error', 'hf_bb_import_export'); ?>' + '</td></tr>');
                        }

                        var w = $(window);
                        var row = $("#row-" + (i - 1));
                        if (row.length) {
                        w.scrollTop(row.offset().top - (w.height() / 2));
                        }

                        done_count++;
                        $('body').trigger('bb_csv_import_request_complete');
                        }
                        });
                        }

                        var rows = [];
                        <?php
                        $limit = apply_filters('bb_csv_import_limit_per_request', 8);
                        $enc = mb_detect_encoding($file, 'UTF-8, ISO-8859-1', true);
                        if ($enc)
                            setlocale(LC_ALL, 'en_US.' . $enc);
                        @ini_set('auto_detect_line_endings', true);

                        $count = 0;
                        $previous_position = 0;
                        $position = 0;
                        $import_count = 0;

// Get CSV positions
                        if (( $handle = fopen($file, "r") ) !== FALSE) {

                            while (( $postmeta = fgetcsv($handle, 0, $this->delimiter, '"', '"') ) !== FALSE) {
                                $count++;

                                if ($count >= $limit) {
                                    $previous_position = $position;
                                    $position = ftell($handle);
                                    $count = 0;
                                    $import_count ++;

// Import rows between $previous_position $position
                                    ?>rows.push([ <?php echo $previous_position; ?>, <?php echo $position; ?> ]); <?php
                                }
                            }

// Remainder
                            if ($count > 0) {
                                ?>rows.push([ <?php echo $position; ?>, '' ]); <?php
                                $import_count ++;
                            }

                            fclose($handle);
                        }
                        ?>

                        var data = rows.shift();
                        var regen_count = 0;
                        import_rows(data[0], data[1]);
                        $('body').on('bb_csv_import_request_complete', function() {
                        if (done_count == <?php echo $import_count; ?>) {

                        if (attachments.length) {

                        $('#import-progress tbody').append('<tr class="regenerating"><td colspan="5"><div class="progress"></div></td></tr>');
                        index = 0;
                        $.each(attachments, function(i, value) {
                        regenerate_thumbnail(value);
                        index ++;
                        if (index == attachments.length) {
                        import_done();
                        }
                        });
                        } else {
                        import_done();
                        }

                        } else {
                        // Call next request
                        data = rows.shift();
                        import_rows(data[0], data[1]);
                        }
                        });
                        // Regenerate a specified image via AJAX
                        function regenerate_thumbnail(id) {
                        $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: { action: "bb_csv_import_regenerate_thumbnail", id: id },
                        success: function(response) {
                        if (response !== Object(response) || (typeof response.success === "undefined" && typeof response.error === "undefined")) {
                        response = new Object;
                        response.success = false;
                        response.error = "<?php printf(esc_js(__('The resize request was abnormally terminated (ID %s). This is likely due to the image exceeding available memory or some other type of fatal error.', 'hf_bb_import_export')), '" + id + "'); ?>";
                        }

                        regen_count ++;
                        $('#import-progress tbody .regenerating .progress').css('width', ((regen_count / attachments.length) * 100) + '%').html(regen_count + ' / ' + attachments.length + ' <?php echo esc_js(__('thumbnails regenerated', 'hf_bb_import_export')); ?>');
                        if (! response.success) {
                        $('#import-progress tbody').append( '<tr><td colspan="5">' + response.error + '</td></tr>' );
                        }
                        },
                        error: function( response ) {
                        $('#import-progress tbody').append( '<tr><td colspan="5">' + response.error + '</td></tr>' );
                        }
                        });
                        }

                        function import_done() {
                        var data = {
                        action: 'bb_csv_import_request',
                        file: '<?php echo $file; ?>',
                        processed_terms: processed_terms,
                        processed_posts: processed_posts,
                        post_orphans: post_orphans,
                        upsell_skus: upsell_skus,
                        crosssell_skus: crosssell_skus
                        };

                        $.ajax({
                        url: '<?php echo add_query_arg(array('import_page' => $this->import_page, 'step' => '4', 'merge' => !empty($_GET['merge']) ? 1 : 0, 'imp_ex_topc' => !empty($_GET['imp_ex_topc']) ? 1 : 0, 'imp_ex_form' => !empty($_GET['imp_ex_form']) ? 1 : 0, 'woo_bb' => !empty($_GET['woo_bb']) ? 1 : 0, 'woo_forum_1' => $data2, 'woo_stat' => $data1, 'woo_stat1' => $data3, 'woo_conv' => $data5, 'woo_conv' => $data5, 'imp_ex_forum' => $data6, 'imp_ex_topic' => $data7), admin_url('admin-ajax.php')); ?>',
                        data:       data,
                        type:       'POST',
                        success:    function( response ) {
                        console.log( response );
                        $('#import-progress tbody').append( '<tr class="complete"><td colspan="5">' + response + '</td></tr>' );
                        $('.importer-loading').hide();
                        }
                        });
                        }
                        });
                    </script>
                    <?php
                } else {
                    echo '<p class="error">' . __('Error finding uploaded file!', 'hf_bb_import_export') . '</p>';
                }
                break;
            case 3 :


                // Check access - cannot use nonce here as it will expire after multiple requests
                //two woocommerce

                if (!current_user_can('read'))
                    die();

                add_filter('http_request_timeout', array($this, 'bump_request_timeout'));

                if (function_exists('gc_enable'))
                    gc_enable();

                @set_time_limit(0);
                @ob_flush();
                @flush();
                $wpdb->hide_errors();

                $file = stripslashes($_POST['file']);
                $mapping = json_decode(stripslashes($_POST['mapping']), true);
                $profile = isset($_POST['profile']) ? $_POST['profile'] : '';
                $eval_field = $_POST['eval_field'];
                $start_pos = isset($_POST['start_pos']) ? absint($_POST['start_pos']) : 0;
                $end_pos = isset($_POST['end_pos']) ? absint($_POST['end_pos']) : '';


                if ($profile !== '') {
                    $profile_array = get_option('hf_bb_csv_imp_exp_mapping');
                    $profile_array[$profile] = array($mapping, $eval_field);
                    update_option('hf_bb_csv_imp_exp_mapping', $profile_array);
                }

                $position = $this->import_start($file, $mapping, $start_pos, $end_pos, $eval_field);
                $this->import();

                $this->import_end();


                $results = array();
                $results['import_results'] = $this->import_results;
                $results['processed_terms'] = $this->processed_terms;
                $results['processed_posts'] = $this->processed_posts;
                $results['post_orphans'] = $this->post_orphans;
                $results['attachments'] = $this->attachments;
                $results['upsell_skus'] = $this->upsell_skus;
                $results['crosssell_skus'] = $this->crosssell_skus;
                // die($results);
                echo "<!--WC_START-->";
                echo json_encode($results);
                echo "<!--WC_END-->";
                exit;
                break;
            case 4 :
                // Check access - cannot use nonce here as it will expire after multiple requests
                if (!current_user_can('read'))
                    die();

                add_filter('http_request_timeout', array($this, 'bump_request_timeout'));

                if (function_exists('gc_enable'))
                    gc_enable();

                @set_time_limit(0);
                @ob_flush();
                @flush();
                $wpdb->hide_errors();

                //Finalize the Steps
                $this->processed_terms = isset($_POST['processed_terms']) ? $_POST['processed_terms'] : array();
                $this->processed_posts = isset($_POST['processed_posts']) ? $_POST['processed_posts'] : array();
                $this->post_orphans = isset($_POST['post_orphans']) ? $_POST['post_orphans'] : array();
                $this->crosssell_skus = isset($_POST['crosssell_skus']) ? array_filter((array) $_POST['crosssell_skus']) : array();
                $this->upsell_skus = isset($_POST['upsell_skus']) ? array_filter((array) $_POST['upsell_skus']) : array();

                _e('Step 1...', 'hf_bb_import_export') . ' ';

                wp_defer_term_counting(true);
                wp_defer_comment_counting(true);

                _e('Step 2...', 'hf_bb_import_export') . ' ';

                echo 'Step 3...' . ' '; // Easter egg
                // reset transients for products
                if (function_exists('WC')) {
                    if (function_exists('wc_delete_product_transients')) {
                        wc_delete_product_transients();
                    } else {
                        $woocommerce->clear_product_transients();
                    }

                    delete_transient('wc_attribute_taxonomies');
                }
                $wpdb->query("DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_wc_product_type_%')");

                _e('Finalizing...', 'hf_bb_import_export') . ' ';


                // SUCCESS
                _e('Finished. Import complete.', 'hf_bb_import_export');

                $this->import_end();
                exit;
                break;
        }

        $this->footer();
    }

    /**
     * format_data_from_csv
     */
    public function format_data_from_csv($data, $enc) {
        return ( $enc == 'UTF-8' ) ? $data : utf8_encode($data);
    }

    /**
     * Display pre-import options
     */
    public function import_options() {
        $j = 0;

        if ($this->id)
            $file = get_attached_file($this->id);
        else if ($this->file_url_import_enabled)
            $file = ABSPATH . $this->file_url;
        else
            return;

        // Set locale
        $enc = mb_detect_encoding($file, 'UTF-8, ISO-8859-1', true);
        if ($enc)
            setlocale(LC_ALL, 'en_US.' . $enc);
        @ini_set('auto_detect_line_endings', true);

        // Get headers
        if (( $handle = fopen($file, "r") ) !== FALSE) {

            $row = $raw_headers = array();

            $header = fgetcsv($handle, 0, $this->delimiter, '"', '"');

            while (( $postmeta = fgetcsv($handle, 0, $this->delimiter, '"', '"') ) !== FALSE) {
                foreach ($header as $key => $heading) {
                    if (!$heading)
                        continue;
                    $s_heading = strtolower($heading);
                    $row[$s_heading] = ( isset($postmeta[$key]) ) ? $this->format_data_from_csv($postmeta[$key], $enc) : '';
                    $raw_headers[$s_heading] = $heading;
                }
                break;
            }
            fclose($handle);
        }

        $mapping_from_db = get_option('hf_bb_csv_imp_exp_mapping');

        if ($this->profile !== '' && !empty($_GET['clearmapping'])) {
            unset($mapping_from_db[$this->profile]);
            update_option('hf_bb_csv_imp_exp_mapping', $mapping_from_db);
            $this->profile = '';
        }
        if ($this->profile !== '')
            $mapping_from_db = $mapping_from_db[$this->profile];

        $saved_mapping = null;
        $saved_evaluation = null;
        if ($mapping_from_db && is_array($mapping_from_db) && $this->profile !== '' && count($mapping_from_db) == 2 && empty($_GET['clearmapping'])) {
            //if(count(array_intersect_key ( $mapping_from_db[0] , $row)) ==  count($mapping_from_db[0])){	
            $reset_action = 'admin.php?clearmapping=1&amp;profile=' . $this->profile . '&amp;import=' . $this->import_page . '&amp;step=1&amp;merge=' . (!empty($_GET['merge']) ? 1 : 0 ) . '&amp;woo_bb=' . (!empty($_GET['woo_bb']) ? 1 : 0 ) . '&amp;im_ex_form=' . (!empty($_GET['imp_ex_form']) ? 1 : 0 ) . '&amp;imp_ex_topc=' . (!empty($_GET['imp_ex_topc']) ? 1 : 0 ) . '&amp;file_url=' . $this->file_url . '&amp;delimiter=' . $this->delimiter . '&amp;merge_empty_cells=' . $this->merge_empty_cells . '&amp;file_id=' . $this->id . '';
            $reset_action = esc_attr(wp_nonce_url($reset_action, 'import-upload'));
            echo '<h3>' . __('Columns are pre-selected using the Mapping file: "<b style="color:gray">' . $this->profile . '</b>".  <a href="' . $reset_action . '"> Delete</a> this mapping file.', 'hf_bb_import_export') . '</h3>';
            $saved_mapping = $mapping_from_db[0];
            $saved_evaluation = $mapping_from_db[1];
            //}	
        }
        $merge = (!empty($_GET['merge']) && $_GET['merge']) ? 1 : 0;
        $woo_bb = (!empty($_GET['woo_bb']) && $_GET['woo_bb']) ? 1 : 0;
        $imp_ex_topc = (!empty($_GET['imp_ex_topc']) && $_GET['imp_ex_topc']) ? 1 : 0;
        $imp_ex_form = (!empty($_GET['imp_ex_form']) && $_GET['imp_ex_form']) ? 1 : 0;
        include( 'views/html-hf-import-options.php' );
    }

    /**
     * The main controller for the actual import stage.
     */
    public function import() {
        global $wpdb;
        if(class_exists('WC'))
        {
           global $woocommerce;
        }
        wp_suspend_cache_invalidation(true);

        if (function_exists('WC')) {
            $this->log->add('csv-import', '---');
            $this->log->add('csv-import', __('Processing bbPress.', 'hf_bb_import_export'));
        } else {
            // $this->log = new WP_Logger();
        }
        foreach ($this->parsed_data as $key => &$item) {
            $product = $this->parser->parse_product_comment($item, 0);

            if (!is_wp_error($product))
                $this->process_product_comments($product);
            else
                $this->add_import_result('failed', $product->get_error_message(), 'Not parsed', json_encode($item), '-');

            unset($item, $product);
        }
        if (function_exists('WC')) {
            $this->log->add('csv-import', __('Finished processing.', 'hf_bb_import_export'));
        } else {
            // $this->log = new WP_Logger();
        }
        wp_suspend_cache_invalidation(false);
    }
 public function wp_hf_let_to_num( $size ) {
          $l   = substr( $size, -1 );
          $ret = substr( $size, 0, -1 );
          switch ( strtoupper( $l ) ) {
            case 'P':
              $ret *= 1024;
            case 'T':
              $ret *= 1024;
            case 'G':
              $ret *= 1024;
            case 'M':
              $ret *= 1024;
            case 'K':
              $ret *= 1024;
          }
          return $ret;
        }
    /**
     * Parses the CSV file and prepares us for the task of processing parsed data
     *
     * @param string $file Path to the CSV file for importing
     */
    public function import_start($file, $mapping, $start_pos, $end_pos, $eval_field) {


        if (function_exists('WC')) {

            $memory = size_format(woocommerce_let_to_num(ini_get('memory_limit')));
            $wp_memory = size_format(woocommerce_let_to_num(WP_MEMORY_LIMIT));

            $this->log->add('csv-import', '---[ New Import ] PHP Memory: ' . $memory . ', WP Memory: ' . $wp_memory);
            $this->log->add('csv-import', __('Parsing bbPress CSV.', 'hf_bb_import_export'));
        } else {
            $memory = size_format($this->wp_hf_let_to_num(ini_get('memory_limit')));
            $wp_memory = size_format($this->wp_hf_let_to_num(WP_MEMORY_LIMIT));
        }

        $this->parser = new HF_BB_CSV_Parser('product');

        list( $this->parsed_data, $this->raw_headers, $position ) = $this->parser->parse_data($file, $this->delimiter, $mapping, $start_pos, $end_pos, $eval_field);
        if (function_exists('WC')) {
            $this->log->add('csv-import', __('Finished parsing bbPress CSV.', 'hf_bb_import_export'));
        }

        unset($import_data);

        wp_defer_term_counting(true);
        wp_defer_comment_counting(true);

        return $position;
    }

    /**
     * Performs post-import cleanup of files and the cache
     */
    public function import_end() {

        foreach (get_taxonomies() as $tax) {
            delete_option("{$tax}_children");
            _get_term_hierarchy($tax);
        }

        wp_defer_term_counting(false);
        wp_defer_comment_counting(false);

        do_action('import_end');
    }

    public function product_id_not_exists($id) {
        global $wpdb;
        //   $query = "SELECT ID FROM $wpdb->posts WHERE ID = $id AND comment_status='open'";
        $posts_that_exist = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ID = %d AND comment_status='open'", $id));
        if (!$posts_that_exist) {
            return true;
        }
        return false;
    }

    /**
     * Handles the CSV upload and initial parsing of the file to prepare for
     * displaying author import options
     *
     * @return bool False if error uploading or invalid file, true otherwise
     */
    public function handle_upload() {
        if ($this->handle_ftp()) {
            return true;
        }
        if (empty($_POST['file_url'])) {

            $file = wp_import_handle_upload();

            if (isset($file['error'])) {
                echo '<p><strong>' . __('Sorry, there has been an error.', 'hf_bb_import_export') . '</strong><br />';
                echo esc_html($file['error']) . '</p>';
                return false;
            }

            $this->id = (int) $file['id'];
            return true;
        } else {

            if (file_exists(ABSPATH . $_POST['file_url'])) {

                $this->file_url = esc_attr($_POST['file_url']);
                return true;
            } else {

                echo '<p><strong>' . __('Sorry, there has been an error.', 'hf_bb_import_export') . '</strong></p>';
                return false;
            }
        }

        return false;
    }

    public function product_comment_exists($id) {
        global $wpdb;
        //   $query = "SELECT ID FROM $wpdb->posts WHERE ID = $id ";
        $posts_that_exist = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ID =%d ", $id));
        if ($posts_that_exist) {
            foreach ($posts_that_exist as $post_exists) {
                return true;
            }
        }

        return false;
    }

    public function get_last_comment_id() {
        global $wpdb;
        $query = "SELECT MAX(ID) FROM $wpdb->posts";
        $results = $wpdb->get_var($query);
        return $results + 1;
    }

    /**
     * Create new posts based on import information
     */
    public function process_product_comments($post) {
        $processing_product_id = absint($post['ID']);
        $merging = !empty($post['merging']);

        $woo_bb_data = !empty($post['woo_bb_data']) ? true : false;
        $imp_ex_topic_data = !empty($post['imp_ex_topic_data']);
        $imp_ex_forum_data = !empty($post['imp_ex_forum_data']);

        $comment_txt = ( $post['comment_content'] ) ? $post['comment_content'] : 'Empty';
        $timestamp = time();
        $datum = date("Y-m-d H:i:s", $timestamp);
        if (!empty($post['post_title'])) {
            $processing_product_title = $post['post_title'];
        }
        if (!empty($processing_product_id) && isset($this->processed_posts[$processing_product_id])) {
            $this->add_import_result('skipped', __('bbPress Data already processed', 'hf_bb_import_export'), $processing_product_id, $comment_txt);
            if (function_exists('WC')) {
                $this->log->add('csv-import', __('> Post ID already processed. Skipping.', 'hf_bb_import_export'), true);
            }
            unset($post);
            return;
        }
        if (!empty($post['post_status']) && $post['post_status'] == 'auto-draft') {
            $this->add_import_result('skipped', __('Skipping auto-draft', 'hf_bb_import_export'), $processing_product_id, $comment_txt);
            if (function_exists('WC')) {
                $this->log->add('csv-import', __('> Skipping auto-draft.', 'hf_bb_import_export'), true);
            }
            unset($post);
            return;
        }
        // Check if post exists when importing
        $is_post_exist_in_db = $this->product_comment_exists($processing_product_id);
        if (!$merging) {
            if ($is_post_exist_in_db) {
                $usr_msg = 'Already exits';
                $this->add_import_result('skipped', __($usr_msg, 'hf_bb_import_export'), $processing_product_id, $comment_txt);
                if (function_exists('WC')) {
                    $this->log->add('csv-import', sprintf(__('> &#8220;%s&#8221;' . $usr_msg, 'hf_bb_import_export'), esc_html($processing_product_title)), true);
                }
                unset($post);
                return;
            }
        }
        // meerging the data
        if ($merging && !empty($is_post_exist_in_db)) {

            // Only merge fields which are set
            $post_id = $processing_product_id;
            if (function_exists('WC')) {
                $this->log->add('csv-import', sprintf(__('> Merging post ID %s.', 'hf_bb_import_export'), $post_id), true);
            }

            if (!empty($post['post_author'])) {
                $postdata['post_author'] = $post['post_author'];
            }
            if (!empty($post['post_date'])) {
                $postdata['post_date'] = date("Y-m-d H:i:s", strtotime($post['post_date']));
            }
            if (!empty($post['post_date_gmt'])) {
                $postdata['post_date_gmt'] = date("Y-m-d H:i:s", strtotime($post['post_date_gmt']));
            }
            if (!empty($post['post_content'])) {
                $postdata['post_content'] = $post['post_content'];
            }
            if (!empty($post['post_title'])) {
                $postdata['post_title'] = $post['post_title'];
            }
            if (!empty($post['post_status'])) {
                $postdata['post_status'] = $post['post_status'];
            }
            if (!empty($post['comment_status'])) {
                $postdata['comment_status'] = $post['comment_status'];
            }
            if (!empty($post['ping_status'])) {
                $postdata['ping_status'] = $post['ping_status'];
            }
            if (!empty($post['post_password'])) {
                $postdata['post_password'] = $post['post_password'];
            }
            if (!empty($post['post_name'])) {
                $postdata['post_name'] = $post['post_name'];
            }
            if (!empty($post['post_parent'])) {
                $postdata['post_parent'] = $post['post_parent'];
            }
            if (!empty($post['guid'])) {
                $postdata['guid'] = $post['guid'];
            }
            if (!empty($post['menu_order'])) {
                $postdata['menu_order'] = $post['menu_order'];
            }
            if (!empty($post['post_type'])) {
                $postdata['post_type'] = $post['post_type'];
            }
            if (!empty($post['post_mime_type'])) {
                $postdata['post_mime_type'] = $post['post_mime_type'];
            }
            if (!empty($post['comment_count'])) {
                $postdata['comment_count'] = $post['comment_count'];
            }
            if (sizeof($postdata) > 1) {
                global $wpdb;
                $result = $wpdb->update('wp_posts', $postdata, array('ID' => $post_id));
            }
        } else {
            $merging = FALSE;
            //check child data
            // Insert product
            if (function_exists('WC')) {
                $this->log->add('csv-import', sprintf(__('> Inserting %s', 'hf_bb_import_export'), esc_html($processing_product_id)), true);
            }
        }
        //Change for wooDiscuz migration
        if ($woo_bb_data) {
            $final_forum = '';
            $final_topic = '';
            $woo_topic_id = '';
            $woo_forum_meta_enable = '0';
            $woo_topic_enable = '0';
            $woo_reply_enable = '0';

            if ($post['comment_type'] != 'woodiscuz') {
                $usr_msg = 'This Not WooDiscuz Comment';
                $this->add_import_result('skipped', __($usr_msg, 'hf_bb_import_export'), $processing_product_id, $comment_txt);
                if (function_exists('WC')) {
                    $this->log->add('csv-import', sprintf(__('> &#8220;%s&#8221;' . $usr_msg, 'hf_bb_import_export'), esc_html($processing_product_title)), true);
                }
                unset($post);
                return;
            }

            //get current time
            //get status 

            if ($_GET['woo_stat'] != 1) {
                $woo_stat = 'closed';
            } else {
                $woo_stat = 'open';
            }

            if ($_GET['woo_stat1'] != 1) {
                $woo_stat1 = 'closed';
            } else {
                $woo_stat1 = 'open';
            }

            if ($_GET['woo_conv'] === '1') {
                // create forum with auto topic
                if (!empty($post['comment_post_ID'])) {
                    global $wpdb;
                    if (!isset($_SESSION['insert_forum'][$post['comment_post_ID']])) {
                        //craete new forum and topic
                        $_SESSION['insert_forum'][$post['comment_post_ID']] = '1';

                        $post_date = ( $post['comment_date'] ) ? date('Y-m-d H:i:s', strtotime($post['comment_date'])) : '';
                        $post_date_gmt = ( $post['comment_date_gmt'] ) ? date('Y-m-d H:i:s', strtotime($post['comment_date_gmt'])) : '';

                        $woo_forum_meta_enable = '1';


                        $query = "SELECT count(*) FROM $wpdb->posts WHERE ID= " . $post['comment_post_ID'] . " AND post_type='product'";
                        $count_results = $wpdb->get_var($query);

                        if ($count_results != '0') {
                            $query = "SELECT post_title FROM $wpdb->posts WHERE ID= " . $post['comment_post_ID'] . " AND post_type='product'";
                            $results = $wpdb->get_var($query);
                            $full_results = $results;
                            $results = strlen($results) > 50 ? substr($results, 0, 50) . "..." : $results;
                            $post_name = strlen($results) > 50 ? substr($results, 0, 50) : $results;
                        } else {
                            $results = 'New Forum';
                            $full_results = $results;
                            $post_name = $results;
                        }
                        $actual_link = site_url() . "/?post_type=forum&#038;p=" . $this->get_last_comment_id();
                        $query = "SELECT count(*) FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                        $author_count = $wpdb->get_var($query);
                        if ($author_count != '0') {
                            $query = "SELECT ID FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                            $author = $wpdb->get_var($query);

                            $auth_data = '0';
                        } else {
                            $author = '0';
                            $auth_data = '1';
                            $auth_email = $post['comment_author_email'];
                            $auth_name = $post['comment_author'];
                        }

                        $postdata = array(
                            'ID' => $processing_product_id,
                            'post_author' => $author,
                            'post_date' => $post_date,
                            'post_date_gmt' => $post_date_gmt,
                            'post_content' => $full_results,
                            'post_title' => $results,
                            'post_status' => 'publish',
                            'comment_status' => $woo_stat,
                            'post_modified' => $datum,
                            'post_modified_gmt' => $datum,
                            'ping_status' => $woo_stat1,
                            'post_password' => '',
                            'post_name' => sanitize_title($post_name),
                            'post_parent' => '0',
                            'guid' => $actual_link,
                            'menu_order' => '',
                            'post_type' => 'forum',
                            'post_mime_type' => '',
                            'comment_count' => '0',
                        );
                        $woo_post_id = wp_insert_post($postdata, true);
                        $final_forum = $woo_post_id;

                        $new_topic = strlen($post['comment_content']) > 50 ? substr($post['comment_content'], 0, 50) . "..." : $post['comment_content'];
                        $post_name = strlen($post['comment_content']) > 50 ? substr($post['comment_content'], 0, 50) : $post['comment_content'];
                        $_SESSION['forum_data']['comment_post_ID'] = $woo_post_id;
                        $actual_link = site_url() . "/?post_type=topic&#038;p=" . $this->get_last_comment_id();

                        $postdata = array(
                            'ID' => $processing_product_id,
                            'post_author' => $author,
                            'post_date' => $post_date,
                            'post_date_gmt' => $post_date_gmt,
                            'post_content' => $post['comment_content'],
                            'post_title' => $new_topic,
                            'post_status' => 'publish',
                            'comment_status' => $woo_stat,
                            'post_modified' => $datum,
                            'post_modified_gmt' => $datum,
                            'ping_status' => $woo_stat1,
                            'post_password' => '',
                            'post_name' => sanitize_title($post_name),
                            'post_parent' => $_SESSION['forum_data']['comment_post_ID'],
                            'guid' => $actual_link,
                            'menu_order' => '',
                            'post_type' => 'topic',
                            'post_mime_type' => '',
                            'comment_count' => '0',
                        );
                        $woo_topic_id = wp_insert_post($postdata, true);
                        $_SESSION['topic_data']['comment_post_ID'] = $woo_topic_id;
                        $woo_topic_enable = '1';
                        $_SESSION['insert_forum_id'][$post['comment_post_ID']] = $this->get_last_comment_id();
                    }
                } else {
                    $usr_msg = 'No Vaild Post Id Found';
                    $this->add_import_result('skipped', __($usr_msg, 'hf_bb_import_export'), $processing_product_id, $comment_txt);
                    if (function_exists('WC')) {
                        $this->log->add('csv-import', sprintf(__('> &#8220;%s&#8221;' . $usr_msg, 'hf_bb_import_export'), esc_html($processing_product_title)), true);
                    }
                    unset($post);
                    return;
                }
            } elseif ($_GET['woo_conv'] === '3') {
                global $wpdb;
                $final_forum = $_GET['woo_forum_1'];
                $_SESSION['forum_data']['comment_post_ID'] = $final_forum;
                $woo_forum_meta_enable = '1';
                if ($post['comment_parent'] === '0') {
                    $this->parent_data = $post['comment_parent'];
                    $_SESSION['new_topic_id'][$post['comment_alter_id']] = $this->get_last_comment_id();
                } else {
                    if (!empty($_SESSION['new_topic_id'][$post['comment_parent']])) {
                        $this->parent_data = $_SESSION['new_topic_id'][$post['comment_parent']];
                    } else {
                        $this->parent_data = $post['comment_parent'];
                    }
                    $_SESSION['new_topic_id'][$post['comment_alter_id']] = $this->get_last_comment_id();
                }
                $final_topic = 'New Topic -' . $datum;
                $data_value = !empty($post['comment_content']) ? $post['comment_content'] : $final_topic;

                $final_topic = strlen($data_value) > 50 ? substr($data_value, 0, 50) . "..." : $data_value;
                $final_post_name = strlen($data_value) > 50 ? substr($data_value, 0, 50) : $data_value;
                $actual_link = site_url() . "/?post_type=topic&#038;p=" . $this->get_last_comment_id();

                if ($_GET['woo_date_enable'] != 1) {
                    $post_date = ( $post['comment_date'] ) ? date('Y-m-d H:i:s', strtotime($post['comment_date'])) : '';
                    $post_date_gmt = ( $post['comment_date_gmt'] ) ? date('Y-m-d H:i:s', strtotime($post['comment_date_gmt'])) : '';
                } else {
                    $post_date = $datum;
                    $post_date_gmt = $datum;
                }
                $query = "SELECT count(*) FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                $author_count = $wpdb->get_var($query);
                if ($author_count != 0) {
                    $query = "SELECT ID FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                    $author = $wpdb->get_var($query);
                    $auth_data = '0';
                } else {
                    $author = '0';
                    $auth_data = '1';
                    $auth_email = $post['comment_author_email'];
                    $auth_name = $post['comment_author'];
                }
                $comment_date = $post['comment_date'];

                if ($this->parent_data != '0') {
                    $actual_link = site_url() . "/?post_type=reply&#038;p=" . $this->get_last_comment_id();
                    $postdata = array(
                        'ID' => $processing_product_id,
                        'post_author' => $author,
                        'post_date' => $post_date,
                        'post_date_gmt' => $post_date_gmt,
                        'post_content' => $post['comment_content'],
                        'post_title' => 'Reply No:' . $this->get_last_comment_id(),
                        'post_modified' => $datum,
                        'post_modified_gmt' => $datum,
                        'post_status' => 'publish',
                        'comment_status' => $woo_stat,
                        'ping_status' => $woo_stat1,
                        'post_password' => '',
                        'post_name' => sanitize_title($post_name),
                        'post_parent' => $this->parent_data,
                        'guid' => $actual_link,
                        'menu_order' => '',
                        'post_type' => 'reply',
                        'post_mime_type' => '',
                        'comment_count' => '0',
                    );
                    $woo_reply_enable = '1';
                    $post_id = wp_insert_post($postdata, true);
                } else {
                    $postdata = array(
                        'ID' => $processing_product_id,
                        'post_author' => $author,
                        'post_date' => $post_date,
                        'post_date_gmt' => $post_date_gmt,
                        'post_content' => $post['comment_content'],
                        'post_title' => $final_topic,
                        'post_modified' => $datum,
                        'post_modified_gmt' => $datum,
                        'post_status' => 'publish',
                        'comment_status' => $woo_stat,
                        'ping_status' => $woo_stat1,
                        'post_password' => '',
                        'post_name' => sanitize_title($final_post_name),
                        'post_parent' => $final_forum,
                        'guid' => $actual_link,
                        'menu_order' => '',
                        'post_type' => 'topic',
                        'post_mime_type' => '',
                        'comment_count' => '0',
                    );
                    $woo_topic_id = wp_insert_post($postdata, true);
                    $_SESSION['topic_data']['comment_post_ID'] = $woo_topic_id;
                    $woo_topic_enable = '1';
                    $post_id = $woo_topic_id;
                }
            } else {
                global $wpdb;
                $final_forum = $_GET['woo_forum_1'];
                $_SESSION['forum_data']['comment_post_ID'] = $final_forum;
               
                if (empty($_SESSION['insert_topic'][$post['comment_post_ID']])) {
                    $_SESSION['insert_topic'][$post['comment_post_ID']] = '1';
                    
                    $query = "SELECT count(*) FROM $wpdb->posts WHERE ID= '" . $post['comment_post_ID'] . "'";
                    $count_results = $wpdb->get_var($query);
                    
                    $final_topic = 'New Topic';
                    
                    if ($count_results != '0') {
                        $query = "SELECT post_title FROM $wpdb->posts WHERE ID='" . $post['comment_post_ID'] . "'";
                        $final_topic = $wpdb->get_var($query);
                    }

                    $final_topic = strlen($final_topic) > 50 ? substr($final_topic, 0, 50) . "..." : $final_topic;
                    $final_post_name = strlen($final_topic) > 50 ? substr($final_topic, 0, 50) : $final_topic;
                   
                    $actual_link = site_url() . "/?post_type=topic&#038;p=" . $this->get_last_comment_id();

                        $post_date = ( $post['comment_date'] ) ? date('Y-m-d H:i:s', strtotime($post['comment_date'])) : '';
                        $post_date_gmt = ( $post['comment_date_gmt'] ) ? date('Y-m-d H:i:s', strtotime($post['comment_date_gmt'])) : '';

                    $query = "SELECT count(*) FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                    $author_count = $wpdb->get_var($query);
                    if ($author_count != 0) {
                        $query = "SELECT ID FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                        $author = $wpdb->get_var($query);
                        $auth_data = '0';
                    } else {
                        $author = '0';
                        $auth_data = '1';
                        $auth_email = $post['comment_author_email'];
                        $auth_name = $post['comment_author'];
                    }
                    $postdata = array(
                        'ID' => $processing_product_id,
                        'post_author' => $author,
                        'post_date' => $post_date,
                        'post_date_gmt' => $post_date_gmt,
                        'post_content' => $final_topic,
                        'post_title' => $final_topic,
                        'post_modified' => $datum,
                        'post_modified_gmt' => $datum,
                        'post_status' => 'publish',
                        'comment_status' => $woo_stat,
                        'ping_status' => $woo_stat1,
                        'post_password' => '',
                        'post_name' => sanitize_title($final_post_name),
                        'post_parent' => $final_forum,
                        'guid' => $actual_link,
                        'menu_order' => '',
                        'post_type' => 'topic',
                        'post_mime_type' => '',
                        'comment_count' => '0',
                    );
                    $woo_topic_id = wp_insert_post($postdata, true);
                    $_SESSION['topic_data']['comment_post_ID'] = $woo_topic_id;
                    $woo_topic_enable = '1';
                }
            }

            if ($_GET['woo_conv'] != '3') {

                $actual_link = site_url() . "/?post_type=reply&#038;p=" . $this->get_last_comment_id();

                if ($_GET['woo_forum_1'] != '0') {
                    if ($_SESSION['insert_topic'][$post['comment_post_ID']] === '1') {
                        $_SESSION['insert_topic'][$post['comment_post_ID']] = '2';
                    } 
                    else if ($_SESSION['insert_topic'][$post['comment_post_ID']] === '2') {
                        $query = "SELECT count(*) FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                        $author_count = $wpdb->get_var($query);
                        if ($author_count != 0) {
                            $query = "SELECT ID FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                            $author = $wpdb->get_var($query);
                            $auth_data = '0';
                        } else {
                            $author = '0';

                            $auth_data = '1';

                            $auth_email = $post['comment_author_email'];
                            $auth_name = $post['comment_author'];
                        }

                        $comment_date = $post['comment_date'];
                        $postdata = array(
                            'ID' => $processing_product_id,
                            'post_author' => $author,
                            'post_date' => $post_date,
                            'post_date_gmt' => $post_date_gmt,
                            'post_content' => $post['comment_content'],
                            'post_title' => 'Reply No:' . $this->get_last_comment_id(),
                            'post_modified' => $datum,
                            'post_modified_gmt' => $datum,
                            'post_status' => 'publish',
                            'comment_status' => $woo_stat,
                            'ping_status' => $woo_stat1,
                            'post_password' => '',
                            'post_name' => $this->get_last_comment_id(),
                            'post_parent' => $_SESSION['topic_data']['comment_post_ID'],
                            'guid' => $actual_link,
                            'menu_order' => '',
                            'post_type' => 'reply',
                            'post_mime_type' => '',
                            'comment_count' => '0',
                        );
                        $woo_reply_enable = '1';
                    }
                } else {
                     if ($_SESSION['insert_forum'][$post['comment_post_ID']] === '1') {
                        $_SESSION['insert_forum'][$post['comment_post_ID']] = '2';
                    } 
                    else if ($_SESSION['insert_forum'][$post['comment_post_ID']] === '2') {
                        $query = "SELECT count(*) FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                        $author_count = $wpdb->get_var($query);
                        if ($author_count != 0) {
                            $query = "SELECT ID FROM $wpdb->users WHERE user_email= '" . $post['comment_author_email'] . "'";
                            $author = $wpdb->get_var($query);
                            $auth_data = '0';
                        } else {
                            $author = '0';

                            $auth_data = '1';

                            $auth_email = $post['comment_author_email'];
                            $auth_name = $post['comment_author'];
                        }

                        $comment_date = $post['comment_date'];
                        $postdata = array(
                            'ID' => $processing_product_id,
                            'post_author' => $author,
                            'post_date' => $post_date,
                            'post_date_gmt' => $post_date_gmt,
                            'post_content' => $post['comment_content'],
                            'post_title' => 'Reply No:' . $this->get_last_comment_id(),
                            'post_modified' => $datum,
                            'post_modified_gmt' => $datum,
                            'post_status' => 'publish',
                            'comment_status' => $woo_stat,
                            'ping_status' => $woo_stat1,
                            'post_password' => '',
                            'post_name' => $this->get_last_comment_id(),
                            'post_parent' => $_SESSION['topic_data']['comment_post_ID'],
                            'guid' => $actual_link,
                            'menu_order' => '',
                            'post_type' => 'reply',
                            'post_mime_type' => '',
                            'comment_count' => '0',
                        );
                        $woo_reply_enable = '1';
                    }
                }
            }
        } elseif ($imp_ex_forum_data) {
            if ($post['post_parent'] === '0') {
                $this->parent_data = $post['post_parent'];
                $_SESSION['new_id'][$post['post_alter_id']] = $this->get_last_comment_id();
            } else {
                if (!empty($_SESSION['new_id'][$post['post_parent']])) {
                    $this->parent_data = $_SESSION['new_id'][$post['post_parent']];
                } else {
                    $this->parent_data = $post['post_parent'];
                }
                $_SESSION['new_id'][$post['post_alter_id']] = $this->get_last_comment_id();
            }
            // change it for server
            if ($post['post_type'] === 'forum') {
                $usr_msg = 'This Forum Skipped';
                $this->add_import_result('skipped', __($usr_msg, 'hf_bb_import_export'), $processing_product_id, $comment_txt);
                if (function_exists('WC')) {
                    $this->log->add('csv-import', sprintf(__('> &#8220;%s&#8221;' . $usr_msg, 'hf_bb_import_export'), esc_html($processing_product_title)), true);
                }
                unset($post);
                return;
            } else if ($post['post_type'] === 'topic') {
                $this->parent_data = $_GET['imp_ex_forum'];
                $post_type = 'topic';
            } else {
                $post_type = 'reply';
            }

            $actual_link = site_url() . "/?post_type=$post_type&#038;p=" . $this->get_last_comment_id();
            $comment_date = $post['post_date'];
            if (email_exists($post['eh_user_email'])) {
                $post_author = email_exists($post['eh_user_email']);
            } else {
                if ($post['post_type'] === 'forum') {
                    $post_author = '1';
                } else {
                    $post_author = '0';
                }
                $auth_data = '1';
                $auth_email = $post['eh_user_email'];
                $auth_name = $post['eh_user_name'];
            }
            $postdata = array(
                'ID' => $processing_product_id,
                'post_author' => $post_author,
                'post_date' => ( $post['post_date'] ) ? date('Y-m-d H:i:s', strtotime($post['post_date'])) : '',
                'post_date_gmt' => ( $post['post_date_gmt'] ) ? date('Y-m-d H:i:s', strtotime($post['post_date_gmt'])) : '',
                'post_content' => $post['post_content'],
                'post_title' => $post['post_title'],
                'post_status' => $post['post_status'],
                'post_modified' => $datum,
                'post_modified_gmt' => $datum,
                'comment_status' => $post['comment_status'],
                'ping_status' => $post['ping_status'],
                'post_password' => $post['post_password'],
                'post_name' => $post['post_name'],
                'post_parent' => $this->parent_data,
                'guid' => $actual_link,
                'menu_order' => $post['menu_order'],
                'post_type' => $post['post_type'],
                'post_mime_type' => $post['post_mime_type'],
                'comment_count' => $post['comment_count'],
            );
        } elseif ($imp_ex_topic_data) {

            if ($post['post_type'] === 'forum') {
                $usr_msg = 'This Forum Skipped';
                $this->add_import_result('skipped', __($usr_msg, 'hf_bb_import_export'), $processing_product_id, $comment_txt);
                if (function_exists('WC')) {
                    $this->log->add('csv-import', sprintf(__('> &#8220;%s&#8221;' . $usr_msg, 'hf_bb_import_export'), esc_html($processing_product_title)), true);
                }
                unset($post);
                return;
            } else if ($post['post_type'] === 'topic') {
                $usr_msg = 'This Topic Skipped';
                $this->add_import_result('skipped', __($usr_msg, 'hf_bb_import_export'), $processing_product_id, $comment_txt);
                if (function_exists('WC')) {
                    $this->log->add('csv-import', sprintf(__('> &#8220;%s&#8221;' . $usr_msg, 'hf_bb_import_export'), esc_html($processing_product_title)), true);
                }
                unset($post);
                return;
            } else {
                $post_type = 'reply';
            }

            $actual_link = site_url() . "/?post_type=$post_type&#038;p=" . $this->get_last_comment_id();
            $comment_date = $post['post_date'];

            $this->parent_data = $_GET['imp_ex_topic'];

            if (email_exists($post['eh_user_email'])) {
                $post_author = email_exists($post['eh_user_email']);
            } else {
                if ($post['post_type'] === 'forum') {
                    $post_author = '1';
                } else {
                    $post_author = '0';
                }
                $auth_data = '1';
                $auth_email = $post['eh_user_email'];
                $auth_name = $post['eh_user_name'];
            }
            $postdata = array(
                'ID' => $processing_product_id,
                'post_author' => $post_author,
                'post_date' => ( $post['post_date'] ) ? date('Y-m-d H:i:s', strtotime($post['post_date'])) : '',
                'post_date_gmt' => ( $post['post_date_gmt'] ) ? date('Y-m-d H:i:s', strtotime($post['post_date_gmt'])) : '',
                'post_content' => $post['post_content'],
                'post_title' => $post['post_title'],
                'post_status' => $post['post_status'],
                'post_modified' => $datum,
                'post_modified_gmt' => $datum,
                'comment_status' => $post['comment_status'],
                'ping_status' => $post['ping_status'],
                'post_password' => $post['post_password'],
                'post_name' => $post['post_name'],
                'post_parent' => $this->parent_data,
                'guid' => $actual_link,
                'menu_order' => $post['menu_order'],
                'post_type' => $post['post_type'],
                'post_mime_type' => $post['post_mime_type'],
                'comment_count' => $post['comment_count'],
            );
        } else {

            if ($post['post_parent'] === '0') {
                $this->parent_data = $post['post_parent'];
                $_SESSION['new_id'][$post['post_alter_id']] = $this->get_last_comment_id();
            } else {
                if (!empty($_SESSION['new_id'][$post['post_parent']])) {
                    $this->parent_data = $_SESSION['new_id'][$post['post_parent']];
                } else {
                    $this->parent_data = $post['post_parent'];
                }
                $_SESSION['new_id'][$post['post_alter_id']] = $this->get_last_comment_id();
            }
            // change it for server
            if ($post['post_type'] === 'forum') {
                $post_type = 'forum';
            } else if ($post['post_type'] === 'topic') {
                $post_type = 'topic';
            } else {
                $post_type = 'reply';
            }

            $actual_link = site_url() . "/?post_type=$post_type&#038;p=" . $this->get_last_comment_id();
            $comment_date = $post['post_date'];
            if (email_exists($post['eh_user_email'])) {
                $post_author = email_exists($post['eh_user_email']);
            } else {
                if ($post['post_type'] === 'forum') {
                    $post_author = '1';
                } else {
                    $post_author = '0';
                }
                $auth_data = '1';
                $auth_email = $post['eh_user_email'];
                $auth_name = $post['eh_user_name'];
            }
            $postdata = array(
                'ID' => $processing_product_id,
                'post_author' => $post_author,
                'post_date' => ( $post['post_date'] ) ? date('Y-m-d H:i:s', strtotime($post['post_date'])) : '',
                'post_date_gmt' => ( $post['post_date_gmt'] ) ? date('Y-m-d H:i:s', strtotime($post['post_date_gmt'])) : '',
                'post_content' => $post['post_content'],
                'post_title' => $post['post_title'],
                'post_status' => $post['post_status'],
                'post_modified' => $datum,
                'post_modified_gmt' => $datum,
                'comment_status' => $post['comment_status'],
                'ping_status' => $post['ping_status'],
                'post_password' => $post['post_password'],
                'post_name' => $post['post_name'],
                'post_parent' => $this->parent_data,
                'guid' => $actual_link,
                'menu_order' => $post['menu_order'],
                'post_type' => $post['post_type'],
                'post_mime_type' => $post['post_mime_type'],
                'comment_count' => $post['comment_count'],
            );
        }


        if ($_GET['woo_conv'] != '3') {

            $post_id = wp_insert_post($postdata, true);
        }
        global $wpdb;
        if (!$merging) {
            if ($post['post_type'] === 'forum') {

                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_last_active_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_last_reply_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_last_topic_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_topic_count_hidden', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_total_topic_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_topic_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_total_reply_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_reply_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_forum_subforum_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_last_active_time', 'meta_value' => $comment_date));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_edit_lock', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_edit_last', 'meta_value' => '0'));
            } if ($post['post_type'] === 'topic') {
                $query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='_bbp_total_topic_count' AND Post_id=" . $this->parent_data;
                $new_results = $wpdb->get_var($query);
                $new_results = $new_results + 1;

                update_post_meta($this->parent_data, '_bbp_total_topic_count', $new_results);
                //   update_post_meta($this->parent_data, '_bbp_topic_count', $new_results);
                update_post_meta($this->parent_data, '_bbp_last_active_time', $comment_date);
                update_post_meta($this->parent_data, '_bbp_last_topic_id', $post_id);
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_voice_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_reply_count_hidden', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_reply_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_last_active_time', 'meta_value' => $comment_date));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_last_active_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_last_reply_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_author_ip', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_topic_id', 'meta_value' => $post_id));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_forum_id', 'meta_value' => $this->parent_data));

                if ($auth_data != '0') {
                    $auth_data = '0';
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_website', 'meta_value' => ''));
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_email', 'meta_value' => $auth_email));
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_name', 'meta_value' => $auth_name));
                }
            }
            if ($post['post_type'] === 'reply') {

                $query = "SELECT post_parent FROM $wpdb->posts WHERE ID=" . $this->parent_data;
                $results = $wpdb->get_var($query);
                $query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='_bbp_total_reply_count' AND Post_id=" . $results;
                $new_results = $wpdb->get_var($query);
                $new_results = $new_results + 1;
                $query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='_bbp_reply_count' AND Post_id=" . $this->parent_data;
                $new_results1 = $wpdb->get_var($query);
                $new_results1 = $new_results1 + 1;

                update_post_meta($results, '_bbp_last_active_time', $comment_date);
                update_post_meta($results, '_bbp_total_reply_count', $new_results);
                //  update_post_meta($results, '_bbp_reply_count', $new_results);
                update_post_meta($results, '_bbp_last_reply_id', $post_id);

                update_post_meta($this->parent_data, '_bbp_reply_count', $new_results1);
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_author_ip', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_reply_to', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_topic_id', 'meta_value' => $this->parent_data));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_forum_id', 'meta_value' => $results));

                if ($auth_data != '0') {

                    $auth_data = '0';
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_website', 'meta_value' => ''));
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_email', 'meta_value' => $auth_email));
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_name', 'meta_value' => $auth_name));
                }
            }

            if ($woo_bb_data && $woo_forum_meta_enable != '0') {
                $woo_forum_meta_enable = '0';

                $forum_id = $_SESSION['forum_data']['comment_post_ID'];

                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_last_active_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_last_reply_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_last_topic_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_topic_count_hidden', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_total_topic_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_topic_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_total_reply_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_reply_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_forum_subforum_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_bbp_last_active_time', 'meta_value' => $comment_date));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_edit_lock', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $forum_id, 'meta_key' => '_edit_last', 'meta_value' => '0'));
            }
            if ($woo_bb_data && $woo_topic_enable != '0') {
                $woo_topic_enable = '0';

                $qu_to_id = (int) $_SESSION['forum_data']['comment_post_ID'];
                $topic_id = (int) $_SESSION['topic_data']['comment_post_ID'];


                $query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='_bbp_total_topic_count' AND Post_id=" . $qu_to_id;
                $new_results = $wpdb->get_var($query);
                $new_results = $new_results + 1;

                update_post_meta($qu_to_id, '_bbp_total_topic_count', $new_results);
                //   update_post_meta($this->parent_data, '_bbp_topic_count', $new_results);
                update_post_meta($qu_to_id, '_bbp_last_active_time', $comment_date);
                update_post_meta($qu_to_id, '_bbp_last_topic_id', $topic_id);

                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_topic_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_forum_id', 'meta_value' => $qu_to_id));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_voice_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_reply_count_hidden', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_reply_count', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_last_active_time', 'meta_value' => $comment_date));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_last_active_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_last_reply_id', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_author_ip', 'meta_value' => '0'));

                if ($auth_data != '0') {
                    $auth_data = '0';
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_anonymous_website', 'meta_value' => ''));
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_anonymous_email', 'meta_value' => $auth_email));
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $topic_id, 'meta_key' => '_bbp_anonymous_name', 'meta_value' => $auth_name));
                }
            }
            if ($woo_bb_data && $woo_reply_enable != '0') {
                $woo_reply_enable = '0';

                $qu_f_id = (int) $_SESSION['forum_data']['comment_post_ID'];

                if ($_GET['woo_conv'] === '3') {
                    $qu_t_id = (int) $this->parent_data;
                } else {

                    $qu_t_id = (int) $_SESSION['topic_data']['comment_post_ID'];
                }

                $query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='_bbp_total_reply_count' AND Post_id=" . $qu_f_id;
                $new_results = $wpdb->get_var($query);
                $new_results = $new_results + 1;
                $query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='_bbp_reply_count' AND Post_id=" . $qu_t_id;
                $new_results1 = $wpdb->get_var($query);
                $new_results1 = $new_results1 + 1;

                update_post_meta($qu_f_id, '_bbp_last_active_time', $comment_date);
                update_post_meta($qu_f_id, '_bbp_total_reply_count', $new_results);
                //  update_post_meta($results, '_bbp_reply_count', $new_results);
                update_post_meta($qu_f_id, '_bbp_last_reply_id', $post_id);
                update_post_meta($qu_t_id, '_bbp_reply_count', $new_results1);

                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_author_ip', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_reply_to', 'meta_value' => '0'));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_topic_id', 'meta_value' => $qu_t_id));
                $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_forum_id', 'meta_value' => $qu_f_id));
                if ($auth_data != '0') {
                    $auth_data = '0';
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_website', 'meta_value' => ''));
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_email', 'meta_value' => $auth_email));
                    $wpdb->insert($wpdb->postmeta, array('post_id' => $post_id, 'meta_key' => '_bbp_anonymous_name', 'meta_value' => $auth_name));
                }
            }
        }

        //$new_Id.push($post_id);
        if (function_exists('WC')) {
            $this->log->add('csv-import', sprintf(__($_SESSION['new_id'][$post['post_alter_id']] . 'hi'), esc_html($processing_product_title)));
        }
        if (is_wp_error($post_id)) {

            $this->add_import_result('failed', __('Failed to import', 'hf_bb_import_export'), $processing_product_id);
            if (function_exists('WC')) {
                $this->log->add('csv-import', sprintf(__('Failed to import &#8220;%s&#8221;', 'hf_bb_import_export'), esc_html($processing_product_title)));
            }
            unset($post);
            return;
        } else {
            if (function_exists('WC')) {
                $this->log->add('csv-import', sprintf(__('> Inserted - post ID is %s.', 'hf_bb_import_export'), $post_id));
            }
        }


        unset($postdata);
        // map pre-import ID to local ID
        if (empty($processing_product_id)) {
            $processing_product_id = (int) $post_id;
        }
        $this->processed_posts[intval($processing_product_id)] = (int) $post_id;
        /**
          if ( ! empty( $post['postmeta'] ) && is_array( $post['postmeta'] ) ) {
          update_comment_meta( $post_id, 'rating',  $post['postmeta'][0]['value']  );
          update_comment_meta( $post_id, 'verified',  $post['postmeta'][1]['value']  );
          update_comment_meta( $post_id, 'title',  $post['postmeta'][2]['value']  );
          }
         * 
         */
        if ($merging) {
            $this->add_import_result('merged', 'Merge successful', $post_id, $comment_txt);
            if (function_exists('WC')) {
                $this->log->add('csv-import', sprintf(__('> Finished merging post ID %s.', 'hf_bb_import_export'), $post_id));
            }
        } else {

            $this->add_import_result('imported', 'Import successful', $post_id, $comment_txt);
            if (function_exists('WC')) {
                $this->log->add('csv-import', sprintf(__('> Finished importing post ID %s.', 'hf_bb_import_export'), $post_id));
            }
        }
        unset($post);
    }

    /**
     * Log a row's import status
     */
    protected function add_import_result($status, $reason, $post_id = '', $cmd_title) {
        $this->import_results[] = array(
            'post_id' => $post_id,
            'status' => $status,
            'reason' => $reason,
            'comment_link' => $this->get_ftr_link($post_id, '0'),
            'cmd_title' => $this->get_ftr_link($post_id, '1'),
        );
    }

    public function get_ftr_link($id, $num) {
        global $wpdb;

        if ($num != '0') {
            $query = "SELECT post_type FROM $wpdb->posts WHERE ID=" . $id;
            $results = $wpdb->get_var($query);
            return ucfirst($results);
        } else {
            $query = "SELECT guid FROM $wpdb->posts WHERE ID=" . $id;
            $results = $wpdb->get_var($query);
            return $results;
        }
    }

    /**
     * Attempt to download a remote file attachment
     */
    public function fetch_remote_file($url, $post) {

        // extract the file name and extension from the url
        $file_name = basename(current(explode('?', $url)));
        $wp_filetype = wp_check_filetype($file_name, null);
        $parsed_url = @parse_url($url);

        // Check parsed URL
        if (!$parsed_url || !is_array($parsed_url))
            return new WP_Error('import_file_error', 'Invalid URL');
        // Ensure url is valid
        $url = str_replace(" ", '%20', $url);

        // Get the file
        $response = wp_remote_get($url, array(
            'timeout' => 10
        ));

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200)
            return new WP_Error('import_file_error', 'Error getting remote image');
        // Ensure we have a file name and type
        if (!$wp_filetype['type']) {

            $headers = wp_remote_retrieve_headers($response);

            if (isset($headers['content-disposition']) && strstr($headers['content-disposition'], 'filename=')) {

                $disposition = end(explode('filename=', $headers['content-disposition']));
                $disposition = sanitize_file_name($disposition);
                $file_name = $disposition;
            } elseif (isset($headers['content-type']) && strstr($headers['content-type'], 'image/')) {

                $file_name = 'image.' . str_replace('image/', '', $headers['content-type']);
            }
            unset($headers);
        }

        // Upload the file
        $upload = wp_upload_bits($file_name, '', wp_remote_retrieve_body($response));

        if ($upload['error'])
            return new WP_Error('upload_dir_error', $upload['error']);

        // Get filesize
        $filesize = filesize($upload['file']);

        if (0 == $filesize) {
            @unlink($upload['file']);
            unset($upload);
            return new WP_Error('import_file_error', __('Zero size file downloaded', 'hf_bb_import_export'));
        }

        unset($response);

        return $upload;
    }

    /**
     * Decide what the maximum file size for downloaded attachments is.
     * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
     *
     * @return int Maximum attachment file size to import
     */
    public function max_attachment_size() {
        return apply_filters('import_attachment_size_limit', 0);
    }

    private function handle_ftp() {
        $enable_ftp_ie = !empty($_POST['enable_ftp_ie']) ? true : false;
        if ($enable_ftp_ie == false) {
            $settings_in_db = get_option('hf_bb_importer_ftp', null);
            $settings_in_db['enable_ftp_ie'] = false;
            update_option('hf_bb_importer_ftp', $settings_in_db);
            return false;
        }

        $ftp_server = !empty($_POST['ftp_server']) ? $_POST['ftp_server'] : '';
        $ftp_server_path = !empty($_POST['ftp_server_path']) ? $_POST['ftp_server_path'] : '';
        $ftp_user = !empty($_POST['ftp_user']) ? $_POST['ftp_user'] : '';
        $ftp_password = !empty($_POST['ftp_password']) ? $_POST['ftp_password'] : '';
        $use_ftps = !empty($_POST['use_ftps']) ? true : false;


        $settings = array();
        $settings['ftp_server'] = $ftp_server;
        $settings['ftp_user'] = $ftp_user;
        $settings['ftp_password'] = $ftp_password;
        $settings['use_ftps'] = $use_ftps;
        $settings['enable_ftp_ie'] = $enable_ftp_ie;
        $settings['ftp_server_path'] = $ftp_server_path;


        $local_file = 'wp-content/plugins/import-export-for-bbpress/temp-import.csv';
        $server_file = $ftp_server_path;

        update_option('hf_bb_importer_ftp', $settings);

        $ftp_conn = $use_ftps ? ftp_ssl_connect($ftp_server) : ftp_connect($ftp_server);
        $error_message = "";
        $success = false;
        if ($ftp_conn == false) {
            $error_message = "There is connection problem\n";
        }

        if (empty($error_message)) {
            if (ftp_login($ftp_conn, $ftp_user, $ftp_password) == false) {
                $error_message = "Not able to login \n";
            }
        }
        if (empty($error_message)) {

            if (ftp_get($ftp_conn, ABSPATH . $local_file, $server_file, FTP_BINARY)) {
                $error_message = "";
                $success = true;
            } else {
                $error_message = "There was a problem\n";
            }
        }

        ftp_close($ftp_conn);

        if ($success) {
            $this->file_url = $local_file;
        } else {
            die($error_message);
        }

        return true;
    }

    // Display import page title
    public function header() {
        echo '<div class="wrap"><div class="icon32" id="icon-woocommerce-importer"><br></div>';
        echo '<h2>' . ( empty($_GET['merge']) ? __('Import', 'hf_bb_import_export') : __('Import - Merge bbPress CSV', 'hf_bb_import_export') ), ( empty($_GET['woo_bb']) ? __('', 'hf_bb_import_export') : __(' - Convert WooDiscuz Comments to bbPress', 'hf_bb_import_export') ) . '</h2>';
    }

    // Close div.wrap
    public function footer() {
        echo '</div>';
        add_action('wp_logout', array($this,'hf_bb_im_ex_myEndSession'));
        add_action('wp_login', array($this,'hf_bb_im_ex_myEndSession'));
    }

    /**
     * Display introductory text and file upload form
     */
    public function greet() {
        $action = 'admin.php?import=bb_csv&amp;step=1&amp;merge=' . (!empty($_GET['merge']) ? 1 : 0 ) . '&amp;woo_bb=' . (!empty($_GET['woo_bb']) ? 1 : 0 ) . '&amp;imp_ex_form=' . (!empty($_GET['imp_ex_form']) ? 1 : 0 ) . '&amp;imp_ex_topc=' . (!empty($_GET['imp_ex_topc']) ? 1 : 0 );
        $bytes = apply_filters('import_upload_size_limit', wp_max_upload_size());
        $size = size_format($bytes);
        $upload_dir = wp_upload_dir();
        $ftp_settings = get_option('hf_bb_importer_ftp');
        include( 'views/html-hf-import-greeting.php' );
    }

    /**
     * Added to http_request_timeout filter to force timeout at 60 seconds during import
     * @return int 60
     */
    public function bump_request_timeout($val) {
        return 60;
    }

}

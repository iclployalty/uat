<form action="<?php echo admin_url('admin.php?import=' . $this->import_page . '&step=2&merge=' . $merge . '&woo_bb=' . $woo_bb . '&imp_ex_form=' . $imp_ex_form . '&imp_ex_topc=' . $imp_ex_topc); ?>" method="post">
    <style>
        /* Tooltip container */
        .tooltip {
            position: relative;
            display: inline-block;
            }

        /* Tooltip text */
        .tooltip .tooltiptext {
            visibility: hidden;
            background-color: black;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;
            opacity: 0.65;

            /* Position the tooltip text - see examples below! */
            position: absolute;
            z-index: 1;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip:hover .tooltiptext {
            visibility: visible;
        }
   
    </style>

    <?php wp_nonce_field('import-options'); ?>
    <input type="hidden" name="import_id" value="<?php echo $this->id; ?>" />
    <?php if ($this->file_url_import_enabled) : ?>
        <input type="hidden" name="import_url" value="<?php echo $this->file_url; ?>" />
    <?php endif; ?>
    <?php
    if ($_GET['woo_bb'] === '1') {
        $map_div = 'style="display:block"';
        $bb_ex_div = 'style="display:none"';
        $woo_div = 'style="display:block"';
        $bb_ext_div = 'style="display:none"';
        $mapping_url = '/../data/data-hf-woo-reserved-fields-pair.php';
    } elseif ($_GET['imp_ex_form'] === '1') {
        $map_div = 'style="display:block"';
        $woo_div = 'style="display:none"';
        $bb_ex_div = 'style="display:block"';
        $bb_ext_div = 'style="display:none"';
        $mapping_url = '/../data/data-hf-reserved-fields-pair.php';
    } elseif ($_GET['imp_ex_topc'] === '1') {
        $map_div = 'style="display:block"';
        $woo_div = 'style="display:none"';
        $bb_ex_div = 'style="display:none"';
        $bb_ext_div = 'style="display:block"';
        $mapping_url = '/../data/data-hf-reserved-fields-pair.php';
    } else {
        $map_div = 'style="display:block"';
        $woo_div = 'style="display:none"';
        $bb_ex_div = 'style="display:none"';
        $bb_ext_div = 'style="display:none"';
        $mapping_url = '/../data/data-hf-reserved-fields-pair.php';
    }
    ?>

    <div <?php echo $bb_ext_div; ?>>
        <script type="text/javascript">
            jQuery(function ($) {
                $("#bb_ex_topic").change(function () {
                    var end = this.value;
                    if (end === '0'){$("#submit_but").hide();}else{$("#submit_but").show();}                    
                }).change();
            });
        </script>
        <table class="form-table">  
            <tr>

                <th id='f_bb'>

                    <label for="bb_ex_topic"><?php _e('Select Topic', 'hf_bb_import_export'); ?></label>

                </th>
                <td >
                    <div id='p_woodis_body' style="width:45%;">

                        <select id="bb_ex_topic" name="bb_ex_topic" class="wc-enhanced-select" >
                            <?php
                            echo '<option  value="0"> -- SELECT TOPIC --</option>';
                            $args = array(
                                'posts_per_page' => -1,
                                'post_type' => 'topic',
                                'post_status' => 'publish',
                                'suppress_filters' => true
                                );
                            $products = get_posts($args);
                            foreach ($products as $product) {
                                $data_value = strlen($product->post_title) > 50 ? substr($product->post_title, 0, 50) . "..." : $product->post_title;
                                $data_value1 = $product->post_title;
                                echo '<option title="' . $data_value1 . '" value="' . $product->ID . '">#' . $product->ID . ' - ' . $data_value . '</option>';
                            }
                            ?>

                        </select>
                    </div>  
                </td>
            </tr>
        </table>
    </div>

    <div <?php echo $bb_ex_div; ?>>
        <script type="text/javascript">
            jQuery(function ($) {
                $("#bb_ex_forum").change(function () {
                    var end = this.value;
                    if (end === '0'){$("#submit_but").hide();}else{$("#submit_but").show();}                    
                }).change();
            });
        </script>
        <table class="form-table">  
            <tr>

                <th id='f_bb'>

                    <label for="bb_ex_forum"><?php _e('Select Forum', 'hf_bb_import_export'); ?></label>

                </th>
                <td >
                    <div id='p_woodis_body' style="width:45%;">

                        <select id="bb_ex_forum" name="bb_ex_forum" class="wc-enhanced-select" >
                            <?php
                            echo '<option  value="0"> -- SELECT FORUM --</option>';
                            $args = array(
                                'posts_per_page' => -1,
                                'post_type' => 'forum',
                                'post_status' => 'publish',
                                'suppress_filters' => true
                                );
                            $products = get_posts($args);
                            foreach ($products as $product) {
                                $data_value = strlen($product->post_title) > 50 ? substr($product->post_title, 0, 50) . "..." : $product->post_title;
                                $data_value1 = $product->post_title;
                                echo '<option title="' . $data_value1 . '" value="' . $product->ID . '">#' . $product->ID . ' - ' . $data_value . '</option>';
                            }
                            ?>

                        </select>
                    </div>  
                </td>
            </tr>
        </table>
    </div>
    <div <?php echo $woo_div; ?>>
        <script type="text/javascript">

            jQuery(function ($) {
                $("#submit_but").show();
                $("#woo_selection").change(function () {
                    var end = this.value;
                    if (end === '1')
                    {
                        $("#instr").hide();
                        $("#woo_forum_1").append('<option value="0">--Product Name As Forum Name--</option>');
                        $("#woo_forum_1").attr("disabled", "disabled");
                        $('#woo_forum_1 option[value="0"]').attr("selected", true);

                    }
                    if (end === '2')
                    {
                        $("#woo_forum_1").removeAttr("disabled");
                        $("#instr").show();
                        $('#woo_forum_1 option[value="0"]').remove();
                    }
                    if (end === '3')
                    {
                        $("#woo_forum_1").removeAttr("disabled");
                        $("#instr").show();
                        $('#woo_forum_1 option[value="0"]').remove();
                    }
                }).change();

            });
        </script>
        <table class="form-table">  

            <tr>
                <th id='f_bb'>
                    <label for="woo_forum"><?php _e('Select Import Method', 'hf_bb_import_export'); ?></label>
                </th>
                <td >
                    <div id='p_woodis_body'>

                        <select id="woo_selection" name="woo_selection" style="width:45%;" >
                            <?php
                            echo '<option  value="1">Import Products As Forum</option>';
                            echo '<option  value="2">Import Products As Topic</option>';
                            echo '<option  value="3">Each Conversation As New Topic</option>';
                            ?>

                        </select>
                    </div>  

                </td>
            </tr>

            <tr>

                <th id='f_bb'>

                    <label for="woo_forum"><?php _e('Select Forum', 'hf_bb_import_export'); ?></label>

                </th>
                <td >
                    <div id='p_woodis_body' style="width:45%;">

                        <select id="woo_forum_1" name="woo_forum_1" class="wc-enhanced-select" >
                            <?php
                            echo '<option  value="0">--Product Name As Forum Name--</option>';

                            $args = array(
                                'posts_per_page' => -1,
                                'post_type' => 'forum',
                                'post_status' => 'publish',
                                'suppress_filters' => true
                                );
                            $products = get_posts($args);
                            foreach ($products as $product) {
                                $data_value = strlen($product->post_title) > 50 ? substr($product->post_title, 0, 50) . "..." : $product->post_title;
                                $data_value1 = $product->post_title;
                                echo '<option title="' . $data_value1 . '" value="' . $product->ID . '">#' . $product->ID . ' - ' . $data_value . '</option>';
                            }
                            ?>

                        </select>



                    </div>  
                </td>

            </tr>
            <tr>
                <th id='f_bb'>

                    <label for="woo_stat"><?php _e('Comment Status', 'hf_bb_import_export'); ?></label>

                </th>
                <td >
                    <div id='p_woodis_body'>

                        <select id="woo_stat" name="woo_stat" >
                            <?php
                            echo '<option  value="1">Open</option>';
                            echo '<option  value="2">Closed</option>';
                            ?>

                        </select>
                    </div>  
                </td>
            </tr>
            <tr>
                <th id='f_bb'>

                    <label for="woo_stat1"><?php _e('Remote Comments Status', 'hf_bb_import_export'); ?></label>

                </th>
                <td >
                    <div id='p_woodis_body'>

                        <select id="woo_stat1" name="woo_stat1" >
                            <?php
                            echo '<option  value="1">Open</option>';
                            echo '<option  value="2">Closed</option>';
                            ?>

                        </select>



                    </div>  
                </td>
            </tr>
            <tr style="display: none;">
                <th id='f_bb'>

                    <label for="woo_date"><?php _e('Post Date as Current Date', 'hf_bb_import_export'); ?></label>

                </th>
                <td >
                    <div id='p_woodis_body'>

                        <input type="checkbox"  id="woo_date_enable"  name="woo_date_enable" value="1"  /><?php _e('Enable', 'hf_bb_import_export'); ?>


                    </div>  
                </td>
            </tr>

        </table>

    </div>
    <h3><?php _e('Map Fields', 'hf_bb_import_export'); ?></h3>
    <?php if ($this->profile == '') { ?>
    <?php _e('Mapping file name:', 'hf_bb_import_export'); ?> <input type="text" name="profile" value="" placeholder="Enter filename to save" />
    <?php } else { ?>
    <input type="hidden" name="profile" value="<?php echo $this->profile; ?>" />
    <?php } ?>
    <p><?php _e('Here you can map your imported columns to product data fields.', 'hf_bb_import_export'); ?></p>
    <table class="widefat widefat_importer">
        <thead>
            <tr>
                <th><?php _e('Map to', 'hf_bb_import_export'); ?></th>
                <th><?php _e('Column Header', 'hf_bb_import_export'); ?></th>
                <th><?php _e('Evaluation Field', 'hf_bb_import_export'); ?>
                    <?php $plugin_url = HF_BB_ImpExpCsv_AJAX_Handler::hf_get_wc_path(); ?>
                    <?php if (function_exists('WC')) { ?>
                    <div class="tooltip">  <img style="float:none;" src="<?php echo $plugin_url; ?>/assets/images/help.png" height="20" width="20" /> 
                    <span class="tooltiptext" style="top: -5px;right: 105%; width: 250px;"><?php _e('Assign constant value HikeFoce to post_author:</br>=HikeFoce</br>Append a value By HikeFoce to posts_content:</br>&By HikeFoce</br>Prepend a value HikeFoce to posts_content:</br>&HikeFoce [VAL].', 'hf_bb_import_export'); ?></span>
                    </div>
                      <?php } else { ?>
                    <div class="tooltip">  <img style="float:none;" src="<?php echo $plugin_url; ?>/images/help.png" height="20" width="20" /> 
                    <span class="tooltiptext" style="top: -5px;right: 105%;width: 250px;"><?php _e('Assign constant value HikeFoce to post_author:</br>=HikeFoce</br>Append a value By HikeFoce to posts_content:</br>&By HikeFoce</br>Prepend a value HikeFoce to posts_content:</br>&HikeFoce [VAL].', 'hf_bb_import_export'); ?></span>
                    </div>
                    <?php } ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $wpost_attributes = include( dirname(__FILE__) . $mapping_url );

            foreach ($wpost_attributes as $key => $value) :
                $sel_key = ($saved_mapping && isset($saved_mapping[$key])) ? $saved_mapping[$key] : $key;
            $evaluation_value = ($saved_evaluation && isset($saved_evaluation[$key])) ? $saved_evaluation[$key] : '';
            $evaluation_value = stripslashes($evaluation_value);
            $values = explode('|', $value);
            $value = $values[0];
            $tool_tip = $values[1];
            ?>
            <tr>
                <td width="25%">
                    <?php if (function_exists('WC')) { ?>
                    <div class="tooltip">  <img class="help_tip" style="float:none;" src="<?php echo $plugin_url; ?>/assets/images/help.png" height="20" width="20" /> 
                     
                    <span class="tooltiptext" style="top: -5px;left: 105%;width: 100px;"><?php echo $tool_tip; ?></span>
                    </div>
                    <?php } else { ?>
                    
                    <div class="tooltip">  <img class="help_tip" style="float:none;" data-tip="<?php echo $tool_tip; ?>" src="<?php echo $plugin_url; ?>/images/help.png" height="20" width="20" /> 
                    <span class="tooltiptext" style="top: -5px;left: 105%;width: 100px;"><?php echo $tool_tip; ?></span>
                    </div>

                    <?php } ?>
                    <select name="map_to[<?php echo $key; ?>]" disabled="true" 
                        style=" -webkit-appearance: none;
                        -moz-appearance: none;
                        text-indent: 1px;
                        text-overflow: '';
                        background-color: #f1f1f1;
                        border: none;
                        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.07) inset;
                        color: #32373c;
                        outline: 0 none;
                        transition: border-color 50ms ease-in-out 0s;">
                        <option value="<?php echo $key; ?>" <?php if ($key == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
                    </select>                             
                </td>
                <td width="25%">
                    <select name="map_from[<?php echo $key; ?>]">
                        <option value=""><?php _e('Do not import', 'hf_bb_import_export'); ?></option>
                        <?php
                        foreach ($row as $hkey => $hdr):

                            $hdr = strlen($hdr) > 50 ? substr($hdr, 0, 50) . "..." : $hdr;
                        ?>
                        <option value="<?php echo $raw_headers[$hkey]; ?>" <?php selected(strtolower($sel_key), $hkey); ?>><?php echo $raw_headers[$hkey] . " &nbsp;  : &nbsp; " . $hdr; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php do_action('bb_csv_product_data_mapping', $key); ?>
            </td>
            <td width="10%"><input type="text" name="eval_field[<?php echo $key; ?>]" value="<?php echo $evaluation_value; ?>"  /></td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>

<p class="submit" id='submit_but'>
    <input type="submit" class="button button-primary" value="<?php esc_attr_e('Continue', 'hf_bb_import_export'); ?>" />
    <input type="hidden" name="delimiter" value="<?php echo $this->delimiter ?>" />
    <input type="hidden" name="bb_clean_before_import" value="<?php echo $this->bb_clean_before_import ?>" />
</p>
</form>

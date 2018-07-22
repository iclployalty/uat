<div class="tool-box">
    <h3 class="title"><?php _e('Export bbPress Forums, Topics, and Replies in CSV Format:', 'hf_bb_import_export'); ?></h3>
    <p><?php _e('Export and download your Forums, Topics, and Replies in CSV format. This file can be used to import Forums, Topics, Replies back into your shop.', 'hf_bb_import_export'); ?></p>
    <form action="<?php echo admin_url('admin.php?page=HF_BB_CSV_IM_EX&action=export'); ?>" method="post">

        <table class="form-table">
            <tr>
                <th>
                    <label for="v_limit"><?php _e('Limit', 'hf_bb_import_export'); ?></label>
                </th>
                <td>
                    <input type="number" min="1" name="limit" id="v_limit" placeholder="<?php _e('Unlimited', 'hf_bb_import_export'); ?>" class="input-text" />
                    <p style="font-size: 12px"><?php _e('The number of rows to return.', 'hf_bb_import_export'); ?></p>
                </td>
            </tr>
                 <tr>
                <th>
                    <label for="v_date"><?php _e('Date', 'hf_bb_import_export'); ?></label>
                </th>
                <td>
                    <input type="date" name="cmt_date_from" id="datepicker1" placeholder="<?php _e('From date', 'hf_bb_import_export'); ?>" class="input-text" /> -
                     <input type="date" name="cmt_date_to" id="datepicker2" placeholder="<?php _e('To date', 'hf_bb_import_export'); ?>" class="input-text" />
                    <p style="font-size: 12px"><?php _e('The Post date.', 'hf_bb_import_export'); ?></p>
                </td>
            </tr>
            
            <!-- BB PRESS -->
             <tr>
                <th id="a_woodis">
                    <label for="v_bb_main"><?php _e('bbPress Filter', 'hf_bb_import_export'); ?></label>
                </th>
                <td>
                    <input type="checkbox"  id="bb_forum_enable" name="bb_forum_enable[]" value="1"  /><?php _e('Forums ', 'hf_bb_import_export'); ?>
                
                    <input type="checkbox"  id="bb_topic_enable" name="bb_topic_enable[]" value="1"  /><?php _e('Topics ', 'hf_bb_import_export'); ?>
                    <input type="checkbox"  id="bb_reply_enable"  name="bb_reply_enable[]" value="1"  /><?php _e('Replies ', 'hf_bb_import_export'); ?>
                </td>
            </tr>
            <tr>
             
            <th id='f_bb'>
                   
                    <label for="v_prods"><?php _e('Forum', 'hf_bb_import_export'); ?></label>
                   
                    </th>
                <td >
                
            <div id='p_woodis_body'>
                    <select id="v_prods" name="forum[]" data-placeholder=" <?php _e('All Forums', 'hf_bb_import_export'); ?>" style="width:45%;"  multiple="multiple">
                        <?php
                            $args = array(
                                'posts_per_page'   => -1,
                                'post_type'        => 'forum',
                                'post_status'      => 'publish',
                                'suppress_filters' => true 
                            );
                            $products   = get_posts($args);
                            foreach ($products as $product) {
                                $data_value= strlen($product->post_title) > 50 ? substr($product->post_title,0,50)."..." : $product->post_title;
                               $data_value1=$product->post_title;
                                echo '<option title="'. $data_value1 .'" value="' . $product->ID . '">#'. $product->ID .' - '. $data_value . '</option>';
                            }
                        ?>
                    </select>
                                                        
                    <p style="font-size: 12px"><?php _e('forum Bundle under these Forums will be exported.', 'hf_bb_import_export'); ?></p>
           </div>  
                    <br>
                </td>
           
                 </tr>
                 <th id='ft_bb'>
                   
                    <label for="v_prods"><?php _e('Forum', 'hf_bb_import_export'); ?></label>
                   
                    </th>
                <td >
                
            <div id='p_woodis_body'>
                    <select id="v_prods" name="tforum[]" data-placeholder=" <?php _e('All Forums', 'hf_bb_import_export'); ?>" style="width:45%;" multiple="multiple">
                        <?php
                            $args = array(
                                'posts_per_page'   => -1,
                                'post_type'        => 'forum',
                                'post_status'      => 'publish',
                                'suppress_filters' => true 
                            );
                            $products   = get_posts($args);
                            foreach ($products as $product) {
                                $data_value= strlen($product->post_title) > 50 ? substr($product->post_title,0,50)."..." : $product->post_title;
                               $data_value1=$product->post_title;
                                echo '<option title="'. $data_value1 .'" value="' . $product->ID . '">#'. $product->ID .' - '. $data_value . '</option>';
                            }
                        ?>
                    </select>
                                                        
                    <p style="font-size: 12px"><?php _e('topics and Replies under these Forums will be exported.', 'hf_bb_import_export'); ?></p>
            </div>  
                    <br>
                     <input type="checkbox"  id="bb_tt_enable"  name="bb_tt_enable[]" value="1"  /><?php _e('with Replies', 'hf_bb_import_export'); ?>
          
                </td>
           
                 </tr>
                 <tr>
             
                <th id='t_bb'>
                   
                    <label for="v_prods"><?php _e('Topic', 'hf_bb_import_export'); ?></label>
                   
                    </th>
                <td >
                
            <div id='p_woodis_body'>
                    <select id="v_prods" name="Topic[]" data-placeholder=" <?php _e('All Topics', 'hf_bb_import_export'); ?>" style="width:45%;" multiple="multiple">
                        <?php
                            $args = array(
                                'posts_per_page'   => -1,
                                'post_type'        => 'topic',
                                'post_status'      => 'publish',
                                'suppress_filters' => true 
                            );
                            $products   = get_posts($args);
                            foreach ($products as $product) {
                                    $data_value= strlen($product->post_title) > 50 ? substr($product->post_title,0,50)."..." : $product->post_title;
                               $data_value1=$product->post_title;
                                echo '<option title="'. $data_value1 .'" value="' . $product->ID . '">#'. $product->post_parent .'/'. $product->ID .' - '. $data_value . '</option>';
                           
                                   }
                        ?>
                    </select>
                                                        
                    <p style="font-size: 12px"><?php _e('Replies under these topics will be exported.', 'hf_bb_import_export'); ?></p>
           </div>   
                            <br>
            
                </td>
                
                 </tr>
                <script type="text/javascript">
                     jQuery( function( $ ) {
                            
                       var article  = jQuery ( '#a_woodis').closest( 'tr' );
                        var f_bb    =   jQuery ( '#f_bb' ).closest( 'tr' );
                        var t_bb    =   jQuery ( '#t_bb' ).closest( 'tr' );
                        var r_bb    =   jQuery ( '#r_bb' ).closest( 'tr' );
                        var ft_bb    =   jQuery ( '#ft_bb' ).closest( 'tr' );
                    
                   
                      
                        
                         $( '#bb_forum_enable' ).change(function(){
                            if ( $( this ).is( ':checked' ) ) {
                                    $('#bb_topic_enable').attr('checked', false);
                                    $('#bb_reply_enable').attr('checked', false);
                                    $( t_bb ).hide();
                                    $( f_bb ).show();
                                    $( ft_bb ).hide();
                                    $('#ex_but').val('Export Forums');
                            
                            } 
                            else
                            {
                                $( t_bb ).hide();
                                $( f_bb ).hide();
    
                                    $( ft_bb ).hide();
                            }
                            
                        }).change();
                        $( '#bb_topic_enable' ).change(function(){
                            if ( $( this ).is( ':checked' ) ) {
                                    $('#bb_reply_enable').attr('checked', false);
                                    $('#bb_forum_enable').attr('checked', false);
                                   $( ft_bb ).show();
                                   $( f_bb ).hide();
                                   $( t_bb ).hide();
                                   $('#ex_but').val('Export Topics');
                            
                            } 
                            else
                            {
                                $( t_bb ).hide();
                                $( f_bb ).hide();

                                    $( ft_bb ).hide();
                            } 
                        }).change();
                        $( '#bb_reply_enable' ).change(function(){
                            if ( $( this ).is( ':checked' ) ) {
                                    $('#bb_topic_enable').attr('checked', false);
                                    $('#bb_forum_enable').attr('checked', false);
                                   $( f_bb ).hide();
                                   $( t_bb ).show();
                                   
                                    $( ft_bb ).hide();
                                   $('#ex_but').val('Export Reply Comments');
                            
                            } 
                            else
                            {
                                $( t_bb ).hide();
                                $( f_bb ).hide();
                                
                                    $( ft_bb ).hide();
                                
                            }
                        }).change();
                      
                    });
            </script>
            
          
            <tr>
                <th>
                    <label for="v_delimiter"><?php _e('Delimiter', 'hf_bb_import_export'); ?></label>
                </th>
                <td>
                    <input type="text" name="delimiter" id="v_delimiter" placeholder="<?php _e(',', 'hf_bb_import_export'); ?>" class="input-text" />
                    <p style="font-size: 12px"><?php _e('Column seperator for exported file', 'hf_bb_import_export'); ?></p>
                </td>
            </tr>
            <tr>
                <th id="data_bb" colspan="2">
                    <label for="v_columns"><?php _e('Columns - bbPress', 'hf_bb_import_export'); ?></label>
               

            <table id="datagrid">
                <th style="text-align: left;">
                    <label for="v_columns"><?php _e('Column', 'hf_bb_import_export'); ?></label>
                </th>
                <th style="text-align: left;">
                    <label for="v_columns_name"><?php _e('Column Name', 'hf_bb_import_export'); ?></label>
                </th>
                
                <?php foreach ($post_columns as $pkey => $pcolumn) {
                            $ena=($pkey =='post_alter_id')?'style="display:none;"':'';
                         ?>
            <tr <?php echo $ena; ?> >
                <td>
                    
                    <input name= "columns[<?php echo $pkey; ?>]" type="checkbox"  value="<?php echo $pkey; ?>" checked>
                    <label for="columns[<?php echo $pkey; ?>]"><?php _e($pcolumn, 'hf_bb_import_export'); ?></label>
                </td>
                <td>
                    <?php 
                    $tmpkey = $pkey;
                    if (strpos($pkey, 'yoast') === false) {
                            $tmpkey = ltrim($pkey, '_');
                        }
                    ?>
                     <input type="text" name="columns_name[<?php echo $pkey; ?>]"  value="<?php echo $tmpkey; ?>" class="input-text" />
                </td>
            </tr>
                <?php } ?>
                
            </table><br/> </th><td></td>
            
            
            </tr>
        </table>
            
            
        <p class="submit"><input type="submit" id="ex_but" class="button button-primary" value="<?php _e('Export', 'hf_bb_import_export'); ?>" /></p>
    </form>
</div>

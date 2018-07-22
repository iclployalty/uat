<div class="tool-box">
    <h3 class="title"><?php _e('Import Forums, Topics and Replies in CSV Format:', 'hf_bb_import_export'); ?></h3>
    <p><?php _e('Import Forums, Topics and Replies in CSV format from different sources ( from your computer OR from another server via FTP )', 'hf_bb_import_export'); ?></p>
    <?php
    $merge_url = admin_url('admin.php?import=bb_csv&merge=1');
    $import_url = admin_url('admin.php?import=bb_csv');
    $im_ex_forum_url = admin_url('admin.php?import=bb_csv&imp_ex_form=1');
    $im_ex_topic_url = admin_url('admin.php?import=bb_csv&imp_ex_topc=1');
    $woo_url = admin_url('admin.php?import=bb_csv&woo_bb=1');
    ?>

    <table class="form-table">
        <tr>
            <th>
                <label>  <?php _e(' Select import method ', 'hf_bb_import_export'); ?> </label>
            </th>
            <td>
                <select id="import_selection" name="import_selection" style="width:45%;" >
                    <?php
                    echo '<option  value="0"> -- SELECT OPTION -- </option>';
                    echo '<option  value="1"> Bulk Import Process </option>';
                    echo '<option  value="2"> Import to Existing Forum </option>';
                    echo '<option value="3" > Import to Existing Topic </option>';
                    echo '<option value="4"> Merge bbPress Data </option>';
                    echo '<option value="5" > WooDiscuz to bbPress </option>';
                    ?>

                </select>
            </td>
        </tr>
    </table>
    <br>
    <a class="button button-primary" id="mylink" href="#"><?php _e('Import to bbPress', 'hf_bb_import_export'); ?></a>
    &nbsp;
    <br>
    <br>    
</div>
<script type="text/javascript">

    jQuery(function ($) {
    $('#import_selection').change(function () {
    if(this.value == '0'){jQuery("#mylink").attr("href", '<?php echo '#'; ?>');}
    if(this.value == '1'){jQuery("#mylink").attr("href", '<?php echo $import_url ?>');}
    if(this.value == '2'){jQuery("#mylink").attr("href", '<?php echo $im_ex_forum_url ?>');}
    if(this.value == '3'){jQuery("#mylink").attr("href", '<?php echo $im_ex_topic_url ?>');}
    if(this.value == '4'){jQuery("#mylink").attr("href", '<?php echo $merge_url ?>');}
    if(this.value == '5'){jQuery("#mylink").attr("href", '<?php echo $woo_url ?>');}
    }).change();});
</script>

<?php
/*
 *  (View file)
 * 
 *  ALL Variables available from the called script
 *  
 *  Show Export Result
 */
global $error;
$output = array();
?>
<div id='loadingmessage'></div>
<div class="result" style="display:none" id="result<?php echo $operationCategory;?>">
<strong class='red'><?php echo $error;?></strong>
<table class='widefat' style="background: #173e43; border: none;">
<thead>
<tr><th colspan='2' style="background:#3fb0ac;color:#FFF; border-bottom:2px solid #DDD;"><strong><?php _e( 'Result', 'wpaie' ); ?></strong></th></tr>
</thead>
<tbody>
<tr><th><?php _e( 'Records Read:', 'wpaie' ); ?>:</th>
<td class="recordsRead" id="recordsRead<?php echo $operationCategory;?>">
<strong><?php echo $output["recordsRead"];?></strong></td></tr>
<tr><th><?php _e( 'Download Link:', 'wpaie' ); ?></th>
<td class="downloadLink" id="downloadLink<?php echo $operationCategory;?>">
    <strong><?php echo $output["recordsInserted"];?></strong></td></tr>
</tbody>
</table>
</div>
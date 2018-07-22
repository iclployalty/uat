<form method="post" class="wpaieExportForm submitWPAIEForm" data-type="<?php echo $operationType;?>">
<?php $this->showOutputResult($operationType)?>
  <div class="formControls">
 	<div class="heading">
  		<span>Export Wordpress Table File</span>
 	</div>  
    <div class="control-group">
      <label class="control-label">Select Table<span class="star">*</span></label>
      <div class="controls">
        <select name="wpTables" id="wpTables" class="selectData">
          <option value="0">--Select--</option>
          <?php foreach($tables as $table) { ?>
          <option value="<?php echo $table;?>"><?php echo $table;?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="control-group" id="wpTableCol" style="display:none">
      <label class="control-label">Select Columns<span class="star">*</span></label>
      <div class="controls" >
        <select name="wpTableColumns[]" id="wpTableColumns" class="small w-wrap selectData">
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Order By</label>
      <div class="controls">
        <input type="text" id="orderBy" name="orderBy"  />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Order</label>
      <div class="controls">
        <select class="small w-wrap" id="orderAscDesc" name="orderAscDesc" >
          <option value="ASC">ASC</option>
          <option value="DESC">DESC</option>
        </select>
      </div>
    </div>
	<div class="control-group">
      <label class="control-label">Export as </label>
      <div class="controls">
        <select class="small w-wrap" id="optionFileType" name="optionFileType" >
          <option value="csv">CSV</option>
          <option value="excel5">Excel 2003</option>
          <option value="excel2007">Excel 2007</option>
          <option value="pdf">PDF</option>
          <option value="xml">XML</option>
        </select>
      </div>
  </div>
  <div class="control-group">
      <div class="controls exportSubmit">
        <input type="hidden" value="<?php echo $operationType;?>" name="operationCategory" />
        <input type="submit" value="submit" name="submitExport" id="submitExport" class="submit" data-type="<?php echo $operationType;?>"/><span id="processing<?php echo $operationType;?>" class="submit" style="display:none">Processing...</span>
        <input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="7"/>
      </div>
  </div>
</div>
</form> 
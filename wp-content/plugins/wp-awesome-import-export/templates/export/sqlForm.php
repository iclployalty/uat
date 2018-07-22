<form method="post" class="wpaieExportForm submitWPAIEForm" data-type="<?php echo $operationType;?>">
<?php $this->showOutputResult($operationType)?>
  <div class="formControls">
 	<div class="heading">
  		<span>Export SQL File</span>
 	</div>
    <div class="control-group">
      <label class="control-label">Write Query</label>
      <div class="controls" id="sqldiv">
        <textarea name="sql" id="sql" class="textarea"></textarea>        
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
        <input type="submit" value="submit" name="submitExport" id="submitExport" class="submit" data-type="<?php echo $operationType;?>"/> <span id="processing<?php echo $operationType;?>" class="submit" style="display:none">Processing...</span>
        <input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="8"/>
      </div>
    </div>
  </div>
</form> 
<form method="post" class="wpaieExportForm submitWPAIEForm" data-type="<?php echo $operationType;?>">
<?php $this->showOutputResult($operationType)?>
  <div class="formControls">
 	<div class="heading">
  		<span>Export Users File</span>
 	</div>  
   <div class="control-group">
      <label class="control-label">Select User Fields<span class="star">*</span></label>
      <div class="controls">
        <select class="small w-wrap" id="userFields" name="userFields[]" multiple="multiple">
		  <?php 
                  $idNotSelct=0;
                  foreach($userFields as $userField) {
                    if($idNotSelct==0)
                      echo '<option value="'.$userField.'">'.$userField.'</option>';
                    else
                      echo '<option selected="selected" value="'.$userField.'">'.$userField.'</option>';  
                  $idNotSelct++; } ?>        
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Select User Meta</label>
      <div class="controls">
    <select class="small w-wrap" id="userMeta" name="userMeta[]" multiple="multiple" >
          <?php foreach($metaFields as $fields) {?>
          <option><?php echo $fields;?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Role Name</label>
      <div class="controls">
        <input type="text" name="userRole" id="userRole" />
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
        <input type="submit" value="submit" name="submitExport" id="submitExport" class="submit" data-type="<?php echo $operationType;?>" /><span id="processing<?php echo $operationType;?>" class="submit" style="display:none">Processing...</span>
        <input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="4"/>
      </div>
    </div>
  </div>
</form>    
<?php
/*
 *  (View file)
 * 
 *  ALL Variables available from the called script
 *  
 *  Taxonomy Form
 */
?>
<form method="post" class="wpaieExportForm submitWPAIEForm" data-type="<?php echo $taxonomyType;?>">
<?php $this->showOutputResult($taxonomyType)?>
  <div class="formControls">
 	<div class="heading">
  		<span>Export Taxonomy File</span>
 	</div>  
    <?php if(strtoupper($taxonomyType)=="CATEGORY") {?>
    <div class="control-group">
      <label class="control-label">Select Category/Tags<span class="star">*</span></label>
      <div class="controls">
        <select class="small w-wrap" id="taxonomyType<?php echo $taxonomyType;?>" name="taxonomyType[]" multiple="multiple">
          <option value="post_tag">Tags</option>
          <option value="category">Category</option>
        </select>
      </div>
    </div>
    <?php } else if(strtoupper($taxonomyType)=="TAXONOMY") {?>
    <div class="control-group">
      <label class="control-label">Select Custom Taxonomy<span class="star">*</span></label>
      <div class="controls">
        <select class="small w-wrap" id="taxonomyType<?php echo $taxonomyType;?>" name="taxonomyType[]" multiple="multiple">
          <?php foreach($customFields as $field) {?>
          <option value="<?php echo $field;?>"><?php echo $field;?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <?php }?>
    <div class="control-group">
      <label class="control-label">Order By</label>
      <div class="controls">
        <input type="text" id="orderBy" name="orderBy" value="name" />
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
      <label class="control-label">Hide Empty</label>
      <div class="controls">
        <select class="small w-wrap" id="hideEmpty" name="hideEmpty" >
          <option value="true">Yes</option>
          <option value="false">No</option>
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
        <input type="hidden" value="<?php echo $taxonomyType;?>" name="operationCategory" />
        <input type="submit" value="submit" name="submitExport" id="submitExport" class="submit" data-type="<?php echo $taxonomyType;?>" /><span id="processing<?php echo $taxonomyType;?>" class="submit" style="display:none">Processing...</span>
        <?php 
         if($taxonomyType=='Category')
               echo '<input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="2"/>';
            if($taxonomyType=='Taxonomy')
               echo '<input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="5"/>';
        ?>
      </div>
    </div>
  </div>
</form>
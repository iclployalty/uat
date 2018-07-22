<form method="post" class="wpaieExportForm submitWPAIEForm" data-type="<?php echo $operationType; ?>">
    <?php $this->showOutputResult($operationType) ?>
    <div class="formControls">
        <div class="heading">
            <span>Export Comment File</span>
        </div>  
        <div class="control-group">
            <label class="control-label">Select Comment Fields<span class="star">*</span></label>
            <div class="controls">
                <select class="small w-wrap" id="commentFields" name="commentFields[]" multiple="multiple">
                    <?php
                    $idNotSelect = 0;
                    foreach ($commentFields as $commentField) {
                        if ($idNotSelect == 0)
                            echo '<option value="' . $commentField . '" >' . $commentField . '</option>';
                        else
                            echo '<option value="' . $commentField . '" selected="selected">' . $commentField . '</option>';
                        $idNotSelect++;
                    }
                    ?>        
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Select Comment Status</label>
            <div class="controls">
                <select class="small w-wrap" id="commentStatus" name="commentStatus">
                    <?php foreach ($commentStatus as $status) { ?>
                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Post ID</label>
            <div class="controls">
                <input type="text" id="byPostId" name="byPostId"  />
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
            <label class="control-label">Search By Post Author</label>
            <div class="controls">
                <input type="text" id="postAuthor" name="postAuthor" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Search By Post Status</label>
            <div class="controls">
                <select class="small w-wrap" id="postStatus" name="postStatus">
                    <option value="">All</option>
                    <?php foreach ($postStatus as $status) { ?>
                        <option><?php echo $status; ?></option>
                    <?php } ?>
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
                <input type="hidden" value="<?php echo $operationType; ?>" name="operationCategory" />
                <input type="submit" value="submit" name="submitExport" id="submitExport" class="submit" data-type="<?php echo $operationType; ?>"/><span id="processing<?php echo $operationType; ?>" class="submit" style="display:none">Processing...</span>
                <input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="3"/>
            </div>
        </div>
    </div>
</form>
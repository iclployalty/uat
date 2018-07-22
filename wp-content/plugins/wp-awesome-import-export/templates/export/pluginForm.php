<form method="post" class="wpaieExportForm submitWPAIEForm" data-type="product">
    <?php $this->showOutputResult('product'); ?>
    <div class="formControls">
        <div class="heading">
            <span>Export Plugin File</span>
        </div>  
        <div class="control-group">
            <label class="control-label">Select Plugin</label>
            <div class="controls">
                <select class="small w-wrap" id="postType" name="postType">
                    <option selected="selected" value="product">Woocommerce Product</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Select Columns<span class="star">*</span></label>
            <div class="controls">
                <select class="small w-wrap" id="postColumns<?php echo $postType; ?>" name="postColumns[]" multiple="multiple">
                    <?php foreach ($postColumns as $key => $val) { ?>
                        <option value="<?php echo $key; ?>" selected="selected"><?php echo $val; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Export Featured Image</label>
            <div class="controls">
                <select class="small w-wrap" id="exportFeaturedImg" name="exportFeaturedImg" >
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Select Meta</label>
            <div class="controls">
                <select class="small w-wrap" id="postMeta" name="postMeta[]" multiple="multiple" >
                    <?php foreach ($metaFields as $fields) { ?>
                        <option selected="selected"><?php echo $fields; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Select Taxonomies</label>
            <div class="controls">
                <select class="small w-wrap" id="postCustomFields" name="postCustomFields[]" multiple="multiple" >
                    <?php
                    foreach ($customFields as $fields) {
                        $selected = '';
                        if (trim($fields) === 'product_type' || trim($fields) === 'product_tag')
                            $selected = 'selected="selected"'
                            ?>
                        <option <?php echo $selected; ?>><?php echo $fields; ?></option>
                        <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">No. Of Products</label>
            <div class="controls">
                <select class="small w-wrap optionNoOfPost" id="optionNoOfPost" name="optionNoOfPost" data-type="<?php echo $pluginName; ?>" >
                    <option value="allposts">All Posts</option>
                    <option value="postrange">Post Range(Limit)</option>
                    <option value="postrangebypostid">Post Range(By post Id)</option>
                    <option value="specificpostbyid">Specific Post (By post Id's)</option>
                </select>
            </div>
        </div>
        <div class="control-group postRange" style="display:none" data-type="<?php echo $pluginName; ?>">
            <label class="control-label"></label>
            <div class="controls">
                <input type="text" id="postStartRange" name="postStartRange" placeholder="Start Post Id" />
                <input type="text" id="postTotalCount" name="postTotalCount" placeholder="Total Posts" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Product Status</label>
            <div class="controls">
                <select class="small w-wrap" id="postStatus" name="postStatus[]" multiple="multiple" >
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
                <input type="hidden" value="plugins" name="operationCategory" />
                <input type="submit" value="submit" name="submitExport" id="submitExport" class="submit" data-type="<?php echo 'product' ?>"/>
                <span id="processing<?php echo 'product'; ?>" class="submit" style="display:none">Processing...</span>
                <input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="9"/>
            </div>
        </div>
    </div>
</form>
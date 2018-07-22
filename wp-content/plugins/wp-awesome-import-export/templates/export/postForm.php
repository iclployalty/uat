<form method="post" class="wpaieExportForm submitWPAIEForm" data-type="<?php echo $postType; ?>">
    <?php $this->showOutputResult($postType) ?>
    <div class="formControls">
        <div class="heading">
            <span>Export 
                <?php
                if ($postType === "POST") {
                    echo "Post";
                }
                if ($postType === "PAGE") {
                    echo "Page";
                }
                ?>
                File</span>
        </div>  
        <div class="control-group" <?php if ($postType === "POST" || $postType === "PAGE") echo "style=display:none;"; ?>>
            <label class="control-label">Select Post Type<span class="star">*</span></label>
            <div class="controls">
                <select class="small w-wrap" id="postType" name="postType">
                    <?php
                    if ($postType === "POST") {
                        echo "<option  selected='selected' value='POST'>POST</option>";
                    }
                    ?>
                    <?php
                    if ($postType === "PAGE") {
                        echo "<option  selected='selected' value='PAGE'>PAGE</option>";
                    }
                    foreach ($customPostTypes as $customPost) {
                        ?>
                        <option value="<?php echo $customPost; ?>"><?php echo $customPost; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Select Post Columns<span class="star">*</span></label>
            <div class="controls">
                <select class="small w-wrap" id="postColumns<?php echo $postType; ?>" name="postColumns[]" multiple="multiple">
                    <?php
                    $idNotSelect = 0;
                    foreach ($postColumns as $column) {
                        if ($idNotSelect == 0)
                            echo '<option>' . $column . '</option>';
                        else
                            echo '<option selected="selected">' . $column . '</option>';
                        $idNotSelect++;
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Select Post Meta</label>
            <div class="controls">
                <select class="small w-wrap" id="postMeta" name="postMeta[]" multiple="multiple" >
                <?php foreach ($metaFields as $fields) { ?>
                        <option><?php echo $fields; ?></option>
                <?php } ?>
                </select>
            </div>
        </div>
        <?php if ($postType != "PAGE") { ?>
            <div class="control-group">
                <label class="control-label">Select Taxonomies</label>
                <div class="controls">
                    <select class="small w-wrap" id="postCustomFields" name="postCustomFields[]" multiple="multiple" >
                        <option value="post_tag" selected="selected">Post Tags</option>
                        <option value="category" selected="selected">Post Category</option>
        <?php foreach ($customFields as $fields) { ?>
                            <option><?php echo $fields; ?></option>
            <?php } ?>
                    </select>
                </div>
            </div>
        <?php } ?>
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
            <label class="control-label">No. Of Posts</label>
            <div class="controls">
                <select class="small w-wrap optionNoOfPost" id="optionNoOfPost" name="optionNoOfPost" data-type="<?php echo $postType; ?>" >
                    <option value="allposts">All Posts</option>
                    <option value="postrange">Post Range(Limit)</option>
                    <option value="postrangebypostid">Post Range(By post Id)</option>
                    <option value="specificpostbyid">Specific Post (By post Id's)</option>
                </select>
            </div>
        </div>
        <div class="control-group postRange" style="display:none" data-type="<?php echo $postType; ?>">
            <label class="control-label"></label>
            <div class="controls">
                <input type="text" id="postStartRange" name="postStartRange" placeholder="Start Post Id" />
                <input type="text" id="postTotalCount" name="postTotalCount" placeholder="Total Posts" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Post Status</label>
            <div class="controls">
                <select class="small w-wrap" id="postStatus" name="postStatus[]" multiple="multiple" >
                    <?php
                    foreach ($postStatus as $status) {
                        $selected = "";
                        if ($status == "publish")
                            $selected = "selected=selected";
                        ?>
                        <option <?php echo $selected; ?>><?php echo $status; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Order By</label>
            <div class="controls">
                <input type="text" id="orderBy" name="orderBy" value="post_title"  />
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
                <input type="hidden" value="<?php echo $postType; ?>" name="operationCategory" />
                <input type="submit" value="submit" name="submitExport" id="submitExport" class="submit" data-type="<?php echo $postType; ?>"  />
                <span id="processing<?php echo $postType; ?>" class="submit" style="display:none">Processing...</span>
                <?php
                if ($postType == 'POST')
                    echo '<input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="0"/>';
                if ($postType == 'PAGE')
                    echo '<input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="1"/>';
                if ($postType == 'CustomPost')
                    echo '<input type="hidden" id="lastActivateTabId" name="lastActivateTabId" value="6"/>';
                ?>
            </div>
        </div>
    </div>
</form>
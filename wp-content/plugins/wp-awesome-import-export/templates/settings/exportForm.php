<div class="formControls">
    <div class="heading">
        <span>Export Settings</span>
    </div>    
    <div class="control-group">
        <label class="control-label"><div class="setting_name"> Author Details</div> <div title="Whether to show authorId or authorName while exporting Posts" class="help-inline"></div></label>
        <div class="controls">
            <select class="small w-wrap" id="authorDetails" name="authorDetails">
                <option <?php if ($option['authorDetails'] == "authorId") echo "selected=selected"; ?> value="authorId">Show Author Id</option>
                <option <?php if ($option['authorDetails'] == "authorName") echo "selected=selected"; ?> <?php ?> value="authorName">Show Author Name</option>
            </select>
            
        </div>
    </div>   
    <div class="control-group">
        <label class="control-label"><div class="setting_name">Send exported file on email</div></label>
        <div class="controls">
            <input type="radio" class="fileMailChecked fileMailConfrimation" name="fileMailConfrimation" id="fileMailConfrimation" value="yes" <?php if ($option["fileMailConfrimation"] == "yes") echo "checked==checked"; ?>/>Yes
            <input type="radio" class="fileMailUnchecked fileMailConfrimation" name="fileMailConfrimation" id="fileMailConfrimation" value="no" <?php if ($option["fileMailConfrimation"] == "no") echo "checked==checked"; ?>/>No
        </div>
    </div>

    <div class="control-group export_from">
        <label class="control-label">From email</label>
        <div class="controls">
            <input type="text" name="export_from" id="export_from" value="<?php echo $option["export_from"]; ?>" />          
        </div>
    </div>
    <div class="control-group export_subject">
        <label class="control-label">Email subject</label>
        <div class="controls">
            <input type="text" name="export_subject" id="export_subject" value="<?php echo $option["export_subject"]; ?>" />          
        </div>
    </div>    	
</div> 
<?php
/*
 *  (View file)
 * 
 *  ALL Variables available from the called script
 *  
 *  Import Form
 */
?>
<div class="formControls">
    <div class="heading">
        <span>Import Settings</span>
    </div>    
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Select Post Columns</div> <div class="help-inline" title="Only selected post status will be showed at the time of import for mapping post columns." class="help-inline"></div></label>
        <div class="controls">
            <select class="small w-wrap" id="postColumns" name="postColumns[]" multiple="multiple">
                <?php
                foreach ($postColums as $postColumn) {
                    $selected = "";
                    if (in_array($postColumn, $selectedPostCols))
                        $selected = "selected=selected";
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $postColumn; ?>"><?php echo $postColumn; ?></option>
                    <?php
                }
                ?>
            </select>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Default Post Status</div><div class="help-inline" title="If you don't enter any information for post status in import, then selected post status will be saved."></div></label>
        <div class="controls">
            <select class="small w-wrap" id="postStatus" name="postStatus">
                <?php
                foreach ($postStatus as $status) {

                    $selected = "";
                    if (in_array($status, $option))
                        $selected = "selected=selected";
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $status; ?>"><?php echo $status; ?></option>
                <?php }
                ?></select>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Select Post Meta</div> <div class="help-inline" title="Only selected post meta will be showed at the time of import for mapping post meta."></div></label>
        <div class="controls">
            <select class="small w-wrap" id="postMeta" name="postMeta[]" multiple="multiple"
                    title="Only selected post meta will be showed at the time of import for mapping post meta.">
                        <?php
                        foreach ($metaFields as $meta) {
                            $selected = "";
                            if (in_array($meta, $selectedPostMetaCols))
                                $selected = "selected=selected";
                            ?>
                    <option <?php echo $selected; ?> value="<?php echo $meta; ?>"><?php echo $meta; ?></option>
                    <?php
                }
                ?></select>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Select Custom Taxonmoies </div><div class="help-inline" title="Only selected custom taxonmoies will be showed at the time of import for mapping custom taxonmoies.">
            </div></label>
        <div class="controls">
            <select class="small w-wrap" id="customTaxonomies" name="customTaxonomies[]" multiple="multiple">
                <?php
                foreach ($customTaxonomies as $taxonomies) {
                    $selected = "";
                    if (in_array($taxonomies, $selectedCustomTaxCols))
                        $selected = "selected=selected";
                    ?>
                    <option  <?php echo $selected; ?> value="<?php echo $taxonomies; ?>"><?php echo $taxonomies; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name"> Woocommerce Product Meta</div><div class="help-inline" title="List of woocommerce product meta fields "></div></label>
        <div class="controls">
            <select class="small w-wrap" id="wooMeta" name="wooMeta[]" multiple="multiple">
                <?php
                foreach ($allWooMeta as $meta) {
                    $selected = "";
                    if (in_array($meta, $selectedWooMeta))
                        $selected = "selected=selected";
                    ?>
                    <option  <?php echo $selected; ?> value="<?php echo $meta; ?>"><?php echo $meta; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Default Post Date</div> <div class="help-inline" title="If you don't enter any information for post date in import, then selected post date information will be saved."></div></label>
        <div class="controls">
            <select class="small w-wrap" id="postDate" name="postDate">
                <option value="currentdate">Current Date</option>
                <option value="setdate">Set Date</option>
            </select>
            <input type="text" placeholder="yyyy-mm-dd format" name="setDate" id="setDate" class="datepicker" style="display:none" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Duplicate Post Title</div> <div class="help-inline" title="What to do when import contains duplicate post title? Skip that post or Update that post."></div></label>
        <div class="controls">
            <select class="small w-wrap" id="duplicateEntry" name="duplicateEntry">
                <option value="skip" <?php if ($option["duplicateEntry"] == "skip") echo "selected=selected"; ?> >Skip Post</option>
                <option value="update" <?php if ($option["duplicateEntry"] == "update") echo "selected=selected"; ?>>update Post</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Category Separator</div><div class="help-inline" title="Category separator for post import"></div></label>
        <div class="controls">
            <input type="text" value="<?php echo $option["categorySeparator"]; ?>" name="categorySeparator" id="categorySeparator" title="Category separator for post import" />

        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Custom Taxo. Separator</div> <div class="help-inline" title="Only selected custom taxonmoies will be showed at the time of import for mapping custom taxonmoies."></div></label>
        <div class="controls">
            <input type="text" value="<?php echo $option["termSeparator"]; ?>" name="termSeparator" id="termSeparator" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Set Memory size</div> <div class="help-inline" title="If you are importing many rows of data, then php memory needs to be more."></div></label>
        <div class="controls">
            <input type="text" value="<?php echo $option["inisetting"]; ?>" name="inisetting" id="inisetting" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <div class="setting_name">Import Post Content Images </div><div class="help-inline" title="If yes, then it will try to download images from post content. Function will try to download files in same folder so that after replacing urls, it will automatically work."></div></label>
        <div class="controls">
            <input type="radio" class="postContentImg postContentImgChk" name="postContentImg" id="" value="yes"  <?php if ($option["postContentImg"] == "yes") echo "checked==checked"; ?>/>Yes
            <input type="radio" class="postContentImg postContentImgUnchk" name="postContentImg" id="" value="no"  <?php if ($option["postContentImg"] == "no") echo "checked==checked"; ?> />No

        </div>
    </div>
    <div class="control-group featureImage">
        <label class="control-label"> <div class="setting_name">Set 1st Image As Featured </div><div class="help-inline" title="If yes, this will set first image of post content as featured image"></div></label>
        <div class="controls">
            <input type="radio" class="setFeatureImgByDefault" name="setFeatureImgByDefault" id="" value="yes" <?php if ($option["setFeatureImgByDefault"] == "yes") echo "checked==checked"; ?>/>Yes
            <input type="radio" class="setFeatureImgByDefault" name="setFeatureImgByDefault" id="" value="no" <?php if ($option["setFeatureImgByDefault"] == "no") echo "checked==checked"; ?>/>No

        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
            <div class="setting_name">Create hierarchical category </div>
           <div class="help-inline" title="If yes, it will create categories in hierarchical order."></div>
        </label>
        <div class="controls">
            <input type="radio" name="categorySetting" value="yes" <?php if ($option["categorySetting"] == "yes") echo "checked==checked"; ?>/>Yes
            <input type="radio" name="categorySetting" value="no" <?php if ($option["categorySetting"] == "no") echo "checked==checked"; ?>/>No
        </div>
    </div>

</div>
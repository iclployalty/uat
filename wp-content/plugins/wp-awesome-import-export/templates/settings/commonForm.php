<div id="awesome-content" class="settings">
    <div id="wpaie_tabs" class="wpaie_tabs">
        <nav>
            <ul class="tabElements">
                <li id="tabImport"><a href="#tab-1" class="icon-shop">Import Settings</a></li>
                <li id="tabExport"><a href="#tab-2" class="icon-cup">Export Settings</a></li>
                <li id="tabGeneral"><a href="#tab-3" class="icon-food">General Settings</a></li>
            </ul>
        </nav>
        <div class="wp-awesome-content">
            <form method="post" id="importSettingForm" class="submitWPAIEForm">
                <section id="tab-1">
                    <div class="formControls">
                        <div class="heading">
                            <span>Import Settings</span>
                        </div>    
                        <div class="control-group">
                            <label class="control-label">Select Post Columns</label>
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
                                <span title="Only selected post status will be showed at the time of import for mapping post columns." class="help-inline"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Default Post Status</label>
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
                                <span class="help-inline" title="If you don't enter any information for post status in import, then selected post status will be saved."></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Select Post Meta</label>
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
                                <span class="help-inline" title="Only selected post meta will be showed at the time of import for mapping post meta."></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Select Custom Taxonmoies</label>
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
                                <span class="help-inline" title="Only selected custom taxonmoies will be showed at the time of import for mapping custom taxonmoies.">
                                </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Default Post Date</label>
                            <div class="controls">
                                <select class="small w-wrap" id="postDate" name="postDate">
                                    <option value="currentdate">Current Date</option>
                                    <option value="setdate">Set Date</option>
                                </select>
                                <input type="text" placeholder="yyyy-mm-dd format" name="setDate" id="setDate" class="datepicker" style="display:none" />
                                <span class="help-inline" title="If you don't enter any information for post date in import, then selected post date information will be saved."></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Duplicate Post Title</label>
                            <div class="controls">
                                <select class="small w-wrap" id="duplicateEntry" name="duplicateEntry">
                                    <option value="skip" <?php if ($option["duplicateEntry"] == "skip") echo "selected=selected"; ?> >Skip Post</option>
                                    <option value="update" <?php if ($option["duplicateEntry"] == "update") echo "selected=selected"; ?>>update Post</option>
                                </select>
                                <span class="help-inline" title="What to do when import contains duplicate post title? Skip that post or Update that post."></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"> Category Separator</label>
                            <div class="controls">
                                <input type="text" value="<?php echo $option["categorySeparator"]; ?>" name="categorySeparator" id="categorySeparator" title="Category separator for post import" />
                                <span class="help-inline" title="Category separator for post import"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"> Custom Taxo. Separator</label>
                            <div class="controls">
                                <input type="text" value="<?php echo $option["termSeparator"]; ?>" name="termSeparator" id="termSeparator" />
                                <span class="help-inline" title="Custom Taxonomy separator for importing post"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"> Woocommerce Product Meta</label>
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
                                <span class="help-inline" title="List of woocommerce product meta fields "></span>
                            </div>
                        </div>
                        
                    </div>
                </section>
                <section id="tab-2">
                    <div class="formControls">
                        <div class="heading">
                            <span>Export Settings</span>
                        </div>    
                        <div class="control-group">
                            <label class="control-label"> Author Details</label>
                            <div class="controls">
                                <select class="small w-wrap" id="authorDetails" name="authorDetails">
                                    <option <?php if ($option['authorDetails'] == "authorId") echo "selected=selected"; ?> value="authorId">Show Author Id</option>
                                    <option <?php if ($option['authorDetails'] == "authorName") echo "selected=selected"; ?> <?php ?> value="authorName">Show Author Name</option>
                                </select>
                                <span title="Whether to show authorId or authorName while exporting Posts" class="help-inline"></span>
                            </div>
                        </div>
                    </div> 
                </section>
                <section id="tab-3">
                    <div class="formControls">
                        <div class="heading">
                            <span>General Settings</span>
                        </div>    
                        <div class="control-group">
                            <label class="control-label">CSV Delimiter</label>
                            <div class="controls">
                                <input type="text" name="csvDelimiter" id="csvDelimiter" value="," />
                                <span title="CSV File Delimiter" class="help-inline"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"> XMl Root Element</label>
                            <div class="controls">
                                <input type="text" name="xmlRootElement" id="xmlRootElement" value="root" />
                                <span title="xml root element to be exported." class="help-inline"></span>
                            </div>
                        </div>
                        
                    </div> 
                </section>
            </form>
        </div>
    </div>
</div>
<script>
    jQuery(function($) {
        $("#wpaie_tabs").tabs().addClass("tab-current");
        $('#lastActivateTabId').val(0);
        $('.ui-tabs-active').addClass('tab-current');
        $("#wpaie_tabs li").click(function() {
            $("#wpaie_tabs li").removeClass('tab-current');
            $(this).addClass('tab-current');
        });

<?php if (isset($_POST['lastActivateTabId'])) { ?>
            $("#wpaie_tabs li").removeClass('tab-current');
            $("#wpaie_tabs li").eq(<?php echo $_POST['lastActivateTabId']; ?>).addClass('tab-current');
            $("#wpaie_tabs").tabs({active: <?php echo $_POST['lastActivateTabId']; ?>});
<?php } ?>
    });
</script>
<div id="awesome-content" class="import">
    <div id="wpaie_tabs" class="wpaie_tabs">
        <nav>
            <ul class="tabElements">
                <li id="tabPost" class="tab-current"><a href="#tabs-1" class="icon-shop">Post</a></li>
                <li id="tabPage"><a href="#tabs-2"  class="icon-shop">Pages</a></li>
                <li id="tabCategory"><a href="#tabs-3" class="icon-shop tab-current">Categories/Tags</a></li>
                <li id="tabComment"><a href="#tabs-4" class="icon-shop">Comments</a></li>
                <li id="tabUser"><a href="#tabs-5" class="icon-shop">User/Roles</a></li>
                <li id="tabTaxonomy"><a href="#tabs-6" class="icon-shop">Custom Taxo.</a></li>
                <li id="tabCustomPost"><a href="#tabs-7" class="icon-shop">Custom Post</a></li>
                <li id="tabWPTable"><a href="#tabs-8" class="icon-shop">Any WP Table</a></li>
                <li id="tabPlugins"><a href="#tabs-9" class="icon-shop">Plugins</a></li>
                <li id="tabPlugins"><a href="#tabs-10" class="icon-shop">Menu</a></li>
            </ul>
        </nav>
        <div class="wp-awesome-content">
            <section id="tabs-1">
                <?php
                $this->getUploadFileControl("Post");
                if (isset($_POST["uploadFileSubmitPost"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "Post");
                }
                ?>
            </section>
            <section id="tabs-2" style="display: none;">
                <?php
                $this->getUploadFileControl("Page");
                if (isset($_POST["uploadFileSubmitPage"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "Page");
                }
                ?>
            </section>
            <section id="tabs-3" style="display: none;">
                <?php
                $this->getUploadFileControl("Category");
                if (isset($_POST["uploadFileSubmitCategory"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "Category");
                }
                ?>
            </section>
            <section id="tabs-4" style="display: none;">
                <?php
                $this->getUploadFileControl("Comment");
                if (isset($_POST["uploadFileSubmitComment"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "Comment");
                }
                ?>
            </section>
            <section id="tabs-5" style="display: none;">
                <?php
                $this->getUploadFileControl("User");
                if (isset($_POST["uploadFileSubmitUser"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "User");
                }
                ?>
            </section>
            <section id="tabs-6" style="display: none;">
                <?php
                $this->getUploadFileControl("Taxonomy");
                if (isset($_POST["uploadFileSubmitTaxonomy"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "Taxonomy");
                }
                ?>
            </section>
            <section id="tabs-7" style="display: none;">
                <?php
                $this->getUploadFileControl("CustomPost");
                if (isset($_POST["uploadFileSubmitCustomPost"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "CustomPost");
                }
                ?>
            </section>
            <section id="tabs-8" style="display: none;">
                <?php
                $this->getUploadFileControl("WPTable");
               if (isset($_POST["uploadFileSubmitWPTable"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "WPTable");
                }
                ?>
            </section>
            <section id="tabs-9" style="display: none;">  
                <?php
                if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                    $this->getUploadFileControl("Plugins");
                    if (isset($_POST["uploadFileSubmitPlugins"])) {
                        $data = $this->renderUploadedFile();
                        $this->mapFields($data, "Plugins");
                    }
                }else{
                    echo "<h3 style='color:#fff;text-align:center;'>Please install woocommerce first</h3>";
                }
                ?>
            </section>
             <section id="tabs-10" style="display: none;">
                <?php
               $this->getUploadFileControl("MENU");
               if (isset($_POST["uploadFileSubmitMENU"])) {
                    $data = $this->renderUploadedFile();
                    $this->mapFields($data, "MENU");
                }
                ?>
            </section>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(function ($) {
<?php if (isset($_POST['lastActivateTabId'])) { ?>
            $("#wpaie_tabs li").removeClass('tab-current');
            $("#wpaie_tabs li").eq(<?php echo $_POST['lastActivateTabId']; ?>).addClass('tab-current');
            $("#wpaie_tabs").tabs({active: <?php echo $_POST['lastActivateTabId']; ?>});
<?php } ?>
    });
</script>
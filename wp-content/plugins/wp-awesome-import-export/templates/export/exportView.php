 <?php 
    $woocommerce_active = false;
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        $woocommerce_active = true;
    }
?>
<div id="awesome-content" class="export">
    <div id="wpaie_tabs" class="wpaie_tabs">
        <nav>
            <ul class="tabElements">
                <li id="tabPost"><a href="#tabs-1" class="icon-shop">Post</a></li>
                <li id="tabPage"><a href="#tabs-2"  class="icon-shop">Pages</a></li>
                <li id="tabCategory"><a href="#tabs-3" class="icon-shop tab-current">Categories/Tags</a></li>
                <li id="tabComment"><a href="#tabs-4" class="icon-shop">Comments</a></li>
                <li id="tabUser"><a href="#tabs-5" class="icon-shop">User/Roles</a></li>
                <li id="tabTaxonomy"><a href="#tabs-6" class="icon-shop">Custom Taxo.</a></li>
                <li id="tabCustomPost"><a href="#tabs-7" class="icon-shop">Custom Post</a></li>
                <li id="tabWPTable"><a href="#tabs-8" class="icon-shop">WP Tables</a></li>
                <?php if (WPAIE_SQL_ALLOW) { ?>
                    <li id="tabSQL"><a href="#tabs-9" class="icon-shop">SQL</a></li>
                <?php } ?>
                <li id="tabPlugins"><a href="#tabs-10" class="icon-shop">Plugins</a></li>
                <li id="tabPlugins"><a href="#tabs-11" class="icon-shop">Woo-Order</a></li>
                <li id="tabPlugins"><a href="#tabs-12" class="icon-shop">Menu</a></li>
            </ul>
        </nav>
        <div class="wp-awesome-content">
            <section id="tabs-1">
                <?php $this->getExportPostForm("POST"); ?>
            </section>
            <section id="tabs-2">
                <?php $this->getExportPostForm("PAGE"); ?>
            </section>
            <section id="tabs-3">
                <?php $this->getTaxonomyForm("Category"); ?>
            </section>
            <section id="tabs-4"> 
                <?php $this->getCommentForm("Comment"); ?>
            </section>
            <section id="tabs-5"> 
                <?php $this->getUserForm("User"); ?>
            </section>
            <section id="tabs-6">
                <?php $this->getTaxonomyForm("Taxonomy"); ?>
            </section>
            <section id="tabs-7">
                <?php $this->getExportPostForm("CustomPost"); ?>
            </section>
            <section id="tabs-8">
                <?php $this->getWPTableForm("WPTable"); ?>
            </section>
            <?php if (WPAIE_SQL_ALLOW) { ?>
                <section id="tabs-9">
                    <?php $this->getSQLForm("SQL"); ?>
                </section>
            <?php } ?>
            <section id="tabs-10">
                <?php
                if ( $woocommerce_active ) {
                    $this->getExportPluginForm("Plugins");
                }else{
                    echo "<h3 style='color:#fff;text-align:center;'>Please install woocommerce first</h3>";
                }
                ?>
            </section>
            <section id="tabs-11"> 
                 <?php
                if ( $woocommerce_active ) {
                   $this->getExportWooOrderForm("Order");
                }else{
                    echo "<h3 style='color:#fff;text-align:center;'>Please install woocommerce first</h3>";
                }
                ?>
            </section>
            <section id="tabs-12">
                <?php $this->getWpMenus("MENU"); ?>
            </section>
        </div>
    </div>
</div>
<script>
    jQuery(function ($) {
        $("#wpaie_tabs").tabs().addClass("tab-current");
        $('#lastActivateTabId').val(0);
        $('.ui-tabs-active').addClass('tab-current');
        $("#wpaie_tabs li").click(function () {
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
<?php
class ACS_View {
    public function __construct() {
    }

    public function importView() {
        ob_start();
        require_once WPAIE_PLUGIN_DIR . '/templates/import/importView.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function exportView() {
        ob_start();
        require_once WPAIE_PLUGIN_DIR . '/templates/export/exportView.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function fileManagerView($data) {
        global $wpdb;
        ob_start();
        require_once WPAIE_PLUGIN_DIR . '/templates/filemanager/filemanager.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
    
    function getFileManagerView($fileType,$data) {
        switch ($fileType) {
            case "IMPORT":
                $this->getImportFileManagerView($data);
                break;
            case "EXPORT":
                $this->getExportFileManagerView($data);
                break;
        }
    }

    function getImportFileManagerView($data) {
        $importFileManager=$data["IMPORT"];
        require_once WPAIE_PLUGIN_DIR . '/templates/filemanager/importFileManager.php';
    }

    function getExportFileManagerView($data) {
        $exortFileManager=$data["EXPORT"];
        require_once WPAIE_PLUGIN_DIR . '/templates/filemanager/exportFileManagerView.php';
    }

    public function settingView() {
        ob_start();
        require_once WPAIE_PLUGIN_DIR . '/templates/settings/settingsView.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function getUploadFileControl($operationCategory) {
        require WPAIE_PLUGIN_DIR . '/templates/import/fileUploadView.php';
    }

    public function mapFields($data, $operationCategory) {
         global $uploadedFilePath,$fileRealPath;
        $mapFields = array();
        $postType = "";
        switch (strtoupper($operationCategory)) {
            case "POST":
                $mapFields = getPostFields();
                $postType = "POST";
                break;
            case "PAGE":
                $mapFields = getPageFields();
                $postType = "PAGE";
                break;
            case "CATEGORY":
                $mapFields = getCategoryFields();
                $taxonomyType = $_POST["taxonomyType"];
                break;
            case "COMMENT":
                $mapFields = getCommentFields();
                break;
            case "USER":
                $mapFields = getUserFields();
                break;
            case "TAXONOMY":
                $mapFields = getCategoryFields();
                $taxonomyType = $_POST["customTaxonomy"];
                break;
            case "CUSTOMPOST":
                $mapFields = getPostFields();
                $postType = $_POST["customPostType"];
                break;
            case "WPTABLE":
                $mapFields = getDBTableColumns();
                break;
            case "PLUGINS":
                $mapFields = getPluginFields();
                if ($_POST["thirdpartyplugins"] == "woocommerce_product")
                    $postType = "product";
                break;
            case "MENU":
                $mapFields = getImportWpMenus();
                break;
            default:
                break;
        }
        require WPAIE_PLUGIN_DIR . '/templates/import/importMapFields.php';
    }

    public function renderUploadedFile() {
        global $message, $uploadedFilePath,$fileRealPath;
        $data = array();
        if (isset($_POST["operationCategory"])) {
            $ACS = new ACS();
            $fileuploaded = uploadFile();
            if ($_POST['uploadFileUrl']) {
                $fileName = $uploadedFilePath;
            } else {
                $fileName = $fileuploaded["file"];
                $uploadedFilePath = $fileName;
                $fileRealPath=$fileuploaded["url"];
            }

            $fileExtension = getFileExtension($fileName);

            if ($fileExtension == "csv")
                $data = $ACS->csvToArray($fileName);
            else if ($fileExtension == "xls" || $fileExtension == "xlsx")
                $data = $ACS->excelToArray($fileName);
            else if ($fileExtension == "xml") {
                $data = $ACS->xmlToArray($fileName);
                $data = $ACS->formatInputData("xml", $data);
            }
        }
        return $data;
    }

    function showOutputResult($operationCategory) {
        require WPAIE_PLUGIN_DIR . '/templates/export/showResult.php';
    }
    
    function getWpMenus($operationType){
        $args = array(
            'orderby'           => 'name', 
            'order'             => 'ASC',
            'hide_empty'        => false, 
        );
        $menus = get_terms('nav_menu',$args);
        require WPAIE_PLUGIN_DIR . '/templates/export/menuForm.php';
    }

    function getSQLForm($operationType) {
        require WPAIE_PLUGIN_DIR . '/templates/export/sqlForm.php';
    }

    function getWPTableForm($operationType) {
        $ACS = new ACS();
        $tables = $ACS->getDBTables();
        require WPAIE_PLUGIN_DIR . '/templates/export/wpTableForm.php';
    }

    function getUserForm($operationType) {
        $ACS = new ACS();

        $userFields = array(
            "ID",
            "user_login",
            "user_nicename",
            "user_email",
            "user_url",
            "user_registered",
            "user_activation_key",
            "user_status",
            "display_name",
        );

        $metaFields = $ACS->getUserMeta();
        require WPAIE_PLUGIN_DIR . '/templates/export/userForm.php';
    }

    function getCommentForm($operationType) {
        $ACS = new ACS();
        $commentFields = array(
            "comment_ID",
            "comment_post_ID",
            "comment_author",
            "comment_author_email",
            "comment_author_url",
            "comment_author_IP",
            "comment_date",
            "comment_content",
            "comment_approved",
            "comment_type",
            "user_id",
            "comment_parent",
            "comment_agent",
            "comment_karma"
        );

        $commentStatus = array(
            "approve",
            "hold",
            "spam",
            "trash",
            "post-trashed"
        );
        $postStatus = array(
            'publish',
            'future',
            'draft',
            'pending',
            'private',
            'inherit'
        );
        require WPAIE_PLUGIN_DIR . '/templates/export/commentForm.php';
    }

    function getTaxonomyForm($taxonomyType) {
        $ACS = new ACS();
        $customFields = $ACS->getCustomTaxonomies();
        require WPAIE_PLUGIN_DIR . '/templates/export/taxonomyForm.php';
    }

    function getExportPostForm($postType) {
        global $postStatus;

        $ACS = new ACS();

        $postStatus = array(
            'publish',
            'future',
            'draft',
            'pending',
            'private',
            'inherit'
        );

        $option = get_option('wpaieOptions');

        $postColumns = array(
            'ID',
            'post_title',
            'post_content',
            'post_excerpt',
            'post_date',
            'post_name',
            'post_author',
            'post_parent',
            'post_status'
        );

        $customFields = $ACS->getCustomTaxonomies();

        $metaFields = $ACS->getPostMeta();

        $customPostTypes = $ACS->getCustomPostType();

        require WPAIE_PLUGIN_DIR . '/templates/export/postForm.php';
    }

    function getExportPluginForm($pluginName) {
        global $pluginName;

        $ACS = new ACS();

        $postStatus = array(
            'publish',
            'future',
            'draft',
            'pending',
            'private',
            'inherit'
        );

        $option = get_option('wpaieOptions');

        $postColumns = array(
            'ID' => "product_id",
            'post_title' => "product_name",
            'post_content' => "product_content",
            'post_excerpt' => "product_short_desc",
            'post_date' => "publish_date",
            'post_name' => "product_slug",
            'post_parent' => "product_parent",
            'post_status' => "product_status"
        );

        $customFields = get_object_taxonomies("product");

        $metaFields = $option["woocommerceProductMeta"];

        require WPAIE_PLUGIN_DIR . '/templates/export/pluginForm.php';
    }

    function getExportWooOrderForm($order) {

        global $order;

        $ACS = new ACS();
      
        $orderStatus = wc_get_order_statuses();
        $fieldInCSV = array(
            'order_id',
            'customer_name',
            'order_type',
            'order_status',
            'product_name',
            'product_tax',
            'quantity',
            'amount_paid',
        );

        $details = array('billing', 'shipping');

        require WPAIE_PLUGIN_DIR . '/templates/export/orderForm.php';
    }

    function getSettingForm($settingType) {
        switch ($settingType) {
            case "IMPORT":
                $this->getImportSettingForm();
                break;
            case "EXPORT":
                $this->getExportSettingForm();
                break;
            case "GENERAL":
                $this->getGeneralSettingForm();
                break;
        }
    }

    function getGeneralSettingForm() {
        $option = get_option('wpaieOptions');
        require_once WPAIE_PLUGIN_DIR . '/templates/settings/generalForm.php';
    }

    function getExportSettingForm() {
        $option = get_option('wpaieOptions');
        require_once WPAIE_PLUGIN_DIR . '/templates/settings/exportForm.php';
    }

    function getImportSettingForm() {
        $ACS = new ACS();

        $postColums = array(
            'post_title',
            'post_content',
            'post_excerpt',
            'post_date',
            'post_name',
            'post_author',
            'post_parent',
            'post_status',
            'post_tag',
            'post_category',
            'featured_image',
        );

        $postStatus = array(
            'publish',
            'future',
            'draft',
            'pending',
            'private',
            'auto-draft',
            'inherit',
            'trash'
        );

        $metaFields = $ACS->getPostMeta();

        $customTaxonomies = $ACS->getCustomTaxonomies();

        $option = get_option('wpaieOptions');

        $selectedPostCols = $option["postFields"];

        $selectedPostMetaCols = $option["postMetaFields"];

        $selectedCustomTaxCols = $option["customTaxonomiesFields"];

        $selectedWooMeta = $option["woocommerceProductMeta"];

        $allWooMeta = array(
            "_product_image_gallery",
            "_product_attributes",
            "_product_variation",
            "_visibility",
            "_stock_status",
            "total_sales",
            "_downloadable",
            "_virtual",
            "_regular_price",
            "_sale_price",
            "_purchase_note",
            "_featured",
            "_weight",
            "_length",
            "_width",
            "_height",
            "_sku",
            "_sale_price_dates_from",
            "_sale_price_dates_to",
            "_price",
            "_sold_individually",
            "_stock",
            "_backorders",
            "_manage_stock",
            "post_views_count"
        );
        require_once WPAIE_PLUGIN_DIR . '/templates/settings/importForm.php';
    }

}
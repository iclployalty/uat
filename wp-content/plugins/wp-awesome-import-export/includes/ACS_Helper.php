<?php

function array_values_recursive($array) {
    $array = array_values($array);
    for ($loop = 0; $loop < count($array); $loop++) {
        $element = $array[$loop];
        if (is_array($element)) {
            $array[$loop] = array_values_recursive($element);
        }
    }
    return $array;
}

function uploadFile() {
    global $uploadedFilePath,$fileRealPath;

    if (!function_exists('wp_handle_upload')) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    if (!empty($_FILES['uploadFile']['name'])) {
        $upload_overrides = array('test_form' => false, 'mimes' => array('csv' => 'text/csv', 'xls' => 'application/vnd.ms-excel', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'xml' => 'application/xml'));
        $uploadedfile = $_FILES['uploadFile'];
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
    } else if ($_POST['uploadFileUrl']) {
        if (is_valid_url($_POST['uploadFileUrl'])) {
            $url = $_POST['uploadFileUrl'];
            $uploads = wp_upload_dir();
            $filename = substr($url, (strrpos($url, '/')) + 1);
            $uploads = wp_upload_dir(current_time('mysql'));
            if (!is_dir($uploads['basedir'])) {
                return false;
            }
            $uniqueFileName = wp_unique_filename($uploads['path'], $filename);
            $uploadedFilePath = $uploads['path'] . "/$uniqueFileName";
            $uploaded = copy($url, $uploadedFilePath);
        }
    }

    if ($movefile && !isset($movefile['error'])) {
        return $movefile;
    } else {
        echo $movefile['error'];
    }
}

function getFileExtension($fileName) {
    return pathinfo($fileName, PATHINFO_EXTENSION);
}

function isMulti($array) {
    return (count($array) != count($array, COUNT_RECURSIVE));
}

function getTwoDimensionalArray($content) {
    if (isMulti($content[0])) {
        $content = $content[0];
        return getTwoDimensionalArray($content);
    } else {
        return $content;
    }
}

function getPostFields() {
    $ACS = new ACS();
    $option = get_option('wpaieOptions');
    $postColumns = $option["postFields"];

    $customTaxonomies = $option["customTaxonomiesFields"];
    if (is_array($customTaxonomies)) {
        array_walk($customTaxonomies, 'addPrefix', 'CT');
        $postColumns = array_merge($postColumns, $customTaxonomies);
    }

    $metaFields = $option["postMetaFields"];
    if (is_array($metaFields)) {
        array_walk($metaFields, 'addPrefix', 'PM');
        $postColumns = array_merge($postColumns, $metaFields);
    }

    return $postColumns;
}

function getPageFields() {
    $ACS = new ACS();
    $option = get_option('wpaieOptions');
    $postColumns = $option["postFields"];

    if (($key = array_search("post_tag", $postColumns)) !== false) {
        unset($postColumns[$key]);
    }

    if (($key = array_search("post_category", $postColumns)) !== false) {
        unset($postColumns[$key]);
    }

    $metaFields = $option["postMetaFields"];
    if (is_array($metaFields)) {
        array_walk($metaFields, 'addPrefix', 'PM');
        $postColumns = array_merge($postColumns, $metaFields);
    }
    return $postColumns;
}

function getImportWpMenus() {
    $ACS = new ACS();
    $option = get_option('wpaieOptions');
    $postColumns = array(
        '_menu_item_type' => "navigation_type",
        '_menu_item_object' => "navigation_object",
        'ID' => "menu_object_id",
        "menu_parent_id"=>"menu_parent_id",
         'post_id' => "post_id",
        'post_title' => "navigation_name",
        'guid' => "menu_url",
        'menu_order' => "menu_order",
        'menu_name' => "menu_name",
    );

    return $postColumns;
}

function getPluginFields() {
    $ACS = new ACS();

    $option = get_option('wpaieOptions');

    $postColumns = array(
        'post_title' => "product_name",
        'post_content' => "product_content",
        'post_excerpt' => "product_short_desc",
        'post_date' => "publish_date",
        'post_name' => "product_slug",
        'featured_image' => "featured_image",
        'post_parent' => "product_parent",
        'post_status' => "product_status"
    );

    if ($_POST["thirdpartyplugins"] == "woocommerce_product") {
        $customTaxonomies = get_object_taxonomies("product");
        if (is_array($customTaxonomies)) {
            array_walk($customTaxonomies, 'addPrefix', 'CT');
            $postColumns = array_merge($postColumns, $customTaxonomies);
        }

        $metaFields = $option["woocommerceProductMeta"];
        if (is_array($metaFields)) {
            array_walk($metaFields, 'addPrefix', 'PM');
            $postColumns = array_merge($postColumns, $metaFields);
        }
    }
    return $postColumns;
}

function addPrefix(&$item, $key, $prefix) {
    $item = "$prefix: $item";
}

function addStatusPrefix(&$item, $key, $prefix) {
    $item = "$prefix-$item";
}

function getCategoryFields() {
    $categories = array(
        "name",
        "slug",
        "description",
        "parent"
    );
    return $categories;
}

function getCommentFields() {
    $comments = array(
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
    return $comments;
}

function getUserFields() {
    $users = array(
        "user_login",
        "user_nicename",
        "user_email",
        "user_url",
        "user_registered",
        "user_activation_key",
        "user_status",
        "display_name",
        "role",
        "first_name",
        "last_name",
        "nickname",
        "jabber",
        "aim",
        "yim",
        "user_pass",
        "description",
    );
    return $users;
}

function getDBTableColumns() {
    $ACS = new ACS();
    return $ACS->getDBTableColumns($_POST["wpTables"]);
}

function is_valid_url($url) {
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE)
        return false;
    else
        return true;
}
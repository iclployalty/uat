<?php
class ACS {
    
    private $db;
    private $view;
    public $error;
    public $messages = "";
    public $inputDataArray = array();
    public $formattedData = array();
    public $fileOutputMode = "save";
    public $checkFileName = true;
    public $checkFileNameCharacters = true;
    public $replaceOlderFile = false;
    public $uploadedFileName = "";
    public $fileUploadPath = "";
    public $maxSize = 100000;
    public $uploadDirectory = "wpaie_files";
    public $pdfFontName = "helvetica";
    public $pdfFontSize = "8";
    public $pdfFontWeight = "B";
    public $pdfAuthorName = "Author Name";
    public $pdfSubject = "PDF Subject Name";
    public $excelFormat = "2007";
    public $columns = array();
    public $dbTableName;
    public $query;
    public $isFirstRowHeader = false;
    public $delimiter = ",";
    public $enclosure = '"';
    public $isFile = true;
    public $useFirstRowAsTag = false;
    public $outputHTML = "";
    public $tableCssClass = "tblCss";
    public $trCssClass = "trCss";
    public $htmlTableStyle = "";
    public $htmlTRStyle = "";
    public $htmlTDStyle = "";
    public $outputXML = "";
    public $rootElement = "root";
    public $encoding = "utf-8";
    public $rowTagName = "";
    public $append = false;
    public $existingFilePath;
    public $operationType;
    public $operationCategory;
    public $postType;
    public $postMeta;
    public $customTaxonomies;
    public $exportPostRange;
    public $exportFeaturedImage;
    public $exportFileType;
    public $postStatus;
    public $sql;
    public $commentFields;
    public $commentStatus;
    public $orderBy;
    public $orderAscDesc;
    public $hideEmpty = false;
    public $read2lines = false;
    public $output = array();
    public $taxonomy;
    public $pluginName;
    public $postStartRange;
    public $postTotalCount;
    public $postEndRange;
    public $optionNoOfPost;
    public $userMeta;
    public $byPostId;
    public $specificPostById;    
    public $orderStatus;
    public $orderdetails;
    public $orderFields;
    public $startDate;
    public $endDate;
    public $product_gallery = '';
    public $menufields = array();
    public $fileDataIds = '';
    
    function __construct() {
        ini_set( "default_charset", "UTF-8" );
        add_action( 'admin_menu', array(
             $this,
            'wpaie_menu' 
        ) );
        add_action( "admin_enqueue_scripts", array(
             $this,
            'wpaie_admin_scripts' 
        ) );
        add_action( 'wp_ajax_wpaie_ajax_action', array(
             $this,
            'wpaie_ajax_action_callback' 
        ) );
        add_filter( "plugin_action_links_" . WPAIE_PLUGIN_BASENAME, array(
             $this,
            'wpaie_plugin_settings_link' 
        ) );
        $this->db   = new ACS_Model();
        $this->view = new ACS_View();
        
        if ( isset( $_POST[ "submitMapping" ] ) ) {
            $this->renderImport();
        }
    }
    
    public function wpaie_menu() {
        global $wpaie_import, $wpaie_export, $wpaie_settings, $wpaie_file_manager;
        $wpaie_import       = add_menu_page( 'WP Awesome Import & Export', 'WP Awesome Import & Export', 'manage_options', 'wpaie-main', array(
             $this,
            'wpaie_import' 
        ) );
        $wpaie_import       = add_submenu_page( 'wpaie-main', 'WP Awesome Import', 'WP Import', 'manage_options', 'wpaie-main', array(
             $this,
            'wpaie_import' 
        ) );
        $wpaie_export       = add_submenu_page( 'wpaie-main', 'WP Awesome Export', 'WP Export', 'manage_options', 'wpaie_export', array(
             $this,
            'wpaie_export' 
        ) );
        $wpaie_settings     = add_submenu_page( 'wpaie-main', 'WP Awesome Import/Export Settings', 'Settings', 'manage_options', 'wpaie-setting', array(
             $this,
            'wpaie_setting' 
        ) );
        $wpaie_file_manager = add_submenu_page( 'wpaie-main', 'WP Awesome File Manager', 'File Manager', 'manage_options', 'wpaie_file_manager', array(
             $this,
            'wpaie_file_manager' 
        ) );
    }
    
    public function wpaie_admin_scripts() {
        global $wpaie_import, $wpaie_export, $wpaie_settings, $wpaie_file_manager;
        $screen = get_current_screen();
        
        if ( $screen->id != $wpaie_import && $screen->id != $wpaie_export && $screen->id != $wpaie_settings && $screen->id != $wpaie_dashboard && $screen->id != $wpaie_file_manager )
            return;
        
        wp_enqueue_style( 'jquery-ui', plugins_url( 'assets/css/jquery-ui.css', WPAIE_FILE ) );
        wp_enqueue_style( 'wpaie-style', plugins_url( 'assets/css/style.css', WPAIE_FILE ) );
        wp_enqueue_style( 'wpaie-tabs', plugins_url( 'assets/css/component.css', WPAIE_FILE ) );
        wp_enqueue_style( 'q-tooltip', plugins_url( 'assets/css/jquery.qtip.min.css', WPAIE_FILE ) );
        wp_enqueue_style( 'multiple-select', plugins_url( 'assets/css/multiple-select.css', WPAIE_FILE ) );
        wp_enqueue_style( 'select-2', plugins_url( 'assets/css/select2.min.css', WPAIE_FILE ) );
        wp_enqueue_style( 'dataTables-css', plugins_url( "assets/css/datatables.min.css", WPAIE_FILE ) );
        wp_enqueue_style( 'font-awesome-css', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" );
        
        wp_enqueue_script( 'jQuery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'wpaie-tabs', plugins_url( 'assets/js/jquery-ui.min.js', WPAIE_FILE ), array(
             'jquery' 
        ), WPAIE_VERSION );
        wp_enqueue_script( 'wpaie-script', plugins_url( 'assets/js/script.js', WPAIE_FILE ), array(
             'jquery' 
        ), WPAIE_VERSION );
        
        wp_enqueue_script( 'wpaie-validation-js', plugins_url( 'assets/js/validations.js', WPAIE_FILE ), array(
             'jquery' 
        ), WPAIE_VERSION );
        wp_enqueue_script( 'q-tooltip-js', plugins_url( 'assets/js/jquery.qtip.min.js', WPAIE_FILE ), array(
             'jquery' 
        ), WPAIE_VERSION );
        wp_enqueue_script( 'multiple-select-js', plugins_url( 'assets/js/jquery.multiple.select.js', WPAIE_FILE ), array(
             'jquery' 
        ), WPAIE_VERSION );
        wp_enqueue_script( 'dataTables-js', plugins_url( 'assets/js/datatables.min.js', WPAIE_FILE ), array(
             'jquery' 
        ), WPAIE_VERSION );
        wp_enqueue_script( 'select2-js', plugins_url( 'assets/js/select2.min.js', WPAIE_FILE ), array(
             'jquery' 
        ), WPAIE_VERSION );
        
        wp_register_script( 'wpaie-ajax', plugins_url( 'assets/js/ajax.js', WPAIE_FILE ), array(
             'jquery' 
        ), WPAIE_VERSION );
        $wpgeoip_global = array(
             'plugin_url' => WPAIE_PLUGIN_URL 
        );
        wp_localize_script( 'wpaie-ajax', 'wpgeoip_global', $wpgeoip_global );
        wp_enqueue_script( 'wpaie-ajax' );
    }
    
    public function wpaie_ajax_action_callback() {
        global $wpdb;
        $action = $_POST[ 'operation' ];
        switch ( $action ) {
            case "wpTables":
                $cols = $this->getColumnName( $_POST[ "tableName" ] );
                foreach ( $cols as $col ) {
                    echo "<option selected='selected' value='" . $col[ "Field" ] . "'>" . $col[ "Field" ] . "</option>";
                }
                break;
            case "import":
                $output = $this->renderImport();
                echo json_encode( $output );
                break;
            case "export":
                $output = $this->renderExport();
                echo json_encode( $output );
                break;
            case "wpDeleteFile":
                if ( file_exists( $_POST[ "filePath" ] ) ) {
                    if ( unlink( $_POST[ "filePath" ] ) ) {
                        $wpdb->delete( $wpdb->prefix . "wpaie_file_manager", array(
                             "file_id" => $_POST[ "fileId" ] 
                        ) );
                        return true;
                    }
                }
                break;
            case "wpDeleteAllRecords":
                $output = $this->renderDeleteRecords( $_POST[ "fileId" ] );
                break;
        }
        die();
    }
    
    public function wpaie_plugin_settings_link( $links ) {
        $settings_link = '<a href="admin.php?page=wpaie-setting">Settings</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }
    
    public function wpaie_import() {
        echo $this->view->importView();
    }
    
    public function wpaie_export() {
        echo $this->view->exportView();
    }
    
    function wpaie_file_manager() {
        global $wpdb;
        $data[ "IMPORT" ] = $this->db->dbSelect( $wpdb->prefix . 'wpaie_file_manager', array(), array(
             'file_info' => 'import' 
        ) );
        $data[ "EXPORT" ] = $this->db->dbSelect( $wpdb->prefix . 'wpaie_file_manager', array(), array(
             'file_info' => 'export' 
        ) );
        echo $this->view->fileManagerView( $data );
    }
    
    function wpaie_setting() {
        if ( isset( $_POST[ "submitCommonSettings" ] ) ) {
            $updateOptions = array(
                'duplicateEntry' => $_POST[ "duplicateEntry" ],
                'postFields' => $_POST[ "postColumns" ],
                "postMetaFields" => $_POST[ "postMeta" ],
                "postStatus" => $_POST[ "postStatus" ],
                "customTaxonomiesFields" => $_POST[ "customTaxonomies" ],
                "postDate" => $_POST[ "postDate" ],
                "dateval" => $_POST[ "dateval" ],
                "termSeparator" => $_POST[ "termSeparator" ],
                "categorySeparator" => $_POST[ "categorySeparator" ],
                "woocommerceProductMeta" => $_POST[ "wooMeta" ],
                'authorDetails' => $_POST[ "authorDetails" ],
                'csvDelimiter' => $_POST[ "csvDelimiter" ],
                'rootElement' => $_POST[ "xmlRootElement" ],
                'fileMailConfrimation' => $_POST[ "fileMailConfrimation" ],
                'setFeatureImgByDefault' => $_POST[ "setFeatureImgByDefault" ],
                'export_from' => $_POST[ "export_from" ],
                'export_subject' => $_POST[ "export_subject" ],
                'postContentImg' => $_POST[ "postContentImg" ],
                'inisetting' => $_POST[ "inisetting" ],
                'categorySetting' => $_POST[ "categorySetting" ] 
            );
            
            update_option( 'wpaieOptions', $updateOptions );
        }
        
        echo $this->view->settingView();
    }
    
    public function renderExport() {
        global $wpdb;
        $this->operationType = "EXPORT";
        $postData            = array();
        parse_str( $_POST[ 'exportData' ], $postData );
        $this->operationCategory = $postData[ "operationCategory" ];
        $this->exportFileType    = $postData[ "optionFileType" ];
        $this->orderBy           = $postData[ "orderBy" ];
        $this->orderAscDesc      = $postData[ "orderAscDesc" ];
        
        if ( strtoupper( $postData[ "operationCategory" ] ) == "CATEGORY" || strtoupper( $postData[ "operationCategory" ] ) == "TAXONOMY" ) {
            $this->customTaxonomies = $postData[ "taxonomyType" ];
            $this->hideEmpty        = $postData[ "hideEmpty" ];
        } else if ( strtoupper( $postData[ "operationCategory" ] ) == "COMMENT" ) {
            $this->columns       = $postData[ "commentFields" ];
            $this->postStatus    = $postData[ "postStatus" ];
            $this->commentStatus = $postData[ "commentStatus" ];
            $this->postAuthor    = $postData[ "postAuthor" ];
            $this->byPostId      = $postData[ "byPostId" ];
        } else if ( strtoupper( $postData[ "operationCategory" ] ) == "USER" ) {
            $this->columns  = $postData[ "userFields" ];
            $this->userMeta = $postData[ "userMeta" ];
            $this->userRole = $postData[ "userRole" ];
        } else if ( strtoupper( $postData[ "operationCategory" ] ) == "WPTABLE" ) {
            $this->columns     = $postData[ "wpTableColumns" ];
            $this->dbTableName = $postData[ "wpTables" ];
        } else if ( strtoupper( $postData[ "operationCategory" ] ) == "SQL" ) {
            $this->sql = $postData[ "sql" ];
        } else if ( strtoupper( $postData[ "operationCategory" ] ) == "WOOORDER" ) {
            $this->orderdetails = $postData[ "orderdetails" ];
            $this->columns      = $postData[ "orderFields" ];
            $this->startDate    = $postData[ "startDate" ];
            $this->endDate      = $postData[ "endDate" ];
            $this->orderStatus  = $postData[ "orderStatus" ];
        } else if ( strtoupper( $postData[ "operationCategory" ] ) == "MENU" ) {
            $this->menufields = $postData[ "menuFields" ];
        } else {
            $this->columns             = $postData[ "postColumns" ];
            $this->exportFeaturedImage = $postData[ "exportFeaturedImg" ];
            $this->postMeta            = $postData[ "postMeta" ];
            $this->customTaxonomies    = $postData[ "postCustomFields" ];
            $this->postStatus          = $postData[ "postStatus" ];
            $this->postType            = $postData[ "postType" ];
            $this->optionNoOfPost      = $postData[ "optionNoOfPost" ];
            if ( isset( $postData[ "postStartRange" ] ) )
                $this->postStartRange = $postData[ "postStartRange" ];
            if ( isset( $postData[ "postTotalCount" ] ) )
                $this->postTotalCount = $postData[ "postTotalCount" ];
            if ( isset( $postData[ "postEndRange" ] ) )
                $this->postEndRange = $postData[ "postEndRange" ];
            if ( isset( $postData[ "specificpostbyids" ] ) )
                $this->specificPostById = $postData[ "specificpostbyids" ];
        }
        $this->convert( $this->exportFileType, "db" );
        $upload      = wp_upload_dir();
        $getName     = substr( strrchr( $this->output[ "downloadLink" ], '/' ), 1 );
        $fileManager = array(
            "file_name" => $getName,
            "absolute_path" => $upload[ "basedir" ] . '/wpaie_files/' . $getName,
            "file_path" => $this->output[ "downloadLink" ],
            "file_type" => ucfirst( $postData[ "operationCategory" ] ),
            "file_info" => "export",
            "upload_time" => date( "Y/m/d H:i:s" ) 
        );
        $this->db->dbInsert( $wpdb->prefix . 'wpaie_file_manager', $fileManager );
        return $this->output;
    }
    
    public function renderImport() {
        global $wpdb;
        parse_str( $_POST[ 'importData' ], $postData );
        $uploadFilePath = $postData[ 'uploadFilePath' ];
        $importFileType = getFileExtension( $uploadFilePath );
        $dbColumn       = $postData[ 'dbColumn' ];
        
        if ( $importFileType == "xls" || $importFileType == "xlsx" )
            $importFileType = "excel";
        
        for ( $columnCount = 0; $columnCount < $dbColumn; $columnCount++ ) {
            if ( $postData[ 'dbColumn' . $columnCount ] === "new_meta" )
                $col[] = "PM: " . $postData[ 'tbColumn' . $columnCount ];
            else
                $col[] = $postData[ 'dbColumn' . $columnCount ];
        }
        
        $this->columns           = $col;
        $this->isFirstRowHeader  = true;
        $this->operationType     = "IMPORT";
        $this->operationCategory = $postData[ "operationCategory" ];
        
        if ( isset( $postData[ "taxonomyType" ] ) )
            $this->taxonomy = $postData[ "taxonomyType" ];
        
        if ( isset( $postData[ "postType" ] ) )
            $this->postType = $postData[ "postType" ];
        
        if ( isset( $postData[ "wpTable" ] ) )
            $this->dbTableName = $postData[ "wpTable" ];
        
        if ( isset( $postData[ "pluginName" ] ) )
            $this->pluginName = $postData[ "pluginName" ];
        
        $this->convert( $importFileType, "db", $uploadFilePath );
        $getName     = substr( strrchr( $postData[ 'uploadFilePath' ], '/' ), 1 );
        $fileManager = array(
            "file_name" => $getName,
            "absolute_path" => $uploadFilePath,
            "file_path" => $postData[ 'fileRealPath' ],
            "file_type" => $postData[ "operationCategory" ],
            "file_info" => "import",
            "imported_ids" => $this->output[ "fileDataIds" ],
            "upload_time" => date( "Y-m-d H:i:s" ) 
        );
        $this->db->dbInsert( $wpdb->prefix . 'wpaie_file_manager', $fileManager );
        return $this->output;
    }
    
    public function renderDeleteRecords( $fileID ) {
        global $wpdb;
        $getRecords   = $this->getFileData( $wpdb->prefix . "wpaie_file_manager", array(
             "file_id" => $fileID 
        ) );
        $file_type    = array(
            "page",
            "post",
            "custompost" 
        );
        $imported_ids = explode( ',', $getRecords[ 0 ][ "imported_ids" ] );
        if ( in_array( strtolower( $getRecords[ 0 ][ "file_type" ] ), $file_type ) ) {
            foreach ( $imported_ids as $ids ) {
                wp_delete_post( $ids );
            }
            echo "Operation done successfully.";
        } else if ( strtolower( $getRecords[ 0 ][ "file_type" ] ) == "category" || strtolower( $getRecords[ 0 ][ "file_type" ] ) == "taxonomy" ) {
            foreach ( $imported_ids as $ids ) {
                $taxonomyName = get_term( $ids );
                wp_delete_term( $ids, $taxonomyName->taxonomy );
            }
            echo "Operation done successfully.";
        } else if ( strtolower( $getRecords[ 0 ][ "file_type" ] ) == "comment" ) {
            foreach ( $imported_ids as $ids ) {
                wp_delete_comment( $ids );
            }
            echo "Operation done successfully.";
        } else if ( strtolower( $getRecords[ 0 ][ "file_type" ] ) == "user" ) {
            foreach ( $imported_ids as $ids ) {
                wp_delete_user( $ids );
            }
            echo "Operation done successfully.";
        } else if ( strtolower( $getRecords[ 0 ][ "file_type" ] ) == "plugins" ) {
            $this->db->dbExecuteQuery( 'DELETE wp_term_relationships.*,wp_term_taxonomy.*,wp_terms.* FROM wp_term_relationships INNER JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id=wp_term_taxonomy.term_taxonomy_id INNER JOIN wp_terms ON wp_term_taxonomy.term_id=wp_terms.term_id WHERE object_id IN (' . $getRecords[ 0 ][ "imported_ids" ] . ') and wp_term_taxonomy.taxonomy="product_type"' );
            foreach ( $imported_ids as $ids ) {
                wp_delete_post( $ids );
            }
            echo "Operation done successfully.";
        } else if ( strtolower( $getRecords[ 0 ][ "file_type" ] ) == "menu" ) {
            foreach ( $imported_ids as $ids ) {
                wp_delete_post( $ids );
            }
            echo "Operation done successfully.";
        }
        $wpdb->delete( $wpdb->prefix . "wpaie_file_manager", array(
             "file_id" => $fileID 
        ) );
    }
    
    public function getFileData( $tableName, $where ) {
        return $this->db->dbSelect( $tableName, array(), $where );
    }
    
    function getPostMeta() {
        return $this->db->getPostMeta();
    }
    
    function getUserMeta() {
        return $this->db->getUserMeta();
    }
    
    function getCustomTaxonomies() {
        return $this->db->getCustomTaxonomies();
    }
    
    function getCustomPostType() {
        return $this->db->getCustomPostType();
    }
    
    function getDBTables() {
        $tables   = $this->db->dbGetTableName();
        $dbtables = array();
        foreach ( $tables as $table ) {
            $key        = array_keys( $table );
            $dbtables[] = $table[ $key[ 0 ] ];
        }
        return $dbtables;
    }
    
    function getDBTableColumns( $dbTableName ) {
        $columns = $this->db->dbGetColumnName( $dbTableName );
        $col     = array();
        foreach ( $columns as $column ) {
            $col[] = $column[ "Field" ];
        }
        return $col;
    }
    
    function getColumnName( $table ) {
        return $this->db->dbGetColumnName( $table );
    }
    
    public function arrayToDB( $data ) {
        if ( !is_array( $data ) ) {
            $this->error = "Please provide valid input. ";
            return false;
        }
        $this->db->dbInsertBatch( $this->dbTableName, $data );
        if ( $this->db->rows_affected > 0 ) {
            $totalRows          = count( $data );
            $this->db->messages = " Database insert operation done successfully";
            unset( $this->output );
            $this->output[ "recordsRead" ]     = $totalRows;
            $this->output[ "recordsInserted" ] = $this->db->rows_affected;
            $this->output[ "recordsSkipped" ]  = $totalRows - $this->db->rows_affected;
            return true;
        } else {
            $this->error = $this->db->error_info;
            return false;
        }
        return false;
    }
    
    public function arrayToXML( $xmlArray, $outputFileName = "file.xml" ) {
        if ( !is_array( $xmlArray ) ) {
            $this->error = "Please provide valid input. ";
            return false;
        }
        $option            = get_option( 'wpaieOptions' );
        $catSeparator      = $option[ "categorySeparator" ];
        $this->rootElement = "POST";
        $xmlObject         = new SimpleXMLElement( "<?xml version=\"1.0\" encoding=\"$this->encoding\" ?><$this->rootElement></$this->rootElement>" );
        $this->generateXML( $xmlArray, $xmlObject, $this->rootElement );
        $fileSavePath = $this->getWPUploadDir();
        $fileName     = time() . $outputFileName;
        $xmlObject->asXML( $fileSavePath . "/" . $fileName );
        $upload_dir                     = wp_upload_dir();
        $this->output[ "downloadLink" ] = $upload_dir[ 'baseurl' ] . "/" . $this->uploadDirectory . "/" . $fileName;
        return true;
    }
    
    function arrayToHTML( $htmlArray, $outputFileName = "file.html", $isCalledFromPDF = false ) {
        if ( !is_array( $htmlArray ) ) {
            $this->error = "Please provide valid input. ";
            return false;
        }
        $table_output = '<table class="' . $this->tableCssClass . '" style="' . $this->htmlTableStyle . '">';
        $table_head   = "";
        if ( $this->useFirstRowAsTag == true )
            $table_head = "<thead>";
        $table_body = '<tbody>';
        $loop_count = 0;
        
        foreach ( $htmlArray as $k => $v ) {
            if ( $this->useFirstRowAsTag == true && $loop_count == 0 )
                $table_head .= '<tr class="' . $this->trCssClass . '" style="' . $this->htmlTRStyle . '" id="row_' . $loop_count . '">';
            else
                $table_body .= '<tr class="' . $this->trCssClass . '" style="' . $this->htmlTRStyle . '" id="row_' . $loop_count . '">';
            
            foreach ( $v as $col => $row ) {
                if ( $this->useFirstRowAsTag == true && $loop_count == 0 )
                    $table_head .= '<th style="' . $this->htmlTDStyle . '">' . $row . '</th>';
                else
                    $table_body .= '<td style="' . $this->htmlTDStyle . '">' . $row . '</td>';
            }
            $table_body .= '</tr>';
            if ( $this->useFirstRowAsTag == true && $loop_count == 0 )
                $table_body .= '</tr></thead>';
            
            $loop_count++;
        }
        
        $table_body .= '</tbody>';
        $table_output     = $table_output . $table_head . $table_body . '</table>';
        $this->outputHTML = $table_output;
        if ( $this->fileOutputMode == "save" && !$isCalledFromPDF ) {
            if ( $this->fileSavePath && !is_dir( $this->fileSavePath ) )
                mkdir( $this->fileSavePath );
            $fp = fopen( $this->fileSavePath . $outputFileName, "w+" );
            fwrite( $fp, $this->outputHTML );
            fclose( $fp );
        }
        
        return true;
    }
    
    function arrayToPDF( $pdfArray, $outputFileName = "file.pdf" ) {
        if ( !is_array( $pdfArray ) ) {
            $this->error = "Please provide valid input. ";
            return false;
        }
        
        require_once( dirname( __FILE__ ) . "/library/tcpdf/tcpdf.php" );
        $pdf = new TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
        $pdf->SetCreator( PDF_CREATOR );
        $pdf->SetFont( $this->pdfFontName, $this->pdfFontWeight, $this->pdfFontSize, '', 'false' );
        $pdf->SetAuthor( $this->pdfAuthorName );
        $pdf->SetSubject( $this->pdfSubject );
        $pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
        $pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
        $pdf->SetAutoPageBreak( TRUE, PDF_MARGIN_BOTTOM );
        if ( @file_exists( dirname( __FILE__ ) . '/lang/eng.php' ) ) {
            require_once( dirname( __FILE__ ) . '/lang/eng.php' );
            $pdf->setLanguageArray( $l );
        }
        $pdf->AddPage();
        $this->arrayToHTML( $pdfArray, "file.html", true );
        $pdf->writeHTML( $this->outputHTML, true, false, true, false, '' );
        $fileSavePath = $this->getWPUploadDir();
        $fileName     = time() . $outputFileName;
        
        $pdf->Output( $fileSavePath . "/" . $fileName, 'F' );
        $upload_dir                     = wp_upload_dir();
        $this->output[ "downloadLink" ] = $upload_dir[ 'baseurl' ] . "/" . $this->uploadDirectory . "/" . $fileName;
        return true;
    }
    
    function arrayToExcel( $excelArray, $outputFileName = "file.xlsx" ) {
        if ( !is_array( $excelArray ) ) {
            $this->error = "Please provide valid input. ";
            return false;
        }
        if ( $this->append && !isset( $this->existingFilePath ) ) {
            $this->error = "Please provide existing file path, you want to append data ";
            
            return false;
        }
        if ( empty( $outputFileName ) ) {
            if ( $this->excelFormat == "2007" )
                $outputFileName = "file.xlsx";
            else
                $outputFileName = "file.xls";
        }
        require_once( dirname( __FILE__ ) . "/library/PHPExcel/PHPExcel.php" );
        
        if ( $this->append ) {
            require_once( dirname( __FILE__ ) . "/library/PHPExcel/PHPExcel/IOFactory.php" );
            if ( !file_exists( $this->existingFilePath ) ) {
                $this->error = "Could not open " . $this->existingFilePath . " for reading! File does not exist.";
                return false;
            }
            $objPHPExcel = PHPExcel_IOFactory::load( $this->existingFilePath );
        } else {
            $objPHPExcel = new PHPExcel();
        }
        $objPHPExcel->setActiveSheetIndex( 0 );
        
        $cells    = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"
        );
        $colCount = 1;
        
        if ( $this->append )
            $colCount = $objPHPExcel->getActiveSheet()->getHighestRow() + 1;
        
        foreach ( $excelArray as $rows ) {
            $cellLoop = 0;
            foreach ( $rows as $row ) {
                $objPHPExcel->getActiveSheet()->setCellValue( $cells[ $cellLoop ] . $colCount, $row );
                $cellLoop++;
            }
            $colCount++;
        }
        if ( $this->excelFormat == "2007" ) {
            $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
        } else {
            $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
        }
        if ( $this->append ) {
            $objWriter->save( $this->existingFilePath );
        } else {
            $fileSavePath = $this->getWPUploadDir();
            $fileName     = time() . $outputFileName;
            $objWriter->save( $fileSavePath . "/" . $fileName );
            $upload_dir                     = wp_upload_dir();
            $this->output[ "downloadLink" ] = $upload_dir[ 'baseurl' ] . "/" . $this->uploadDirectory . "/" . $fileName;
        }
        
        return true;
    }
    
    function arrayToCSV( $csvArray, $fileName = "file.csv" ) {
        
        if ( !is_array( $csvArray ) ) {
            $this->error = "Please provide valid input. ";
            return false;
        }
        if ( !$fileName ) {
            $this->error = "Please provide the csv file name";
            return false;
        }
        if ( $this->append && !isset( $this->existingFilePath ) ) {
            $this->error = "Please provide existing file path, you want to append data ";
            return false;
        }
        $list         = $csvArray;
        $fileSavePath = $this->getWPUploadDir();
        $fileName     = time() . $fileName;
        $fp           = fopen( $fileSavePath . "/" . $fileName, 'w' );
        
        foreach ( $list as $fields ) {
            fputcsv( $fp, $fields, $this->delimiter, $this->enclosure );
        }
        $upload_dir                     = wp_upload_dir();
        $this->output[ "downloadLink" ] = $upload_dir[ 'baseurl' ] . "/" . $this->uploadDirectory . "/" . $fileName;
        fclose( $fp );
        return true;
    }
    
    function excelToArray( $fileName ) {
        if ( !$fileName ) {
            $this->error = "Please provide the excel file name";
            return false;
        }
        require_once( dirname( __FILE__ ) . "/library/PHPExcel/PHPExcel/IOFactory.php" );
        $objPHPExcel = PHPExcel_IOFactory::load( $fileName );
        return $objPHPExcel->getActiveSheet()->toArray( null, true, true, false );
    }
    
    function xmlToArray( $xmlSource ) {
        $xml       = file_get_contents( $xmlSource );
        $xmlObject = new SimpleXMLElement( $xml );
        return @json_decode( @json_encode( $xmlObject ), 1 );
    }
    
    function csvToArray( $fileName ) {
        if ( !$fileName ) {
            $this->error = "Please provide the csv file name";
            return false;
        }
        
        ini_set( 'auto_detect_line_endings', TRUE );
        $option = get_option( 'wpaieOptions' );
        
        if ( !empty( $option[ "csvDelimiter" ] ) )
            $this->delimiter = $option[ "csvDelimiter" ];
        $csvArray = array();
        
        if ( ( $handle = fopen( $fileName, "r" ) ) !== FALSE ) {
            $arrayIndex1 = 0;
            while ( ( $lineArray = fgetcsv( $handle, 0, $this->delimiter ) ) !== FALSE ) {
                for ( $arrayIndex2 = 0; $arrayIndex2 < count( $lineArray ); $arrayIndex2++ ) {
                    $csvArray[ $arrayIndex1 ][ $arrayIndex2 ] = $lineArray[ $arrayIndex2 ];
                }
                $arrayIndex1++;
                if ( $this->read2lines && $arrayIndex1 == 2 )
                    break;
            }
            fclose( $handle );
        }
        return $csvArray;
    }
    
    function htmlToArray( $htmlContent ) {
        $dom = new DOMDocument();
        if ( $this->isFile )
            $htmlContent = file_get_contents( $htmlContent );
        
        $html                    = $dom->loadHTML( $htmlContent );
        $dom->preserveWhiteSpace = false;
        $tables                  = $dom->getElementsByTagName( 'table' );
        $rows                    = $tables->item( 0 )->getElementsByTagName( 'tr' );
        $cols                    = $rows->item( 0 )->getElementsByTagName( 'th' );
        $row_headers             = NULL;
        foreach ( $cols as $node ) {
            $row_headers[] = $node->nodeValue;
        }
        $data = array();
        $rows = $tables->item( 0 )->getElementsByTagName( 'tr' );
        foreach ( $rows as $row ) {
            $cols = $row->getElementsByTagName( 'td' );
            $row  = array();
            $loop = 0;
            foreach ( $cols as $node ) {
                if ( $row_headers == NULL )
                    $row[] = $node->nodeValue;
                else
                    $row[ $row_headers[ $loop ] ] = $node->nodeValue;
                $loop++;
            }
            $data[] = $row;
        }
        
        return $data;
    }
    
    function convert( $from, $to, $inputSource = "", $outputFileName = "" ) {
        set_time_limit( 0 );
        $inisetting = get_option( "wpaieOptions" );
        if ( !empty( $inisetting[ "inisetting" ] ) && is_numeric( $inisetting[ "inisetting" ] ) )
            ini_set( 'memory_limit', $inisetting[ "inisetting" ] . 'M' );
        
        if ( $this->operationType == "EXPORT" ) {
            switch ( strtoupper( $this->operationCategory ) ) {
                case "POST":
                    $this->exportPosts();
                    break;
                case "PAGE":
                    $this->exportPosts();
                    break;
                case "CATEGORY":
                    $this->exportTaxonomies();
                    break;
                case "COMMENT":
                    $this->exportComments();
                    break;
                case "USER":
                    $this->exportUsers();
                    break;
                case "CUSTOMPOST":
                    $this->exportPosts();
                    break;
                case "WPTABLE":
                    $this->exportTable();
                    break;
                case "TAXONOMY":
                    $this->exportTaxonomies();
                    break;
                case "SQL":
                    $this->exportSQL();
                    break;
                case "PLUGINS":
                    $this->exportPlugin();
                    break;
                case "WOOORDER":
                    $this->exportWooOrders();
                    break;
                case "MENU":
                    $this->exportMenus();
                    break;
                default:
                    break;
            }
        } else if ( $this->operationType == "IMPORT" ) {
            $this->inputDataArray = $this->getInputData( $from, $inputSource, $outputFileName );
            $this->formattedData  = $this->formatInputData( $to, $this->inputDataArray, $from );
            $this->formattedData  = apply_filters( "wpaie_formatted_data", $this->formattedData, $this->operationCategory );
            switch ( strtoupper( $this->operationCategory ) ) {
                case "POST":
                    $this->importPosts();
                    break;
                case "PAGE":
                    $this->importPosts();
                    break;
                case "CATEGORY":
                    $this->importTaxonomies();
                    break;
                case "COMMENT":
                    $this->importComments();
                    break;
                case "USER":
                    $this->importUsers();
                    break;
                case "TAXONOMY":
                    $this->importTaxonomies();
                    break;
                case "CUSTOMPOST":
                    $this->importPosts();
                    break;
                case "WPTABLE":
                    $this->importTable();
                    break;
                case "PLUGINS":
                    $this->importPlugins();
                    break;
                case "MENU":
                    $this->importMenus();
                    break;
                default:
                    break;
            }
        }
    }
    
    function importPlugins() {
        switch ( strtoupper( $this->pluginName ) ) {
            case "WOOCOMMERCE_PRODUCT":
                $this->importWooProducts();
                break;
            default:
                break;
        }
    }
    
    function importTable() {
        return $this->arrayToDB( $this->formattedData );
    }
    
    function importUsers() {
        $recordsAdded      = 0;
        $totalRows         = count( $this->formattedData );
        $this->fileDataIds = '';
        foreach ( $this->formattedData as $users ) {
            $userData = array();
            $um       = array();
            
            foreach ( $users as $key => $val ) {
                if ( strpos( $key, "PM:" ) !== false ) {
                    $key        = trim( substr( $key, 4, strlen( $key ) - 1 ) );
                    $um[ $key ] = $val;
                } else {
                    $userData[ $key ] = $val;
                }
            }
            
            if ( isset( $users[ "user_registered" ] ) )
                $userData[ "user_registered" ] = date( 'Y-m-d H:i:s', strtotime( $users[ 'user_registered' ] ) );
            else
                $userData[ 'user_registered' ] = date( 'Y-m-d H:i:s' );
            
            $user = get_user_by( 'login', $userData[ "user_login" ] );
            
            if ( $user ) {
                $userData[ "ID" ] = $user->ID;
                if ( isset( $users[ "user_pass" ] ) )
                    $userData[ "user_pass" ] = wp_hash_password( $users[ "user_pass" ] );
                
                $userId = wp_update_user( $userData );
            } else {
                if ( isset( $users[ "user_pass" ] ) )
                    $userData[ "user_pass" ] = wp_hash_password( $users[ "user_pass" ] );
                else
                    $userData[ "user_pass" ] = wp_generate_password( 12, false );
                
                $userId = wp_insert_user( $userData );
            }
            
            $result = add_role( $users[ 'role' ], __( $users[ 'role' ] ), array(
                 'read' => true,
                'edit_posts' => true,
                'delete_posts' => false 
            ) );
            if ( null !== $result ) {
                
                $user_id = wp_update_user( array(
                     'ID' => $userId,
                    'role' => $users[ 'role' ] 
                ) );
                
            }
            
            if ( is_wp_error( $userId ) ) {
                $this->db->addMessages( __( "Error in adding/updating users: " . $userId->get_error_message() ), "Import-User" );
                unset( $userData );
                continue;
            } else {
                $recordsAdded++;
                wp_new_user_notification( $userId, $userData[ 'user_pass' ] );
                foreach ( $um as $key => $val ) {
                    if ( !empty( $val ) ) {
                        update_user_meta( $userId, $key, $val );
                    }
                }
            }
            $this->fileDataIds .= $userId . ',';
            do_action( "wpaie_importdata", $userId, 'user' );
            unset( $userData );
            unset( $um );
        }
        
        unset( $this->output );
        $this->output[ "fileDataIds" ]     = rtrim( $this->fileDataIds, "," );
        $this->output[ "recordsRead" ]     = $totalRows;
        $this->output[ "recordsInserted" ] = $recordsAdded;
        $this->output[ "recordsSkipped" ]  = $totalRows - $recordsAdded;
    }
    
    function importComments() {
        $recordsAdded      = 0;
        $totalRows         = count( $this->formattedData );
        $this->fileDataIds = '';
        foreach ( $this->formattedData as $comment ) {
            $commentData = array();
            $cm          = array();
            foreach ( $comment as $key => $val ) {
                if ( strpos( $key, "PM:" ) !== false ) {
                    $key        = trim( substr( $key, 4, strlen( $key ) - 1 ) );
                    $cm[ $key ] = $val;
                } else {
                    $commentData[ $key ] = $val;
                }
            }
            
            if ( isset( $comment[ "comment_date" ] ) )
                $commentData[ "comment_date" ] == date( 'Y-m-d H:i:s', strtotime( $comment[ 'comment_date' ] ) );
            else
                $commentData[ 'comment_date' ] = date( 'Y-m-d H:i:s' );
            
            $commentId = wp_insert_comment( $commentData );
            
            if ( $commentId > 0 )
                $recordsAdded++;
            
            foreach ( $cm as $key => $val ) {
                if ( !empty( $val ) ) {
                    add_comment_meta( $commentId, $key, $val );
                }
            }
            $this->fileDataIds .= $commentId . ',';
            do_action( "wpaie_importdata", $commentId, "comment" );
            unset( $commentData );
            unset( $cm );
        }
        
        unset( $this->output );
        $this->output[ "fileDataIds" ]     = rtrim( $this->fileDataIds, "," );
        $this->output[ "recordsRead" ]     = $totalRows;
        $this->output[ "recordsInserted" ] = $recordsAdded;
        $this->output[ "recordsSkipped" ]  = $totalRows - $recordsAdded;
    }
    
    function importTaxonomies() {
        $totalRows         = count( $this->formattedData );
        $recordsAdded      = 0;
        $taxonomy          = $this->taxonomy;
        $this->fileDataIds = '';
        foreach ( $this->formattedData as $category ) {
            $catData = array(
                 'name' => '' 
            );
            
            if ( isset( $category[ "name" ] ) )
                $catData[ "name" ] = $category[ "name" ];
            
            if ( isset( $category[ "description" ] ) )
                $catData[ "description" ] = $category[ "description" ];
            
            if ( isset( $category[ "parent" ] ) ) {
                if ( is_numeric( trim( $category[ "parent" ] ) ) ) {
                    $catData[ "parent" ] = $category[ "parent" ];
                } else if ( is_string( trim( $category[ "parent" ] ) ) ) {
                    $nameCat = get_category_by_slug( $category[ "parent" ] );
                    if ( $nameCat )
                        $catData[ "parent" ] = $nameCat->term_taxonomy_id;
                    else {
                        wp_create_category( $category[ "parent" ], 0 );
                        $nameCatNew          = get_category_by_slug( $category[ "parent" ] );
                        $catData[ "parent" ] = $nameCatNew->term_taxonomy_id;
                    }
                } else {
                    if ( !empty( $category[ "parent" ] ) ) {
                        $parent_term = term_exists( $category[ "parent" ], $taxonomy );
                        if ( $parent_term !== 0 && $parent_term !== null )
                            $catData[ "parent" ] = $parent_term[ 'term_id' ];
                        else
                            $this->db->addMessages( __( "Parent terms doesn't exists " ), "Parent-terms" );
                    }
                }
            }
            
            if ( isset( $category[ "slug" ] ) ) {
                $catData[ "slug" ] = str_replace( " ", "-", trim( $category[ "slug" ] ) );
            } else {
                $termname = get_term_by( 'name', $category[ "name" ], $taxonomy );
                if ( $termname ) {
                    $catData[ "slug" ] = $termname->slug;
                } else {
                    $catData[ "slug" ] = str_replace( " ", "-", trim( $category[ "name" ] ) );
                }
            }
            
            $term = get_term_by( 'slug', $catData[ "slug" ], $taxonomy );
            
            if ( $term )
                $term = wp_update_term( $term->term_id, $taxonomy, $catData );
            else
                $term = wp_insert_term( $catData[ "name" ], $taxonomy, $catData );
            
            if ( is_wp_error( $term ) ) {
                $this->db->addMessages( __( "Error in adding/updating terms: " . $term->get_error_message() ), "taxonomy-section" );
                unset( $catData );
                continue;
            } else {
                $recordsAdded++;
                $termId = $term[ 'term_id' ];
            }
            
            foreach ( $category as $key => $val ) {
                if ( strpos( $key, "PM:" ) !== false ) {
                    $key = trim( substr( $key, 4, strlen( $key ) - 1 ) );
                    update_option( "taxonomy_" . $termId . "_id", array(
                         $key => $val 
                    ) );
                }
            }
            $this->fileDataIds .= $termId . ',';
            do_action( "wpaie_importdata", $termId, "taxonomy" );
            unset( $catData );
        }
        
        unset( $this->output );
        $this->output[ "fileDataIds" ]     = rtrim( $this->fileDataIds, "," );
        $this->output[ "recordsRead" ]     = $totalRows;
        $this->output[ "recordsInserted" ] = $recordsAdded;
        $this->output[ "recordsSkipped" ]  = $totalRows - $recordsAdded;
    }
    
    function importPosts() {
        global $wpdb;
        $totalRows              = count( $this->formattedData );
        $totalPostInserted      = 0;
        $duplicatePosts         = 0;
        $option                 = get_option( 'wpaieOptions' );
        $catSeparator           = $option[ "categorySeparator" ];
        $termSeparator          = $option[ "termSeparator" ];
        $postContentImg         = $option[ "postContentImg" ];
        $setFeatureImgByDefault = $option[ "setFeatureImgByDefault" ];
        $this->fileDataIds      = '';
        foreach ( $this->formattedData as $postData ) {
            $pm   = array();
            $ct   = array();
            $post = array(
                "post_title" => "",
                "post_content" => "",
                "post_author" => 1,
                "post_type" => $this->postType,
                "post_status" => $option[ "postStatus" ] 
            );
            
            $postId = $this->db->dbPostTitle( $postData[ "post_title" ], $this->postType );
            
            if ( $option[ "duplicateEntry" ] == "skip" && $postId > 0 )
                continue;
            
            foreach ( $postData as $key => $val ) {
                if ( strpos( $key, "PM:" ) !== false ) {
                    $key        = trim( substr( $key, 4, strlen( $key ) - 1 ) );
                    $pm[ $key ] = $val;
                } else if ( strpos( $key, "CT:" ) !== false ) {
                    $key        = trim( substr( $key, 4, strlen( $key ) - 1 ) );
                    $ct[ $key ] = $val;
                } else if ( strpos( $key, "post_category" ) !== false ) {
                    $cat[ $key ] = $val;
                } else {
                    $post[ $key ] = $val;
                }
            }
            
            if ( isset( $postData[ "post_author" ] ) ) {
                if ( is_int( $postData[ "post_author" ] ) )
                    $post[ "post_author" ] = $postData[ "post_author" ];
                else {
                    $author = $this->db->dbCheckUser( $postData[ "post_author" ] );
                    if ( $author > 0 )
                        $post[ "post_author" ] = $author;
                    else
                        $post[ "post_author" ] = 1;
                }
            }
            
            if ( isset( $postData[ 'post_date' ] ) )
                $post[ 'post_date' ] = date( 'Y-m-d H:i:s', strtotime( $postData[ 'post_date' ] ) );
            else if ( !empty( $option[ "dateval" ] ) )
                $post[ 'post_date' ] = date( 'Y-m-d H:i:s', strtotime( $option[ 'dateval' ] ) );
            else
                $post[ 'post_date' ] = date( 'Y-m-d H:i:s' );
            
            $post[ 'post_date_gmt' ] = get_gmt_from_date( $post[ 'post_date' ] );
            
            if ( $postId > 0 ) {
                $post[ "ID" ] = $postId;
                wp_update_post( $post, $wp_error );
                
                foreach ( $pm as $key => $val ) {
                    if ( !empty( $val ) ) {
                        update_post_meta( $postId, $key, $val );
                    }
                }
            } else {
                $postId = wp_insert_post( $post, $wp_error );
                $this->fileDataIds .= $postId . ",";
                foreach ( $pm as $key => $val ) {
                    if ( !empty( $val ) ) {
                        add_post_meta( $postId, $key, $val );
                    }
                }
            }
            
            if ( $postId > 0 )
                $totalPostInserted++;
            
            foreach ( $ct as $key => $val ) {
                if ( !empty( $val ) ) {
                    $this->setTaxonomy( $postId, $key, explode( $termSeparator, $val ) );
                }
            }
            
            if ( isset( $cat[ "post_category" ] ) && !empty( $cat[ "post_category" ] ) ) {
                $this->setTaxonomy( $postId, 'category', explode( $catSeparator, $cat[ "post_category" ] ) );
            }
            
            if ( isset( $postData[ "post_tag" ] ) ) {
                if ( !empty( $postData[ "post_tag" ] ) )
                    wp_set_post_tags( $postId, $postData[ "post_tag" ] );
            }
            if ( trim( $postContentImg ) === "yes" ) {
                if ( trim( $setFeatureImgByDefault ) === "yes" ) {
                    if ( empty( $postData[ "featured_image" ] ) ) {
                        $this->importContentImages( $postData[ 'post_content' ], $postId );
                    }
                }
                if ( isset( $postData[ 'post_content' ] ) )
                    $this->importContentImages( $postData[ 'post_content' ] );
            }
            
            if ( isset( $postData[ "featured_image" ] ) && !empty( $postData[ "featured_image" ] ) ) {
                $this->addFeaturedImage( $postData[ "featured_image" ], $postId );
            }
            if ( strtotime( $postData[ 'post_date' ] ) > time() ) {
                $wpdb->update( $wpdb->prefix . 'posts', array(
                     'post_status' => 'future' 
                ), array(
                     'ID' => $postId 
                ) );
            }
            
            do_action( "wpaie_importdata", $postId, "post" );
            
            unset( $post );
            unset( $pm );
            unset( $ct );
        }
        unset( $this->output );
        $this->output[ "fileDataIds" ]     = rtrim( $this->fileDataIds, "," );
        $this->output[ "recordsRead" ]     = $totalRows;
        $this->output[ "recordsInserted" ] = $totalPostInserted;
        $this->output[ "recordsSkipped" ]  = $totalRows - $totalPostInserted;
    }
    
    function importWooProducts() {
        global $wpdb, $woocommerce;
        $totalRows              = count( $this->formattedData );
        $totalPostInserted      = 0;
        $duplicatePosts         = 0;
        $option                 = get_option( 'wpaieOptions' );
        $catSeparator           = $option[ "categorySeparator" ];
        $termSeparator          = $option[ "termSeparator" ];
        $postContentImg         = $option[ "postContentImg" ];
        $setFeatureImgByDefault = $option[ "setFeatureImgByDefault" ];
        $prevID                 = '';
        $varRegPrice            = array();
        $varSalePrice           = array();
        $this->fileDataIds      = '';
        foreach ( $this->formattedData as $postData ) {
            $pm   = array();
            $ct   = array();
            $post = array(
                "post_title" => "",
                "post_content" => "",
                "post_author" => 1,
                "post_type" => $this->postType,
                "post_status" => $option[ "postStatus" ] 
            );
            
            $postId = $this->db->dbPostTitle( $postData[ "post_title" ], $this->postType );
            
            if ( $option[ "duplicateEntry" ] == "skip" && $postId > 0 )
                continue;
            
            foreach ( $postData as $key => $val ) {
                if ( strpos( $key, "PM:" ) !== false ) {
                    $key        = trim( substr( $key, 4, strlen( $key ) - 1 ) );
                    $pm[ $key ] = $val;
                } else if ( strpos( $key, "CT:" ) !== false ) {
                    $key        = trim( substr( $key, 4, strlen( $key ) - 1 ) );
                    $ct[ $key ] = $val;
                } else if ( strpos( $key, "post_category" ) !== false ) {
                    $cat[ $key ] = $val;
                } else {
                    $post[ $key ] = $val;
                }
            }
            
            if ( isset( $postData[ "post_author" ] ) ) {
                if ( is_int( $postData[ "post_author" ] ) )
                    $post[ "post_author" ] = $postData[ "post_author" ];
                else {
                    $author = $this->db->dbCheckUser( $postData[ "post_author" ] );
                    if ( $author > 0 )
                        $post[ "post_author" ] = $author;
                    else
                        $post[ "post_author" ] = 1;
                }
            }
            
            if ( isset( $postData[ 'post_date' ] ) )
                $post[ 'post_date' ] = date( 'Y-m-d H:i:s', strtotime( $postData[ 'post_date' ] ) );
            else if ( !empty( $option[ "dateval" ] ) )
                $post[ 'post_date' ] = date( 'Y-m-d H:i:s', strtotime( $option[ 'dateval' ] ) );
            else
                $post[ 'post_date' ] = date( 'Y-m-d H:i:s' );
            
            $post[ 'post_date_gmt' ] = get_gmt_from_date( $post[ 'post_date' ] );
            
            if ( isset( $postData[ "CT: product_type" ] ) && $postData[ "CT: product_type" ] == "product_variation" ) {
                $previousPost = get_post( $prevID );
                
                $post = array(
                    "post_title" => "Variation #" . $prevID . " of " . $previousPost->post_title,
                    "post_name" => "product-" . $prevID . "-variation",
                    "post_type" => $postData[ "CT: product_type" ],
                    "post_status" => $postData[ "post_status" ],
                    "post_parent" => $prevID 
                );
            }
            
            if ( $postId > 0 ) {
                $post[ "ID" ] = $postId;
                wp_update_post( $post, $wp_error );
                
                foreach ( $pm as $key => $val ) {
                    if ( !empty( $val ) ) {
                        if ( $key === "_product_image_gallery" ) {
                            $this->save_product_gallery( $postId, $val );
                        } else {
                            update_post_meta( $postId, $key, $val );
                        }
                    }
                }
            } else {
                $postId = wp_insert_post( $post, $wp_error );
                
                foreach ( $pm as $key => $val ) {
                    if ( !empty( $val ) ) {
                        if ( $key === "_product_image_gallery" ) {
                            $this->save_product_gallery( $postId, $val );
                        } else {
                            add_post_meta( $postId, $key, $val );
                        }
                    }
                }
            }
            
            if ( isset( $postData[ "CT: product_type" ] ) && $postData[ "CT: product_type" ] == "variable" ) {
                if ( isset( $postData[ "PM: _product_attributes" ] ) && !empty( $postData[ "PM: _product_attributes" ] ) ) {
                    $product_attributes = explode( ';', trim( $postData[ "PM: _product_attributes" ] ) );
                    $attributes         = array();
                    foreach ( $product_attributes as $product_attribute ) {
                        $attr = explode( ':', trim( $product_attribute ) );
                        
                        if ( !empty( $attr[ 0 ] ) && !empty( $attr[ 1 ] ) ) {
                            $attributes[ $attr[ 0 ] ] = array(
                                "name" => trim( $attr[ 0 ] ),
                                'value' => trim( $attr[ 1 ] ),
                                'is_visible' => true,
                                'is_variation' => true,
                                'is_taxonomy' => 0,
                                'position' => 1 
                            );
                        }
                    }
                    $prevID = $postId;
                    update_post_meta( $postId, "_product_attributes", $attributes );
                }
            }
            
            if ( isset( $postData[ "CT: product_type" ] ) && $postData[ "CT: product_type" ] == "product_variation" ) {
                
                $product_variations = explode( "|", trim( $postData[ "PM: _product_variation" ] ) );
                foreach ( $product_variations as $product_variation ) {
                    $attr = explode( ":", $product_variation );
                    update_post_meta( $postId, "attribute_" . trim( $attr[ 0 ] ), trim( $attr[ 1 ] ) );
                }
                
                $wc_product_variation = new WC_Product_Variation( $postId );
                $regular_price        = $wc_product_variation->regular_price;
                
                if ( isset( $postData[ "PM: _sale_price" ] ) )
                    $sale_price = $wc_product_variation->sale_price;
                else if ( isset( $postData[ "PM: _price" ] ) )
                    $sale_price = $wc_product_variation->sale_price;
                
                $varRegPrice[ $postId ]  = $regular_price;
                $variationMinRegularID   = array_keys( $varRegPrice, min( $varRegPrice ) );
                $variationMaxRegularID   = array_keys( $varRegPrice, max( $varRegPrice ) );
                $varSalePrice[ $postId ] = $sale_price;
                $variationMinSaleID      = array_keys( $varSalePrice, min( $varSalePrice ) );
                $variationMaxSaleID      = array_keys( $varSalePrice, max( $varSalePrice ) );
                update_post_meta( $prevID, "_min_variation_price", min( $varSalePrice ) );
                update_post_meta( $prevID, "_max_variation_price", max( $varSalePrice ) );
                update_post_meta( $prevID, "_min_price_variation_id", $variationMinSaleID[ 0 ] );
                update_post_meta( $prevID, "_max_price_variation_id", $variationMaxSaleID[ 0 ] );
                update_post_meta( $prevID, "_min_variation_sale_price", min( $varSalePrice ) );
                update_post_meta( $prevID, "_max_variation_sale_price", max( $varSalePrice ) );
                update_post_meta( $prevID, "_min_sale_price_variation_id", $variationMinSaleID[ 0 ] );
                update_post_meta( $prevID, "_max_sale_price_variation_id", $variationMaxSaleID[ 0 ] );
                update_post_meta( $prevID, "_min_variation_regular_price", min( $varRegPrice ) );
                update_post_meta( $prevID, "_max_variation_regular_price", max( $varRegPrice ) );
                update_post_meta( $prevID, "_min_regular_price_variation_id", $variationMinRegularID[ 0 ] );
                update_post_meta( $prevID, "_max_regular_price_variation_id", $variationMaxRegularID[ 0 ] );
                update_post_meta( $prevID, "_price", min( $varSalePrice ) );
            }
            
            if ( $postId > 0 )
                $totalPostInserted++;
            
            foreach ( $ct as $key => $val ) {
                if ( !empty( $val ) ) {
                    $this->setTaxonomy( $postId, $key, explode( $termSeparator, $val ) );
                }
            }
            
            if ( isset( $cat[ "post_category" ] ) && !empty( $cat[ "post_category" ] ) ) {
                $this->setTaxonomy( $postId, 'category', explode( $catSeparator, $cat[ "post_category" ] ) );
            }
            
            if ( isset( $postData[ "post_tag" ] ) ) {
                if ( !empty( $postData[ "post_tag" ] ) )
                    wp_set_post_tags( $postId, $postData[ "post_tag" ] );
            }
            
            if ( !isset( $postData[ "PM: _price" ] ) ) {
                if ( isset( $postData[ "PM: _sale_price" ] ) && $postData[ "PM: _sale_price" ] > 0 ) {
                    update_post_meta( $postId, '_price', $postData[ "PM: _sale_price" ] );
                } else if ( isset( $postData[ "PM: _regular_price" ] ) && $postData[ "PM: _regular_price" ] > 0 ) {
                    update_post_meta( $postId, '_price', $postData[ "PM: _regular_price" ] );
                }
            }
            
            if ( trim( $postContentImg ) === "yes" ) {
                if ( trim( $setFeatureImgByDefault ) === "yes" ) {
                    if ( empty( $postData[ "featured_image" ] ) ) {
                        $this->importContentImages( $postData[ 'post_content' ], $prevID );
                    }
                }
                if ( isset( $postData[ 'post_content' ] ) )
                    $this->importContentImages( $postData[ 'post_content' ] );
            }
            
            if ( isset( $postData[ "featured_image" ] ) && !empty( $postData[ "featured_image" ] ) ) {
                $this->addFeaturedImage( $postData[ "featured_image" ], $postId );
            }
            if ( strtotime( $postData[ 'post_date' ] ) > time() ) {
                $wpdb->update( $wpdb->prefix . 'posts', array(
                     'post_status' => 'future' 
                ), array(
                     'ID' => $postId 
                ) );
            }
            $this->fileDataIds .= $postId . ',';
            do_action( "wpaie_importdata", $postId, "product" );
            unset( $post );
            unset( $pm );
            unset( $ct );
        }
        
        unset( $this->output );
        $this->output[ "fileDataIds" ]     = rtrim( $this->fileDataIds, "," );
        $this->output[ "recordsRead" ]     = $totalRows;
        $this->output[ "recordsInserted" ] = $totalPostInserted;
        $this->output[ "recordsSkipped" ]  = $totalRows - $totalPostInserted;
    }
    
    function importMenus() {
        $totalRows         = count( $this->formattedData );
        $totalPostInserted = 0;
        $duplicatePosts    = 0;
        $menuParent        = array();
        $this->fileDataIds = '';
        foreach ( $this->formattedData as $postData ) {
            
            $taxonomyArray = array(
                "category",
                "product_cat",
                "product_tag" 
            );
            $checkMenu     = wp_get_nav_menu_object( trim( $postData[ "menu_name" ] ) );
            
            if ( $checkMenu )
                $menuID = (int) $checkMenu->term_id;
            else
                $menuID = wp_create_nav_menu( trim( $postData[ "menu_name" ] ) );
            
            if ( in_array( $postData[ "_menu_item_object" ], $taxonomyArray ) ) {
                $getCatId = get_term( trim( $postData[ "ID" ] ) );
                $id       = $getCatId->term_id;
            } else if ( trim( $postData[ "_menu_item_object" ] ) == "custom" ) {
                $id = trim( $postData[ "ID" ] );
            } else {
                $getPageId = get_page_by_title( trim( $postData[ "post_title" ] ), ARRAY_A, trim( $postData[ "_menu_item_object" ] ) );
                $id        = $getPageId[ 'ID' ];
            }
            $itemData = array(
                 'menu-item-title' => trim( $postData[ "post_title" ] ),
                'menu-item-object-id' => $id,
                'menu-item-position' => trim( $postData[ "menu_order" ] ),
                'menu-item-object' => trim( $postData[ "_menu_item_object" ] ),
                'menu-item-type' => trim( $postData[ "_menu_item_type" ] ),
                'menu-item-status' => 'publish',
                'menu-item-url' => trim( $postData[ "guid" ] ) 
            );
            
            $menuItemId                                   = wp_update_nav_menu_item( $menuID, 0, $itemData );
            $menuParent[ trim( $postData[ "post_id" ] ) ] = $menuItemId;
            if ( ( trim( $postData[ "menu_parent_id" ] ) != '0' ) && ( ( trim( $postData[ "menu_parent_id" ] ) !== false ) ) && ( isset( $menuParent[ trim( $postData[ "menu_parent_id" ] ) ] ) ) ) {
                update_post_meta( $menuItemId, "_menu_item_menu_item_parent", $menuParent[ $postData[ "menu_parent_id" ] ] );
            }
            $this->fileDataIds .= $menuItemId . ",";
            $totalPostInserted++;
        }
        unset( $this->output );
        $this->output[ "fileDataIds" ]     = rtrim( $this->fileDataIds, "," );
        $this->output[ "recordsRead" ]     = $totalRows;
        $this->output[ "recordsInserted" ] = $totalPostInserted;
        $this->output[ "recordsSkipped" ]  = $totalRows - $totalPostInserted;
    }
    
    function contentImages( $imgPath ) {
        $imgPath   = trim( $imgPath );
        $dirFolder = array_reverse( explode( "/", $imgPath ) );
        $filename  = substr( $imgPath, ( strrpos( $imgPath, '/' ) ) + 1 );
        if ( isset( $dirFolder[ 1 ] ) && isset( $dirFolder[ 2 ] ) && is_numeric( $dirFolder[ 1 ] ) && is_numeric( $dirFolder[ 2 ] ) )
            $uploads = wp_upload_dir( $dirFolder[ 2 ] . '/' . $dirFolder[ 1 ] );
        else
            $uploads = wp_upload_dir( current_time( "mysql" ) );
        if ( !is_dir( $uploads[ 'basedir' ] ) ) {
            return false;
        }
        $uniqueFileName = wp_unique_filename( $uploads[ 'path' ], $filename );
        
        $newFile = $uploads[ 'path' ] . "/$uniqueFileName";
        
        $uploaded = copy( $imgPath, $newFile );
        
        $wp_filetype = wp_check_filetype( basename( $filename ), null );
        
        extract( $wp_filetype );
        $url = $uploads[ 'url' ] . "/$uniqueFileName";
        
        $attachment = array(
            'post_mime_type' => $type,
            'guid' => $url,
            'post_title' => $imageTitle,
            'post_content' => '',
            'post_status' => 'inherit' 
        );
        
        $imageID = wp_insert_attachment( $attachment, $newFile, $postId );
    }
    
    function save_product_gallery( $postId, $product_gallery ) {
        
        $images = explode( '|', $product_gallery );
        
        $gallery = false;
        
        foreach ( $images as $image ) {
            
            $filename = substr( $image, ( strrpos( $image, '/' ) ) + 1 );
            $uploads  = wp_upload_dir( current_time( 'mysql' ) );
            if ( !is_dir( $uploads[ 'basedir' ] ) ) {
                return false;
            }
            
            $uniqueFileName = wp_unique_filename( $uploads[ 'path' ], $filename );
            
            $newFile = $uploads[ 'path' ] . "/$uniqueFileName";
            
            $uploaded = copy( $image, $newFile );
            
            $wp_filetype = wp_check_filetype( basename( $filename ), null );
            
            extract( $wp_filetype );
            $url = $uploads[ 'url' ] . "/$uniqueFileName";
            
            $attachment = array(
                 'post_mime_type' => $type,
                'guid' => $url,
                'post_title' => $imageTitle,
                'post_content' => '',
                'post_status' => 'inherit' 
            );
            
            $imageID = wp_insert_attachment( $attachment, $newFile, $postId );
            
            if ( $imageID )
                $gallery[] = $imageID;
        }
        
        if ( $gallery ) {
            $meta_value = implode( ',', $gallery );
            update_post_meta( $postId, '_product_image_gallery', $meta_value );
        }
    }
    
    function setTaxonomy( $postId, $taxonomy, array $fields ) {
        $termIds              = array();
        $categorySettingValue = get_option( "wpaieOptions" );
        $loopCount            = 0;
        foreach ( $fields as $field ) {
            $field = trim( $field );
            if ( empty( $field ) )
                continue;
            $content = explode( ":", $field );
            if ( count( $content ) == 1 ) {
                $termname = get_term_by( 'name', $field, $taxonomy );
                if ( $termname ) {
                    $slug     = $termname->slug;
                    $parentId = $termname->parent;
                } else {
                    $slug = $field;
                    if ( trim( $categorySettingValue[ "categorySetting" ] === "yes" ) ) {
                        if ( $loopCount == 0 )
                            $parentId = 0;
                        else
                            $parentId = $termLastId;
                    } else {
                        $parentId = 0;
                    }
                }
            } else if ( count( $content ) > 1 ) {
                $slug     = $content[ 0 ];
                $field    = $content[ 1 ];
                $parentId = 0;
            }
            
            $term = get_term_by( 'slug', $slug, $taxonomy );
            
            if ( $term )
                $term = wp_update_term( $term->term_id, $taxonomy, array(
                     'slug' => $slug,
                    'parent' => $parentId 
                ) );
            else
                $term = wp_insert_term( $field, $taxonomy, array(
                     'slug' => $slug,
                    'parent' => $parentId 
                ) );
            
            if ( is_wp_error( $term ) )
                $this->db->addMessages( __( "Error in adding/updating terms: " . $term->get_error_message() ), "taxonomy-section" );
            else {
                $termIds[]  = (int) $term[ 'term_id' ];
                $termLastId = $term[ 'term_id' ];
            }
            $loopCount++;
        }
        
        wp_set_object_terms( $postId, $termIds, $taxonomy, FALSE );
        wp_cache_set( 'last_changed', time() - 1800, 'terms' );
        wp_cache_delete( 'all_ids', $taxonomy );
        wp_cache_delete( 'get', $taxonomy );
        delete_option( "{$taxonomy}_children" );
        _get_term_hierarchy( $taxonomy );
    }
    
    function addFeaturedImage( $imageUrl, $postId ) {
        $imageUrl = trim( $imageUrl );
        $filename = substr( $imageUrl, ( strrpos( $imageUrl, '/' ) ) + 1 );
        $uploads  = wp_upload_dir( current_time( 'mysql' ) );
        if ( !is_dir( $uploads[ 'basedir' ] ) ) {
            return false;
        }
        $uniqueFileName = wp_unique_filename( $uploads[ 'path' ], $filename );
        
        $newFile = $uploads[ 'path' ] . "/$uniqueFileName";
        
        $uploaded = copy( $imageUrl, $newFile );
        
        $wp_filetype = wp_check_filetype( basename( $filename ), null );
        
        extract( $wp_filetype );
        $url = $uploads[ 'url' ] . "/$uniqueFileName";
        
        $attachment = array(
             'post_mime_type' => $type,
            'guid' => $url,
            'post_title' => $imageTitle,
            'post_content' => '',
            'post_status' => 'inherit' 
        );
        
        $thumbId = wp_insert_attachment( $attachment, $newFile, $postId );
        if ( !is_wp_error( $thumbId ) ) {
            require_once( ABSPATH . '/wp-admin/includes/image.php' );
            wp_update_attachment_metadata( $thumbId, wp_generate_attachment_metadata( $thumbId, $newFile ) );
            update_attached_file( $thumbId, $newFile );
            set_post_thumbnail( $postId, $thumbId );
            return $thumbId;
        }
        
        return false;
    }
    
    function importContentImages( $postContent, $postId = '' ) {
        $output = preg_match_all( '/<img[^>]+>/i', trim( $postContent ), $matches );
        foreach ( $matches[ 0 ] as $imgTag ) {
            preg_match_all( '/(alt|title|src)=("[^"]*")/i', trim( $imgTag ), $imgArr );
            $imgPath = preg_replace( '/"/', '', trim( $imgArr[ 2 ][ 0 ] ) );
            $this->contentImages( $imgPath );
            if ( !empty( $postId ) ) {
                $this->addFeaturedImage( $imgPath, $postId );
                break;
            }
        }
    }
    
    function exportPlugin() {
        return $this->exportPosts();
    }
    
    function exportWooOrders() {
       global $wpdb; 
       $args = array(
            'post_type' => wc_get_order_types(),
            'posts_per_page' => -1,
            'post_status' => array_keys(wc_get_order_statuses()),
            'orderby' => 'ID',
            'order' => 'ASC'
        );
        
        if ( count( $this->orderStatus ) ) {
            foreach ( $this->orderStatus as $orderstatus ) {
                $args[ 'post_status' ][] = $orderstatus;
            }
        }        
        
        if ($this->startDate && $this->endDate) {
            $args['date_query'][0]['after'] = $this->startDate;
            $args['date_query'][0]['before'] = $this->endDate;
            $args['date_query'] = array(
                array(
                    'inclusive' => true
                )
            );
        }

        if ( !empty( $this->orderBy ) ) {
            $args[ "orderby" ] = $this->orderBy;
            $args[ "order" ]   = $this->orderAscDesc;
        }
        
        $orders = new WP_Query( $args );
        
        if ( $orders->have_posts() ) {
            $orderCount = 0;
            while ( $orders->have_posts() ) {
                $orders->the_post();
                $order_details = new WC_Order( get_the_ID() );
                $customerName  = $order_details->billing_first_name . ' ' . $order_details->billing_last_name;
                $order_item    = $order_details->get_items();
                
                foreach ( $order_item as $item_id => $item ) {
                    
                    foreach ( $this->columns as $colName ) {
                        
                        if ( $colName == 'order_id' ) {
                            $exportWoo[ $orderCount ][ 'order_id' ] = $order_details->get_order_number();
                        }
                        $exportWoo[ $orderCount ][ 'customer_name' ] = $customerName;
                        if ( $colName == 'product_name' )
                            $exportWoo[ $orderCount ][ 'product_name' ] = $item[ 'name' ];
                        if ( $colName == 'quantity' )
                            $exportWoo[ $orderCount ][ 'quantity' ] = $item[ 'qty' ];
                        if ( $colName == 'order_status' )
                            $exportWoo[ $orderCount ][ 'order_status' ] = $order_details->get_status();
                        if ( $colName == 'amount_paid' )
                            $exportWoo[ $orderCount ][ 'amount_paid' ] = $item[ 'amount_paid' ];
                        if ( $colName == 'order_type' )
                            $exportWoo[ $orderCount ][ 'amount_paid' ] = $item[ 'order_type' ];
                        if ( $colName == 'product_tax' )
                            $exportWoo[ $orderCount ][ 'product_tax' ] = $item[ 'line_subtotal_tax' ];
                    }
                    
                    foreach ( $this->orderdetails as $orderdetails ) {
                        if ( $orderdetails == 'billing' ) {
                            $exportWoo[ $orderCount ][ '_billing_first_name' ] = get_post_meta( get_the_ID(), '_billing_first_name', true );
                            $exportWoo[ $orderCount ][ '_billing_last_name' ]  = get_post_meta( get_the_ID(), '_billing_last_name', true );
                            $exportWoo[ $orderCount ][ '_billing_phone' ]      = get_post_meta( get_the_ID(), '_billing_phone', true );
                            $exportWoo[ $orderCount ][ '_billing_email' ]      = get_post_meta( get_the_ID(), '_billing_email', true );
                            $exportWoo[ $orderCount ][ '_billing_company' ]    = get_post_meta( get_the_ID(), '_billing_company', true );
                            $exportWoo[ $orderCount ][ '_billing_address_1' ]  = get_post_meta( get_the_ID(), '_billing_address_1', true );
                            $exportWoo[ $orderCount ][ '_billing_address_2' ]  = get_post_meta( get_the_ID(), '_billing_address_2', true );
                            $exportWoo[ $orderCount ][ '_billing_city' ]       = get_post_meta( get_the_ID(), '_billing_city', true );
                            $exportWoo[ $orderCount ][ '_billing_postcode' ]   = get_post_meta( get_the_ID(), '_billing_postcode', true );
                            $exportWoo[ $orderCount ][ '_billing_country' ]    = get_post_meta( get_the_ID(), '_billing_country', true );
                            $exportWoo[ $orderCount ][ '_billing_state' ]      = get_post_meta( get_the_ID(), '_billing_state', true );
                        } else if ( $orderdetails == 'shipping' ) {
                            $exportWoo[ $orderCount ][ '_shipping_first_name' ] = get_post_meta( get_the_ID(), '_shipping_first_name', true );
                            $exportWoo[ $orderCount ][ '_shipping_last_name' ]  = get_post_meta( get_the_ID(), '_shipping_last_name', true );
                            $exportWoo[ $orderCount ][ '_shipping_phone' ]      = get_post_meta( get_the_ID(), '_shipping_phone', true );
                            $exportWoo[ $orderCount ][ '_shipping_email' ]      = get_post_meta( get_the_ID(), '_shipping_email', true );
                            $exportWoo[ $orderCount ][ '_shipping_company' ]    = get_post_meta( get_the_ID(), '_shipping_company', true );
                            $exportWoo[ $orderCount ][ '_shipping_address_1' ]  = get_post_meta( get_the_ID(), '_shipping_address_1', true );
                            $exportWoo[ $orderCount ][ '_shipping_address_2' ]  = get_post_meta( get_the_ID(), '_shipping_address_2', true );
                            $exportWoo[ $orderCount ][ '_shipping_city' ]       = get_post_meta( get_the_ID(), '_shipping_city', true );
                            $exportWoo[ $orderCount ][ '_shipping_postcode' ]   = get_post_meta( get_the_ID(), '_shipping_postcode', true );
                            $exportWoo[ $orderCount ][ '_shipping_country' ]    = get_post_meta( get_the_ID(), '_shipping_country', true );
                            $exportWoo[ $orderCount ][ '_shipping_state' ]      = get_post_meta( get_the_ID(), '_shipping_state', true );
                        }
                    }
                    $orderCount++;
                }
            }
        }
        
        $this->export( $exportWoo );
    }
    
    function exportPosts() {
        
        global $wpdb;
        $query            = "SELECT p.ID as post_id,";
        $post_id_selected = false;
        
        if ( $this->columns[ 0 ] == 'ID' ) {
            array_shift( $this->columns );
            $post_id_selected = true;
        }
        
        $cols = implode( ",", $this->columns );
        $query .= $cols . " ";
        
        if ( count( $this->postMeta ) > 0 ) {
            foreach ( $this->postMeta as $meta ) {
                $query .= ',MAX(CASE WHEN pm.meta_key = "' . $meta . '" THEN pm.meta_value ELSE NULL END) as "' . $meta . '"';
            }
        }
        
        $query .= " FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON ( pm.post_id = p.ID)";
        $query .= " LEFT JOIN $wpdb->users u ON ( p.post_author = u.ID ) ";
        $query .= " WHERE p.post_type = '$this->postType'";
        
        if ( count( $this->postStatus ) > 0 ) {
            $postStatus = "";
            foreach ( $this->postStatus as $status ) {
                $postStatus .= "'" . $status . "',";
            }
            $postStatus = rtrim( $postStatus, "," );
            $query .= " AND p.post_status IN ($postStatus)";
        } else {
            $query .= " AND p.post_status IN ('publish')";
        }
        
        if ( $this->optionNoOfPost === "specificpostbyid" ) {
            if ( !empty( $this->specificPostById ) )
                $postIds = $this->specificPostById;
            
            $query .= " AND p.ID IN (" . $postIds . ")";
        }
        
        if ( $this->optionNoOfPost == "postrangebypostid" ) {
            $start = "0";
            $end   = "100";
            
            if ( !empty( $this->postStartRange ) )
                $start = $this->postStartRange;
            
            if ( !empty( $this->postEndRange ) )
                $end = $this->postEndRange;
            
            $query .= " AND p.id between ($start) and ($end)";
        }
        
        $query .= " GROUP BY p.ID";
        
        if ( !empty( $this->orderBy ) ) {
            $query .= " ORDER BY " . $this->orderBy . " " . $this->orderAscDesc;
        }
        
        if ( $this->optionNoOfPost == "postrange" ) {
            $start = "0";
            $end   = "100";
            
            if ( !empty( $this->postStartRange ) )
                $start = $this->postStartRange;
            
            if ( !empty( $this->postTotalCount ) )
                $end = $this->postTotalCount;
            
            $query .= " LIMIT $start, $end";
        }
        
        $posts = $this->db->dbExecuteQuery( $query );
        
        if ( trim( $this->postType ) === "product" ) {
            
            $queryVar = '';
            $postsVar = array();
            
            foreach ( $posts as $post ) {
                if ( !empty( $post[ "_product_attributes" ] ) ) {
                    $attrString     = array();
                    $attributeArray = array();
                    foreach ( unserialize( $post[ "_product_attributes" ] ) as $key => $proAttr ) {
                        $attrString[]       = $key . ":" . $proAttr[ "value" ];
                        $stringAfterImplode = implode( ";", $attrString );
                        if ( trim( $proAttr[ "is_variation" ] ) == 1 )
                            $attributeArray[] = trim( $proAttr[ "name" ] );
                    }
                    $post[ "_product_attributes" ] = $stringAfterImplode;
                }
                
                
                $chekProductType = wp_get_object_terms( $post[ 'post_id' ], 'product_type' );
                if ( trim( $chekProductType[ 0 ]->slug ) === "variable" ) {
                    $postsVar[]      = $post;
                    $variableParentId      = $post[ 'post_id' ];
                    $queryVar        = "SELECT p.ID as post_id,";
                    
                    if ( $this->columns[ 0 ] == 'ID' ) {
                        array_shift( $this->columns );
                    }
                    
                    $cols = implode( ",", $this->columns );
                    $queryVar .= $cols . " ";
                    
                    if ( count( $this->postMeta ) > 0 ) {
                        foreach ( $this->postMeta as $meta ) {
                            $queryVar .= ',MAX(CASE WHEN pm.meta_key = "' . $meta . '" THEN pm.meta_value ELSE NULL END) as "' . $meta . '"';
                        }
                        
                        $queryVar .= " FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm ON ( pm.post_id = p.ID)";
                        $queryVar .= " LEFT JOIN $wpdb->users u ON ( p.post_author = u.ID ) ";
                        $queryVar .= " WHERE p.post_type = 'product_variation' and p.post_parent='" . $variableParentId . "'";
                    }
                    
                    if ( count( $this->postStatus ) > 0 ) {
                        $postStatus = "";
                        foreach ( $this->postStatus as $status ) {
                            $postStatus .= "'" . $status . "',";
                        }
                        $postStatus = rtrim( $postStatus, "," );
                        $queryVar .= " AND p.post_status IN ($postStatus)";
                    } else {
                        $queryVar .= " AND p.post_status IN ('publish')";
                    }
                    
                    $queryVar .= " GROUP BY p.ID";
                    $postsVar = array_merge( $postsVar, $this->db->dbExecuteQuery( $queryVar ) );
                } else {
                    $postsVar[] = $post;
                }
            }
            $posts = $postsVar;
        }
        if ( count( $this->customTaxonomies ) > 0 || $this->exportFeaturedImage ) {
            $postCount = 0;
            foreach ( $posts as $post ) {
                foreach ( $this->customTaxonomies as $taxonomy ) {
                    $posts[ $postCount ][ $taxonomy ] = $this->exportTaxonomy( wp_get_object_terms( $post[ 'post_id' ], $taxonomy ) );
                }
                if ( $this->exportFeaturedImage === "true" ) {
                    $featured_img                          = wp_get_attachment_image_src( get_post_thumbnail_id( $post[ 'post_id' ] ), 'full' );
                    $posts[ $postCount ][ "featured_img" ] = "";
                    if ( isset( $featured_img[ 0 ] ) )
                        $posts[ $postCount ][ "featured_img" ] = $featured_img[ 0 ];
                    
                    if (trim($data["post_type"]) === "product") {

                        $product = new WC_product($post['post_id']);
                        $attachment_ids = $product->get_gallery_attachment_ids();
                        $attachment = array();
                        if (count($attachment_ids)) {
                            foreach ($attachment_ids as $position => $attachment_id) {
                                $attachment_post = get_post($attachment_id);
                                if (is_null($attachment_post)) {
                                    continue;
                                }
                                $gallery_src = wp_get_attachment_image_src($attachment_id, 'full');
                                $attachment[] = isset($gallery_src[0]) ? $gallery_src[0] : "";
                            }
                            $posts[$post_count]["gallery_images"] = implode(",", $attachment);
                        }
                    }
                }
                
                $postCount++;
            }
        }
        if ( !$post_id_selected ) {
            $postLoop = 0;
            foreach ( $posts as $post ) {
                unset( $posts[ $postLoop ][ 'post_id' ] );
                $postLoop++;
            }
        }
        
        $this->export( $posts );
    }
    
    function exportMenus() {
        $posts          = array();
        $menunamesArray = array();
        foreach ( $this->menufields as $menufiels ) {
            $menu  = wp_get_nav_menu_object( $menufiels );
            $items = get_objects_in_term( $menu->term_id, 'nav_menu' );
            
            foreach ( $items as $it ) {
                if ( isset( $items[ 0 ] ) )
                    $menunamesArray[] = $menu->name;
                $posts[] = get_post( $it, ARRAY_A );
            }
        }
        
        if ( !$menu ) {
            return false;
        }
        $data      = array();
        $menuCount = 0;
        
        foreach ( $posts as $post ) {
            
            if ( !empty( $post[ 'post_title' ] ) ) {
                
                $object   = get_post_meta( $post[ 'ID' ], '_menu_item_object', true );
                $type     = get_post_meta( $post[ 'ID' ], '_menu_item_type', true );
                $parentId = get_post_meta( $post[ 'ID' ], '_menu_item_menu_item_parent', true );
                
                $data[ $menuCount ][ 'menu_type' ]      = $type;
                $data[ $menuCount ][ 'menu_object' ]    = $object;
                $data[ $menuCount ][ 'menu_object_id' ] = $post[ 'ID' ];
                if ( $parentId ) {
                    $data[ $menuCount ][ 'menu_parent_id' ] = $parentId;
                } else {
                    $data[ $menuCount ][ 'menu_parent_id' ] = 0;
                }
                $data[ $menuCount ][ 'post_id' ]          = $post[ 'ID' ];
                $data[ $menuCount ][ 'navigation_title' ] = $post[ 'post_title' ];
                $data[ $menuCount ][ 'guid' ]             = $post[ 'guid' ];
                $data[ $menuCount ][ 'menu_order' ]       = $post[ 'menu_order' ];
                $data[ $menuCount ][ 'menu_name' ]        = $menunamesArray[ $menuCount ];
            } else {
                $object_id = get_post_meta( $post[ 'ID' ], '_menu_item_object_id', true );
                $object    = get_post_meta( $post[ 'ID' ], '_menu_item_object', true );
                $type      = get_post_meta( $post[ 'ID' ], '_menu_item_type', true );
                $menuUrl   = get_post_meta( $post[ 'ID' ], '_menu_item_url', true );
                $parentId  = get_post_meta( $post[ 'ID' ], '_menu_item_menu_item_parent', true );
                
                if ( !$menuUrl )
                    $menuUrl = $post[ 'guid' ];
                if ( $type == "taxonomy" ) {
                    $cate = get_term( $object_id, '', ARRAY_A );
                    
                    $cateData                               = get_post( $post[ 'ID' ], ARRAY_A );
                    $data[ $menuCount ][ 'menu_type' ]      = $type;
                    $data[ $menuCount ][ 'menu_object' ]    = $object;
                    $data[ $menuCount ][ 'menu_object_id' ] = $cate[ 'term_id' ];
                    if ( $parentId ) {
                        $data[ $menuCount ][ 'menu_parent_id' ] = $parentId;
                    } else {
                        $data[ $menuCount ][ 'menu_parent_id' ] = 0;
                    }
                    $data[ $menuCount ][ 'post_id' ]          = $post[ 'ID' ];
                    $data[ $menuCount ][ 'navigation_title' ] = $cate[ 'name' ];
                    $data[ $menuCount ][ 'guid' ]             = $cateData[ 'guid' ];
                    $data[ $menuCount ][ 'menu_order' ]       = $cateData[ 'menu_order' ];
                    $data[ $menuCount ][ 'menu_name' ]        = $menunamesArray[ $menuCount ];
                } else if ( 'post_type' == $type || 'custom' == $type ) {
                    $postDetails = get_post( $object_id, ARRAY_A );
                    
                    $data[ $menuCount ][ 'menu_type' ]      = $type;
                    $data[ $menuCount ][ 'menu_object' ]    = $object;
                    $data[ $menuCount ][ 'menu_object_id' ] = $postDetails[ 'ID' ];
                    if ( $parentId ) {
                        $data[ $menuCount ][ 'menu_parent_id' ] = $parentId;
                    } else {
                        $data[ $menuCount ][ 'menu_parent_id' ] = 0;
                    }
                    $data[ $menuCount ][ 'post_id' ]          = $post[ 'ID' ];
                    $data[ $menuCount ][ 'navigation_title' ] = $postDetails[ 'post_title' ];
                    $data[ $menuCount ][ 'guid' ]             = $menuUrl;
                    $data[ $menuCount ][ 'menu_order' ]       = $post[ 'menu_order' ];
                    $data[ $menuCount ][ 'menu_name' ]        = $menunamesArray[ $menuCount ];
                }
            }
            $menuCount++;
        }
        $this->export( $data );
    }
    
    function exportTaxonomies() {
        $exportTerms = array();
        $taxonomies  = $this->customTaxonomies;
        $args        = array(
             'hide_empty' => $this->hideEmpty === 'true' ? true : false 
        );
        
        if ( !empty( $this->commentStatus ) )
            $args[ "status" ] = $this->commentStatus;
        
        if ( !empty( $this->orderBy ) ) {
            $args[ "orderby" ] = $this->orderBy;
            $args[ "order" ]   = $this->orderAscDesc;
        }
        
        $terms = get_terms( $taxonomies, $args );
        if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                if(in_array("product_cat", $taxonomies)){
                $term->display_type = get_woocommerce_term_meta($item->term_id, 'display_type', true);
                $term->menu_order = get_woocommerce_term_meta($item->term_id, 'order', true);
                $thumbnail_id = get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true);
                $term->image = wp_get_attachment_url($thumbnail_id);
                }
                $exportTerms[] = (array) $term;
            }
            
            $this->export( $exportTerms );
        } else {
            $this->db->addMessages( __( "Error in retriving terms: " . $terms->get_error_message() ), "taxonomy-section" );
        }
    }
    
    function exportComments() {
        $args = array();
        
        if ( !empty( $this->commentStatus ) )
            $args[ "status" ] = $this->commentStatus;
        
        if ( !empty( $this->postAuthor ) )
            $args[ "post_author" ] = $this->postAuthor;
        
        if ( !empty( $this->postStatus ) )
            $args[ "post_status" ] = $this->postStatus;
        
        if ( !empty( $this->orderBy ) ) {
            $args[ "orderby" ] = $this->orderBy;
            $args[ "order" ]   = $this->orderAscDesc;
        }
        if ( !empty( $this->byPostId ) ) {
            $args[ "post_id" ] = $this->byPostId;
        }
        
        $comments      = get_comments( $args );
        $totalComments = 0;
        $commentCols   = array(
            "comment_ID",
            "comment_post_ID",
            "comment_author",
            "comment_author_email",
            "comment_author_url",
            "comment_author_IP",
            "comment_date",
            "comment_date_gmt",
            "comment_content",
            "comment_approved",
            "comment_type",
            "user_id",
            "comment_parent",
            "comment_agent",
            "comment_karma" 
        );
        
        $exportComment = array();
        
        foreach ( $comments as $comment ) {
            
            foreach ( $commentCols as $cols ) {
                $newArr = (array) $comment;
                if ( array_key_exists( $cols, $newArr ) && in_array( $cols, $this->columns ) )
                    $exportComment[ $totalComments ][ $cols ] = $newArr[ $cols ];
            }
            $totalComments++;
        }
        $this->export( $exportComment );
    }
    
    function exportUsers() {
        $args           = array(
             "fields" => $this->columns 
        );
        $args[ "role" ] = $this->userRole;
        
        if ( !empty( $this->orderBy ) ) {
            $args[ "orderby" ] = $this->orderBy;
            $args[ "order" ]   = $this->orderAscDesc;
        }
        
        $users = get_users( $args );
        
        $exportUsers = array();
        $userCount   = 0;
        
        foreach ( $users as $user ) {
            $user_info = get_userdata( $user->ID );
            array_push( $user, $user_info->roles[ 0 ] );
            $exportUsers[ $userCount ]                = (array) $user;
            $exportUsers[ $userCount ][ 'user_role' ] = $user_info->roles[ 0 ];
            
            if ( is_array( $this->userMeta ) && count( $this->userMeta ) > 0 ) {
                foreach ( $this->userMeta as $meta ) {
                    $exportUsers[ $userCount ][ $meta ] = get_user_meta( $user->ID, $meta, true );
                }
            }
            
            $userCount++;
        }
        
        $this->export( $exportUsers );
    }
    
    function exportSQL() {
        $result = $this->db->dbExecuteQuery( str_replace( "\'", '"', $this->sql ) );
        $this->export( $result );
    }
    
    function exportTable() {
        $orderBy = "";
        if ( !empty( $this->orderBy ) )
            $orderBy = " ORDER BY $this->orderBy $this->orderAscDesc";
        
        $this->sql = "SELECT * FROM $this->dbTableName" . $orderBy;
        $result    = $this->db->dbExecuteQuery( $this->sql );
        $this->export( $result );
    }
    
    function export( $exportData ) {
        $exportData = apply_filters( "wpaie_formatted_data", $exportData );
        unset( $this->output );
        $this->output[ "recordsRead" ] = count( $exportData );
        
        if ( count( $exportData ) > 0 ) {
            $header[]         = array_keys( $exportData[ 0 ] );
            $header[ 0 ][ 0 ] = strtolower( $header[ 0 ][ 0 ] );
            $exportData       = array_merge( $header, $exportData );
            $this->exportFile( $exportData );
            do_action( "wpaie_exportfile", $this->output[ "downloadLink" ] );
        } else {
            $this->db->addMessages( __( "There are no records found for selected options" ), "Export-Message" );
        }
    }
    
    function exportFile( $posts ) {
        switch ( strtolower( $this->exportFileType ) ) {
            case "csv":
                $this->arrayToCSV( $posts, "file.csv" );
                break;
            case "excel5":
                $this->excelFormat = "Excel5";
                $this->arrayToExcel( $posts, "file.xls" );
                break;
            case "excel2007":
                $this->excelFormat = "2007";
                $this->arrayToExcel( $posts, "file.xlsx" );
                break;
            case "pdf":
                $this->arrayToPDF( $posts, "file.pdf" );
                break;
            case "xml":
                $this->arrayToXML( $posts, "file.xml" );
                break;
            default:
                $this->db->messages = ( _( "Please select valid export file type", "Export File" ) );
                break;
        }
    }
    
    function fileUpload( $fileObject, $fileUploadPath = "", $maxSize = 100000, $allowedFileTypes = array() ) {
        if ( $this->checkValidFileUpload( $fileObject, $fileUploadPath, $maxSize, $allowedFileTypes ) ) {
            if ( !is_dir( $fileUploadPath ) && $fileUploadPath ) {
                mkdir( $fileUploadPath );
            }
            if ( !$this->uploadedFileName )
                $this->uploadedFileName = $fileUploadPath . $fileObject[ "name" ];
            if ( move_uploaded_file( $fileObject[ "tmp_name" ], $this->uploadedFileName ) ) {
                $this->messages = "File uploaded successfully.";
                return true;
            } else {
                $this->error = "Some error occured in file upload. Please check error. Error code: " . $fileObject[ 'error' ];
                return false;
            }
        }
        
        return false;
    }
    
    
    private function getInputData( $from, $inputSource = "", $outputFileName = "" ) {
        $data = array();
        
        switch ( $from ) {
            case "db":
                $data = $this->db->dbSelect( $this->dbTableName, $this->columns );
                break;
            case "excel":
                $data = $this->excelToArray( $inputSource );
                break;
            case "html":
                $data = $this->htmlToArray( $inputSource );
                break;
            case "xml":
                $data = $this->xmlToArray( $inputSource );
                break;
            case "csv":
                $data = $this->csvToArray( $inputSource );
                break;
            case "sql":
                $data = $this->db->dbExecuteQuery( $inputSource );
                break;
            default:
                $this->error = "Please enter valid format";
                break;
        }
        $this->messages .= " Data exported successfully";
        return $data;
    }
    
    public function formatInputData( $type, $content, $from = "" ) {
        $rows     = 0;
        $startRow = 1;
        $data     = array();
        
        if ( $type == "xml" || $from == "xml" ) {
            $content = array_values_recursive( $content );
            $content = getTwoDimensionalArray( $content );
        }
        
        if ( count( $this->columns ) == 0 ) {
            $this->columns = $this->getColumns( $content, $type );
        }
        
        for ( $rows = $startRow; $rows < count( $content ); $rows++ ) {
            for ( $csvColLoop = 0; $csvColLoop < count( $this->columns ); $csvColLoop++ ) {
                $array[ $this->columns[ $csvColLoop ] ] = $content[ $rows ][ $csvColLoop ];
            }
            if ( $this->rowTagName && $to = 'xml' )
                $data[][ $this->rowTagName ] = $array;
            else
                $data[] = $array;
        }
        return $data;
    }
    
    private function checkValidFileUpload( $fileObject, $fileUploadPath, $maxSize, $allowedFileTypes ) {
        if ( count( $allowedFileTypes ) > 0 ) {
            $fileExtensionLowerCase = strtolower( getFileExtension( $fileObject[ 'name' ] ) );
            $fileExtensionUpperCase = strtoupper( getFileExtension( $fileObject[ 'name' ] ) );
            if ( !in_array( $fileExtensionLowerCase, $allowedFileTypes ) && !in_array( $fileExtensionUpperCase, $allowedFileTypes ) ) {
                $this->error = "Invalid file type";
                return false;
            }
        }
        
        if ( $fileObject[ "size" ] == 0 ) {
            $this->error = "File size is 0 bytes. Please upload a valid file";
            return false;
        }
        
        if ( $fileObject[ "size" ] > $maxSize ) {
            $this->error = "File size is greater than max. file size allowed";
            return false;
        }
        
        if ( $fileObject[ "size" ] > $this->getBytes( ini_get( 'upload_max_filesize' ) ) ) {
            $this->error = "File size is greater than max. file size allowed in INI File";
            return false;
        }
        
        if ( $fileObject[ "error" ] > 0 ) {
            $this->error = "There is some error occured. Please check error. Error code: " . $fileObject[ "error" ];
            return false;
        }
        
        if ( file_exists( $fileUploadPath . $fileObject[ "name" ] ) && !$this->replaceOlderFile ) {
            $this->error = $fileObject[ "name" ] . " already exists. ";
            return false;
        }
        
        if ( !preg_match( "`^[-0-9A-Z_\. ]+$`i", $fileObject[ "name" ] ) && $this->checkFileName ) {
            $this->error = $fileObject[ "name" ] . " contains illegal character in name.";
            return false;
        }
        
        if ( !mb_strlen( $fileObject[ "name" ], "UTF-8" ) > 225 && $this->checkFileNameCharacters ) {
            $this->error = $fileObject[ "name" ] . " must be less than 225 characters";
            return false;
        }
        
        return true;
    }
    
    private function getBytes( $sizeStr ) {
        switch ( substr( $sizeStr, -1 ) ) {
            case 'M':
            case 'm':
                return (int) $sizeStr * 1048576;
            case 'K':
            case 'k':
                return (int) $sizeStr * 1024;
            case 'G':
            case 'g':
                return (int) $sizeStr * 1073741824;
            default:
                return $sizeStr;
        }
    }
    
    private function generateXML( $xmlArray, &$xmlObject, $rootElement = "root" ) {
        foreach ( $xmlArray as $key => $value ) {
            if ( is_array( $value ) ) {
                $obj = $xmlObject->addChild( "items" );
                if ( !is_numeric( $key ) ) {
                    $subnode = $obj->addChild( "$key" );
                    $this->generateXML( $value, $subnode, $rootElement );
                } else {
                    $this->generateXML( $value, $obj, $rootElement );
                }
            } else {
                if ( is_numeric( $key ) ) {
                    $key = $value;
                }
                $xmlObject->addChild( "$key", "$value" );
            }
        }
    }
    
    private function getColumns( $content, $type ) {
        foreach ( $content[ 0 ] as $columns ) {
            if ( is_array( $columns ) ) {
                return $this->getColumns( $content[ 0 ] );
            }
            
            if ( $type == "xml" )
                $cols[] = str_replace( " ", "-", trim( $columns ) );
            else
                $cols[] = $columns;
        }
        return $cols;
    }
    
    private function exportTaxonomy( array $items ) {
        $output = array();
        foreach ( $items as $item ) {
            if ( trim( strtolower ( $item->slug ) ) === trim( strtolower ( $item->name ) ) )
                $text = "{$item->slug}";
            else
                $text = "{$item->slug}:{$item->name}";
            if ( $item->parent ) {
                $parent = get_term( $item->parent, $item->taxonomy );
                $text   = $parent->slug . '~' . $text;
            }
            
            $output[] = $text;
        }
        
        return implode( ',', $output );
    }
    
    private function getWPUploadDir() {
        $upload_dir   = wp_upload_dir();
        $fileSavePath = $upload_dir[ 'basedir' ] . "/" . $this->uploadDirectory;
        
        if ( !is_dir( $fileSavePath ) )
            wp_mkdir_p( $fileSavePath );
        
        return $fileSavePath;
    }
    
}
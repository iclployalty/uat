<?php

class ACS_Model {

    public $error_info = ""; // Display the error message, if any. Use this for debugging purpose 
    public $messages = array(); // Display the last message associated with the task like  connected to database
    private $values = array(); // array of values  
    public $query; // Display the last query executed
    public $rows_affected; // Display the no. of rows affected
    public $count_rows; // Display no. of rows returned by select query operation
    public $last_insert_id; // Display the insert id of last insert operation executed	
    public $and_or_condition = "and"; // Use 'and'/'or' in where condition of select statement, default is 'and'	
    public $group_by_column = ""; // Set it to column names you wants to GROUP BY e.g. 'gender' where gender is column name
    public $order_by_column = ""; // Set it to column names you wants to ORDER BY e.g. 'colName DESC'	
    public $limit_val = ""; // Set it to limit the no. of rows returned e.g. '0,10', it generates 'LIMIT 0,10'
    public $having = ""; // Set it to use 'HAVING' keyword in select query e.g. $having="sum(col1)>1000"	
    public $between_columns = array(); // Set it to use 'BETWEEN' keyword in select query e.g. $between=array ("col1"=>val1,"col1"=>val2)
    public $in = array(); // Set it to use 'IN' keyword in select query e.g. $in=array("col1"=>"val1,val2,val3")
    public $not_in = array(); // Set it to use 'NOT IN' keyword in select query e.g. $not_in=array("col1"=>"val1,val2,val3")
    public $like_cols = array(); // Set it to use 'LIKE' keyword in select query e.g. $like_col=array("col1"=>"%v%","col2"=>"c%")				
    public $is_sanitize = true; // Checks whether basic sanitization of query varibles needs to be done or not.
    public $single_row = false; // Returns single row of select query operation if true, else return all rows
    public $backticks = "`"; // Backtick for preventing error if columnname contains reserverd mysql keywords. 
    public $fetch_mode = "ASSOC"; // Determines fetch mode of the result of select query
    public $charset = "utf8"; // If you want to use any other charset than default one, then set this charset value.
    public $rows_returned = 0; // It shows no. of rows returned in select operation
    public $resetAllSettings = true; // It reset all the values on that object after each sql operation if it is set true

    public function __construct() {
        
    }

    /**
     * Insert new records in a table using associative array. Instead of writing long insert queries, you needs to pass
     * array of keys(columns) and values(insert values). This function will automatically create query for you and inserts data.
     * @param   string   $table_name              The name of the table to insert new records.
     * @param   array    $insert_array            Associative array with key as column name and values as column value.
     *
     */
    function dbInsert($table_name, $insert_array) {
        global $wpdb;
        $wpdb->insert($table_name, $insert_array);
        $this->last_insert_id = $wpdb->insert_id;
        $this->rows_affected++;
    }

    /**
     * Insert batch records in a table using array of associative array.This function will insert multiple rows using array
     * of associative array. 
     * @param   string   $table_name                    The name of the table to insert new records.
     * @param   array    $insert_batch_array            Array of associative array with key as column name and values as column value.
     *
     */
    function dbInsertBatch($table_name, $insert_batch_array) {
        foreach ($insert_batch_array as $insert_array) {
            $this->dbInsert($table_name, $insert_array);
        }
    }

    /**
     * Select records from the single table. You can provide columns to be selected and where clause with
     * associative array of conditions with keys as columns and value as column value. Along with these function parameters,
     * you can set group by columnname, order by columnname, limit, like, in , not in, between clause etc. 
     * This function will automatically creates query for you and select data.
     * @param   string   $table_name                  The name of the table to select records.
     * @param   array    $columns                     Array of columns to be selected
     * @param   array    $select_where_condition      Associative array with key as column name and values as column value for where clause.	
     * return   array                                 returns array as result of query.
     */
    function dbSelect($table_name, $columns = array(), $select_where_condition = array()) {
        global $wpdb;
        
        $this->values = array();
        
        /* Get Columns */
        $col = $this->getColumns($columns);

        /* Add where condition */
        $where_condition = $this->getWhereCondition($select_where_condition);

        /* Add like condition */
        $where_condition = $this->getLikeCondition($where_condition);

        /* Add Between condition */
        $where_condition = $this->getBetweenCondition($where_condition);

        /* Add In condition */
        $where_condition = $this->getInCondition($where_condition);

        /* Add Not In condition */
        $where_condition = $this->getNotInCondition($where_condition);

        /* Add Group By and Having condition */
        $where_condition = $this->getGroupByCondition($where_condition);

        /* Add Order By condition */
        $where_condition = $this->getOrderbyCondition($where_condition);

        /* Add Limit condition */
        $where_condition = $this->getLimitCondition($where_condition);

        $this->query = "SELECT " . $col . " FROM " . $this->backticks . trim($table_name) . $this->backticks . $where_condition;
        $result = $wpdb->get_results($this->query, ARRAY_A);
        if (is_array($result))
            $this->rows_returned = count($result);

        return $result;
    }

    /**
     * Executes any mysql query and returns the result array(in case of select query). 
     * Use this for running any other queries that can't be run using the other select,insert,update,delete functions
     * @param   string  $query       			Query to be executed
     * @param   array   $parameter_values       values of the columns passed
     *
     * return   array               result of the query
     */
    function dbExecuteQuery($query, $parameter_values = array()) {
        global $wpdb;
        $result = $wpdb->get_results($query, ARRAY_A);
        return $result;
    }

    /**
     * Retrives the column names from a given table
     * @param   string  $table    The name of the table to get columns.
     *
     * return   array             column name in array
     */
    function dbGetColumnName($table_name) {
        global $wpdb;
        $query = "DESCRIBE $table_name";
        $result = $wpdb->get_results($query, ARRAY_A);
        return $result;
    }

    /**
     * Retrives all the tables from database
     *
     * return   array             table names in array
     */
    function dbGetTableName() {
        global $wpdb;
        $query = "SHOW TABLES LIKE '%'";
        $result = $wpdb->get_results($query, ARRAY_A);
        return $result;
    }

    function dbPostTitle($postTitle, $type = "POST") {
        global $wpdb;
        $query = "select ID from " . $wpdb->posts . " where `post_title`=  %s and post_type = '" . $type . "' 
					and post_status IN ('publish','future','draft','pending','private')";
        $postId = $wpdb->get_var($wpdb->prepare($query, $postTitle));
        return $postId;
    }

    function dbCheckUser($userLogin) {
        global $wpdb;
        $query = "select ID from $wpdb->users where user_login=%s";
        $author = $wpdb->get_var($wpdb->prepare($query, $userLogin));
        return $author;
    }

    function getPostMeta() {
        global $wpdb;
        $postMeta = array();
        $metakeys = $wpdb->get_col("SELECT distinct(meta_key) FROM $wpdb->postmeta GROUP BY meta_key ORDER BY meta_key");

        foreach ($metakeys as $val) {
            $postMeta["PM: " . $val] = $val;
        }

        return $postMeta;
    }

    function getUserMeta() {
        global $wpdb;
        $postMeta = array();
        $metakeys = $wpdb->get_col("SELECT distinct(meta_key) FROM $wpdb->usermeta GROUP BY meta_key ORDER BY meta_key");

        foreach ($metakeys as $val) {
            $postMeta["UM: " . $val] = $val;
        }

        return $postMeta;
    }

    function getCustomTaxonomies() {
        global $wpdb;
        $customTaxonomies = array();
        $taxonomies = get_taxonomies(array(
            'public' => true
        ));
        foreach ($taxonomies as $key => $val) {
            if ($key != 'category' && $key != 'post_tag') {
                $customTaxonomies["CT: " . $val] = $val;
            }
        }
        return $customTaxonomies;
    }

    function getCustomPostType() {
        $args = array(
            'public' => true,
            '_builtin' => false
        );
        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'

        $customPostTypes = get_post_types($args, $output, $operator);

        return $customPostTypes;
    }

    function addMessages($message, $msg_category) {
        $newmsg = array(
            'message' => $message,
            'msg_category' => $msg_category
        );
        $this->messages[] = $newmsg;
    }

    function getMessage() {
        return $this->messages;
    }

    /*     * ********************************************************* Internal Functions ***************************************** */

    /* Returns column names */

    private function getColumns($columns = array()) {
        $col = "*";
        if (count($columns) > 0 && is_array($columns)) {
            $col = "";
            foreach ($columns as $column) {
                $col = $col . $this->backticks . trim($column) . $this->backticks . ",";
            }
            $col = rtrim($col, ",");
        }
        return $col;
    }

    /* Returns where condition */

    private function getWhereCondition($select_where_condition = array()) {
        $where_condition = "";
        $matches = array();
        if (is_array($select_where_condition)) {
            foreach ($select_where_condition as $cols => $vals) {
                $compare = "=";
                if (preg_match("#([^=<>!]+)\s*(=|<|>|(!=)|(>=)|(<=)|(>=))#", strtolower(trim($cols)), $matches)) {
                    $compare = $matches[2];
                    $cols = trim($matches[1]);
                }
                $where_condition = $where_condition . $this->backticks . $cols . $this->backticks . $compare ."'".$vals."'". $this->and_or_condition;
            }

            if ($where_condition)
                $where_condition = " WHERE " . rtrim($where_condition, $this->and_or_condition);
        }
        return $where_condition;
    }

    /* Returns like condition */

    private function getLikeCondition($where_condition = "") {
        if (is_array($this->like_cols) && count($this->like_cols) > 0) {
            $like = "";
            foreach ($this->like_cols as $cols => $vals) {
                $like .= $this->backticks . $cols . $this->backticks . " Like ? " . $this->and_or_condition;
                $this->values[] = $vals;
            }

            if ($where_condition)
                $where_condition .= " " . $this->and_or_condition . " " . rtrim($like, $this->and_or_condition);
            else
                $where_condition = " WHERE " . rtrim($like, $this->and_or_condition);
        }
        return $where_condition;
    }

    /* Returns between condition */

    private function getBetweenCondition($where_condition = "") {
        if (is_array($this->between_columns) && count($this->between_columns) > 0) {
            reset($this->between_columns);
            $between = key($this->between_columns) . " BETWEEN ? and ?";

            foreach ($this->between_columns as $cols => $vals) {
                $this->values[] = $vals;
            }


            if ($where_condition)
                $where_condition .= " " . $this->and_or_condition . " " . $between;
            else
                $where_condition = " WHERE " . $between;
        }

        return $where_condition;
    }

    /* Returns in condition */

    private function getInCondition($where_condition = "") {
        if ($this->in && count($this->in) > 0) {
            $in = "";
            foreach ($this->in as $cols => $vals) {
                $in .= $this->backticks . $cols . $this->backticks . " IN (" . $vals . ") " . $this->and_or_condition;
            }

            if ($where_condition)
                $where_condition .= " " . $this->and_or_condition . " " . rtrim($in, $this->and_or_condition);
            else
                $where_condition = " WHERE " . rtrim($in, $this->and_or_condition);
        }
        return $where_condition;
    }

    /* Returns not in condition */

    private function getNotInCondition($where_condition = "") {
        if ($this->not_in && count($this->not_in) > 0) {
            $not_in = "";
            foreach ($this->not_in as $cols => $vals) {
                $not_in .= $this->backticks . $cols . $this->backticks . " NOT IN (" . $vals . ") " . $this->and_or_condition;
            }

            if ($where_condition)
                $where_condition .= " " . $this->and_or_condition . " " . rtrim($not_in, $this->and_or_condition);
            else
                $where_condition = " WHERE " . rtrim($not_in, $this->and_or_condition);
        }
        return $where_condition;
    }

    /* Returns group by condition */

    private function getGroupByCondition($where_condition = "") {
        if ($this->group_by_column)
            $where_condition .= " GROUP BY " . $this->group_by_column;

        if ($this->group_by_column && $this->having)
            $where_condition .= " HAVING " . $this->having;

        return $where_condition;
    }

    /* Returns order by  condition */

    private function getOrderbyCondition($where_condition = "") {
        if ($this->order_by_column)
            $where_condition .= " ORDER BY " . $this->order_by_column;

        return $where_condition;
    }

    /* Returns limit condition */

    private function getLimitCondition($where_condition = "") {
        if ($this->limit_val)
            $where_condition .= " LIMIT " . $this->limit_val;

        return $where_condition;
    }

    /**
     * Reset all values to default values
     */
    private function resetSettings() {
        $this->and_or_condition = "and";
        $this->group_by_column = "";
        $this->order_by_column = "";
        $this->limit_val = "";
        $this->having = "";
        $this->between_columns = array();
        $this->in = array();
        $this->not_in = array();
        $this->like_cols = array();
        $this->is_sanitize = true;
        $this->single_row = false;
        $this->backticks = "`";
        $this->fetch_mode = "ASSOC";
    }

}
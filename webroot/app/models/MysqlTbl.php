<?php

class MysqlTbl {

    private $DB;
    private $insert_id = false;

    public function __construct() {
        $this->DB =  new mysqli(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
        $this->DB->init();
        if ($this->DB->connect_errno) {
            Error::noDatabase();
        }
    }

    public function getRow($query, $opts) {
        $stmt = $this->getQueryStatement($query, $opts);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {return false;}

        $metaResults = $stmt->result_metadata();
        $fields = $metaResults->fetch_fields();
        $result = array();
        foreach ($fields as $field) {
            $result[$field->name] = null;
        }
        call_user_func_array(array($stmt, 'bind_result'), &$result);
        $stmt->fetch();
        return $result;
    }

    public function getResults($query, $opts = false) {
        $stmt = $this->getQueryStatement($query, $opts);
        $stmt->execute();
        $stmt->store_result();

        $metaResults = $stmt->result_metadata();
        $fields = $metaResults->fetch_fields();

        $results = array();

        for($i = 0; $i < $stmt->num_rows; $i++) {
            $result = array();
            foreach ($fields as $field) {
                $result[$field->name] = null;
            }
            call_user_func_array(array($stmt, 'bind_result'), &$result);
            $stmt->fetch();
            $results[$i] = $result;
        }
        return $results;
    }

    public function getItem($query, $opts = false) {
        $result = $this->getRow($query, $opts);
        $result = array_values($result);
        return $result[0];
    }

    public function query($query, $opts) {
        $stmt = $this->getQueryStatement($query, $opts);
        $stmt->execute();
        $this->insert_id = $this->DB->insert_id;
        return $stmt;
    }

    protected function insertQuery($table, $fields) {

        $query = "INSERT INTO " . $table . " (";

            $first = true;
            foreach($fields as $field => $value) {
                $query .= ($first ? "" : ", ") . $field; $first = false;
            }

        $query .= ") VALUES (";

            $first = true;
            $opts = array();
            foreach($fields as $field => $value) {
                $query .= ($first ? "" : ", ") . "?"; $first = false;
                array_push($opts, $value);
            }

        $query .= ")";

        return $this->query($query, $opts);

    }

    public function insertId() {
        if (!isset($this->insert_id)) {return false;}
        return $this->insert_id;
    }

    /**
     * @return mysqli_stmt
     */
    protected function getQueryStatement($query, $opts = false) {
        $stmt = $this->DB->prepare($query);
        if (!is_object($stmt)) {
            throw new Exception("Error perparing query.");
        }
        if ($opts != false && isset($opts[0])) {
            $types = "";
            $values = array();
            for($i = 0; isset($opts[$i]); $i++) {
                $types .= "s";
                $values[] = $opts[$i];
            }
            call_user_func_array(array($stmt, 'bind_param'), self::refVals(array_merge(array($types), $values)));
        }
        return $stmt;
    }

    public function filterFields($data, $available_fields) {
        $fields = array();
        foreach($available_fields as $field) {
            if (isset($data[$field])) {$fields[$field] = $data[$field];}
        }
        return $fields;
    }

    public function getAll($limit = false) {
        if ($limit <= 0) {$limit = 50;}
        $query = 
            "SELECT * FROM " . 
                self::NAME . 
            " LIMIT ?";
        $opts = array($limit);
        return $this->getResults($query, $opts);
    }

    // Apparently bind_params requires references
    static public function refVals($arr) {
        if (strnatcmp(phpversion(),'5.3') >= 0) {
            $refs = array();
            foreach($arr as $key => $value) $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }

}
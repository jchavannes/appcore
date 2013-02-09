<?php

class UserTbl extends MysqlTbl {

    const NAME = 'user';

    const ID = 'id';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const EMAIL = 'email';
    const DATE_CREATED = 'date_created';
    const PERMISSIONS = 'permissions';

    const CUSTOM_POSTS = 'posts';

    const LOGGED_IN = 'logged_in';

    public function createUser($fields) {

        $time = time();

        $query = 
            "INSERT INTO " . UserTbl::NAME . " (" .
                UserTbl::USERNAME . ", " .
                UserTbl::PASSWORD . ", " .
                UserTbl::DATE_CREATED . ", " .
                UserTbl::PERMISSIONS . ") " .
            "VALUES (?, ?, ?, 0)";

        $opts = array(
            preg_replace('/[^A-Za-z0-9]/', '', $fields[UserTbl::USERNAME]),
            md5(md5($fields[UserTbl::PASSWORD]) . $time),
            $time
        );

        if ($this->query($query, $opts) && $this->insertId() !== false && $this->insertId() > 0) {
            $user_data = array(UserTbl::ID => $this->insertId(), UserTbl::USERNAME => $opts[0], UserTbl::PERMISSIONS => 0);
            return $user_data;
        } else {
            return false;
        }

    }

    public function editUser($data) {

        if (!isset($data[UserTbl::ID])) {return false;}

        $fields = parent::filterFields($data, array(UserTbl::USERNAME, UserTbl::PASSWORD, UserTbl::EMAIL));
        $opts = array();

        $query = "UPDATE " . UserTbl::NAME . " SET ";
        
        $first = true;
        foreach($fields as $name => $value) {
            $query .= (!$first ? ", ":"") . "$name = " . ($name == UserTbl::PASSWORD ? "md5(CONCAT(?, ".UserTbl::DATE_CREATED."))":"?");
            array_push($opts, ($name == UserTbl::PASSWORD ? md5($value) : $value));
            $first = false;
        }
        
        $query .= " WHERE " . UserTbl::ID . " = ?";
        array_push($opts, $data[UserTbl::ID]);

        if (isset($fields[UserTbl::PASSWORD])) {
            $query .= " AND " . UserTbl::PASSWORD . " = md5(CONCAT(?, " . UserTbl::DATE_CREATED . "))";
            array_push($opts, md5($data[UserController::USER_OLDPASSWORD]));
        }

        return $this->query($query, $opts);

    }

    public function checkUser($data) {

        $fields = parent::filterFields($data, array(UserTbl::USERNAME, UserTbl::EMAIL));
        $opts = array();

        $query =
            "SELECT " . 
                UserTbl::ID . ", " .
                UserTbl::USERNAME . ", " .
                UserTbl::EMAIL .
            " FROM " . 
                UserTbl::NAME . 
            " WHERE (";
        
        $first = true;
        foreach ($fields as $name => $value) {
            $query .= (!$first ? " OR ":"") . "$name = ?";
            array_push($opts, $value);
            $first = false;
        }
        $query .= ")";
        if (isset($data[UserTbl::ID])) {
            $query .= " AND " . UserTbl::ID . " != ?";
            array_push($opts, $data[UserTbl::ID]);
        }

        return $this->getResults($query, $opts);

    }

    public function login($fields) {

        $query = 
            "SELECT " .
                UserTbl::ID . ", " .
                UserTbl::USERNAME . ", " .
                UserTbl::EMAIL . ", " .
                UserTbl::DATE_CREATED . ", " .
                UserTbl::PERMISSIONS .
            " FROM " .
                UserTbl::NAME .
            " WHERE " .
                UserTbl::USERNAME . " = ? AND " .
                UserTbl::PASSWORD . " = md5(CONCAT(?, " . UserTbl::DATE_CREATED . "))";

        $opts = array(
            preg_replace('/[^A-Za-z0-9]/', '', $fields[UserTbl::USERNAME]),
            md5($fields[UserTbl::PASSWORD])
        );

        return $this->getRow($query, $opts);

    }

    public function getAllUsers() {
        $query =
            "SELECT " .
                UserTbl::ID . ", " .
                UserTbl::USERNAME . ", " .
                UserTbl::EMAIL . ", " .
                UserTbl::DATE_CREATED . ", " .
                UserTbl::PERMISSIONS .
            " FROM " .
                UserTbl::NAME .
            " LIMIT 250";

        return $this->getResults($query);

    }

    public function getUserInfo($name = false) {
        if ($name === false && !isset($_SESSION[Session::USERNAME])) {
            return false;
        } elseif ($name === false) {
            $name = $_SESSION[Session::USERNAME];
        }
        $query =
            "SELECT " .
                UserTbl::NAME . "." . UserTbl::ID . ", " .
                UserTbl::NAME . "." . UserTbl::USERNAME . ", " .
                UserTbl::NAME . "." . UserTbl::EMAIL . ", " .
                UserTbl::NAME . "." . UserTbl::DATE_CREATED . ", " .
                UserTbl::NAME . "." . UserTbl::PERMISSIONS . ", " .
                "COUNT(" . CommentTbl::NAME . "." . CommentTbl::ID . ") as " . UserTbl::CUSTOM_POSTS .
            " FROM " .
                UserTbl::NAME .
            " LEFT JOIN " . 
                CommentTbl::NAME .
            " ON " .
                UserTbl::NAME . "." . UserTbl::ID . " = " . CommentTbl::NAME . "." . CommentTbl::USER_ID .
            " WHERE " .
                UserTbl::NAME . "." . UserTbl::USERNAME . " = ?";

        $opts = array($name);
        $results = $this->getResults($query, $opts);
        if (isset($results[0]) && $results[0][UserTbl::ID] != "") {return $results[0];}
        return false;
    }

}
<?php

class SessionTbl extends MysqlTbl {

    const NAME = 'session';

    const ID = 'id';
    const USER_ID = 'user_id';
    const IP_ADDRESS = 'ip_address';
    const FIRST_VISIT = 'first_visit';
    const LAST_VISIT = 'last_visit';
    const ID_RESET = 'id_reset';
    const VISITS = 'visits';
    const PHPSESHID = 'phpseshid';

    public function getSession($id) {

        $query = 
            "SELECT " .
                SessionTbl::ID . ", " .
                SessionTbl::USER_ID . ", " .
                SessionTbl::IP_ADDRESS . ", " .
                SessionTbl::FIRST_VISIT . ", " .
                SessionTbl::ID_RESET . ", " .
                SessionTbl::VISITS .
            " FROM " .
                SessionTbl::NAME .
            " WHERE " .
                SessionTbl::PHPSESHID . " = ?";

        $opts = array($id);

        return $this->getRow($query, $opts);

    }

    public function updateSession($updates, $id) {
        
        $opts = array();
        $query = "UPDATE " . SessionTbl::NAME . " SET ";

            $first = true;
            foreach($updates as $field => $value) {
                $query .= ($first ? "" : ", ") . $field . " = ?"; $first = false;
                array_push($opts, $value);
            }

        $query .= " WHERE " . SessionTbl::ID ." = ?";

        array_push($opts, $id);

        return $this->query($query, $opts);

    }

    public function createSession($ip, $time) {

        $fields = array(
            SessionTbl::IP_ADDRESS => $ip,
            SessionTbl::PHPSESHID => session_id(),
            SessionTbl::FIRST_VISIT => $time,
            SessionTbl::LAST_VISIT => $time,
            SessionTbl::ID_RESET => $time,
            SessionTbl::VISITS => 1
        );

        return $this->insertQuery(SessionTbl::NAME, $fields);

    }

    public function setUser($id) {

        $query =
            "UPDATE " .
                SessionTbl::NAME .
            " SET " .
                SessionTbl::USER_ID . " = ?" .
            " WHERE " .
                SessionTbl::PHPSESHID . " = ?";

        $opts = array($id, session_id());

        return $this->query($query, $opts);

    }

    public function getVisits() {

        $query = 
            "SELECT " .
                UserTbl::NAME . "." . UserTbl::USERNAME . " AS " . UserTbl::USERNAME . ", " .
                SessionTbl::NAME . "." . SessionTbl::IP_ADDRESS . " AS " . SessionTbl::IP_ADDRESS . ", " .
                SessionTbl::NAME . "." . SessionTbl::FIRST_VISIT . " AS " . SessionTbl::FIRST_VISIT . ", " .
                SessionTbl::NAME . "." . SessionTbl::LAST_VISIT . " AS " . SessionTbl::LAST_VISIT . ", " .
                SessionTbl::NAME . "." . SessionTbl::VISITS . " AS " . SessionTbl::VISITS . 
            " FROM " .
                SessionTbl::NAME . 
             " LEFT JOIN " . 
                 UserTbl::NAME .
             " ON " .
                 SessionTbl::NAME . "." . SessionTbl::USER_ID . " = " . UserTbl::NAME . "." . UserTbl::ID .
             " ORDER BY " .
                 SessionTbl::LAST_VISIT .
             " DESC";

        return $this->getResults($query);

    }

}
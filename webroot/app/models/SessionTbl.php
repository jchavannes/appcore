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
            " FROM " . SessionTbl::NAME .
            " WHERE " . SessionTbl::PHPSESHID . " = ?";

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

    public function createSession($fields) {

        return $this->insertQuery(SessionTbl::NAME, $fields);

    }

    public function setUser($id) {

        $query =
            "UPDATE " . SessionTbl::NAME .
            " SET " . SessionTbl::USER_ID . " = ?" .
            " WHERE " . SessionTbl::PHPSESHID . " = ?";

        $opts = array($id, session_id());

        return $this->query($query, $opts);

    }

    public function getVisits() {

        $query =
            "SELECT " .
                UserTbl::NAME . "." . UserTbl::USERNAME . " AS " . UserTbl::USERNAME . ", " .
                SessionTbl::NAME . "." . SessionTbl::ID . " AS " . SessionTbl::ID . ", " .
                SessionTbl::NAME . "." . SessionTbl::IP_ADDRESS . " AS " . SessionTbl::IP_ADDRESS . ", " .
                SessionTbl::NAME . "." . SessionTbl::FIRST_VISIT . " AS " . SessionTbl::FIRST_VISIT . ", " .
                SessionTbl::NAME . "." . SessionTbl::LAST_VISIT . " AS " . SessionTbl::LAST_VISIT . ", " .
                SessionTbl::NAME . "." . SessionTbl::VISITS . " AS " . SessionTbl::VISITS . ", " .
                "COUNT(" . HttpRequestTbl::NAME . "." . HttpRequestTbl::SESSION_ID . ") AS request_count " .
            " FROM " . SessionTbl::NAME .
            " LEFT JOIN " . UserTbl::NAME .
                " ON " . SessionTbl::NAME . "." . SessionTbl::USER_ID . " = " . UserTbl::NAME . "." . UserTbl::ID .
            " LEFT JOIN " . HttpRequestTbl::NAME .
                " ON " . SessionTbl::NAME . "." . SessionTbl::ID . " = " . HttpRequestTbl::NAME . "." . HttpRequestTbl::SESSION_ID .
            " WHERE " . SessionTbl::NAME . "." . SessionTbl::VISITS . " > 1" .
            " AND (" . UserTbl::NAME . "." . UserTbl::PERMISSIONS . " & 1 != 1" .
            " OR " . UserTbl::NAME . "." . UserTbl::PERMISSIONS . " IS NULL)" .
            " GROUP BY " . SessionTbl::NAME . "." . SessionTbl::ID .
            " ORDER BY " . SessionTbl::LAST_VISIT . " DESC";

        return $this->getResults($query);

    }

    public function getVisitorInfo($id) {

        $query =
            "SELECT " .
                SessionTbl::ID . ", " .
                SessionTbl::PHPSESHID . ", " .
                SessionTbl::USER_ID . ", " .
                SessionTbl::IP_ADDRESS . ", " .
                SessionTbl::FIRST_VISIT . ", " .
                SessionTbl::LAST_VISIT . ", " .
                SessionTbl::ID_RESET . ", " .
                SessionTbl::VISITS .
            " FROM " . SessionTbl::NAME .
            " WHERE " . SessionTbl::ID . " = ?";

        $opts = array($id);

        return $this->getRow($query, $opts);
    }

    public function getIpSessionCount($ip) {
        $query =
            "SELECT COUNT(*) FROM " . self::NAME .
            " WHERE " . self::IP_ADDRESS . " = ?";
        return $this->getItem($query, array($ip));
    }

}

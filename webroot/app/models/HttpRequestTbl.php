<?php

class HttpRequestTbl extends MysqlTbl {

    const NAME = 'http_request';

    const GET = 'get';
    const POST = 'post';
    const SESSION_ID = 'session_id';
    const DATE = 'date';

    static public function logHttpRequest() {

        $query =
            "INSERT INTO " . HttpRequestTbl::NAME .
            " (" .
                HttpRequestTbl::GET . ", " .
                HttpRequestTbl::POST . ", " .
                HttpRequestTbl::SESSION_ID . ", " .
                HttpRequestTbl::DATE .
            ") " .
            " VALUES (?, ?, ?, ?)";

        $post = "";
        foreach($_POST as $key => $value) {
            $nolog = array(
                "password",
                "verify_password",
                "creditcard_type",
                "creditcard_number",
                "creditcard_expire_month",
                "creditcard_expire_year",
                "creditcard_security_code"
            );
            if(in_array($key, $nolog)) {
                $value = "NOLOG";
            }
            $post .= ($post == "" ? "" : "&") . (string)$key . "=" . (string)$value;
        }

        $get = Loader::getRequest();;

        $opts = array(
            $get,
            $post,
            $_SESSION[Session::DATABASE_ID],
            time()
        );

        $http_request = new HttpRequestTbl();
        $http_request->query($query, $opts);

    }

    public function getPageCounts() {

        $query =
            "SELECT " .
                "DISTINCT(" . HttpRequestTbl::GET . ") as " . HttpRequestTbl::GET . ", " .
                "COUNT(" . HttpRequestTbl::GET . ") as count, " .
                "COUNT(CASE WHEN LENGTH(" . HttpRequestTbl::POST . ") > 0 THEN 1 END) as posts, " .
                "COUNT(DISTINCT " . HttpRequestTbl::SESSION_ID . ") as sessions" .
            " FROM " . HttpRequestTbl::NAME .
            " GROUP BY " . HttpRequestTbl::GET .
            " ORDER BY count DESC";

        return $this->getResults($query);
    }

    public function getAllRequests($id) {
        $query =
            "SELECT * FROM " . self::NAME .
            " WHERE " . self::SESSION_ID . " = ?";
        return $this->getResults($query, array($id));
    }

}
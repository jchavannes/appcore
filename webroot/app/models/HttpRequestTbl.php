<?php

class HttpRequestTbl extends MysqlTbl {

	const NAME = 'http_request';

	const GET = 'get';
	const POST = 'post';
	const SESSION_ID = 'session_id';
	const date = 'date';

	public function logHttpRequest() {

		$query = 
			"INSERT INTO " . 
				HttpRequestTbl::NAME .
			" (" .
				HttpRequestTbl::GET . ", " .
				HttpRequestTbl::POST . ", " .
				HttpRequestTbl::SESSION_ID . ", " .
				HttpRequestTbl::date . ") " .
			" VALUES (?, ?, (" .
				"SELECT " .
					SessionTbl::ID . 
				" FROM " . 
					SessionTbl::NAME .
				" WHERE " .
					SessionTbl::PHPSESHID . " = ?" .
			"), ?)";

		$post = "";
		foreach($_POST as $key => $value) {
			$nolog = array(
				"password",
				"verify_password");
			if(in_array($key, $nolog)) {
				$value = "NOLOG";
			}
			$post .= ($post == "" ? "" : "&") . (string)$key . "=" . (string)$value;
			if(strlen($post) > 255) {break;}
		}

		$get = (isset($_GET['q']) ? $_GET['q'] : null);
		$post = substr($post, 0, 255);

		$opts = array(
			$get,
			$post,
			session_id(),
			time()
		);

		$http_request = new HttpRequestTbl();
		$stmt = $http_request->query($query, $opts);
    	//printf("Errormessage: %s\n", $stmt->error);

	}

	public function getPageCounts() {

		$query = 
			"SELECT " .
				"DISTINCT(" . HttpRequestTbl::GET . ") as " . HttpRequestTbl::GET . ", " .
				"COUNT(" . HttpRequestTbl::GET . ") as count, " .
				"COUNT(CASE WHEN LENGTH(" . HttpRequestTbl::POST . ") > 0 THEN 1 END) as posts, " .
				"COUNT(DISTINCT " . HttpRequestTbl::SESSION_ID . ") as sessions" .
			" FROM " .
				HttpRequestTbl::NAME . 
			" GROUP BY " .
				HttpRequestTbl::GET .
			" ORDER BY count DESC";

		return $this->getResults($query);
	}

}
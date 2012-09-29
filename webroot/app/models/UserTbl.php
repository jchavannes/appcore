<?php

class UserTbl extends MysqlTbl {

	const NAME 			= 'user';

	const ID 			= 'id';
	const USERNAME 		= 'username';
	const PASSWORD 		= 'password';
	const EMAIL 		= 'email';
	const DATE_CREATED 	= 'date_created';
	const PERMISSIONS	= 'permissions';

	const CUSTOM_POSTS	= 'posts';

	const LOGGED_IN		= 'logged_in';

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

		if($this->query($query, $opts) && $this->insertId() !== false && $this->insertId() > 0) {
			$user_data = array(Session::USER_ID => $this->insertId(), Session::USERNAME => $opts[0]);
			return $user_data;
		} else {
			return false;
		}

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

		$row = $this->getRow($query, $opts);

		return $row;

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
		if($name === false && !isset($_SESSION['username'])) {
			return false;
		} elseif($name === false) {
			$name = $_SESSION['username'];
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
		if(isset($results[0])) {return $results[0];}
		return false;
	}

}
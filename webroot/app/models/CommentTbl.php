<?php

class CommentTbl extends MysqlTbl {

	const NAME = 'comment';

	const ID = 'id';
	const USER_ID = 'user_id';
	const ITEM = 'item';
	const PARENT_ID = 'parent_id';
	const TITLE = 'title';
	const MESSAGE = 'message';
	const DATE = 'date';
	
	public function getAllComments($item) {

		$query = 
			"SELECT " .
				self::NAME . "." . self::ID . ", " .
				self::NAME . "." . self::USER_ID . ", " .
				self::NAME . "." . self::PARENT_ID . ", " .
				self::NAME . "." . self::TITLE . ", " .
				self::NAME . "." . self::MESSAGE . ", " .
				self::NAME . "." . self::DATE . ", " .
				UserTbl::NAME . "." . UserTbl::USERNAME . 
			" FROM " .
				self::NAME . 
			" LEFT JOIN " .
				UserTbl::NAME . 
			" ON " .
				self::NAME . "." . self::USER_ID . " = " . UserTbl::NAME . "." . UserTbl::ID . 
			" WHERE " .
				self::NAME . "." . self::ITEM . " = ?";

		$opts = array($item);

		return $this->getResults($query, $opts);

	}

	public function addComment($query_opts) {

		$opts = array();

		foreach($query_opts as $opt) {
			$opts[] = $opt;
		}

		if(count($opts) != 6) {return false;}

		$query = 
			"INSERT INTO " . 
				self::NAME . " (" .
					self::USER_ID . ", " .
					self::ITEM . ", " .
					self::PARENT_ID . ", " .
					self::TITLE . ", " .
					self::MESSAGE . ", " .
					self::DATE . 
				")" .
			" VALUES (?, ?, ?, ?, ?, ?)";

		return $this->query($query, $opts);

	}

	public function deleteComment($id, $admin = false) {

		if($admin === true) {
			$query =
				"DELETE FROM " .
					self::NAME . 
				" WHERE " .
					self::ID . " = ?";
			$opts = array($id);

		} else {
			$query =
				"DELETE FROM " .
					self::NAME . 
				" WHERE " .
					self::ID . " = ?" .
				" AND " .
					self::USER_ID . " = ?";
			$opts = array($id, $_SESSION[Session::USER_ID]);
		}

		return $this->query($query, $opts);

	}

}
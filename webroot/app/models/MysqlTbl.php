<?php

class MysqlTbl {

	private $DB;
	private $insert_id = false;

	public function __construct() {
		$this->DB =  new mysqli(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	}

	public function getRow($query, $opts) {
		$stmt = $this->getQueryStatement($query, $opts);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows == 0) {return false;}

		$metaResults = $stmt->result_metadata();
		$fields = $metaResults->fetch_fields();
		$first = true;
		$result = array();
		$eval_code = '$stmt->bind_result(';
		foreach($fields as $field) {
			$eval_code .= ($first ? "" : ", ") . '$result[\''.$field->name.'\']'; $first = false;
		}
		$eval_code .= ');';
		eval($eval_code);
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
			$first = true;
			$result = array();
			$eval_code = '$stmt->bind_result(';
			foreach($fields as $field) {
				$eval_code .= ($first ? "" : ", ") . '$result[\''.$field->name.'\']'; $first = false;
			}
			$eval_code .= ');';
			eval($eval_code);
			$stmt->fetch();
			$results[$i] = $result;
		}

		return $results;
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
		if(!isset($this->insert_id)) {return false;}
		return $this->insert_id;
	}

	protected function getQueryStatement($query, $opts = false) {
		$stmt = $this->DB->prepare($query);
		if($opts != false && isset($opts[0])) {
			$eval_code = "'";
			for($i = 0; isset($opts[$i]); $i++) {
				$eval_code = 's' . $eval_code . ', $opts[' . $i . ']';
			}
			$eval_code = '$stmt->bind_param(\'' . $eval_code . ');';
			eval($eval_code);
		}
		return $stmt;
	}

	public function filterFields($data, $available_fields) {
		$fields = array();
		foreach($available_fields as $field) {
			if(isset($data[$field])) {$fields[$field] = $data[$field];}
		}
		return $fields;
	}

}
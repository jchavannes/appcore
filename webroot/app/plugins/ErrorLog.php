<?php

class Error {

	const MAX_LOG_LENGTH = 1000;

	static public function log($newline) {

		$logfile = ROOT_DIR . "../app.log";
		$logs = array();

		if (file_exists($logfile)) {
			$logs = explode("\n", file_get_contents($logfile));
		}

		$logs[] = $newline;

		if (count($logs) > self::MAX_LOG_LENGTH) {
			$logs = array_slice($logs, count($logs) - self::MAX_LOG_LENGTH);
		}

		file_put_contents($logfile, implode($logs, "\n"));

	}

}
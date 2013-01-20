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

    static public function dump($var) {
        echo "<pre style='background:#d5d5d5; border:2px solid #888; padding:5px;'>";
        var_dump($var);
        echo "</pre>";
    }

}
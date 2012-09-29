<?php

	$config = array(
		"MYSQL_HOST" => "localhost",
		"MYSQL_USERNAME" => "root",
		"MYSQL_PASSWORD" => "password",
		"MYSQL_DATABASE" => "appcore"
	);

	$filename = ROOT_DIR . CONFIG_DIR . "local.config.php";

	if(file_exists($filename)) {

		$default_config = $config;
		include($filename);
		$config = array_merge($default_config, $config);

	}

	foreach($config as $k => $v) {define($k, $v);}
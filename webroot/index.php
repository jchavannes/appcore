<?php

$super_globals = array(
	'WEBROOT' => (dirname($_SERVER["SCRIPT_NAME"]) != "/") ? "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER["SCRIPT_NAME"])."/" : "http://".$_SERVER['HTTP_HOST']."/",
	'JS_DIR' => 'inc/js/',
	'ROOT_DIR' => dirname(__FILE__) . DIRECTORY_SEPARATOR,
	'CONFIG_DIR' => 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR,
	'MODEL_DIR' => 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR,
	'VIEW_DIR' => 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
	'CONTROLLER_DIR' => 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR,
	'UTIL_DIR' => 'app' . DIRECTORY_SEPARATOR . 'util' . DIRECTORY_SEPARATOR,
	'HELPER_DIR' => 'app' . DIRECTORY_SEPARATOR . 'util' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR,
);
foreach($super_globals as $k => $v) {define($k, $v);}

includeFile(ROOT_DIR . CONFIG_DIR . "config.php");

$class_paths = array(
	CONTROLLER_DIR . "IndexController.php",
	CONTROLLER_DIR . "ViewController.php",
	CONTROLLER_DIR . "AdminController.php",
	CONTROLLER_DIR . "HomeController.php",
	CONTROLLER_DIR . "UserController.php",
	CONTROLLER_DIR . "CommentController.php",
	CONTROLLER_DIR . "SampleController.php",
	UTIL_DIR . "Session.php",
	UTIL_DIR . "Permissions.php",
	MODEL_DIR . "MysqlTbl.php",
	MODEL_DIR . "SessionTbl.php",
	MODEL_DIR . "UserTbl.php",
	MODEL_DIR . "CommentTbl.php",
	MODEL_DIR . "HttpRequestTbl.php",
	HELPER_DIR . "FormHelper.php"
);
foreach($class_paths as $path) {includeFile(ROOT_DIR . $path);}

IndexController::load();

function includeFile($filename) {
	if(file_exists($filename)) {
		include($filename);
	}
}
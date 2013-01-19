<?php

define('DS', DIRECTORY_SEPARATOR);

$super_globals = array(
	'WEBROOT' => (dirname($_SERVER["SCRIPT_NAME"]) != "/") ? "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER["SCRIPT_NAME"])."/" : "http://".$_SERVER['HTTP_HOST']."/",
	'CSS_DIR' => 'inc/css/',
	'IMG_DIR' => 'inc/img/',
	'JS_DIR' => 'inc/js/',
	'LIB_DIR' => 'inc/lib/',
	'ROOT_DIR' => dirname(__FILE__) . DS,
	'CONFIG_DIR' => 'app' . DS . 'config' . DS,
	'MODEL_DIR' => 'app' . DS . 'models' . DS,
	'VIEW_DIR' => 'app' . DS . 'views' . DS,
	'CONTROLLER_DIR' => 'app' . DS . 'controllers' . DS,
	'PLUGIN_DIR' => 'app' . DS . 'plugins' . DS,
	'HELPER_DIR' => 'app' . DS . 'plugins' . DS . 'helpers' . DS,
	'LB' => '
');
foreach($super_globals as $k => $v) {define($k, $v);}

includeFile(ROOT_DIR . CONFIG_DIR . "config.php");

$class_paths = array(
	CONTROLLER_DIR . "ViewController.php",
	CONTROLLER_DIR . "AuthController.php",
	CONTROLLER_DIR . "AdminController.php",
	CONTROLLER_DIR . "HomeController.php",
	CONTROLLER_DIR . "UserController.php",
	CONTROLLER_DIR . "CommentController.php",
	CONTROLLER_DIR . "SampleController.php",
	PLUGIN_DIR . "Loader.php",
	PLUGIN_DIR . "Session.php",
	PLUGIN_DIR . "Permissions.php",
	PLUGIN_DIR . "Sheets.php",
	PLUGIN_DIR . "Forms.php",
	PLUGIN_DIR . "ErrorLog.php",
	MODEL_DIR . "MysqlTbl.php",
	MODEL_DIR . "SessionTbl.php",
	MODEL_DIR . "UserTbl.php",
	MODEL_DIR . "CommentTbl.php",
	MODEL_DIR . "HttpRequestTbl.php",
);
foreach($class_paths as $path) {includeFile(ROOT_DIR . $path);}

Loader::load();

function includeFile($filename) {
	if(file_exists($filename)) {
		include($filename);
	}
}
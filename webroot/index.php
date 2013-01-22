<?php

define('DS', DIRECTORY_SEPARATOR);
define('WEBROOT', (dirname($_SERVER["SCRIPT_NAME"]) != "/") ? "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER["SCRIPT_NAME"])."/" : "http://".$_SERVER['HTTP_HOST']."/");
define('CSS_DIR',  'inc/css/');
define('IMG_DIR',  'inc/img/');
define('JS_DIR',  'inc/js/');
define('LIB_DIR',  'inc/lib/');
define('ROOT_DIR',  dirname(__FILE__) . DS);
define('CONFIG_DIR',  ROOT_DIR . 'app' . DS . 'config' . DS);
define('MODEL_DIR',   ROOT_DIR . 'app' . DS . 'models' . DS);
define('VIEW_DIR',   ROOT_DIR . 'app' . DS . 'views' . DS);
define('CONTROLLER_DIR',   ROOT_DIR . 'app' . DS . 'controllers' . DS);
define('PLUGIN_DIR',   ROOT_DIR . 'app' . DS . 'plugins' . DS);
define('HELPER_DIR',   ROOT_DIR . PLUGIN_DIR . 'helpers' . DS);
define('LB',  '
');

$includePaths = array(
    CONFIG_DIR . "config.php",
    CONTROLLER_DIR . "ViewController.php",
    CONTROLLER_DIR . "AdminViewController.php",
	CONTROLLER_DIR . "AuthController.php",
	CONTROLLER_DIR . "AdminController.php",
	CONTROLLER_DIR . "HomeController.php",
	CONTROLLER_DIR . "UserController.php",
	CONTROLLER_DIR . "CommentController.php",
	PLUGIN_DIR . "Loader.php",
	PLUGIN_DIR . "Session.php",
	PLUGIN_DIR . "Permissions.php",
	PLUGIN_DIR . "Sheets.php",
	PLUGIN_DIR . "Forms.php",
	PLUGIN_DIR . "Error.php",
	MODEL_DIR . "MysqlTbl.php",
	MODEL_DIR . "SessionTbl.php",
	MODEL_DIR . "UserTbl.php",
	MODEL_DIR . "CommentTbl.php",
	MODEL_DIR . "HttpRequestTbl.php",
);

foreach($includePaths as $filename) {
    if(file_exists($filename)) {
        include($filename);
    }
}

Loader::load();

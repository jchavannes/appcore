<?php

define('DS', DIRECTORY_SEPARATOR);
define('LB',  PHP_EOL);
define('WEBROOT', (dirname($_SERVER["SCRIPT_NAME"]) != "/") ? "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER["SCRIPT_NAME"])."/" : "http://".$_SERVER['HTTP_HOST']."/");
define('CSS_DIR',  'inc/css/');
define('IMG_DIR',  'inc/img/');
define('JS_DIR',  'inc/js/');
define('LIB_DIR',  'inc/lib/');
define('ROOT_DIR',  dirname(__FILE__) . DS);
define('APP_DIR',  ROOT_DIR . 'app' . DS);
define('CONFIG_DIR',  APP_DIR . 'config' . DS);
define('MODEL_DIR',   APP_DIR . 'models' . DS);
define('VIEW_DIR',   APP_DIR . 'views' . DS);
define('CONTROLLER_DIR',   APP_DIR . 'controllers' . DS);
define('PLUGIN_DIR',   APP_DIR . 'plugins' . DS);

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

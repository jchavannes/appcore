<?php

define('WEBROOT', (dirname($_SERVER["SCRIPT_NAME"]) != "/") ? "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER["SCRIPT_NAME"])."/" : "http://".$_SERVER['HTTP_HOST']."/");
define('ROOT_DIR', dirname(__FILE__) . '/');
define('LB', PHP_EOL);

define('APP_DIR', ROOT_DIR . 'app/');
define('CONFIG_DIR', APP_DIR . 'config/');
define('CONTROLLER_DIR', APP_DIR . 'controllers/');
define('MODEL_DIR', APP_DIR . 'models/');
define('PLUGIN_DIR', APP_DIR . 'plugins/');
define('VIEW_DIR', APP_DIR . 'views/');

define('CSS_DIR', 'inc/css/');
define('IMG_DIR', 'inc/img/');
define('JS_DIR', 'inc/js/');
define('LIB_DIR', 'inc/lib/');

require(CONFIG_DIR . "config.php");

function __autoload($className) {
    $classPaths = Config::getObject()->getClassPaths();
    if (array_key_exists($className, $classPaths) && file_exists($classPaths[$className])) {
        include($classPaths[$className]);
    }
}

Loader::load();

<?php

$filename = CONFIG_DIR . "config.local.php";
if (file_exists($filename)) {
    include($filename);
}

if (!defined("FORCE_SSL_ONLY")) {
    define("FORCE_SSL_ONLY", true);
}

if (!defined("DEVELOPMENT_MODE")) {
    define("DEVELOPMENT_MODE", false);
}

if (!defined("MYSQL_HOST")) {
    define("MYSQL_HOST", "localhost");
}
if (!defined("MYSQL_USERNAME")) {
    define("MYSQL_USERNAME", "root");
}
if (!defined("MYSQL_PASSWORD")) {
    define("MYSQL_PASSWORD", "password");
}
if (!defined("MYSQL_DATABASE")) {
    define("MYSQL_DATABASE", "appcore");
}


class Config {

    public function getClassPaths($paths=array()) {
        $paths = array_merge($paths, array(
            "AdminViewController"       => CONTROLLER_DIR . "AdminViewController.php",
            "AdminController"           => CONTROLLER_DIR . "AdminController.php",
            "AuthController"            => CONTROLLER_DIR . "AuthController.php",
            "CommentController"         => CONTROLLER_DIR . "CommentController.php",
            "HomeController"            => CONTROLLER_DIR . "HomeController.php",
            "InstallController"            => CONTROLLER_DIR . "InstallController.php",
            "UserController"            => CONTROLLER_DIR . "UserController.php",
            "ViewController"            => CONTROLLER_DIR . "ViewController.php",

            "Error"                     => PLUGIN_DIR . "Error.php",
            "Forms"                     => PLUGIN_DIR . "Forms.php",
            "Loader"                    => PLUGIN_DIR . "Loader.php",
            "Permissions"               => PLUGIN_DIR . "Permissions.php",
            "Request"                   => PLUGIN_DIR . "Request.php",
            "Session"                   => PLUGIN_DIR . "Session.php",

            "CommentTbl"                => MODEL_DIR . "CommentTbl.php",
            "HttpRequestTbl"            => MODEL_DIR . "HttpRequestTbl.php",
            "MysqlTbl"                  => MODEL_DIR . "MysqlTbl.php",
            "SessionTbl"                => MODEL_DIR . "SessionTbl.php",
            "UserTbl"                   => MODEL_DIR . "UserTbl.php",
        ));
        return $paths;
    }

    /**
     * Can be used to create shortened urls without requiring a controller. For
     * instance, this would reroute '/test' to 'HomeController::testAction()'.
     *
     * return array(
     *     "test" => array(
     *         self::ROUTE_CONTROLLER => "HomeController",
     *         self::ROUTE_ACTION => "testAction"
     *     ),
     *     ...
     * );
     *
     * @return array
     */
    public function getRoutes($routes=array()) {
        return $routes;
    }

    /**
     * Checks for local config class, otherwise uses default
     *
     * @return LocalConfig|Config
     */
    static public function getObject() {
        $className = class_exists("LocalConfig") ? "LocalConfig" : "Config";
        return new $className();
    }

}

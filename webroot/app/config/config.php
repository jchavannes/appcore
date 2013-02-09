<?php

$config = array(
    "MYSQL_HOST" => "localhost",
    "MYSQL_USERNAME" => "root",
    "MYSQL_PASSWORD" => "password",
    "MYSQL_DATABASE" => "appcore"
);

$filename = CONFIG_DIR . "local.config.php";
if(file_exists($filename)) {
    $default_config = $config;
    include($filename);
    $config = array_merge($default_config, $config);
}

foreach($config as $k => $v) {define($k, $v);}


class Config {

    public function getClassPaths($paths=array()) {
        $paths = array_merge($paths, array(
            "AdminViewController"       => CONTROLLER_DIR . "AdminViewController.php",
            "AdminController"           => CONTROLLER_DIR . "AdminController.php",
            "AppController"             => CONTROLLER_DIR . "AppController.php",
            "AuthController"            => CONTROLLER_DIR . "AuthController.php",
            "CommentController"         => CONTROLLER_DIR . "CommentController.php",
            "HomeController"            => CONTROLLER_DIR . "HomeController.php",
            "UserController"            => CONTROLLER_DIR . "UserController.php",
            "ViewController"            => CONTROLLER_DIR . "ViewController.php",
            "Loader"                    => PLUGIN_DIR . "Loader.php",
            "Session"                   => PLUGIN_DIR . "Session.php",
            "Permissions"               => PLUGIN_DIR . "Permissions.php",
            "Forms"                     => PLUGIN_DIR . "Forms.php",
            "Error"                     => PLUGIN_DIR . "Error.php",
            "MysqlTbl"                  => MODEL_DIR . "MysqlTbl.php",
            "SessionTbl"                => MODEL_DIR . "SessionTbl.php",
            "UserTbl"                   => MODEL_DIR . "UserTbl.php",
            "CommentTbl"                => MODEL_DIR . "CommentTbl.php",
            "HttpRequestTbl"            => MODEL_DIR . "HttpRequestTbl.php",
        ));
        return $paths;
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

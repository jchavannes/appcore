<?php

class ViewController {

    public $view;

    public function __construct() {
        $this->view = new stdClass();
    }

    public function defaultAction() {
        self::errorAction();
    }

    public function loadLayout($page) {
        $this->loadFile("snippets/header.phtml");
        $this->loadFile($page);
        $this->loadFile("snippets/footer.phtml");
    }

    public function loadFile($file) {
        $file = VIEW_DIR . $file;
        if (file_exists($file)) {
            require($file);
        }
        else {
            throw new Exception("Unable to find file: $file");
        }
    }

    public function errorAction() {
        header('HTTP/1.0 404 Not Found');
        $this->loadLayout("404.phtml");
    }

    protected function redirect($url = "") {
        $url = WEBROOT . $url;
        header("Location: " . $url);
        exit(0);
    }

    static public function ajaxSuccess($opts = array()) {
        if (!isset($opts['status']) || $opts['status'] == "") {$opts['status'] = "success";}
        self::ajaxMessage($opts);
    }
    static public function ajaxError($opts = array()) {
        if (!is_array($opts)) {$opts = array("message" => $opts);}
        if (!isset($opts['message']) || $opts['message'] == "") {$opts['message'] = "There was an error.";}
        if (!isset($opts['title']) || $opts['title'] == "") {$opts['title'] = "Error";}
        if (!isset($opts['status']) || $opts['status'] == "") {$opts['status'] = "error";}
        self::ajaxMessage($opts);
    }
    static public function ajaxAuthError() {
        self::ajaxError(array("title" => "Authentication Error", "message" => "Please make sure you are logged in or try refreshing your browser window."));
    }
    static public function ajaxMessage($opts = array()) {
        echo '{';
        $first = true;
        foreach($opts as $opt => $val) {
            echo ($first ? '': ', ') . '"' . $opt . '": "' . $val . '"';
            $first = false;
        }
        echo '}';
    }

    public function loadCss($path) {
        $url = WEBROOT . CSS_DIR . $path;
        echo "<link rel='stylesheet' href='$url'>".LB;
    }
    public function loadJs($path) {
        $url = WEBROOT . JS_DIR . $path;
        echo "<script type='text/javascript' language='javascript' src='$url'></script>".LB;
    }
    public function loadLib($path) {
        $url = WEBROOT . LIB_DIR . $path;
        echo "<script type='text/javascript' language='javascript' src='$url'></script>".LB;
    }
    public function loadImg($path, $params) {
        $attr_text = "";
        if (isset($params)) {
            foreach($params as $attr => $val) {
                $attr_text .= " $attr=\"".$val."\"";
            }
        }
        $url = WEBROOT . IMG_DIR . $path;
        echo "<img".$attr_text." src='$url' />".LB;
    }
    
}

<?php

class Request {

    const ROUTE_CONTROLLER = "controller";
    const ROUTE_ACTION = "action";

    public $request;
    public $parts;

    public function __construct() {
        $webpath = substr($_SERVER['SCRIPT_NAME'], 0, 0 - strlen("index.php"));
        $this->request = substr($_SERVER['REQUEST_URI'], strlen($webpath));
        $this->parts = array_merge(array(null), explode("/", $this->request));
    }

    /**
     * Given a level greater than zero, returns that particular URL part.
     * If that parameter does not exist, returns false.
     * If a level is not specified, return array of URL parts.
     *
     * @param int $level
     * @return array|bool|string
     */
    public function getRequestPart($level=0) {
        if (empty($this->request)) {
            if ($level > 1) {
                return false;
            }
            elseif ($level == 1) {
                return "home";
            }
            else {
                return array("home");
            }
        }
        else {
            if (!$level) {
                return $this->parts;
            }
            elseif (isset($this->parts[$level]) && !empty($this->parts[$level])) {
                return $this->parts[$level];
            }
            else {
                return false;
            }
        }
    }

    /**
     * Returns the complete unparsed URL, with parameters.
     *
     * @return string
     */
    public function getRequest() {
        return $this->request;
    }

    static public function getObject() {
        return new self();
    }

}

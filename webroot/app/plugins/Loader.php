<?php

class Loader {

    public $controller;
    public $action;
    public $routes;

    public function __construct() {
        $this->checkConfig();
        $this->processRequest();
    }

    public function load() {

        Session::load();

        // CSRF Check
        if (!$this->checkCSRF()) {
            ViewController::ajaxAuthError();
            return;
        }

        try {
            ob_start();
            $controller = new $this->controller;
            $controller->{$this->action}();
            ob_end_flush();
        }
        catch(Exception $e) {
            if (ob_get_status()) {
                ob_end_clean();
            }
            Error::show($e);
        }

    }

    private function checkCSRF() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || !empty($_POST)) {
            if (!Forms::checkVerifier()) {
                return false;
            }
        }
        return true;
    }

    public function processRequest() {

        // Used for creating short urls
        $this->routes = Config::getObject()->getRoutes();

        // Error page is default until we find a valid action
        $this->controller = "HomeController";
        $this->action = "errorAction";

        // Get request query info
        $request = new Request();

        // If no request, this is the homepage
        if (empty($request->request)) {
            $this->action = "defaultAction";
        }

        // Check for re-route and set
        if ($request->getRequestPart(1) !== false && isset($this->routes[$request->getRequestPart(1)])) {
            $route = $this->routes[$request->getRequestPart(1)];
            $this->controller = $route[Request::ROUTE_CONTROLLER];
            $this->action = $route[Request::ROUTE_ACTION];
        }
        else {

            // Find correct controller
            $controller = $request->getRequestPart(1);
            if (!empty($controller)) {
                $controller = ucfirst($controller)."Controller";
                if (class_exists($controller)) {
                    $this->controller = $controller;

                    // We found a controller, set default action
                    $this->action = "defaultAction";
                }
            }

            // Find correct action
            $action = $request->getRequestPart(2);
            if (!empty($action)) {

                // Request specifies an action, set to error until we find one
                $this->action = "errorAction";
                $action = preg_replace('/[^a-z]/i', '', $action) . "Action";
                if (method_exists($this->controller, $action)) {

                    // Found an action
                    $this->action = $action;
                }
            }
        }

    }

    private function checkConfig() {
        $filename = CONFIG_DIR . "local.config.php";
        if (!file_exists($filename)) {
            Error::noLocalConfig();
        }
    }

    static public function getObject() {
        return new self();
    }

}

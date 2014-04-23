<?php

class Loader {

    static public function load() {
        try {
            self::renderResponse();
        }
        catch (Redirect $e) {
            $redirectType = $e->permanent ? "301 Moved Permanently" : "302 Found";
            header("HTTP/1.1 $redirectType");
            header("Location: $e->url");
        }
        catch (Exception $e) {
            if (ob_get_status()) {
                ob_end_clean();
            }
            if (DEVELOPMENT_MODE) {
                Error::show($e);
            }
            else {
                $url = WEBROOT . "error";
                header("Location: $url");
            }
        }
    }

    static public function renderResponse() {

        if (!DEVELOPMENT_MODE) {
            self::forceWWW();
        }

        if (FORCE_SSL_ONLY) {
            self::forceSSLOnly();
        }

        self::checkForRedirects();

        Session::load();

        HttpRequestTbl::logHttpRequest();

        // CSRF Check
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || !empty($_POST)) {
            if (!Forms::checkVerifier()) {
                throw new Redirect(WEBROOT . "error");
            }
        }

        // Used for creating short urls
        $reroutes = array(
            "sample_route" => array("home", "default"),
        );

        // Error page is default until we find a valid action
        $route = array(
            "controller" => "HomeController",
            "action" => "errorAction"
        );

        // Get request query info
        $request = array("url" => "", "parts" => array());
        $request['url'] = self::getRequest(false);
        if(!empty($request['url'])) {
            $request['parts'] = self::getPageArg();
        } else {
            // If no request, this is the homepage
            $route['action'] = "defaultAction";
        }

        // Check for potential re-route
        if(isset($request['parts'][1]) && isset($reroutes[$request['parts'][1]])) {
            $request['parts'][2] = $reroutes[$request['parts'][1]][1];
            $request['parts'][1] = $reroutes[$request['parts'][1]][0];
        }

        // Find correct controller
        if (!empty($request['parts'][1])) {
            $controller = ucfirst($request['parts'][1])."Controller";
            if (class_exists($controller)) {
                $route['controller'] = $controller;
                // We found a controller, set default action
                $route['action'] = "defaultAction";
            }
        }

        if (!empty($request['parts'][2])) {
            // Request specifies an action, set to error until we find one
            $route['action'] = "errorAction";
            $action = self::dashToCamelCase(preg_replace('/[^a-z]/i', '', $request['parts'][2]) . "Action");
            if (method_exists($route['controller'], $action)) {
                $route['action'] = $action;
            }
        }

        ob_start();
        $controller = new $route['controller'];
        $controller->$route['action']();
        ob_end_flush();

    }

    static public function checkForRedirects() {
        $request = self::getRequest();
        $redirects = array(
            "redirect" => "sample_route",
        );
        if (isset($redirects[$request])) {
            $url = WEBROOT . $redirects[$request];
            throw new Redirect($url);
        }
    }

    static public function forceWWW() {
        if (stripos(WEBROOT, "://www.") === false) {
            $url = str_ireplace("://", "://www.", WEBROOT) . self::getRequest();
            throw new Redirect($url);
        }
    }

    static public function forceSSLOnly() {
        if (PROTOCOL != "https:") {
            $url = str_replace("http:", "https:", WEBROOT) . self::getRequest();
            throw new Redirect($url);
        }
    }

    static public function dashToCamelCase($string) {
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
        $str[0] = strtolower($str[0]);
        return $str;
    }

    static public function getPageArg($level = false) {
        $request = self::getRequest();
        if (empty($request)) {
            if($level !== false) {
                if($level == 1) {return "home";}
                else {return false;}
            }
            return array("home");
        }
        list($request) = explode("?", $request);
        $args = array_merge(array(null), explode("/", $request));
        if ($level === false) {return $args;}
        if (!isset($args[$level]) || empty($args[$level])) {return false;}
        return $args[$level];
    }

    static public function getRequest($includeParams=true) {
        $webpath = substr($_SERVER['SCRIPT_NAME'], 0, 0 - strlen("index.php"));
        $full_request = substr($_SERVER['REQUEST_URI'], strlen($webpath));
        if ($includeParams) {
            return $full_request;
        }
        else {
            $partial = explode("?", $full_request);
            return $partial[0];
        }
    }

}

class Redirect extends Exception {
    public $url;
    public $permanent;
    public function __construct($url, $permanent=false) {
        $this->url = $url;
        $this->permanent = $permanent;
    }
}

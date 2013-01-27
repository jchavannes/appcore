<?php

class Loader {

	static public function load() {

		Session::load();

        // CSRF Check
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || !empty($_POST)) {
            if (!Forms::checkVerifier()) {
                ViewController::ajaxAuthError();
                return;
            }
        }

		// Used for creating short urls
		$reroutes = array(
			"test" => array("admin", "comments"),
		);

		// Error page is default until we find a valid action
		$route = array(
			"controller" => "HomeController",
			"action" => "errorAction"
		);

		// Get request query info
		$request = array("url" => "", "parts" => array());
		$request['url'] = self::getRequest();
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
			$action = preg_replace('/[^a-z]/i', '', $request['parts'][2]) . "Action";
			if (method_exists($route['controller'], $action)) {
				$route['action'] = $action;
			}
		}

		try {
			ob_start();
            $controller = new $route['controller'];
            $controller->$route['action']();
			ob_end_flush();
		}
		catch(Exception $e) {
			if (ob_get_status()) {
				ob_end_clean();
			}
            Error::show($e);
		}

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
		$args = array_merge(array(null), explode("/", $request));
		if ($level === false) {return $args;}
		if (!isset($args[$level]) || empty($args[$level])) {return false;}
		return $args[$level];
	}

	static public function getRequest() {
		$webpath = substr($_SERVER['SCRIPT_NAME'], 0, 0 - strlen("index.php"));
		return substr($_SERVER['REQUEST_URI'], strlen($webpath));
	}

}
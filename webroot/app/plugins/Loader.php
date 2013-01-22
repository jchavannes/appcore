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
		if(isset($_GET['rq']) && !empty($_GET['rq'])) {
			$request['url'] = $_GET['rq'];
			$request['parts'] = explode("/", $request['url']);
		} else {
			// If no request, this is the homepage
			$route['action'] = "defaultAction";
		}

		// Check for potential re-route
		if(isset($request['parts'][0]) && isset($reroutes[$request['parts'][0]])) {
			$request['parts'][1] = $reroutes[$request['parts'][0]][1];
			$request['parts'][0] = $reroutes[$request['parts'][0]][0];
		}

		// Find correct controller
		if (!empty($request['parts'][0])) {
			$controller = ucfirst($request['parts'][0])."Controller";
			if (class_exists($controller)) {
				$route['controller'] = $controller;
				// We found a controller, set default action
				$route['action'] = "defaultAction";
			}
		}

		if (!empty($request['parts'][1])) {
			// Request specifies an action, set to error until we find one
			$route['action'] = "errorAction";
			$action = preg_replace('/[^a-z]/i', '', $request['parts'][1]) . "Action";
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
		if(!isset($_GET['rq']) || empty($_GET['rq'])) {
			if($level !== false) {
				if($level == 0) {return "home";}
				else {return false;}
			}
			return array("home");
		}
		$args = explode("/", $_GET['rq']);
		if($level === false) {return $args;}
		if(!isset($args[$level])) {return false;}
		return $args[$level];
	}

}
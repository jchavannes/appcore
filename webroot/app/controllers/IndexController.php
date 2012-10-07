<?php

class IndexController {

	public function load() {

		Session::load();

		$request = array();

		global $route;
		$routes = array(
			"home" => array("controller" => "HomeController"),
			"auth" => array("controller" => "AuthController"),
			"user" => array("controller" => "UserController"),
			"login" => array("controller" => "HomeController", "action" => "loginAction"),
			"logout" => array("controller" => "HomeController", "action" => "logoutAction"),
			"signup" => array("controller" => "HomeController", "action" => "signupAction"),
			"admin" => array("controller" => "AdminController"),
			"comment" => array("controller" => "CommentController"),
			"sample" => array("controller" => "HomeController", "action" => "sampleAction"),
			"error" => array("controller" => "HomeController", "action" => "badUrl")
		);

		if(isset($_GET['q']) && !empty($_GET['q'])) {
			$request['url'] = $_GET['q'];
			$request['parts'] = explode("/", $request['url']);
			if(isset($routes[$request['parts'][0]])) {
				$route = $routes[$request['parts'][0]];
			} else {
				$route = $routes['error'];
			}
		} else {
			$route = $routes['home'];
		}

		if(!isset($route['action'])) {
			if(isset($request['parts'][1])) {
				$route['action'] = $request['parts'][1] . "Action";
			} else {
				$route['action'] = "defaultAction";
			}
		}
		if(!IndexController::runAction($route)) {
			$route = $routes['error'];
			IndexController::runAction($route);
		}

	}

	public function runAction($route) {

		if(class_exists($route['controller']) && method_exists($route['controller'], $route['action'])) {
			call_user_method($route['action'], $route['controller']);
			return true;
		}
		return false;

	}

	public function getPageArg($level = false) {
		if(!isset($_GET['q']) || empty($_GET['q'])) {
			if($level !== false) {
				if($level == 0) {return "home";}
				else {return false;}
			}
			return array("home");
		}
		$args = explode("/", $_GET['q']);
		if($level === false) {return $args;}
		if(!isset($args[$level])) {return false;}
		return $args[$level];
	}

}
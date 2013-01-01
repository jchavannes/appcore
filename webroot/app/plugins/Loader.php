<?php

class IndexController {

	public function load() {

		Session::load();

		$request = array();

		global $route;
		$reroutes = array(
			"test" => array("admin", "comments"),
		);

		if(isset($_GET['q']) && !empty($_GET['q'])) {
			$request['url'] = $_GET['q'];
			$request['parts'] = explode("/", $request['url']);
		} else {
			$request['url'] = "home";
			$request['parts'] = array("home");
		}

		if(isset($reroutes[$request['parts'][0]])) {
			$request['parts'][1] = $reroutes[$request['parts'][0]][1];
			$request['parts'][0] = $reroutes[$request['parts'][0]][0];
		}

		$route = array(
			"controller" => "HomeController",
			"action" => "defaultAction"
		);

		if (isset($request['parts'][0])) {
			$controller = ucfirst($request['parts'][0])."Controller";
			if (class_exists($controller)) {
				$route['controller'] = $controller;
			}
		}

		if (isset($request['parts'][1])) {
			$action = $request['parts'][1]."Action";
			if (method_exists($route['controller'], $action)) {
				$route['action'] = $action;
			}
		}

		call_user_func(array($route['controller'], $route['action']));

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
<?php

class ViewController {

	public function defaultAction() {
		self::errorAction();
	}

	public function errorAction() {
		header('HTTP/1.0 404 Not Found');
		include(ROOT_DIR . VIEW_DIR . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "404.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DS . "footer.phtml");
	}

	protected function redirect($url = "") {
		$url = WEBROOT . $url;
		header("Location: " . $url);
		die();
	}

	public function ajaxSuccess($opts = array()) {
		if(!isset($opts['status']) || $opts['status'] == "") {$opts['status'] = "success";}
		self::ajaxMessage($opts);
	}
	public function ajaxError($opts = array()) {
		if(!is_array($opts)) {$opts = array("message" => $opts);}
		if(!isset($opts['message']) || $opts['message'] == "") {$opts['message'] = "There was an error.";}
		if(!isset($opts['title']) || $opts['title'] == "") {$opts['title'] = "Error";}
		if(!isset($opts['status']) || $opts['status'] == "") {$opts['status'] = "error";}
		self::ajaxMessage($opts);
	}
	public function ajaxAuthError() {
		self::ajaxError(array("title" => "Authentication Error", "message" => "Please make sure you are logged in or try refreshing your browser window."));
	}
	public function ajaxMessage($opts = array()) {
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
		if(isset($params)) {
			foreach($params as $attr => $val) {
				$attr_text .= " $attr=\"".$val."\"";
			}
		}
		$url = WEBROOT . IMG_DIR . $path;
		echo "<img".$attr_text." src='$url' />".LB;
	}
	
}

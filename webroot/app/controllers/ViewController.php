<?php

class ViewController {

	public function defaultAction() {
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "home.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}

	public function badUrl() {
		echo "Bad url!";
	}

	protected function redirect($url = "") {
		$url = WEBROOT . $url;
		header("Location: " . $url);
		die();
	}

	public function loadJS($path) {
		$url = WEBROOT . JS_DIR . $path;
		echo "<script type='text/javascript' language='javascript' src='$url'></script>";
	}
	
}

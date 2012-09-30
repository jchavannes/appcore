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

	public function loadCss($path) {
		$url = WEBROOT . CSS_DIR . $path;
		echo "<link rel='stylesheet' href='$url'>".LB;
	}
	public function loadImg($path) {
		$url = WEBROOT . LIB_DIR . $path;
		echo "<script type='text/javascript' language='javascript' src='$url'></script>".LB;
	}
	public function loadJs($path) {
		$url = WEBROOT . JS_DIR . $path;
		echo "<script type='text/javascript' language='javascript' src='$url'></script>".LB;
	}
	public function loadLib($path) {
		$url = WEBROOT . LIB_DIR . $path;
		echo "<script type='text/javascript' language='javascript' src='$url'></script>".LB;
	}
	
}

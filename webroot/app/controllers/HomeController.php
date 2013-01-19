<?php

class HomeController extends ViewController {

	const SAMPLE_COMMENT_ID = 'sample_page';
	const ABOUT_COMMENT_ID = 'about_page';

	public function defaultAction() {
		include(ROOT_DIR . VIEW_DIR . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "home.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DS . "footer.phtml");
	}

}

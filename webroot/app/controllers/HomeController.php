<?php

class HomeController extends ViewController {

	const SAMPLE_COMMENT_ID = 'sample_page';
	const ABOUT_COMMENT_ID = 'about_page';

	public function defaultAction() {

        $this->loadLayout("home.phtml");

	}

}

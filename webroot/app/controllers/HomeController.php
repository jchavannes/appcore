<?php

class HomeController extends ViewController {
	
	const ABOUT_COMMENT_ID = 'about_page';

	public function defaultAction() {

        $this->loadLayout("home.phtml");

	}

}

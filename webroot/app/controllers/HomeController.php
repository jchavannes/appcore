<?php

class HomeController extends ViewController {

	const SAMPLE_COMMENT_ID = 'sample_page';
	const ABOUT_COMMENT_ID = 'about_page';

	public function logoutAction() {

		Session::logout();
		parent::Redirect();

	}

	public function loginAction() {

		if(SESSION::isLoggedIn()) {parent::Redirect();}

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "login.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

	public function signupAction() {

		if(SESSION::isLoggedIn()) {parent::Redirect();}

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "signup.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

	public function aboutAction() {

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "about.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

	public function sampleAction() {
		if(!Session::isLoggedIn()) {
			AdminController::noAccessAction();
			return;
		}
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "samplePage.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}

}

<?php

class HomeController extends ViewController {

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

}

<?php

class HomeController extends ViewController {

	public function logout() {
		Session::logout();
		parent::Redirect();
	}

	public function login() {

		if(SESSION::isLoggedIn()) {parent::Redirect();}

		$login_form = array(
			'helper' => new FormHelper(FormHelper::LOGIN_FORM),
			'error' => false
		);

		if(	$login_form['helper']->checkVerifier()
			&& isset($_POST[AuthController::LOGIN_USERNAME])
			&& isset($_POST[AuthController::LOGIN_PASSWORD]))
		{
			$fields = array(
				UserTbl::USERNAME => $_POST[AuthController::LOGIN_USERNAME],
				UserTbl::PASSWORD => $_POST[AuthController::LOGIN_PASSWORD]
			);
			if(Session::login($fields)) {
				parent::Redirect();
			} else {
				$login_form['error'] = true;
			}

		}

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "login.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

	public function signup() {

		if(SESSION::isLoggedIn()) {parent::Redirect();}

		$signup_form['helper'] = new FormHelper(FormHelper::SIGNUP_FORM);

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "signup.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

}

<?php

class HomeController extends ViewController {

	const SIGNUP_USERNAME 			= 'username';
	const SIGNUP_PASSWORD 			= 'password';
	const SIGNUP_VERIFY_PASSWORD 	= 'verify_password';

	const LOGIN_USERNAME 			= 'username';
	const LOGIN_PASSWORD 			= 'password';

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
			&& isset($_POST[HomeController::LOGIN_USERNAME])
			&& isset($_POST[HomeController::LOGIN_PASSWORD]))
		{
			$fields = array(
				UserTbl::USERNAME => $_POST[HomeController::LOGIN_USERNAME],
				UserTbl::PASSWORD => $_POST[HomeController::LOGIN_PASSWORD]
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
		
		$signup_form = array(
			UserTbl::USERNAME => "test",
			UserTbl::PASSWORD => "test",
			'error' => false
		);

		$signup_form['helper'] = new FormHelper(FormHelper::SIGNUP_FORM);

		if(isset($_POST[FormHelper::SIGNUP_FORM])) {

			if(!$signup_form['helper']->checkVerifier()) {
				$signup_form['error'] = true;
				$signup_form['error_message'] = "Please try again.";
			} elseif(!isset($_POST[HomeController::SIGNUP_USERNAME]) || empty($_POST[HomeController::SIGNUP_USERNAME])) {
				$signup_form['error'] = true;
				$signup_form['error_message'] = "Please enter a username.";
			} elseif(!isset($_POST[HomeController::SIGNUP_PASSWORD]) || !isset($_POST[HomeController::SIGNUP_VERIFY_PASSWORD]) || empty($_POST[HomeController::SIGNUP_PASSWORD]) || empty($_POST[HomeController::SIGNUP_VERIFY_PASSWORD])) {
				$signup_form['error'] = true;
				$signup_form['error_message'] = "Please enter a password.";
			} elseif(!$_POST[HomeController::SIGNUP_PASSWORD] == $_POST[HomeController::SIGNUP_VERIFY_PASSWORD]) {
				$signup_form['error'] = true;
				$signup_form['error_message'] = "Password do not match.";			
			} else {

				$fields = array(
					UserTbl::USERNAME => $_POST[HomeController::SIGNUP_USERNAME],
					UserTbl::PASSWORD => $_POST[HomeController::SIGNUP_PASSWORD]
				);
				$user = new UserTbl();

				if($user_data = $user->createUser($fields)) {
					Session::setUser($user_data);
					parent::Redirect();
				} else {
					$signup_form['error'] = true;
				}
			}
		}

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "signup.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

}

<?php

class AuthController extends ViewController {

	const SIGNUP_USERNAME 			= 'username';
	const SIGNUP_PASSWORD 			= 'password';
	const SIGNUP_VERIFY_PASSWORD 	= 'verify_password';

	const LOGIN_USERNAME 			= 'username';
	const LOGIN_PASSWORD 			= 'password';
	
	const SIGNUP_FORM_ID = 'signup_form';

	public function signupAction() {

		if(SESSION::isLoggedIn()) {return parent::ajaxError("You are logged in.");}
		
		$signup_form = array(
			'helper' => new FormHelper(FormHelper::SIGNUP_FORM)
		);

		if(isset($_POST[FormHelper::SIGNUP_FORM])) {

			if(!$signup_form['helper']->checkVerifier()) {
				self::ajaxAuthError();
				return;
			} elseif(!isset($_POST[AuthController::SIGNUP_USERNAME]) || empty($_POST[AuthController::SIGNUP_USERNAME])) {
				self::ajaxError(array(
					"message" => "Please enter a username.",
					"field" => AuthController::SIGNUP_USERNAME
				));
				return;
			} elseif(!isset($_POST[AuthController::SIGNUP_PASSWORD]) || empty($_POST[AuthController::SIGNUP_PASSWORD])) {
				self::ajaxError(array(
					"message" => "Please enter a password.",
					"field" => AuthController::SIGNUP_PASSWORD
				));
				return;
			} elseif(!isset($_POST[AuthController::SIGNUP_VERIFY_PASSWORD]) || empty($_POST[AuthController::SIGNUP_VERIFY_PASSWORD]) || $_POST[AuthController::SIGNUP_PASSWORD] != $_POST[AuthController::SIGNUP_VERIFY_PASSWORD]) {
				self::ajaxError(array(
					"message" => "Passwords do not match.",
					"field" => AuthController::SIGNUP_VERIFY_PASSWORD
				));
				return;	
			} else {

				$fields = array(
					UserTbl::USERNAME => $_POST[AuthController::SIGNUP_USERNAME],
					UserTbl::PASSWORD => $_POST[AuthController::SIGNUP_PASSWORD]
				);
				$user = new UserTbl();

				if($user_data = $user->createUser($fields)) {
					Session::setUser($user_data);
					self::ajaxSuccess();
					return;
				} else {
					self::ajaxError("Unable to create user.");
					return;
				}
			}
		}
		self::ajaxError("No data.");
	}

	public function defaultAction() {
		echo "sup";
	}

}
<?php

class AuthController extends ViewController {

	const SIGNUP_USERNAME 			= 'username';
	const SIGNUP_PASSWORD 			= 'password';
	const SIGNUP_VERIFY_PASSWORD 	= 'verify_password';

	const LOGIN_USERNAME 			= 'username';
	const LOGIN_PASSWORD 			= 'password';
	
	const SIGNUP_FORM_ID = 'signup_form';
	const LOGIN_FORM_ID = 'login_form';

	public function loginAction() {

		if(SESSION::isLoggedIn()) {return parent::ajaxError("You are logged in.");}

		$FormHelper = new FormHelper(FormHelper::LOGIN_FORM);

		if(isset($_POST[FormHelper::LOGIN_FORM])) {

			if(!$FormHelper->checkVerifier()) {
				self::ajaxAuthError();
				return;
			} elseif(!isset($_POST[AuthController::LOGIN_USERNAME]) || empty($_POST[AuthController::LOGIN_USERNAME])) {
				self::ajaxError(array(
					"message" => "Please enter a username.",
					"field" => AuthController::LOGIN_USERNAME
				));
				return;
			} elseif(!isset($_POST[AuthController::LOGIN_PASSWORD]) || empty($_POST[AuthController::LOGIN_PASSWORD])) {
				self::ajaxError(array(
					"message" => "Please enter a password.",
					"field" => AuthController::LOGIN_PASSWORD
				));
				return;			
			} else {
				$fields = array(
					UserTbl::USERNAME => $_POST[AuthController::LOGIN_USERNAME],
					UserTbl::PASSWORD => $_POST[AuthController::LOGIN_PASSWORD]
				);
				if(Session::login($fields)) {
					self::ajaxSuccess();
					return;
				} else {
					self::ajaxError("No match found, please check your username and password.");
					return;
				}
			}

		}
		self::ajaxError();

	}

	public function signupAction() {

		if(SESSION::isLoggedIn()) {return parent::ajaxError("You are logged in.");}
		
		$FormHelper = new FormHelper(FormHelper::SIGNUP_FORM);

		if(isset($_POST[FormHelper::SIGNUP_FORM])) {

			if(!$FormHelper->checkVerifier()) {
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
		self::ajaxError();

	}

	public function defaultAction() {
		echo "sup";
	}

}
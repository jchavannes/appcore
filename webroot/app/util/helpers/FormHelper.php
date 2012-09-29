<?php

class FormHelper {

	const SIGNUP_FORM 	= 'signup_form_verifer';
	const LOGIN_FORM 	= 'login_form_verifer';
	const COMMENT_FORM 	= 'comment_form_verifer';

	private $verifier = array();

	public function __construct($id) {
		$this->verifier['name'] = $id;
		if(isset($_SESSION[$id])) {
			$this->verifier['old'] = $_SESSION[$this->verifier['name']];
		} else {
			$this->verifier['old'] = false;
		}
		$this->verifier['new'] = md5(uniqid());
		$_SESSION[$this->verifier['name']] = $this->verifier['new'];
	}

	public function getVerifierField() {
		echo "<input type='hidden' name='" . $this->verifier['name'] . "' value='" . $this->verifier['new'] . "' />";
	}

	public function checkVerifier() {
		return (isset($_POST[$this->verifier['name']]) && $_POST[$this->verifier['name']] == $this->verifier['old']);
	}

	public function newVerifier() {
		return $this->verifier['new'];
	}

	public function oldVerifier() {
		return $this->verifier['old'];
	}

}
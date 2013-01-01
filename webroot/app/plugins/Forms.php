<?php

class FormHelper {

	const SIGNUP_FORM 	= 'signup_form_verifier';
	const LOGIN_FORM 	= 'login_form_verifier';
	const COMMENT_FORM 	= 'comment_form_verifier';

	private $verifier = array();

	public function __construct($id) {
		if(!isset($_SESSION[Session::FORM_VERIFIERS])) {$_SESSION[Session::FORM_VERIFIERS] = array();}
		if(!isset($_SESSION[Session::FORM_VERIFIERS][$id])) {
			$_SESSION[Session::FORM_VERIFIERS][$id] = md5(uniqid());
		}
		$this->verifier['name'] = $id;
		$this->verifier['val'] = $_SESSION[Session::FORM_VERIFIERS][$id];
	}

	public function getVerifierField() {
		echo "<input type='hidden' name='" . $this->verifier['name'] . "' value='" . $this->verifier['val'] . "' />";
	}

	public function checkVerifier() {
		return (isset($_POST[$this->verifier['name']]) && $_POST[$this->verifier['name']] == $this->verifier['val']);
	}

}
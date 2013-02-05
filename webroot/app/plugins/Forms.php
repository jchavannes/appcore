<?php

class Forms {

    const FORM_CSRF_FIELD = 'csrf_token';

    public function __construct() {
        if(!isset($_SESSION[Session::CSRF_TOKEN])) {
            $_SESSION[Session::CSRF_TOKEN] = md5(session_id().time());
        }
    }

    static public function getVerifierField() {
        new self();
        echo "<input type='hidden' name='" . self::FORM_CSRF_FIELD . "' value='" . $_SESSION[Session::CSRF_TOKEN] . "' />";
    }

    static public function checkVerifier() {
        new self();
        return isset($_POST[self::FORM_CSRF_FIELD]) && $_POST[self::FORM_CSRF_FIELD] == $_SESSION[Session::CSRF_TOKEN];
    }

}
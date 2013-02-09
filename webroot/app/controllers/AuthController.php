<?php

class AuthController extends ViewController {

    const SIGNUP_FORM_ID = 'signup_form';
    const SIGNUP_USERNAME = 'username';
    const SIGNUP_PASSWORD = 'password';
    const SIGNUP_VERIFY_PASSWORD = 'verify_password';

    const LOGIN_FORM_ID = 'login_form';
    const LOGIN_USERNAME = 'username';
    const LOGIN_PASSWORD = 'password';
    
    public function loginAction() {

        if (SESSION::isLoggedIn()) {return parent::ajaxError("You are logged in.");}

        if (!isset($_POST[AuthController::LOGIN_USERNAME]) || empty($_POST[AuthController::LOGIN_USERNAME])) {
            self::ajaxError(array(
                "message" => "Please enter a username.",
                "field" => AuthController::LOGIN_USERNAME
            ));
            return;
        } elseif (!isset($_POST[AuthController::LOGIN_PASSWORD]) || empty($_POST[AuthController::LOGIN_PASSWORD])) {
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
            if (Session::login($fields)) {
                self::ajaxSuccess();
                return;
            } else {
                self::ajaxError("No match found, please check your username and password.");
                return;
            }
        }

    }

    public function signupAction() {

        if (SESSION::isLoggedIn()) {return parent::ajaxError("You are logged in.");}

        if (!isset($_POST[AuthController::SIGNUP_USERNAME]) || empty($_POST[AuthController::SIGNUP_USERNAME])) {
            self::ajaxError(array(
                "message" => "Please enter a username.",
                "field" => AuthController::SIGNUP_USERNAME
            ));
            return;
        } elseif (!isset($_POST[AuthController::SIGNUP_PASSWORD]) || empty($_POST[AuthController::SIGNUP_PASSWORD])) {
            self::ajaxError(array(
                "message" => "Please enter a password.",
                "field" => AuthController::SIGNUP_PASSWORD
            ));
            return;
        } elseif (!isset($_POST[AuthController::SIGNUP_VERIFY_PASSWORD]) || empty($_POST[AuthController::SIGNUP_VERIFY_PASSWORD]) || $_POST[AuthController::SIGNUP_PASSWORD] != $_POST[AuthController::SIGNUP_VERIFY_PASSWORD]) {
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

            if ($user_data = $user->createUser($fields)) {
                Session::setUser($user_data);
                self::ajaxSuccess();
                return;
            } else {
                self::ajaxError("Unable to create user.");
                return;
            }
        }

    }

    public function editAction() {

        if (!SESSION::isLoggedIn() || (!Session::checkPermission(Permissions::SUPER_ADMIN) && $_POST[UserController::USER_ID] != $_SESSION[Session::USER_ID])) {
            self::ajaxAuthError();
            return;
        }
        if (!isset($_POST[UserController::USER_ID]) || empty($_POST[UserController::USER_ID])) {
            self::ajaxError();
            return;
        }

        $data = array(UserTbl::ID => $_POST[UserController::USER_ID]);

        if (isset($_POST[UserController::USER_USERNAME]) && !empty($_POST[UserController::USER_USERNAME]) && Session::checkPermission(Permissions::SUPER_ADMIN)) {
            $data[UserTbl::USERNAME] = $_POST[UserController::USER_USERNAME];
        }

        if (isset($_POST[UserController::USER_EMAIL]) && !empty($_POST[UserController::USER_EMAIL])) {
            $data[UserTbl::EMAIL] = $_POST[UserController::USER_EMAIL];
        }

        if (isset($_POST[UserController::USER_OLDPASSWORD]) && !empty($_POST[UserController::USER_OLDPASSWORD])
            && isset($_POST[UserController::USER_NEWPASSWORD]) && !empty($_POST[UserController::USER_NEWPASSWORD])
            && isset($_POST[UserController::USER_VERIFY_NEWPASSWORD]) && !empty($_POST[UserController::USER_VERIFY_NEWPASSWORD])
            && $_POST[UserController::USER_NEWPASSWORD] == $_POST[UserController::USER_VERIFY_NEWPASSWORD]) {
            $data[UserTbl::PASSWORD] = $_POST[UserController::USER_NEWPASSWORD];
            $data[UserController::USER_OLDPASSWORD] = $_POST[UserController::USER_OLDPASSWORD];
        }
        
        $UserTbl = new UserTbl();

        if ($results = $UserTbl->checkUser($data)) {
            if (count($results) > 0) {
                if ($results[0][UserTbl::USERNAME] == $_POST[UserController::USER_USERNAME]) {
                    self::ajaxError("Username already in use.");
                    return;
                }
                if ($results[0][UserTbl::EMAIL] == $_POST[UserController::USER_EMAIL]) {
                    self::ajaxError("Email address already in use.");
                    return;
                }
                self::ajaxError();
                return;
            }
        }

        if ($UserTbl->editUser($data)) {
            if ($data[UserTbl::ID] == $_SESSION[Session::USER_ID]) {
                $_SESSION[Session::USERNAME] = $data[UserTbl::USERNAME];
            }
            self::ajaxSuccess();
            return;
        }
        self::ajaxError();
        return;
    }

    public function defaultAction() {
        echo "sup";
    }

}
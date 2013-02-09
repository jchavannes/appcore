<?php

class UserController extends AdminViewController {

    const USER_ID = 'user_id';
    const USER_USERNAME = 'username';
    const USER_EMAIL = 'email';
    const USER_OLDPASSWORD = 'old_password';
    const USER_NEWPASSWORD = 'new_password';
    const USER_VERIFY_NEWPASSWORD = 'verify_new_password';

    const FORM_ID = 'user_edit_form';
    
    public function viewAction() {

        $request = new Request();
        $username = $request->getRequestPart(3);

        $UserTbl = new UserTbl();

        // Someone else's profile
        if (!empty($username)) {
            if ($user_info = $UserTbl->getUserInfo($username)) {
                $user_info['me'] = false;
            }
        }
        // Your profile
        else {
            if ($user_info = $UserTbl->getUserInfo()) {
                $user_info['me'] = true;
            }
        }


        if ($user_info === false) {
            $this->loadLayout("user/notFound.phtml");
        } else {
            $this->view->userinfo = $user_info;
            $this->loadLayout("user/view.phtml");
        }
    }

    public function defaultAction() {
        UserController::viewAction();
    }

    public function editAction() {

        $request = new Request();
        $username = $request->getRequestPart(3);

        $UserTbl = new UserTbl();
        $user_info = false;

        if (!empty($username)) {
            if (Session::checkPermission(Permissions::SUPER_ADMIN) || $username == $_SESSION[SESSION::USERNAME]) {
                $user_info = $UserTbl->getUserInfo($username);
            }
        } else {
            $user_info = $UserTbl->getUserInfo($_SESSION[SESSION::USERNAME]);
        }

        if ($user_info === false) {
            $this->loadLayout("user/notFound.phtml");
        } else {
            $this->view->userinfo = $user_info;
            $this->loadLayout("user/edit.phtml");
        }

    }

}
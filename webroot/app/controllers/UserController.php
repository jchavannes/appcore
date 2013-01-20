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

		$page = Loader::getPageArg();

		$UserTbl = new UserTbl();

		if(isset($page[2]) && !empty($page[2])) {
			// Someone else's profile
			if($user_info = $UserTbl->getUserInfo($page[2])) {$user_info['me'] = false;}
		} else {
			// Your profile
			if($user_info = $UserTbl->getUserInfo()) {$user_info['me'] = true;}
		}


		if($user_info === false) {
            $this->loadLayout("user" . DS . "notFound.phtml");
		} else {
            $this->view->userinfo = $user_info;
            $this->loadLayout("user" . DS . "view.phtml");
		}
	}

	public function defaultAction() {
		UserController::viewAction();
	}

	public function editAction() {

		$page = Loader::getPageArg();

		$UserTbl = new UserTbl();
		$user_info = false;

		if(isset($page[2]) && !empty($page[2])) {
			if(Session::checkPermission(Permissions::SUPER_ADMIN) || Loader::getPageArg(2) == $_SESSION[SESSION::USERNAME]) {
				$user_info = $UserTbl->getUserInfo(Loader::getPageArg(2));
			}
		} else {
			$user_info = $UserTbl->getUserInfo($_SESSION[SESSION::USERNAME]);
		}

		if($user_info === false) {
            $this->loadLayout("user" . DS . "notFound.phtml");
		} else {
            $this->view->userinfo = $user_info;
            $this->loadLayout("user" . DS . "edit.phtml");
		}

	}

}
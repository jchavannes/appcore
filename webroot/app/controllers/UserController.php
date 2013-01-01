<?php

class UserController extends ViewController {

	const USER_ID = 'user_id';
	const USER_USERNAME = 'username';
	const USER_EMAIL = 'email';
	const USER_OLDPASSWORD = 'old_password';
	const USER_NEWPASSWORD = 'new_password';
	const USER_VERIFY_NEWPASSWORD = 'verify_new_password';

	const FORM_ID = 'user_edit_form';
	
	public function viewAction() {

		$page = IndexController::getPageArg();

		$UserTbl = new UserTbl();

		if(isset($page[2]) && !empty($page[2])) {
			// Someone else's profile
			if($user_info = $UserTbl->getUserInfo($page[2])) {$user_info['me'] = false;}			
		} else {
			// Your profile
			if($user_info = $UserTbl->getUserInfo()) {$user_info['me'] = true;}			
		}


		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		if($user_info === false) {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "notFound.phtml");
		} else {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "view.phtml");
		}
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}

	public function defaultAction() {
		UserController::viewAction();
	}

	public function editAction() {

		$page = IndexController::getPageArg();

		$UserTbl = new UserTbl();
		$user_info = false;

		if(isset($page[2]) && !empty($page[2])) {
			if(Session::checkPermission(Permissions::SUPER_ADMIN) || IndexController::getPageArg(2) == $_SESSION[SESSION::USERNAME]) {
				$user_info = $UserTbl->getUserInfo(IndexController::getPageArg(2));
			}
		} else {
			$user_info = $UserTbl->getUserInfo($_SESSION[SESSION::USERNAME]);
		}

		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		if($user_info === false) {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "notFound.phtml");
		} else {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . "edit.phtml");
		}
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

}
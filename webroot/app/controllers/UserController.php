<?php

class UserController extends ViewController {
	
	public function viewAction() {

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");

		$UserTbl = new UserTbl();

		if(isset($page[2])) {
			// Someone else's profile
			$user_info = $UserTbl->getUserInfo($page[2]);
			$user_info['me'] = false;
		} else {
			// Your profile
			$user_info = $UserTbl->getUserInfo();
			$user_info['me'] = true;
		}

		if($user_info === false) {
			include(ROOT_DIR . VIEW_DIR . "user" . DIRECTORY_SEPARATOR . "notFound.phtml");
		} else {
			include(ROOT_DIR . VIEW_DIR . "user" . DIRECTORY_SEPARATOR . "view.phtml");
		}

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}

	public function defaultAction() {
		UserController::viewAction();
	}

}
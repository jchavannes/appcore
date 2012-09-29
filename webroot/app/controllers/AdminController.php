<?php

class AdminController extends ViewController {
	
	function defaultAction() {

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");

		if(Session::checkPermission(Permissions::SUPER_ADMIN)) {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "adminIndex.phtml");
		} else {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "noAccess.phtml");
		}

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}
	
	function usersAction() {

		$UserTbl = new UserTbl();
		$userList = $UserTbl->getAllUsers();

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");

		if(Session::checkPermission(Permissions::SUPER_ADMIN)) {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "users.phtml");
		} else {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "noAccess.phtml");
		}
		
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}
	
	function visitorsAction() {

		$HttpRequestTbl = new HttpRequestTbl();
		$requests = $HttpRequestTbl->getPageCounts();

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");

		if(Session::checkPermission(Permissions::SUPER_ADMIN)) {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "visitors.phtml");
		} else {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "noAccess.phtml");
		}
		
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}
	
	function commentsAction() {

		$CommentTbl = new CommentTbl();
		$commentList = $CommentTbl->getAllComments('sample_page');
		
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");

		if(Session::checkPermission(Permissions::SUPER_ADMIN)) {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "comments.phtml");
		} else {
			include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "noAccess.phtml");
		}
		
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

}
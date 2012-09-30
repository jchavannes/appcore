<?php

class AdminController extends ViewController {
	
	function defaultAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::badUrl();
			return;
		}

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "adminIndex.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}
	
	function usersAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::badUrl();
			return;
		}

		$UserTbl = new UserTbl();
		$userList = $UserTbl->getAllUsers();

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "users.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}
	
	function visitorsAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::badUrl();
			return;
		}

		$HttpRequestTbl = new HttpRequestTbl();
		$requests = $HttpRequestTbl->getPageCounts();

		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "visitors.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}
	
	function commentsAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::badUrl();
			return;
		}

		$CommentTbl = new CommentTbl();
		$commentList = $CommentTbl->getAllComments('sample_page');
		
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "comments.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}
	
	function noAccessAction() {
		
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "noAccess.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

}
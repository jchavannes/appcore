<?php

class AdminController extends ViewController {
	
	function defaultAction() {

		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "index.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}

    public function signupAction() {

        if(SESSION::isLoggedIn()) {parent::Redirect();}

        include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
        include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "signup.phtml");
        include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

    }

    public function loginAction() {

        if(SESSION::isLoggedIn()) {parent::Redirect();}

        include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
        include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "login.phtml");
        include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

    }

    public function logoutAction() {

        Session::logout();
        parent::Redirect('admin/');

    }

	public function aboutAction() {

		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "about.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}
	
	function usersAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::badUrl();
			return;
		}

		$UserTbl = new UserTbl();
		$userList = $UserTbl->getAllUsers();

		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "users.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}
	
	function requestsAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::badUrl();
			return;
		}

		$HttpRequestTbl = new HttpRequestTbl();
		$requests = $HttpRequestTbl->getPageCounts();

		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "requests.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}
	
	function visitorsAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::badUrl();
			return;
		}

		$SessionTbl = new SessionTbl();
		$visits = $SessionTbl->getVisits();

		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "visitors.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}
	
	function commentsAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::badUrl();
			return;
		}

		$CommentTbl = new CommentTbl();
		$commentList = $CommentTbl->getAllComments('sample_page');
		
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "comments.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}
	
	function noAccessAction() {
		
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "noAccess.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DIRECTORY_SEPARATOR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");

	}

}
<?php

class AdminController extends ViewController {
	
	function defaultAction() {

		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "index.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");
	}

    public function signupAction() {

        if(SESSION::isLoggedIn()) {parent::Redirect('admin/');}

        include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
        include(ROOT_DIR . VIEW_DIR . "admin" . DS . "signup.phtml");
        include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");

    }

    public function loginAction() {

        if(SESSION::isLoggedIn()) {parent::Redirect('admin/');}

        include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
        include(ROOT_DIR . VIEW_DIR . "admin" . DS . "login.phtml");
        include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");

    }

    public function logoutAction() {

        Session::logout();
        parent::Redirect('admin/');

    }

	public function aboutAction() {

		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "about.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");

	}
	
	function usersAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::errorAction();
			return;
		}

		$UserTbl = new UserTbl();
		$userList = $UserTbl->getAllUsers();

		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "users.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");

	}
	
	function requestsAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::errorAction();
			return;
		}

		$HttpRequestTbl = new HttpRequestTbl();
		$requests = $HttpRequestTbl->getPageCounts();

		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "requests.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");
	}
	
	function visitorsAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::errorAction();
			return;
		}

		$SessionTbl = new SessionTbl();
		$visits = $SessionTbl->getVisits();

		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "visitors.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");
	}
	
	function commentsAction() {

		if(!Session::checkPermission(Permissions::SUPER_ADMIN)) {
			self::errorAction();
			return;
		}

		$CommentTbl = new CommentTbl();
		$commentList = $CommentTbl->getAllComments('sample_page');
		
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "comments.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");

	}
	
	function noAccessAction() {
		
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "noAccess.phtml");
		include(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");

	}

}
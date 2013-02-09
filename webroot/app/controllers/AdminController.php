<?php

class AdminController extends AdminViewController {
    
    function defaultAction() {
        $this->loadLayout("index.phtml");
    }

    public function signupAction() {
        if (SESSION::isLoggedIn()) {parent::Redirect('admin/');}
        $this->loadLayout("signup.phtml");
    }

    public function loginAction() {
        if (SESSION::isLoggedIn()) {parent::Redirect('admin/');}
        $this->loadLayout("login.phtml");
    }

    public function logoutAction() {
        Session::logout();
        parent::Redirect('admin/');
    }

    public function aboutAction() {
        $this->loadLayout("about.phtml");
    }
    
    function usersAction() {

        if (!Session::checkPermission(Permissions::SUPER_ADMIN)) {
            self::errorAction();
            return;
        }

        $UserTbl = new UserTbl();
        $this->view->users = $UserTbl->getAllUsers();
        $this->loadLayout("users.phtml");

    }
    
    function requestsAction() {

        if (!Session::checkPermission(Permissions::SUPER_ADMIN)) {
            self::errorAction();
            return;
        }

        $HttpRequestTbl = new HttpRequestTbl();
        $this->view->requests = $HttpRequestTbl->getPageCounts();
        $this->loadLayout("requests.phtml");

    }
    
    function visitorsAction() {

        if (!Session::checkPermission(Permissions::SUPER_ADMIN)) {
            self::errorAction();
            return;
        }

        $SessionTbl = new SessionTbl();
        $this->view->visits = $SessionTbl->getVisits();
        $this->loadLayout("visitors.phtml");

    }
    
    function commentsAction() {

        if (!Session::checkPermission(Permissions::SUPER_ADMIN)) {
            self::errorAction();
            return;
        }

        $CommentTbl = new CommentTbl();
        $this->view->comments = $CommentTbl->getAllComments('sample_page');
        $this->loadLayout("comments.phtml");

    }
    
    function noAccessAction() {
        $this->loadLayout("noAccess.phtml");
    }

}
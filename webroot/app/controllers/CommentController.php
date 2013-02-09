<?php

class CommentController extends ViewController {

    const COMMENT_ITEM = 'comment_item';
    const COMMENT_TITLE = 'comment_title';
    const COMMENT_MESSAGE = 'comment_message';

    const FORM_ID = 'comment_form';
    
    public function defaultAction() {
        self::errorAction();
    }

    public function addAction() {

        if(    !isset($_POST[self::COMMENT_ITEM])
            || !isset($_POST[self::COMMENT_TITLE])
            || !isset($_POST[self::COMMENT_MESSAGE]) || empty($_POST[self::COMMENT_MESSAGE]))
        {
            self::ajaxError(array(
                "message" => "Please enter a comment message.",
                "field" => CommentController::COMMENT_MESSAGE
            ));
            return;        
        }

        $query_opts = array(
            CommentTbl::USER_ID => isset($_SESSION[Session::USER_ID]) ? $_SESSION[Session::USER_ID] : "0",
            CommentTbl::ITEM => $_POST[self::COMMENT_ITEM],
            CommentTbl::PARENT_ID => 0,
            CommentTbl::TITLE => $_POST[self::COMMENT_TITLE],
            CommentTbl::MESSAGE => $_POST[self::COMMENT_MESSAGE],
            CommentTbl::DATE => time()
        );

        $CommentTbl = new CommentTbl();
        if($CommentTbl->addComment($query_opts)) {
            self::ajaxSuccess();
            return;
        }
        
    }

    public function deleteAction() {

        if(isset($_POST['id']) && $deleteId = $_POST['id']) {
            $CommentTbl = new CommentTbl();
            if(Session::checkPermission(Permissions::SUPER_ADMIN)) {
                if($CommentTbl->deleteComment($deleteId, true)) {
                    self::ajaxSuccess();
                    return;
                }
            } else {
                if($CommentTbl->deleteComment($deleteId)) {
                    self::ajaxSuccess();
                    return;
                }
            }
        }
        self::ajaxError();
        
    }

    static public function showComments($id) {
        $comments = array(
            'id' => $id,
            'table' => new CommentTbl()
        );
        $comments['data'] = $comments['table']->getAllComments($id);
        require(VIEW_DIR . "admin/snippets/comments.phtml");
    }

}
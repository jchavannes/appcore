<?php

class CommentController extends ViewController {

	const COMMENT_ITEM = 'comment_item';
	const COMMENT_TITLE = 'comment_title';
	const COMMENT_MESSAGE = 'comment_message';

	const FORM_ID = 'comment_form';
	
	public function defaultAction() {

	}

	public function addAction() {

		if(	!isset($_POST[self::COMMENT_ITEM])
			|| !isset($_POST[self::COMMENT_TITLE])
			|| !isset($_POST[self::COMMENT_MESSAGE]) || strlen($_POST[self::COMMENT_MESSAGE]) <= 1)
		{
			echo '{"status": "error", "error_id":1, "message": "Please enter a comment message."}';
			return;		
		}

		$helper = new FormHelper(FormHelper::COMMENT_FORM);
		if($helper->checkVerifier()) {
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
				echo '{"status": "success"}';
				return;
			}
		}
		echo '{"status": "error", "error_id":2, "message": "We were unable to validate your submissions, please refresh your page."}';
		
	}

}
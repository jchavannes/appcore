<?php

class CommentController extends ViewController {

	const COMMENT_TITLE = 'comment_title';
	const COMMENT_MESSAGE = 'comment_message';

	const FORM_ID = 'comment_form';
	
	public function defaultAction() {

	}

	public function addAction() {

		$helper = new FormHelper(FormHelper::COMMENT_FORM);

		if(	$helper->checkVerifier()
			&& isset($_POST[self::COMMENT_TITLE])
			&& isset($_POST[self::COMMENT_MESSAGE]))
		{
			$query_opts = array(
				CommentTbl::ID => $_SESSION[Session::USER_ID],
				CommentTbl::TITLE => $_POST[self::COMMENT_TITLE],
				CommentTbl::MESSAGE => $_POST[self::COMMENT_MESSAGE]
			);

			$CommentTbl = new CommentTbl();
			$CommentTbl->addComment($query_opts);

			/*
			if(Session::login($fields)) {
				parent::Redirect();
			} else {
				$login_form['error'] = true;
			}
			*/

		}

		
	}

}
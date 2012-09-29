<?php

class SampleController extends ViewController {

	const COMMENT_DEFAULT = 'sample_page';

	public function defaultAction() {
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "header.phtml");
		$comments = array();
		$comments['table'] = new CommentTbl();
		$comments['helper'] = new FormHelper(FormHelper::COMMENT_FORM);		
		$comments['data'] = $comments['table']->getAllComments(SampleController::COMMENT_DEFAULT);

		include(ROOT_DIR . VIEW_DIR . "samplePage.phtml");
		include(ROOT_DIR . VIEW_DIR . "snippets" . DIRECTORY_SEPARATOR . "footer.phtml");
	}
}

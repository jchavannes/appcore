<?php

class AdminViewController extends ViewController {

    public function loadLayout($page) {

        $this->loadFile(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "header.phtml");
        $this->loadFile(ROOT_DIR . VIEW_DIR . "admin" . DS . $page);
        $this->loadFile(ROOT_DIR . VIEW_DIR . "admin" . DS . "snippets" . DS . "footer.phtml");

    }

}
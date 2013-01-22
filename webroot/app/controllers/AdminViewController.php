<?php

class AdminViewController extends ViewController {

    public function loadLayout($page) {

        $this->loadFile("admin" . DS . "snippets" . DS . "header.phtml");
        $this->loadFile("admin" . DS . $page);
        $this->loadFile("admin" . DS . "snippets" . DS . "footer.phtml");

    }

}
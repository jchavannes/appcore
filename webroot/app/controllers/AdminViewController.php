<?php

class AdminViewController extends ViewController {

    public function loadLayout($page) {

        $this->loadFile("admin/snippets/header.phtml");
        $this->loadFile("admin/" . $page);
        $this->loadFile("admin/snippets/footer.phtml");

    }

}
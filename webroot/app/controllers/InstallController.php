<?php

/**
 * This is only run if 'local.config.php' cannot be found.
 *
 * It will attempt to setup a database connection and save
 * the credentials in a newly created 'local.config.php'.
 *
 * Once complete, create a file in the config folder that
 * bust be deleted in order to run the install again. This
 * is a preventative measure against unintentionally
 * running the install.
 *
 * Best practice would probably be to overwrite this class
 * and delete 'views/admin/install/install.phtml'.
 */
class InstallController extends ViewController {

    static public function runInstall() {
        $self = new self();
        $self->loadFile("admin/install/install.phtml");
    }

}

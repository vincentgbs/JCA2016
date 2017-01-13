<?php
require_once FILE . 'framework/controller/controller.php';

class acctController extends controller {

    public function __construct()
    {
        parent::__construct();
        $this->getController('user');
        if (!$this->userController->check()) {
            $this->flashMessage('Please log in to access this page.');
            $this->redirect('user/login', false, URL);
        } // else
        $this->getModel('acct');
        $this->getView('acct');
        $this->getSettings('acct');
    }

    public function home()
    {
        $this->acctView->home();
    }

    public function companies()
    {
        if (isset($_POST['function'])) {
            //
        }
        // $q = 'CREATE TABLE `test` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
        // $q = 'DROP TABLE `test`;';
        // $this->acctModel->execute($q);
        $this->acctView->companies();
    }

}

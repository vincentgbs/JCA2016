<?php
require_once FILE . 'framework/controller/controller.php';
define('ACCTDBUSER', 'acct_user');
define('ACCTDBPASS', 'password');

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
        $this->acctView->companies();
    }

}

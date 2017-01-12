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
        echo 'Accounting controller';
    }

}

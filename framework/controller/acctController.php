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
        $this->getModel('acct', $this->userController->userModel->db);
        $this->getView('acct');
        $this->getSettings('acct');
    }

    public function home()
    {
        $this->acctView->home();
    }

    public function queries()
    {
        if (isset($_POST['function'])) {
            $function = $this->post('function', 'a', 16);
            switch ($function) {
                case 'create':
                    $db = (object)$_POST;
                    if ($this->acctModel->createDatabase($db)) {
                        echo 'Database added.';
                    } return;
                break;
                case 'delete':
                    $db = (object)['db_id' => $this->post('db_id', 'i')];
                    if ($this->acctModel->deleteDatabase($db)) {
                        echo 'Database deleted.';
                    } return;
                break;
                default: exit('Invalid database function.');
            }
        }
        $dbs = $this->acctModel->readDatabase();
        $this->acctView->queries($dbs);
    }

}

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

    public function company()
    {
        if (isset($_POST['function'])) {
            $function = $this->post('function', 'a', 8);
            switch ($function) {
                case 'company_search':
                    $company_search = $this->post('company_search', 'a');
                    echo json_encode(NULL); return;
                break;
                case 'create':
                    if ($this->csrfCheck()) {
                        if ($this->acctModel->createCompany($this->post('company_name', 'a', 64))) {
                            //
                        } else {
                            echo ('Database error creating company'); return;
                        }
                    }// csrfCheck
                    echo ($this->csrf_message); return;
                break;
            }
        }
        $companys = $this->acctModel->readCompany();
        $this->acctView->companys($companys);
    }

}

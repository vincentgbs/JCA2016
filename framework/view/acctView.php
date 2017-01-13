<?php
require_once FILE . 'framework/view/view.php';

class acctView extends view {

    public function __construct()
    {
        parent::__construct();
        $this->loadTemplate('acct/header', null, 'header');
        $this->loadTemplate('acct/footer', null, 'footer');
    }

    public function home()
    {
        $this->body .= 'Accounting Controller';
        $this->display();
    }

    public function companys($companys)
    {
        $this->loadTemplate('acct/company/head');
        foreach ($companys as $company) {
            $this->loadTemplate('acct/company/company', $company);
        }
        $this->loadTemplate('acct/company/foot');
        $this->display();
    }

}
?>

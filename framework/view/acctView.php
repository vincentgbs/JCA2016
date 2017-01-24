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

    public function queries($dbs, $data)
    {
        $this->loadTemplate('acct/queries/db/head');
        foreach ($dbs as $db) {
            $this->loadTemplate('acct/queries/db/database', $db);
        }
        $this->loadTemplate('acct/queries/db/foot');
        $this->loadTemplate('acct/queries/builder', $data);
        $this->display();
    }

}
?>

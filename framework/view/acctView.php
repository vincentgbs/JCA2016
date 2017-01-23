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
        $this->loadTemplate('acct/queries/head');
        foreach ($dbs as $db) {
            $this->loadTemplate('acct/queries/database', $db);
        }
        $this->loadTemplate('acct/queries/foot', $data);
        $this->display();
    }

}
?>

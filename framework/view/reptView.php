<?php
require_once FILE . 'framework/view/view.php';

class reptView extends view {

    public function __construct()
    {
        parent::__construct();
        $this->loadTemplate('rept/header', null, 'header');
        $this->loadTemplate('rept/footer', null, 'footer');
    }

    public function home()
    {
        $this->body .= 'Accounting Controller';
        $this->display();
    }

    public function queries($dbs, $data)
    {
        $this->loadTemplate('rept/queries/db/head');
        foreach ($dbs as $db) {
            $this->loadTemplate('rept/queries/db/database', $db);
        }
        $this->loadTemplate('rept/queries/db/foot');
        // $this->loadTemplate('rept/queries/builder', $data);
        $this->display();
    }

}
?>

<?php
require_once FILE . 'framework/model/model.php';

class acctModel extends model {

    public function createDatabase($db)
    {
        //
    }

    public function readDatabase($db=false)
    {
        $q = 'SELECT * FROM acct_ls_databases';
        if ($db) {
            //
        }
        return $this->select($q);
    }

}
?>

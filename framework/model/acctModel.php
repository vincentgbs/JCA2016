<?php
require_once FILE . 'framework/model/model.php';

class acctModel extends model {

    public function __construct($db=false)
    {
        $this->db = new mysqli(DBHOST, ACCTDBUSER, ACCTDBPASS, DATABASE);
        if ($this->db->connect_errno) {
            exit('500 Internal Server Error: Database connection error');
        }
    }

    public function createCompany($company)
    {
        //
    }

    public function readCompany($company)
    {
        //
    }

    public function updateCompany($company, $update)
    {
        //
    }

    public function deleteCompany($company)
    {
        //
    }

}
?>

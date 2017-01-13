<?php
require_once FILE . 'framework/model/model.php';
// define('ACCTDBUSER', 'acct_user');
// define('ACCTDBPASS', 'password');

class acctModel extends model {

    public function __construct($db=false)
    {
        if ($db) { // avoid creating an extra connection
            $this->db = $db;
        } else {
            $user = (@(ACCTDBUSER==NULL) ? ACCTDBUSER : DBUSER);
            $pass = (@(ACCTDBPASS==NULL) ? ACCTDBPASS : DBPASS);
            $this->db = new mysqli(DBHOST, $user, $pass, DATABASE);
        }
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

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
        $q = "INSERT INTO `acct_ls_company` (`company`, `future_data`)
            VALUES ({$this->wrap($company)}, NULL);";
        if ($this->execute($q)) {
            $q = "CREATE TABLE `acct_ledger_{$company}` (
                `ledger_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `date` date NOT NULL,
                `account_id` int(11) NOT NULL,
                `debit` float DEFAULT NULL,
                `credit` float DEFAULT NULL,
                `memo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                PRIMARY KEY (`ledger_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
            return $this->execute($q);
        }
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

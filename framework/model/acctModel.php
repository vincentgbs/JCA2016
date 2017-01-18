<?php
require_once FILE . 'framework/model/model.php';

class acctModel extends model {

    public function createCompany($company)
    {
        $q = "INSERT INTO `acct_ls_company` (`company`, `future_data`)
            VALUES ({$this->wrap($company->company)}, NULL);";
        if ($this->execute($q)) {
            $q = "CREATE TABLE `acct_ledger_{$company->abbreviation}` (
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

    public function readCompany($company=false)
    {
        $q = 'SELECT * FROM `acct_ls_company`';
        if ($company) {
            $q .= ' WHERE';
            foreach ($company as $key => $value) {
                $q .= " `$key` = {$this->wrap($value)} AND";
            }
            $q = substr($q, 0, -4) . ';';
        }
        return $this->select($q);
    }

    // public function updateCompany($company, $update)
    // {
    //     $q = "UPDATE acct_ls_company SET `future_data`={$this->wrap($update->future_data)}
    //         WHERE `company_id`={$company->company_id};";
    //     return $this->execute($q);
    // }

    // public function deleteCompany($company)
    // {
    //     $q = 'DELETE FROM `acct_ls_company` WHERE';
    //     foreach ($company as $key => $value) {
    //         $q .= " `$key` = {$this->wrap($value)} AND";
    //     }
    //     $q = substr($q, 0, -4) . ';';
    //     if ($this->execute($q)) {
    //         $q = "DROP TABLE `acct_ledger_{$company->company}`";
    //     }
    // }

}
?>

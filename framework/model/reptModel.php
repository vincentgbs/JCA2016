<?php
require_once FILE . 'framework/model/model.php';

class reptModel extends model {

    public function createDatabase($db)
    {
        $q = "INSERT INTO `rept_ls_databases`
            (`nickname`, `host`, `username`, `password`, `database`)
            VALUES ({$this->wrap($db->nickname)},
                {$this->wrap($db->host)},
                {$this->wrap($db->username)},
                {$this->wrap($db->password)},
                {$this->wrap($db->database)});";
        return $this->execute($q);
    }

    public function readDatabase($db=false)
    {
        $q = 'SELECT * FROM `rept_ls_databases`';
        if ($db) {
            $q .= ' WHERE';
            foreach ($db as $key => $value) {
                $q .= " `$key` = {$this->wrap($value)} AND";
            }
            $q = substr($q, 0, -4) . ';';
        }
        return $this->select($q);
    }

    public function deleteDatabase($db)
    {
        $q = 'DELETE FROM `rept_ls_databases`';
        if ($db) {
            $q .= ' WHERE';
            foreach ($db as $key => $value) {
                $q .= " `$key` = {$this->wrap($value)} AND";
            }
            $q = substr($q, 0, -4) . ';';
        }
        return $this->execute($q);
    }



    public function readCache($table)
    {
        $q = "SELECT * FROM `cache_{$table}`;";
        return $this->select($q);
    }

}
?>

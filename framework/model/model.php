<?php
require_once FILE . 'framework/application.php';

abstract class model extends application {

    public function __construct($db=false)
    {
        if ($db) { // avoid creating an extra connection
            $this->db = $db;
        } else {
            $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DATABASE);
        }
        if ($this->db->connect_errno) {
            exit('500 Internal Server Error: Database connection error');
        }
    }

    public function select($query)
    {
        $result = $this->db->query($query, MYSQLI_USE_RESULT);
        $return = [];
        while ($row = $result->fetch_object()) {
            $return[] = $row;
        }
        $result->close();
        return $return;
    }

    public function execute($query)
    {
        return $this->db->query($query);
    }

    public function wrap($text, $slash=";'")
    {
        if (isset($text)) {
            return "'" . addcslashes($text, $slash) . "'";
        } else {
            return 'NULL';
        }
    }

    public function getSettings($category)
    {
        $q = "SELECT `setting_name`, `setting_value`
            FROM `sys_ls_settings` WHERE `cat`='{$category}';";
        return $this->select($q);
    }

}
?>

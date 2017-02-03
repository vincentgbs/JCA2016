<?php
require_once FILE . 'framework/controller/controller.php';

/* A combined controller for simple backend work */
class frameworkController extends controller {

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SERVER['PHP_AUTH_USER'])
        || $_SERVER['PHP_AUTH_USER'] != ADMINU
        || $_SERVER['PHP_AUTH_PW'] != ADMINP) {
            header('WWW-Authenticate: Basic realm="Framework Controller"');
            header('HTTP/1.0 401 Unauthorized');
            exit ('Invalid login credentials.');
        }
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DATABASE);
        if ($this->db->connect_errno) {
            exit('500 Internal Server Error: Database connection error');
        }
        $this->view = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="author" content=""><meta name="keywords" content=""><meta name="description" content=""><meta name="viewport" content="width=device-width, initial-scale=1"><title>User Management Module</title><link rel="stylesheet" type="text/css" href="/css/library/bootstrap.min.css"><link rel="stylesheet" type="text/css" href="/css/library/jquery-ui.min.css"><link rel="stylesheet" type="text/css" href="/css/library/1.10.12.dataTables.min.css"><link rel="stylesheet" type="text/css" href="/css/style.css"><script type="text/javascript" src="/js/library/jquery-3.1.1.min.js"></script><script type="text/javascript" src="/js/library/sha256.js"></script><script type="text/javascript" src="/js/library/jquery-ui.min.js"></script><script type="text/javascript" src="/js/vanilla.js"></script><script type="text/javascript" src="/js/library/1.10.12.dataTables.min.js"></script></head>';
    }

    public function permissions()
    {
        if (isset($_POST['permission'])) {
            //
        }
        $this->view .= 'Add permission: <input type="text"><br>';
        $this->view .= 'Add permission to group: <input type="text"><br>';
        $this->view .= 'Add group: <input type="text"><br>';
        $this->view .= 'Add user to group: <input type="text"><br>';

        $permissions = $this->select('SELECT * FROM user_view_permissions;');
        $this->view .= '<pre>';
        foreach ($permissions as $permission) {
            $this->view .= serialize($permission) . '<br>';
        }
        $this->view .= '</pre>';
        echo $this->view;
    }



    private function select($query)
    {
        $result = $this->db->query($query, MYSQLI_USE_RESULT);
        $return = [];
        while ($row = $result->fetch_object()) {
            $return[] = $row;
        }
        $result->close();
        return $return;
    }

    private function execute($query)
    {
        return $this->db->query($query);
    }

    private function wrap($text, $slash=";'")
    {
        if (isset($text)) {
            return "'" . addcslashes($text, $slash) . "'";
        } else {
            return 'NULL';
        }
    }

}
?>

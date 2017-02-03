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
        $this->view .= '<div id="tabs"><ul><li><a href="#tabs-4">All</a></li><li><a href="#tabs-1">Permissions</a></li><li><a href="#tabs-2">Groups</a></li><li><a href="#tabs-3">Users</a></li></ul>';
        $this->view .= '<div id="tabs-1">
        <table><thead><th>permission_id</th><th>function</th><th>group_id</th><th>group_name</th></thead><tbody>';
        $permissions = $this->select('SELECT * FROM `user_ls_permissions` `p` LEFT JOIN `user_rel_permissions` `r` ON `p`.`permission_id`=`r`.`permission_id` LEFT JOIN `user_ls_groups` `g` ON `r`.`group_id`=`g`.`group_id`;');
        foreach ($permissions as $p) {
            $this->view .= '<tr><td>' . "{$p->permission_id}</td><td>{$p->function}</td><td>{$p->group_id}</td><td>{$p->group_name}" . '<td></tr>';
        }
        $this->view .= '</tbody></table></div><div id="tabs-2">
        <table><thead><th>group_id</th><th>group_name</th></thead><tbody>';
        $groups = $this->select('SELECT * FROM `user_ls_groups`;');
        foreach ($groups as $g) {
            $this->view .= '<tr><td>' . "{$g->group_id}</td><td>{$g->group_name}" . '<td></tr>';
        }
        $this->view .= '</tbody></table></div><div id="tabs-3">
        <table><thead><th>user_id</th><th>username</th><th>email</th><th>group_id</th><th>group_name</th></thead><tbody>';
        $users = $this->select('SELECT * FROM `user_ls_users` `u`
            LEFT JOIN `user_rel_groups` `r` ON `u`.`user_id`=`r`.`user_id`
            LEFT JOIN `user_ls_groups` `g` ON `r`.`group_id`=`g`.`group_id`;');
        foreach ($users as $u) {
            $this->view .= '<tr><td>' . "{$u->user_id}</td><td>{$u->username}</td><td>{$u->email}</td><td>{$u->group_id}</td><td>{$u->group_name}" . '<td></tr>';
        }
        $this->view .= '</tbody></table></div><div id="tabs-4">';
        $all = $this->select('SELECT * FROM `user_view_permissions`;');
        foreach ($all as $a) {
            $this->view .= serialize($a) . '<br>';
        }
        $this->view .= '</div></div><script>$(function(){$("#tabs").tabs();});</script>';
        $this->display();
    }





    private function display()
    {
        $this->view .= '</body> <!-- </body class="container"> --></html> <!-- </html lang="en"> -->';
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

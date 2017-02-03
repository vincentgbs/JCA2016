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
        $this->view = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>User Management Module</title><link rel="stylesheet" type="text/css" href="/css/library/bootstrap.min.css"><link rel="stylesheet" type="text/css" href="/css/library/jquery-ui.min.css"><link rel="stylesheet" type="text/css" href="/css/library/1.10.12.dataTables.min.css"><link rel="stylesheet" type="text/css" href="/css/style.css"><script type="text/javascript" src="/js/library/jquery-3.1.1.min.js"></script><script type="text/javascript" src="/js/library/sha256.js"></script><script type="text/javascript" src="/js/library/jquery-ui.min.js"></script><script type="text/javascript" src="/js/vanilla.js"></script><script type="text/javascript" src="/js/library/1.10.12.dataTables.min.js"></script></head>';
    }

    public function permissions()
    {
        if (isset($_POST['permission'])) {
            //
        }
        $this->view .= '<style>td {min-width:10%;}</style><div>
        username: <input type="text" id="username">
        group_name: <input type="text" id="group_name">
        function: <input type="text" id="function">
        <button>Add/Update</button>
        </div><hr>
        <table id="permissions"><thead><tr>
            <th>username</th>
            <th>email</th>
            <th>group_id</th>
            <th>group_name</th>
            <th>permission_id</th>
            <th>function</th>
            <th>delete</th>
            </tr></thead><tbody>';
        $all = $this->select('SELECT * FROM `user_view_permissions`;');
        foreach ($all as $a) {
            $this->view .= '<tr>
                <td>' . "{$a->username}</td>
                <td>{$a->email}</td>
                <td>{$a->group_id}</td>
                <td>{$a->group_name}</td>
                <td>{$a->permission_id}</td>
                <td>{$a->function}</td>
                <td>{$a->user_id} | {$a->group_id}" . '<td>
            </tr>';
        }
        $this->view .= '</tbody>
            </table>
        <script></script>';
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

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
            $username = $this->post('username', 'a', 99);
            $group = $this->post('group', 'a', 99);
            $permission = $this->post('permission', 's', 99, '/');
            if (true) {// validate $permission format */* and make sure group_name is not blank
                $q = "INSERT IGNORE INTO `user_ls_permissions` (`function`) VALUES ({$permission});";
                $q = "INSERT IGNORE INTO `user_ls_groups` (`group_name`) VALUES ({$group});";
                $q = "INSERT INTO `user_rel_permissions`
                    VALUES (
                    (SELECT `group_id` FROM `user_ls_groups` WHERE `group_name`='{$group}'),
                    (SELECT `permission_id` FROM `user_ls_permissions` WHERE `function`='$permission'));";
            }
            if ($username != '') { // username isn't blank
                $q = "INSERT INTO `user_rel_groups` (`user_id`, `group_id`)
                VALUES (
                (SELECT `user_id` FROM `user_ls_users` WHERE `username`='{$username}'),
                (SELECT `group_id` FROM `user_ls_groups` WHERE `group_name`='{$group}')
                );";
            }
            return;
        }
        $this->view .= '<style>td {min-width:10%;}</style><div>
        username: <input type="text" id="username">
        group_name: <input type="text" id="group_name">
        permission(function): <input type="text" id="permission">
        <button id="add_permission_button">Add/Update</button>
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
        <script>$(document).ready(function(){
            $("#add_permission_button").on("click", function(){
                var username = $("#username").val();
                var group_name = $("#group_name").val();
                var permission = $("#permission").val();
                $.ajax({
                    url: "?url=framework/permissions",
                    type: "POST",
                    data: {
                        username: username,
                        group_name: group_name,
                        permission: permission
                    },
                    success: function(response){
                        alert(response);
                    }
                }); // ajax
            });
        });</script>';
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

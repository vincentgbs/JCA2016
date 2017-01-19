<?php
require_once FILE . 'framework/controller/controller.php';

class acctController extends controller {

    public function __construct()
    {
        parent::__construct();
        $this->getController('user');
        if (!$this->userController->check()) {
            $this->flashMessage('Please log in to access this page.');
            $this->redirect('user/login', false, URL);
        } // else
        $this->getModel('acct', $this->userController->userModel->db);
        $this->getView('acct');
        // $this->getSettings('acct');
        if (!isset($_SESSION['zoos_connection'])) {
            $dbs = $this->acctModel->readDatabase();
            $_SESSION['zoos_connection'] = $dbs[0];
        }
    }

    public function home()
    {
        $this->acctView->home();
    }

    public function queries()
    {
        if (isset($_POST['function'])) {
            $function = $this->post('function', 'a', 16);
            switch ($function) {
                case 'create':
                    $db = (object)$_POST;
                    if ($this->acctModel->createDatabase($db)) {
                        echo 'Database added.';
                    } return;
                break;
                case 'delete':
                    $db = (object)['db_id' => $this->post('db_id', 'i')];
                    if ($this->acctModel->deleteDatabase($db)) {
                        echo 'Database deleted.';
                    } return;
                break;
                case 'brand':
                    // $db = new mysqli($_SESSION['zoos_connection']->host,
                    //     $_SESSION['zoos_connection']->username,
                    //     $_SESSION['zoos_connection']->password,
                    //     $_SESSION['zoos_connection']->database);
                    // $brand = $this->post('brand');
                    // $query = "SELECT * FROM `cs_ecomm_brand` WHERE `brand` LIKE '{$brand}%';";
                    // $result = $db->query($query, MYSQLI_USE_RESULT);
                    // $return = [];
                    // while ($row = $result->fetch_object()) {
                    //     $return[] = $row->brand;
                    // }
                    // $result->close();
                    // echo json_encode($return); return;
                break;
                default: exit('Invalid database function.');
            }
        }
        $dbs = $this->acctModel->readDatabase();
        $this->acctView->queries($dbs);
    }

}

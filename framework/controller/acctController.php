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
        $this->getSettings('acct');
        $this->setConnection();
    }

    private function setConnection($params=false)
    {
        if ($params) {
            $dbs = $this->acctModel->readDatabase($params);
        } else if (!isset($_SESSION['zoos_connection'])) {
            $dbs = $this->acctModel->readDatabase([
                'nickname' => $this->settings['default_database_connection']
            ]);
        } else { return true; }
        if (isset($dbs[0])) {
            $_SESSION['zoos_connection'] = $dbs[0];
            return true;
        } else {
            exit('Database connection does not exist.');
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
                case 'select':
                    if ($this->setConnection(['db_id' => $this->post('db_id', 'i')])) {
                        echo 'Database selected.';
                    }
                    return;
                break;
                case 'update_tables':
                    $brands = $this->acctModel->readCache('cache_cs_ecomm_brand');
                    $channels = $this->acctModel->readCache('cache_cs_journal_channel');
                    // first in our model, select all from cache_cs_ecomm_brand;
                    // check last ids... and update accordingly

                    // $db = new mysqli($_SESSION['zoos_connection']->host,
                    //     $_SESSION['zoos_connection']->username,
                    //     $_SESSION['zoos_connection']->password,
                    //     $_SESSION['zoos_connection']->database);
                    // $query = "SELECT * FROM `cs_ecomm_brand`;";
                    // $result = $db->query($query, MYSQLI_USE_RESULT);
                    // $return = [];
                    // while ($row = $result->fetch_object()) {
                    //     $return[] = $row->brand;
                    // }
                    // $result->close();
                break;
                default: exit('Invalid database function.');
            }
        }
        $data['brands'] = $this->acctModel->readCache('cache_cs_ecomm_brand');
        $data['channels'] = $this->acctModel->readCache('cache_cs_journal_channel');
        $dbs = $this->acctModel->readDatabase();
        $this->acctView->queries($dbs, $data);
    }

}

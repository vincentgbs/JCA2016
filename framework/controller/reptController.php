<?php
require_once FILE . 'framework/controller/controller.php';

class reptController extends controller {

    public function __construct()
    {
        parent::__construct();
        $this->getController('user');
        if (!$this->userController->check()) {
            $this->flashMessage('Please log in to access this page.');
            $this->redirect('user/login', false, URL);
        } // else
        $this->getModel('rept', $this->userController->userModel->db);
        $this->getView('rept');
        $this->getSettings('rept');
        $this->setConnection();
    }

    private function setConnection($params=false)
    {
        if ($params) {
            $dbs = $this->reptModel->readDatabase($params);
        } else if (!isset($_SESSION['zoos_connection'])) {
            $dbs = $this->reptModel->readDatabase([
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
        $this->reptView->home();
    }

    public function queries()
    {
        if (isset($_POST['function'])) {
            $function = $this->post('function', 'a', 16);
            switch ($function) {
                case 'create':
                    $db = (object)$_POST;
                    if ($this->reptModel->createDatabase($db)) {
                        echo 'Database added.';
                    } return;
                break;
                case 'delete':
                    $db = (object)['db_id' => $this->post('db_id', 'i')];
                    if ($this->reptModel->deleteDatabase($db)) {
                        echo 'Database deleted.';
                    } return;
                break;
                case 'select':
                    if ($this->setConnection(['db_id' => $this->post('db_id', 'i')])) {
                        echo 'Database selected.';
                    }
                    return;
                break;
                default: exit('Invalid database function.');
            }
        }
        $data['brands'] = $this->reptModel->readCache('cs_ecomm_brand');
        $data['channels'] = $this->reptModel->readCache('cs_journal_channel');
        $dbs = $this->reptModel->readDatabase();
        $this->reptView->queries($dbs, $data);
    }

    private function select($query)
    {
        $db = new mysqli($_SESSION['zoos_connection']->host,
            $_SESSION['zoos_connection']->username,
            $_SESSION['zoos_connection']->password,
            $_SESSION['zoos_connection']->database);
        $query = "SELECT * FROM `cs_ecomm_brand`;";
        $result = $db->query($query, MYSQLI_USE_RESULT);
        $return = [];
        while ($row = $result->fetch_object()) {
            $return[] = $row->brand;
        }
        $result->close();
        $db->close();
        return $return;
    }

    public function test()
    {
        if (isset($_FILES['fileToUpload'])) {
            require_once FILE . 'framework/libraries/simpleChunking.php';
            $upload = new simpleChunking();
            return $upload->upload('test.csv', $this->post('count', 'i'));
        }
        $this->reptView->loadTemplate('rept/test');
        $this->reptView->display();
    }


}

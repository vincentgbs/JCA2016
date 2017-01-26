<?php
require_once FILE . 'framework/controller/controller.php';

class cmsController extends controller {

    public function __construct()
    {
        parent::__construct();
        $this->getController('user');
        if (!$this->userController->check()) {
            $this->flashMessage('Please log in to access this page.');
            $this->redirect('user/login', false, URL);
        } // else
        // $this->getModel('cms', $this->userController->userModel->db);
        $this->getView('cms');
        $this->getSettings('cms');
    }

    public function edit()
    {
        echo 'Hello World';
    }

    public function upload()
    {
        if (isset($_FILES['fileToUpload'])) {
            require_once FILE . 'framework/libraries/simpleChunking.php';
            $type = $this->post('type', 'a', 9);
            switch ($type) {
                case 'html':
                    $upload = new simpleChunking('html');
                    $name = $this->post('name', 'a') . '.tpl';
                    return $upload->upload($name);
                break;
                case 'css':
                    $upload = new simpleChunking('css');
                    $name = $this->post('name', 'a') . '.tpl';
                    return $upload->upload($name);
                break;
                case 'js':
                    $upload = new simpleChunking('js');
                    $name = $this->post('name', 'a') . '.tpl';
                    return $upload->upload($name);
                break;
                default: exit('Invalid upload function.');
            }
        }
        $this->cmsView->upload();
    }

}
?>

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
        $this->getModel('cms', $this->userController->userModel->db);
        $this->getView('cms');
        $this->getSettings('cms');
    }

    public function home()
    {
        $this->cmsView->home();
    }

    public function edit()
    {
        // edit templates
        $this->cmsView->edit();
    }

    public function upload()
    {
        if (isset($_FILES['fileToUpload'])) {
            require_once FILE . 'framework/libraries/simpleChunking.php';
            $type = $this->post('type', 'a', 9);
            switch ($type) {
                case 'html':
                    $upload = new simpleChunking('html');
                    $name = $this->post('name', 'a') . '.html';
                    return $upload->upload($name);
                break;
                case 'css':
                    $upload = new simpleChunking('css');
                    $name = $this->post('name', 'a') . '.css';
                    return $upload->upload($name);
                break;
                case 'js':
                    $upload = new simpleChunking('js');
                    $name = $this->post('name', 'a') . '.js';
                    return $upload->upload($name);
                break;
                case 'img':
                    $upload = new simpleChunking('img');
                    $filetypes = ['image/jpeg'=>'jpg', 'image/png'=>'png']; // filter
                    $ext = $filetypes[$this->post('filetype', 's', 16, '/')]; // match type
                    $name = $this->post('name', 'a') . '.' . $ext;
                    return $upload->upload($name);
                break;
                case 'aud':
                    // $upload = new simpleChunking('aud');
                    // $name = $this->post('name', 'a') . $this->post('filetype', 'a');
                    // return $upload->upload($name);
                    return;
                break;
                case 'file':
                    // $upload = new simpleChunking('file');
                    // $name = $this->post('name', 'a') . $this->post('filetype', 'a');
                    // return $upload->upload($name);
                    return;
                break;
                default: exit('Invalid upload function.');
            }
        } else if (isset($_POST['upload_form'])) {
            $form = $this->post('upload_form', 'a', 9);
            if (in_array($form, ['html', 'css', 'js', 'img', 'aud', 'file'])) {
                $this->cmsView->loadTemplate('cms/upload/' . $form);
                return $this->cmsView->display(false);
            } else {
                exit('Invalid upload function.');
            }
        }
        $this->cmsView->upload();
    }

}
?>

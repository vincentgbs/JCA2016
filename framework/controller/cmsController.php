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

    private function upload_file($ext, $types, $type)
    {
        if (array_key_exists($ext, $types)) {
            $upload = new simpleChunking($type);
            $ext = $types[$ext]; // match type
            $name = $this->post('name', 's', 99, '-_') . '.' . $ext;
            return $upload->upload($name);
        } else {
            exit('Invalid ' . $type . ' file.');
        }
    }

    private function addFileToDatabase($filename, $type)
    {
        $file = new stdClass();
        $file->type = $type;
        $file->name = NULL;
        $file->resource = file_get_contents($filename);
        $file->data = NULL;
    }

    public function upload()
    {
        if (isset($_FILES['fileToUpload'])) {
            require_once FILE . 'framework/libraries/simpleChunking.php';
            $type = $this->post('type', 'a', 9);
            switch ($type) {
                case 'html':
                    $htmltypes = ['texthtml'=>'html', 'textphp'=>'html', 'textplain'=>'html'];
                    $check = $this->upload_file($this->post('filetype', 'a', 16), $htmltypes, 'html');
                    if ($check) { return $this->addFileToDatabase($check, 'html'); }
                break;
                case 'css':
                    $csstypes = ['textcss'=>'css'];
                    $check = $this->upload_file($this->post('filetype', 'a', 16), $csstypes, 'css');
                    if ($check) { return $this->addFileToDatabase($check, 'css'); }
                break;
                case 'js':
                    $jstypes = ['applicationx-javascript'=>'js'];
                    $check = $this->upload_file($this->post('filetype', 's', 32, '-'), $jstypes, 'js');
                    if ($check) { return $this->addFileToDatabase($check, 'js'); }
                break;
                case 'img':
                    $imgtypes = ['imagepng'=>'png', 'imagejpeg'=>'jpg', 'imagegif'=>'gif', 'imagesvg+xml'=>'svg'];
                    return $this->upload_file($this->post('filetype', 's', 16, '+'), $imgtypes, 'img');
                break;
                case 'aud':
                    $audtypes = ['audiompeg'=>'aud', 'audiox-wav'=>'aud'];
                    return $this->upload_file($this->post('filetype', 's', 16, '-'), $audtypes, 'aud');
                break;
                case 'file':
                    $upload = new simpleChunking('files');
                    $name = $this->post('name', 'a') . '.' . $this->post('filetype', 'a');
                    return $upload->upload($name);
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

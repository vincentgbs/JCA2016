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
        if (isset($_POST['edit_form'])) {
           $form = $this->post('edit_form', 'a', 9);
           if (in_array($form, ['html', 'css', 'js', 'page'])) {
               $this->cmsView->loadTemplate('cms/edit/' . $form);
               return $this->cmsView->display(false);
           } else {
               exit('Invalid edit function.');
           }
       }
        $this->cmsView->edit();
    }

    private function upload_file($ext, $types, $type)
    {
        if (array_key_exists($ext, $types)) {
            $upload = new simpleChunking($type);
            $ext = $types[$ext]; // match type
            $name = $this->post('name', 's', 99, '-_') . '.' . $ext;
            $chunk = $upload->upload($name);
            if (in_array($type, ['html', 'css', 'js'])) {
                if ($chunk) {
                    $this->addFileToDatabase($chunk, $type, $ext);
                } else { return; }
            } else {
                return $chunk;
            }
        } else {
            exit('Invalid ' . $type . ' file.');
        }
    }

    private function addFileToDatabase($file, $type, $ext)
    {
        $resource = new stdClass();
        $resource->type = $type;
        $resource->name = $file->name;
        $resource->resource = htmlspecialchars(file_get_contents($file->base . $file->name));
        $resource->data = serialize(['extension'=>$ext]);
        return $this->cmsModel->createResource($resource);
    }

    public function upload()
    {
        if (isset($_FILES['fileToUpload'])) {
            require_once FILE . 'framework/libraries/simpleChunking.php';
            $type = $this->post('type', 'a', 9);
            switch ($type) {
                case 'html':
                    $htmltypes = ['texthtml'=>'html', 'textphp'=>'html', 'textplain'=>'html'];
                    return $this->upload_file($this->post('filetype', 'a', 16), $htmltypes, 'html');
                break;
                case 'css':
                    $csstypes = ['textcss'=>'css'];
                    return $this->upload_file($this->post('filetype', 'a', 16), $csstypes, 'css');
                break;
                case 'js':
                    $jstypes = ['applicationx-javascript'=>'js'];
                    return $this->upload_file($this->post('filetype', 's', 32, '-'), $jstypes, 'js');
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

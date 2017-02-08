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

    public function pages()
    {
        if (isset($_POST['function'])) {
            switch ($this->post('function', 'a', 32)) {
                case 'create':
                    $page = (object)['page_name'=>$this->post('name', 'a', 99)];
                    if ($this->cmsModel->createPage($page)) {
                        echo ('Page created.'); return;
                    } else {
                        exit('Error creating page.');
                    }
                    break;
                case 'read':
                    $page = (object)['`ls`.`page_id`'=>$this->post('page_id', 'i')];
                    $page = $this->cmsModel->readPage($page);
                    foreach ($page as $row) {
                        //
                    }
                    // get update form
                    break;
                case 'update':
                    // NEED - (page name OR id) AND template name AND order AND (add OR remove)
                    // add or remove html templates from page
                    break;
                case 'delete':
                    // NEED - name OR id
                    // delete page
                    break;
                default: echo ('Invalid page function.'); return;
            }
        }
        $this->cmsView->pages();
    }

    public function edit()
    {
        if (isset($_POST['search'])) {
            return $this->resourceSearch( $this->post('name', 'a', 99) );
        } else if (isset($_POST['function'])) {
            $function = $this->post('function', 'a', 32);
            if ($function == 'getupdateform') {
                $type = $this->post('type', 'a', 8);
                $name = $this->post('name', 's', 105, '-_|');
                return $this->resourceUpdateForm($type, $name);
            } else if ($function == 'updateresource') {
                $type = $this->post('type', 'a', 8);
                $name = $this->post('name', 's', 99, '-_');
                $filename = FILE . 'html/cache/' . $type . '/' . $name . '.' . $type;
                file_put_contents($filename, $_POST['resource']);
                echo ($name . '.' .$type . ' updated.'); return;
            } else {
                return false;
            }
        }
        $this->cmsView->edit();
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

    private function resourceSearch($name)
    {
        $resources = [];
        foreach (['html', 'css', 'js'] as $type) {
            $resources = array_merge($resources, scandir(FILE . 'html/cache/' . $type));
        }
        foreach ($resources as $resource) {
            if (substr($resource, 0, strlen($name)) == $name) {
                $results[] = ['id'=>0, 'name'=>$resource];
            }
        }
        if (isset($results)) {
            echo (json_encode($results));
        } else {
            echo (json_encode([['id'=>0, 'name'=>'No results found']]));
        }
    }

    private function resourceUpdateForm($type, $name)
    {
        $resource = new stdClass();
        $name = explode('|', $name);
        if (isset($name[1])) {
            $filename = FILE . 'html/cache/' . $type . '/' . $name[0] . '.' . $name[1];
            $resource->name = $name[0];
            $resource->type = $type;
        } else {
            echo ('No file found.'); return;
        }
        if (is_file($filename)) {
            $resource->resource = file_get_contents($filename);
        } else {
            echo ('No file found.'); return;
        }
        $this->cmsView->loadTemplate('cms/edit/update', $resource);
        return $this->cmsView->display(false);
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

}
?>

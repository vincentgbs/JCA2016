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
        $this->cmsView->body .= '<p class="row col-md-12">This CMS was designed only to create and manage simple html pages.</p>';
        return $this->cmsView->display();
    }

    public function preview()
    {
        //
    }

    public function pages()
    {
        if (isset($_POST['search'])) {
            return $this->pageSearch( strtolower($this->post('name', 'a', 99)) );
        } else if (isset($_POST['function'])) {
            switch ($this->post('function', 'a', 32)) {
                case 'create':
                    $page = (object)['page_name' => strtolower($this->post('name', 'a', 99))];
                    if ($this->cmsModel->createPage($page)) {
                        echo ($page->page_name . ' created.'); return;
                    } else {
                        exit('Error creating page.');
                    }
                break;
                case 'read':
                    $page = (object)['page_name' => strtolower($this->post('name', 'a', 99))];
                    $page = $this->cmsModel->readPage($page);
                    foreach ($page as $html) {
                        $this->cmsView->loadTemplate('cms/pages/update', $html);
                        $this->resourceUpdateForm('html', $html->html_template);
                    }
                    return $this->cmsView->display(false);
                break;
                case 'updateadd':
                    $template = new stdClass();
                    $template->page_id = $this->post('page_id', 'i');
                    $template->page_order = $this->post('page_order', 'i');
                    $template->html_template = $this->post('html_template', 'a');
                    if ($this->cmsModel->shiftOrder($template, '+1')) {
                        if ($this->cmsModel->addTemplate($template)) {
                            echo 'Template added to page.'; return;
                        } else { // undo shift
                            $this->cmsModel->shiftOrder($template, '-1');
                        }
                    } // else
                    exit('Error adding template to page.');
                break;
                case 'updateremove':
                    $template = new stdClass();
                    $template->page_id = $this->post('page_id', 'i');
                    $template->page_order = $this->post('page_order', 'i');
                    $template->html_template = $this->post('html_template', 'a');
                    if ($this->cmsModel->removeTemplate($template)) {
                        if ($this->cmsModel->shiftOrder($template, '-1')) {
                            echo 'Template removed from page.'; return;
                        } else {
                            $this->cmsModel->addTemplate($template);
                        }
                    } // else
                    exit('Error removing template from page.');
                    return;
                break;
                case 'delete':
                    $page = (object)['page_name' => strtolower($this->post('name', 'a', 99))];
                    if ($this->cmsModel->deletePage($page)) {
                        echo ($page->page_name . ' deleted.'); return;
                    } else {
                        exit('Error deleting page.');
                    }
                break;
                default: echo ('Invalid page function.'); return;
            }
        }
        $this->cmsView->pages();
    }

    public function edit()
    {
        if (isset($_POST['search'])) {
            return $this->resourceSearch( strtolower($this->post('name', 'a', 99)) );
        } else if (isset($_POST['function'])) {
            $type = strtolower($this->post('type', 'a', 8));
            $name = strtolower($this->post('name', 's', 105, '-_|'));
            switch ($this->post('function', 'a', 32)) {
                case 'getupdateform':
                    $this->resourceUpdateForm($type, $name);
                    return $this->cmsView->display(false);
                break;
                case 'updateresource':
                    $filename = FILE . 'html/cache/' . $type . '/' . $name . '.' . $type;
                    file_put_contents($filename, $_POST['resource']);
                    echo ($name . '.' .$type . ' updated.'); return;
                break;
                case 'deleteresource':
                    $filename = FILE . 'html/cache/' . $type . '/' . $name . '.' . $type;
                    if (is_file($filename)) { unlink($filename); }
                    echo ($name . '.' .$type . ' deleted.'); return;
                break;
                default: echo ('Invalid page function.'); return;
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
                    $name = strtolower($this->post('name', 'a') . '.' . $this->post('filetype', 'a'));
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
            echo (json_encode([['id'=>0, 'name'=>'No resources found']]));
        }
    }

    private function pageSearch($name)
    {
        $pages = $this->cmsModel->searchPages($name);
        foreach ($pages as $page) {
            $results[] = ['id'=>$page->page_id, 'name'=>$page->page_name];
        }
        if (isset($results)) {
            echo (json_encode($results));
        } else {
            echo (json_encode([['id'=>0, 'name'=>'No pages found']]));
        }
    }

    private function resourceUpdateForm($type, $name)
    {
        $resource = new stdClass();
        $name = explode('|', $name);
        $resource->type = $type;
        if (isset($name[1])) {
            $filename = FILE . 'html/cache/' . $type . '/' . $name[0] . '.' . $name[1];
            $resource->name = $name[0];
        } else if (isset($name[0]) && $name[0] != '') {
            $filename = FILE . 'html/cache/' . $type . '/' . $name[0] . '.' . $type;
            $resource->name = $name[0];
        } else {
            echo ('No file found.'); return;
        }
        if (is_file($filename)) {
            $resource->resource = file_get_contents($filename);
        } else {
            $resource->resource = '';
        }
        $this->cmsView->loadTemplate('cms/edit/update', $resource);
    }

    private function upload_file($ext, $types, $type)
    {
        if (array_key_exists($ext, $types)) {
            $upload = new simpleChunking($type);
            $ext = $types[$ext]; // match type
            $name = strtolower($this->post('name', 's', 99, '-_') . '.' . $ext);
            return $upload->upload($name);
        } else {
            exit('Invalid ' . $type . ' file.');
        }
    }

}
?>

<?php
require_once FILE . 'framework/controller/controller.php';

class jcaController extends controller {

    public function __construct()
    {
        parent::__construct();
        $this->getModel('jca');
        $this->getView('jca');
    }

    public function home()
    {
        if (isset($_GET['page'])) {
            $page = (object)['page_name' => strtolower($this->get('page', 'a', 99))];
        } else {
            $page = (object)['page_name' => JCADEFAULTPAGE];
        }
        $this->jcaView->simple($this->jcaModel->readPage($page));
    }

    // special pages
    public function index()
    {
        $this->bannerCheck();
        $templates['top'] = ['headtop', 'headindex', 'headbot', 'indextop'];
        $templates['loop'] = 'announcement';
        $templates['bottom'] = ['indexbot', 'footer'];
        $events = $this->jcaModel->readEvents();
        return $this->jcaView->oneloop($templates, $events);
    }

    // special pages
    public function events()
    {
        $templates['top'] = ['headtop', 'headevents', 'headbot', 'eventstop'];
        $templates['loop'] = 'event';
        $templates['bottom'] = ['eventsbot', 'footer'];
        $events = $this->jcaModel->readEvents();
        return $this->jcaView->events($events);
    }

    // special pages
    public function sermons()
    {
        $templates['top'] = ['headtop', 'headsermons', 'headbot', 'sermonstop'];
        $templates['loop'] = 'sermon';
        $templates['bottom'] = ['sermonsbot', 'footer'];
        $sermons = $this->jcaModel->readSermons();
        return $this->jcaView->sermons($sermons);
    }

    public function forms()
    {
        //
    }

    public function edit()
    {
        $this->getController('user');
        if (!$this->userController->check()) {
            $this->flashMessage('Please log in to access this page.');
            $this->redirect('user/login', false, URL);
        }
        if (isset($_POST['edit'])) {
            $form = $this->post('edit', 'a', 32);
            if (in_array($form, ['banner', 'events', 'sermons', 'forms'])) {
                $function = 'read' . ucfirst($form);
                $data = $this->jcaModel->$function();
                $this->jcaView->loadTemplate('jca/edit/' . $form, $data);
                return $this->jcaView->display(false);
            } else {
                exit('Invalid edit function.');
            }
        } else if (isset($_POST['function'])) {
            switch ($this->post('function', 'a', 32)) {
                case 'banner':
                    if (isset($_POST['delete'])) {
                        return $this->jcaModel->deleteBanner();
                    }
                    $banner = new stdClass();
                    $banner->banner_title = $this->post('banner_title', 'w', 99);
                    $banner->banner_body = $this->post('banner_body', NULL, 999);
                    $banner->commencement = $this->post('commencement', 's', 32, '-');
                    $banner->expiration = $this->post('expiration', 's', 32, '-');
                    if ($this->jcaModel->createBanner($banner)) {
                        echo ('title: ' . $banner->banner_title .
                            '<br>body: ' . $banner->banner_body); return;
                    } else {
                        exit('Error updating banner.');
                    }
                break;
                case 'events':
                    echo ('HERE'); return;
                break;
                case 'sermons':
                    echo ('HERE'); return;
                break;
                case 'forms':
                    echo ('HERE'); return;
                break;
                default: exit('Invalid edit function.');
            }
        }
        return $this->jcaView->edit();
    }

    private function bannerCheck()
    {
        $banner = $this->jcaModel->readBanner(true);
        if (isset($banner[0], $banner[0]->banner_title, $banner[0]->banner_body)) {
            if (is_file(FILE . 'html/cache/html/emergencybanner.html')) {
                $html = file_get_contents(FILE . 'html/cache/html/emergencybanner.html');
                $html = str_replace("{{{@banner_title}}}", $banner[0]->banner_title, $html);
                $html = str_replace("{{{@banner_body}}}", $banner[0]->banner_body, $html);
                $this->jcaView->body .= $html;
            } else {
                $this->jcaView->body .= '<p>' . $banner[0]->banner_title
                    . '<br>' . $banner[0]->banner_body . '</p>';
            }
            return true;
        } // else { return NULL; }
    }

}
?>

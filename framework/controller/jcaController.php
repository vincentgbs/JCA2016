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
                    echo ('HERE'); return;
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

}
?>

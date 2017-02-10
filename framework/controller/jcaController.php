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
            define('JCADEFAULTPAGE', 'imnew');
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
        $events = $this->jcaModel->readEvents();
        return $this->jcaView->events($events);
    }

    // special pages
    public function sermons()
    {
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
        } // else
        // banner
        // events
        // sermons
    }

}
?>

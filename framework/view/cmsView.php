<?php
require_once FILE . 'framework/view/view.php';

class cmsView extends view {

    public function __construct()
    {
        parent::__construct();
        $this->loadTemplate('cms/header', null, 'header');
        $this->loadTemplate('cms/footer', null, 'footer');
    }

    public function home()
    {
        $this->body .= 'DISPLAY ALL PAGES';
        $this->display();
    }

    public function edit()
    {
        $this->loadTemplate('cms/edit/head', null, 'header');
        $this->loadTemplate('cms/edit/foot', null, 'footer');
        $this->display();
    }

    public function upload()
    {
        $this->loadTemplate('cms/upload/head', null, 'header');
        $this->loadTemplate('cms/upload/foot', null, 'footer');
        $this->display();
    }

}
?>

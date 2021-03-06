<?php
require_once FILE . 'framework/view/view.php';

class cmsView extends view {

    public function __construct()
    {
        parent::__construct();
        $this->loadTemplate('cms/header', null, 'header');
        $this->loadTemplate('cms/footer', null, 'footer');
    }

    public function preview($pages)
    {
        $this->loadTemplate('cms/preview/head');
        foreach ($pages as $page) {
            $this->loadTemplate('cms/preview/page', $page);
        }
        $this->loadTemplate('cms/preview/foot');
        $this->display();
    }

    public function pages()
    {
        $this->loadTemplate('cms/pages/head', null, 'header');
        $this->loadTemplate('cms/pages/foot', null, 'footer');
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

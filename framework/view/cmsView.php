<?php
require_once FILE . 'framework/view/view.php';

class cmsView extends view {

    public function __construct()
    {
        parent::__construct();
        $this->loadTemplate('cms/header', null, 'header');
        $this->loadTemplate('cms/footer', null, 'footer');
    }

}
?>

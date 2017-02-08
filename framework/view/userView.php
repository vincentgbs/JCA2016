<?php
require_once FILE . 'framework/view/view.php';

class userView extends view {

    public function __construct()
    {
        parent::__construct();
        $this->loadTemplate('user/header', null, 'header');
        $this->loadTemplate('user/footer', null, 'footer');
    }

    public function home($links)
    {
        $this->loadTemplate('user/links/head');
        foreach ($links as $link) {
            $this->loadTemplate('user/links/link', $link);
        }
        $this->loadTemplate('user/links/foot');
        $this->loadTemplate('user/home');
        $this->display();
    }

    public function update()
    {
        $this->loadTemplate('user/update');
        $this->display();
    }

    public function register()
    {
        $this->loadTemplate('user/registration');
        $this->display();
    }

    public function login()
    {
        $this->loadTemplate('user/columns/head', 2);
        $this->loadTemplate('user/login');
        $this->loadTemplate('user/columns/middle', 2);
        $this->loadTemplate('user/apis/google/intro');
        $this->loadTemplate('user/columns/foot', 2);
        $this->display();
    }

    public function google($data)
    {
        $this->loadTemplate('user/apis/google/login', $data);
        $this->display(false);
    }

    public function request()
    {
        $this->loadTemplate('user/request');
        $this->display();
    }

    public function reset($user)
    {
        $this->loadTemplate('user/reset', $user);
        $this->display();
    }

    public function deactivate()
    {
        $this->loadTemplate('user/deactivate');
        $this->display();
    }

}
?>

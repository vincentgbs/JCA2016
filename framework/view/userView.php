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
        $this->loadTemplate('user/login');
        $this->display();
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



<?php
// public function getHomepage($user, $apps)
// {
//     $this->loadTemplate('user/apps', $apps);
//     $this->loadTemplate('user/update', $user);
//     $this->display();
// }
//
//
// public function googleLogin($data)
// {
//     $this->loadTemplate('user/google', $data);
//     $this->display(false);
// }
//
?>

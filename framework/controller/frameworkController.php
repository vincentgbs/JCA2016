<?php
require_once FILE . 'framework/controller/controller.php';

class frameworkController extends controller {

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            exit ('You must log in to access this page.');
        } else if ($_SERVER['PHP_AUTH_USER'] != '' || $_SERVER['PHP_AUTH_PW'] != '') {
            header('HTTP/1.0 401 Unauthorized');
            exit ('Invalid login credentials.');
        }
    }

    public function permissions()
    {
        echo 'HERE';
    }

}
?>

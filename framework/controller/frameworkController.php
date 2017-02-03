<?php
require_once FILE . 'framework/controller/controller.php';

class frameworkController extends controller {

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != ADMINU || $_SERVER['PHP_AUTH_PW'] != ADMINP) {
            header('WWW-Authenticate: Basic realm="Framework Controller"');
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

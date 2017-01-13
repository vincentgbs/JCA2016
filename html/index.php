<?php
if (is_file('../framework/config.php')) {
    include_once('../framework/config.php');
} else {
    exit('Missing configuration file.');
}

if (DEBUG == 'ON') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $time_start = microtime(true);
} else if (DEBUG == 'OFF') {
    error_reporting(E_ERROR);
}

if (isset($_GET['url']) && $_GET['url'] !== '') {
    $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
    $url = strtolower(preg_replace("/[^a-zA-Z0-9\/]/", '', $url));
} else {
    $url = HOME;
}
define('URL', $url);

$route = explode('/', $url);
if (isset($route[0]) && is_file(FILE . 'framework/controller/' . $route[0] . 'Controller.php')) {
    $controller = $route[0] . 'Controller';
    include_once FILE . 'framework/controller/' . $controller . '.php';
    if (class_exists($controller)) {
        $controller = new $controller();
    } else {
        exit('404 Not Found: Missing controller class');
    }
} else {
    exit('404 Not Found: Missing controller file');
}

if (isset($route[1]) && method_exists($controller, $route[1])) {
    $function = $route[1];
    $controller->$function();
} else {
    $controller->home();
}

if (DEBUG == 'ON' && LOG == 'ON') {
    $time_end = microtime(true);
    $execution_time = number_format($time_end - $time_start, 3);
    $log = '../framework/log.txt';
    $lines = count(file($log));
    if ($lines > 999) { $open = 'w'; }
    else { $open = 'a'; }
    $fp = fopen($log, $open);
    fwrite($fp, URL . ': ' . number_format(memory_get_peak_usage(1))
        . ' bytes and '. $execution_time . ' seconds'
        . (isset($_SESSION['USER'])?' by '.$_SESSION['USER']->username:NULL) . PHP_EOL);
    fclose($fp);
}
?>

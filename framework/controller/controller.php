<?php
require_once FILE . 'framework/application.php';
include_once FILE . 'framework/controller/userController.php';

abstract class controller extends application {

    public function __construct()
    {
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            exit('Could not initiate session (ini_set).');
        }
        $this->checkSession();
    }

    protected function checkSession($name='USER_SESSION_ID', $https=0, $path='/')
    {
        if (session_id() == '') {
            session_start();
            if (isset($_SESSION['CREATED'])) {
                if (HTTPS === 1 && !isset($_COOKIE['USER_SESSION_ID'])) {
                    exit('<h1>You are not an a secure (https) connection</h1>');
                }
                if (time() > $_SESSION['EXPIRE'] || // session has expired
                    $_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'] || // different browser
                    $_SESSION['USER_SESSION_ID'] != $_COOKIE['USER_SESSION_ID']) { // wrong session id
                    return $this->endSession();
                }
                if (time() - $_SESSION['CREATED'] > 600) {
                    $this->refreshSession();
                }
            } else { // start a new session
                return $this->startSession($name, $https);
            }
        }
    }

    protected function startSession($name, $https)
    {
        $_SESSION['CREATED'] = time();
        $_SESSION['EXPIRE'] = time() + (LOGINEXPIRE * 86400);
        $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['USER_SESSION_ID'] = bin2hex(random_bytes(32)); // random
        $domain = str_replace('https://', '', str_replace('http://', '', DOMAIN));
        return $this->setCookie($name, $_SESSION['USER_SESSION_ID']);
    }

    protected function refreshSession()
    {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }

    protected function endSession($name='USER_SESSION_ID')
    {
        $_SESSION = [];
        setcookie('PHPSESSID', '', 0, '/', '', HTTPS, 1);
        $this->setCookie($name, '', 0);
        return session_destroy();
    }

    protected function setCookie($name, $value='', $time=NULL, $https=NULL, $js=1)
    {
        if ($time === NULL) { $time = LOGINEXPIRE * 60 * 60 * 24; }
        if ($https === NULL) { $https = HTTPS; }
        if (HTTPS === 1 && (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'on')) {
            return;
        } // else (HTTPS is not required || HTTP isset && 'on')
        return setcookie($name, $value, (time() + $time), '/', '', $https, $js);
    }

    public function home() { echo 'Default homepage'; }

    public function get($var, $type=null, $len=null, $special=false)
    {
        if (!isset($_GET[$var])) { return; }
        if ($type == 'i') { // integer
            $text = filter_input(INPUT_GET, $var, FILTER_SANITIZE_NUMBER_INT);
            } elseif ($type == 'f') { // float
                $text = filter_input(INPUT_GET, $var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            } elseif ($type == 'l') { // letters
                $text = filter_input(INPUT_GET, $var, FILTER_SANITIZE_STRING);
                $text = preg_replace("/[^a-zA-Z]/", '', $text);
            } elseif ($type == 'a') { //alphanumeric
                $text = filter_input(INPUT_GET, $var, FILTER_SANITIZE_STRING);
                $text = preg_replace("/[^a-zA-Z0-9]/", '', $text);
            } elseif ($type == 's' && $special) { //alphanumeric and 'special characters'
                $text = filter_input(INPUT_GET, $var, FILTER_SANITIZE_SPECIAL_CHARS);
                if (stristr($special, '/')) { $special = str_replace('/', '\/', $special); }
                $text = preg_replace("/[^a-zA-Z0-9{$special}]/", '', $text);
            } elseif ($type == 'e') { // emails
                $text = filter_input(INPUT_GET, $var, FILTER_SANITIZE_EMAIL);
            } elseif ($type == 'w') { // words and spaces
                $text = filter_input(INPUT_GET, $var, FILTER_SANITIZE_STRING);
                $text = preg_replace("/[^\w\ ]/", '', $text);
            } elseif ($type == 'r') { // raw
                $text = filter_input(INPUT_GET, $var, FILTER_UNSAFE_RAW);
            } else {
                $text = filter_input(INPUT_GET, $var, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        if (isset($len) && $len < strlen($text)) {
            $text = substr($text, 0, $len);
        }
        return htmlspecialchars($text);
    }

    protected function csrfCheck($url=false)
    {
        $this->csrf_message = 'Csrf token mismatch. Please refresh your page and try again.';
        $csrf = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_STRING);
        $csrf = substr(preg_replace("/[^a-zA-Z0-9]/", '', $csrf), 0, 64);
        return ($csrf === $_SESSION['CSRF_TOKENS'][($url ? $url : URL)]);
    }

    public function post($var, $type=null, $len=null, $special=false)
    {
        if (!isset($_POST[$var])) { return; }
        if ($type == 'i') { // integer
            $text = filter_input(INPUT_POST, $var, FILTER_SANITIZE_NUMBER_INT);
            } elseif ($type == 'f') { // float
                $text = filter_input(INPUT_POST, $var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            } elseif ($type == 'l') { // letters
                $text = filter_input(INPUT_POST, $var, FILTER_SANITIZE_STRING);
                $text = preg_replace("/[^a-zA-Z]/", '', $text);
            } elseif ($type == 'a') { //alphanumeric
                $text = filter_input(INPUT_POST, $var, FILTER_SANITIZE_STRING);
                $text = preg_replace("/[^a-zA-Z0-9]/", '', $text);
            } elseif ($type == 's' && $special) { //alphanumeric and 'special characters'
                $text = filter_input(INPUT_POST, $var, FILTER_SANITIZE_STRING);
                if (stristr($special, '/')) { $special = str_replace('/', '\/', $special); }
                $text = preg_replace("/[^a-zA-Z0-9{$special}]/", '', $text);
            } elseif ($type == 'e') { // emails
                $text = filter_input(INPUT_POST, $var, FILTER_SANITIZE_EMAIL);
            } elseif ($type == 'w') { // words and spaces
                $text = filter_input(INPUT_POST, $var, FILTER_SANITIZE_STRING);
                $text = preg_replace("/[^\w\ ]/", '', $text);
            } elseif ($type == 'r') { // raw
                $text = filter_input(INPUT_POST, $var, FILTER_UNSAFE_RAW);
            } else {
                $text = filter_input(INPUT_POST, $var, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        if (isset($len) && $len < strlen($text)) {
            $text = substr($text, 0, $len);
        }
        return htmlspecialchars($text);
    }

    public function redirect($url, $goToLastPage=false, $setLastPage=false)
    {
        if ($setLastPage) { $_SESSION['last_page'] = $setLastPage; }
        if ($goToLastPage && isset($_SESSION['last_page'])) {
            $url = $_SESSION['last_page'];
            unset($_SESSION['last_page']);
        }
        if (URLTYPE == 'PRETTY') {
            header('Location: /' . $url);
        } else {
            header('Location: ?url=' . $url);
        }
        exit;
    }

    protected function flashMessage($message=false, $echo=true)
    {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            if ($echo) { $this->redirect($echo); }
        } else {
            $message = (isset($_SESSION['flash_message'])?$_SESSION['flash_message']:false);
            if ($message) {
                unset($_SESSION['flash_message']);
                if ($echo) { echo $message; }
            }
            return $message;
        }
    }

    protected function getSettings($category)
    {
        $model = $category . 'Model';
        if (isset($this->$model)) {
            $settings = $this->$model->getSettings($category);
            foreach ($settings as $setting) {
                $this->settings[$setting->setting_name] = $setting->setting_value;
            }
        }
    }

    public function simpleCurl($url, $post=false)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        $html = curl_exec($curl);
        curl_close($curl);
        return $html;
    }

}
?>

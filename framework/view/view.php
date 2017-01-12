<?php
require_once FILE . 'framework/application.php';

abstract class view extends application {

    public function __construct()
    {
        $this->file = FILE . 'framework/templates/';
        $this->header = '';
        $this->body = '';
        $this->footer = '';
    }

    public function loadTemplate($template, $data=null, $load='body', $return=false)
    {
        ob_start();
        require($this->file . $template . '.php');
        $html = ob_get_contents();
        ob_end_clean();
        if ($return) { return $html; }
        $this->$load .= $html;
    }

    public function display($all=true)
    {
        $_SESSION['CSRF_TOKENS'][URL] = bin2hex(random_bytes(32));
        echo ($all ? $this->header : '');
        echo str_replace('{{{@csrf_token}}}', $_SESSION['CSRF_TOKENS'][URL], $this->body);
        echo ($all ? $this->footer : '');
    }

    // public function database_decode($content)
    // {
    //     $content = html_entity_decode(htmlspecialchars_decode($content));
    //     $content = preg_replace("/(&#39;)/", "'", $content);
    //     return $content;
    // }

}
?>

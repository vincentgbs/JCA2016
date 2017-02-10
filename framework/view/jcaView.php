<?php
require_once FILE . 'framework/view/view.php';

class jcaView extends view {

    public function simple($page)
    {
        foreach ($page as $html) {
            if (is_file(FILE . 'html/cache/html/' . $html->html_template . '.html')) {
                $this->jcaView->body .= file_get_contents(FILE . 'html/cache/html/' . $html->html_template . '.html');
            }
        }
        return $this->display(false);
    }

    public function oneloop($templates, $data)
    {
        $base = FILE . 'html/cache/html/';
        foreach ($templates['top'] as $template) {
            if (is_file($base . $template . '.html')) {
                $this->body .= file_get_contents($base . $template . '.html');
            }
        }
        if (is_file($base . $templates['loop'] . '.html')) {
            foreach ($data as $datum) {
                $html = file_get_contents($base . $templates['loop'] . '.html');
                foreach ($datum as $key => $value) {
                    $html = str_replace("{{{@data:{$key}}}}", $value, $html);
                }
                $this->body .= $html;
            }
        }
        foreach ($templates['bottom'] as $template) {
            if (is_file($base . $template . '.html')) {
                $this->body .= file_get_contents($base . $template . '.html');
            }
        }
        $this->display();
    }

}
?>
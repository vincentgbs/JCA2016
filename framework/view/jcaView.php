<?php
require_once FILE . 'framework/view/view.php';

class jcaView extends view {

    private function bannerCheck($template)
    {
        if (isset($this->banner) && $template=='headbot') {
            $this->body .= $this->banner;
            unset($this->banner);
        }
    }

    public function simple($page)
    {
        foreach ($page as $template) {
            if (is_file(FILE . 'html/cache/html/' . $template->html_template . '.html')) {
                $html = file_get_contents(FILE . 'html/cache/html/' . $template->html_template . '.html');
                $html = str_replace("{{{@url}}}", DOMAIN, $html);
                $this->body .= $html;
            }
            $this->bannerCheck($template->html_template);
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
            $this->bannerCheck($template);
        }
        if (is_file($base . $templates['loop'] . '.html')) {
            foreach ($data as $datum) {
                $html = file_get_contents($base . $templates['loop'] . '.html');
                foreach ($datum as $key => $value) {
                    $html = str_replace("{{{@data:{$key}}}}",
                        html_entity_decode(html_entity_decode($value)), $html);
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

    public function edit()
    {
        $this->loadTemplate('jca/edit');
        $this->display();
    }

}
?>

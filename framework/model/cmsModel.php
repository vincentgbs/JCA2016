<?php
require_once FILE . 'framework/model/model.php';

class cmsModel extends model {

    public function searchPages($name)
    {
        $q = "SELECT * FROM `cms_ls_pages` WHERE `page_name` LIKE '{$name}%';";
        return $this->select($q);
    }

    public function createPage($page)
    {
        $q = "INSERT INTO `cms_ls_pages` (`page_name`)
            VALUES ({$this->wrap($page->page_name)});";
        return $this->execute($q);
    }

    public function readPage($page)
    {
        $q = 'SELECT * FROM `cms_rel_pages` AS `rel`
        JOIN `cms_ls_pages` AS `ls` ON `rel`.`page_id`=`ls`.`page_id`
        WHERE ';
        foreach ($page as $key => $value) {
            $q .= "{$key} = {$this->wrap($value)} AND";
        }
        $q = substr($q, 0, -4) . ' ORDER BY `page_order` ASC;';
        return $this->select($q);
    }

    public function deletePage($page)
    {
        $q = 'DELETE FROM `cms_ls_pages` WHERE ';
        foreach ($page as $key => $value) {
            $q .= "{$key} = {$this->wrap($value)} AND";
        }
        $q = substr($q, 0, -4) . ';';
        return $this->execute($q);
    }

    public function shiftOrder($template, $direction)
    {
        $q = "UPDATE `cms_rel_pages` SET `page_order`=(`page_order`{$direction})
            WHERE `page_id`=$template->page_id AND `page_order`>={$template->page_order}";
        if ($direction == '+1') { $q .= ' ORDER BY `page_order` DESC;'; }
        return $this->execute($q);
    }

    public function addTemplate($template)
    {
        $q = "INSERT INTO `cms_rel_pages` (`page_id`, `html_template`, `page_order`)
            VALUES ({$template->page_id},
                {$this->wrap($template->html_template)},
                {$template->page_order});";
        return $this->execute($q);
    }

    public function removeTemplate($template)
    {
        $q = "DELETE FROM `cms_rel_pages`
            WHERE `page_id`=$template->page_id AND
                `page_order`=$template->page_order;";
        return $this->execute($q);
    }

}
?>

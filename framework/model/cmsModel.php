<?php
require_once FILE . 'framework/model/model.php';

class cmsModel extends model {

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
            $q .= "{$key} = {$value} AND";
        }
        $q = substr($q, 0, -4) . 'ORDER BY `page_order` ASC';
        return $this->select($q);
    }

    public function updatePage()
    {
        //
    }

    public function deletePage()
    {
        //
    }

}
?>

<?php
require_once FILE . 'framework/model/model.php';

class cmsModel extends model {

    public function createResource($resource)
    {
        $q = "INSERT INTO `cms_ls_{$resource->type}` (`{$resource->type}_name`,
                `{$resource->type}`, `cms_data`)
            VALUES ({$this->wrap($resource->name)},
                {$this->wrap($resource->resource)},
                {$this->wrap($resource->data)})
            ON DUPLICATE KEY UPDATE
                `{$resource->type}`={$this->wrap($resource->resource)},
                `cms_data`={$this->wrap($resource->data)};";
        return $this->execute($q);
    }

    public function readResource($resource)
    {
        $q = "";
    }

    public function updateResource($resource)
    {
        //
    }

    public function deleteResource($resource)
    {
        //
    }

}
?>

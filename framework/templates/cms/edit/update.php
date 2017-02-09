<div class="row col-md-12 update_resource_container">
    <input type="text" class="update_resource_name" value="<?php echo $data->name; ?>" disabled>
    <input type="text" class="update_resource_type" value="<?php echo $data->type; ?>" disabled>
    <textarea class="col-md-12 update_resource" rows="9"><?php echo $data->resource; ?></textarea>
    <button class="btn btn-default update_resource_button">update</button>
    <button class="btn btn-warning delete_resource_button">delete</button>
</div>

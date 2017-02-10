<div class="row col-md-12 update_page_container">
    Insert template here: <input type="text" class="add_html_template" placeholder="template">
    <input type="hidden" class="page_name" value="<?php echo $data->page_name; ?>" disabled>
    <input type="hidden" class="page_order" value="<?php echo $data->page_order; ?>" disabled>
    <input type="hidden" class="page_id" value="<?php echo $data->page_id; ?>" disabled>
    <input type="hidden" class="remove_html_template" value="<?php echo $data->html_template; ?>" disabled>
    <button class="btn btn-default insert_template_button">Insert</button>
    <?php if(isset($data->html_template)) { ?>
        <button class="btn btn-warning remove_template_button" title="Remove the template below">Remove (<?php echo $data->html_template; ?>)</button>
     <?php } ?>
</div>

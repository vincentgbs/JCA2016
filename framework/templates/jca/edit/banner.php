<h3>BANNER</h3>
<style>
#banner_title, #banner_body {
    width: 100%;
}
</style>

<div class="row col-md-12">
    <div class="col-md-6">
        <br>Start: <input type="text" value="<?php echo date("Y-m-d"); ?>" id="commencement">
        <br>End: <input type="text" value="<?php echo date('Y-m-d', strtotime("+1 week")); ?>" id="expiration">
        <br>Title: <input type="text" id="banner_title" maxlength="99">
        <br>Body: <textarea id="banner_body" maxlength="999"></textarea>
        <br><button class="btn btn-default" id="update_banner_button">Update</button>
        <button class="btn btn-warning" id="delete_banner_button">Remove</button>
    </div> <!-- </div class="col-md-6"> -->
    <div class="col-md-6">
        <pre id="banner_container"><?php
            if (isset($data[0])) {
                echo 'title: ' . (isset($data[0]->banner_title)?$data[0]->banner_title:NULL);
                echo '<br>body: ' . (isset($data[0]->banner_body)?$data[0]->banner_body:NULL);
                echo '<br>start: ' . (isset($data[0]->commencement)?$data[0]->commencement:NULL);
                echo '<br>end: ' . (isset($data[0]->expiration)?$data[0]->expiration:NULL);
            }
        ?></pre>
    </div> <!-- </div class="col-md-6"> -->
</div> <!-- <div class="row col-md-12"> -->

<script>
$(document).ready(function(){
    $("#update_banner_button").on('click', function(){
        var commencement = $("#commencement").val();
        var expiration = $("#expiration").val();
        var banner_title = $("#banner_title").val();
        var banner_body = $("#banner_body").val();
        if (banner_title == '' || banner_body == '') {
            return flashMessage('Missing banner title and/or body');
        }
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'banner',
                banner_title: banner_title,
                commencement: commencement,
                expiration: expiration,
                banner_body: banner_body
            },
            success: function(response) {
                $("#banner_container").html(response);
            }
        }); // ajax
    });

    $(document).on('click', ".btn-danger#delete_banner_button", function(){
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'banner',
                delete: 0
            },
            success: function(response) {
                $("#banner_container").html(response);
            }
        }); // ajax
    });
});
</script>

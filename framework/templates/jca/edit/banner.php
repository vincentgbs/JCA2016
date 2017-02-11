<h3>BANNER</h3>

<div class="row col-md-12">
    <div class="col-md-6">
        <br>Title: <input type="text" id="banner_title">
        <br>Start: <input type="text" value="<?php echo date("Y-m-d"); ?>" id="commencement">
        <br>End: <input type="text" value="<?php echo date('Y-m-d', strtotime("+1 week")); ?>" id="expiration">
        <br>Body: <textarea id="banner_body"></textarea>
        <br><button class="btn btn-default" id="update_banner_button">Update</button>
    </div> <!-- </div class="col-md-6"> -->
    <div class="col-md-6">
        <pre id="banner_container"><?php var_dump($data); ?></pre>
    </div> <!-- </div class="col-md-6"> -->
</div> <!-- <div class="row col-md-12"> -->

<script>
$(document).ready(function(){
    $("#update_banner_button").on('click', function(){
        var banner_title = $("#banner_title").val();
        var commencement = $("#commencement").val();
        var expiration = $("#expiration").val();
        var banner_body = $("#banner_body").val();
        console.debug(banner_title, commencement, expiration, banner_body);
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'banner'
            },
            success: function(response) {
                $("#banner_container").html(response);
            }
        }); // ajax
    });
});
</script>

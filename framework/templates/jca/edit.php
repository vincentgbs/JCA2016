<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Content Managment System</title>

    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN; ?>/css/library/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN; ?>/css/library/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN; ?>/css/library/1.10.12.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN; ?>/css/style.css">
    <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/1.10.12.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/vanilla.js"></script>
</head>

<body class="container">
    <div class="noscript_lightbox">
        <div class="text-center bg-primary lead">Your browser does not support JavaScript!</div>
    </div>
    <div id="flash_message"></div>

    <div class="row col-md-12">
        <button class="btn btn-info edit_form_button" edit="banner">Edit Banner</button>
        <button class="btn btn-info edit_form_button" edit="events">Edit Events</button>
        <button class="btn btn-info edit_form_button" edit="sermons">Edit Sermons</button>
        <button class="btn btn-info edit_form_button" edit="forms">Edit Forms</button>
        <a href="/cms/pages"><button class="btn btn-default">Edit Pages (cms) </button></a>
        <a href="/cms/edit"><button class="btn btn-default">Edit Templates (cms) </button></a>
        <a href="/cms/upload"><button class="btn btn-default">Upload Files (cms) </button></a>
        <a href="/user/home"><button class="btn btn-default">User Pages (user) </button></a>
    </div> <!-- </div class="row col-md-12"> -->
<hr class="row col-md-12">
    <div class="row col-md-12" id="edit_container">
    </div> <!-- </div class="row col-md-12"> -->
</body> <!-- </body class="container"> -->
<script>
$(document).ready(function(){
    $(".edit_form_button").on('click', function(){
        var edit = $(this).attr('edit');
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                edit: edit
            },
            success: function(response) {
                $("#edit_container").html(response);
            }
        }); // ajax
    });
});
</script>
</html> <!-- </html lang="en"> -->

<h3>BANNER</h3>

<br>Title: <input type="text" id="banner_title">
<br>Start: <input type="text" value="<?php echo date("Y-m-d"); ?>" id="banner_commencement">
<br>End: <input type="text" value="<?php echo date('Y-m-d', strtotime("+1 week")); ?>" id="banner_expiration">
<br>Body: <textarea id="banner_body"></textarea>

<script>
$(document).ready(function(){
    //
});
</script>

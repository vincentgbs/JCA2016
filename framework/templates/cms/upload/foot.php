<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/simpleChunking.js"></script>
<script>
$(document).ready(function(){
    $("#sendRequest").on('click', function(){
        var name = $("#filename").val();
        sendRequest('?url=cms/upload', {'name': name});
    });

    $("#uploadCanceled").on('click', function(){
        uploadCanceled();
    });

    $("#fileToUpload").on('change', function(){
        fileSelected();
    });
});
</script>

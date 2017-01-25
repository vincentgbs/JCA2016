<form enctype="multipart/form-data" method="post">
    <div>
        <label for="filename">Name:</label> <input type="text" id="filename">
    </div>
    <div>
        <label for="fileToUpload">Select a File to Upload</label><br />
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="fileSelected();"/>
    </div>
</form>
<div id="fileName"></div><div id="fileSize"></div><div id="fileType"></div>
<button class="btn btn-default" onClick="uploadCanceled();">Cancel</button>
<button class="btn btn-default" id="sendRequest">Upload</button>
<div id="progressNumber"></div>
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/simpleChunking.js"></script>
<script>
$(document).ready(function(){
    $("#sendRequest").on('click', function(){
        var name = $("#filename").val();
        sendRequest('?url=rept/test', name);
    });
});
</script>

<form enctype="multipart/form-data" method="post">
    <div class="row">
        <label for="fileToUpload">Select a File to Upload</label><br />
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="fileSelected();"/>
    </div>
</form>
<!-- <div id="fileName"></div><div id="fileSize"></div><div id="fileType"></div> -->
<button class="btn btn-default" onClick="uploadCanceled();">Cancel</button>
<button class="btn btn-default" onclick="sendRequest('?url=rept/test');">Upload</button>
<!-- <div id="progressNumber"></div> -->
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/simpleChunking.js"></script>

<form id="form1" enctype="multipart/form-data" method="post" action="?url=rept/test">
    <div class="row">
        <label for="fileToUpload">Select a File to Upload</label><br />
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="fileSelected();"/>
        <input type="button" value="cancel"  onClick="uploadCanceled();"/>
    </div>
    <div id="fileName"></div>
    <div id="fileSize"></div>
    <div id="fileType"></div>
    <div class="row">
        <input type="button" onclick="sendRequest();" value="Upload" />
    </div>
    <div id="progressNumber"></div>
</form>

<script type="text/javascript">
    (window.BlobBuilder = window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder);
    function sendRequest() {
        var blob = document.getElementById('fileToUpload').files[0];
        const BYTES_PER_CHUNK = 1048576; // 1MB chunk sizes.
        const SIZE = blob.size;
        var start = 0;
        var end = BYTES_PER_CHUNK;
        while( start < SIZE ) {
            var chunk = blob.slice(start, end);
            if (!chunk) { alert('Your browser does not support uploading.'); return; }
            uploadFile(chunk);
            start = end;
            end = start + BYTES_PER_CHUNK;
        }
    }

    function fileSelected() {
        var file = document.getElementById('fileToUpload').files[0];
        if (file) {
            var fileSize = 0;
            if (file.size > 1024 * 1024)
                fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
            else
                fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
            document.getElementById('fileName').innerHTML = 'Name: ' + file.name;
            document.getElementById('fileSize').innerHTML = 'Size: ' + fileSize;
            document.getElementById('fileType').innerHTML = 'Type: ' + file.type;
        }
    }

    function uploadFile(blobFile) {
        //var file = document.getElementById('fileToUpload').files[0];
        var fd = new FormData();
        fd.append("fileToUpload", blobFile);

        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", uploadProgress, false);
        xhr.addEventListener("load", uploadComplete, false);
        xhr.addEventListener("error", uploadFailed, false);
        xhr.addEventListener("abort", uploadCanceled, false);
        xhr.open("POST", "?url=rept/test");
        xhr.onload = function(e) {
            console.debug("loaded!");
          };
        xhr.send(fd);
    }

    function uploadProgress(e) {
        if (e.lengthComputable) {
            var percentComplete = Math.round(e.loaded * 100 / e.total);
            document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
        }
        else {
            document.getElementById('progressNumber').innerHTML = 'unable to compute';
        }
    }

    function uploadComplete(e) {
        console.debug(e.target.responseText);
    }

    function uploadFailed(e) {
        alert('There was an error attempting to upload the file.');
    }

    function uploadCanceled(e) {
        xhr.abort();
        console.debug('The upload has been canceled by the user or the browser dropped the connection.');
    }
</script>

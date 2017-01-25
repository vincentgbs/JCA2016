(window.BlobBuilder = window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder);
function sendRequest(url, fileId='fileToUpload', bpc=1048576)
{
    var blob = document.getElementById(fileId).files[0];
    if (typeof blob == 'undefined') { alert('No file was selected'); return; }
    const BYTES_PER_CHUNK = bpc;
    const SIZE = blob.size;
    var start = 0;
    var end = BYTES_PER_CHUNK;
    var count = 0;
    while( start < SIZE ) {
        var chunk = blob.slice(start, end);
        if (!chunk) { alert('Your browser does not support uploading.'); return; }
        uploadFile(url, fileId, chunk, count, SIZE, BYTES_PER_CHUNK);
        start = end;
        end = start + BYTES_PER_CHUNK;
        count += 1;
    }
}

function fileSelected()
{
    var file = document.getElementById('fileToUpload').files[0];
    if (file) {
        var fileSize = 0;
        if (file.size > 1024 * 1024)
            fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
        else
            fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
        if (document.getElementById('fileName')) {
            document.getElementById('fileName').innerHTML = 'Name: ' + file.name;
        }
        if (document.getElementById('fileSize')) {
            document.getElementById('fileSize').innerHTML = 'Size: ' + fileSize;
        }
        if (document.getElementById('fileType')) {
            document.getElementById('fileType').innerHTML = 'Type: ' + file.type;
        }
    }
}

function uploadFile(url, name, blobFile, count, size, chunk)
{
    var fd = new FormData();
    fd.append(name, blobFile); // _FILES
    fd.append('count', count); // _POST
    fd.append('size', size); // _POST
    fd.append('chunk', chunk); // _POST

    var xhr = new XMLHttpRequest();
    xhr.upload.addEventListener("progress", uploadProgress, false);
    xhr.addEventListener("load", uploadComplete, false);
    xhr.addEventListener("error", uploadFailed, false);
    xhr.addEventListener("abort", uploadCanceled, false);
    xhr.open("POST", url);
    xhr.onload = function(e) { console.debug(count + ' loaded.'); };
    xhr.send(fd);
}

function uploadProgress(e)
{
    if (document.getElementById('progressNumber')) {
        if (e.lengthComputable) {
            var percentComplete = Math.round(e.loaded * 100 / e.total);
            document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
        }
        else {
            document.getElementById('progressNumber').innerHTML = 'Unable to compute.';
        }
    }
}

function uploadComplete(e)
{
    var response = e.target.responseText.trim();
    if (response == 'Upload complete.' || response == '') {
        console.debug(response);
    } else {
        alert(response); // any other error message
    }
}

function uploadFailed() { alert('There was an error attempting to upload this file.'); }

function uploadCanceled()
{
    if (typeof xhr != 'undefined') {
        xhr.abort();
    }
    alert('The upload has been canceled by the user or the browser dropped the connection.');
}

(window.BlobBuilder = window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder);
function sendRequest(url, post=false, fileId='fileToUpload', bpc=1048576)
{
    var blob = document.getElementById(fileId).files[0];
    if (typeof blob == 'undefined') { alert('No file was selected'); return; }
    const SIZE = blob.size;
    var start = 0;
    var count = 0;
    while (start < SIZE) {
        var blobFile = blob.slice(start, start+bpc);
        if (!blobFile) { alert('Your browser does not support uploading.'); return; }
        uploadFile(url, post, fileId, bpc, blobFile, count, SIZE);
        start += bpc;
        count += 1;
    }
}

function uploadFile(url, post, fileId, bytes_per_chunk, blobFile, count, total_size)
{
    var fd = new FormData();
    fd.append(fileId, blobFile); // _FILES
    fd.append('count', count); // _POST
    fd.append('size', total_size); // _POST
    fd.append('chunk', bytes_per_chunk); // _POST
    if (post) {
        for (var key in post) {
            fd.append(key, post[key]); // _POST
        }
    }

    var xhr = new XMLHttpRequest();
    xhr.upload.addEventListener("progress", uploadProgress, false);
    xhr.addEventListener("load", uploadComplete, false);
    xhr.addEventListener("error", uploadFailed, false);
    xhr.addEventListener("abort", uploadCanceled, false);
    xhr.open("POST", url);
    xhr.onload = function() { console.debug(count + ' loaded.'); };
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
            document.getElementById('progressNumber').innerHTML = '';
        }
    }
}

function uploadComplete(e)
{
    var response = e.target.responseText.trim();
    if (response != '' ) {
        alert(response); // any message
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



function fileSelected(displayFileName='fileName', displayFileSize='fileSize', displayFileType='fileType')
{
    var file = document.getElementById('fileToUpload').files[0];
    if (file) {
        if (file.size == 0) {
            alert('You cannot upload an empty file.'); return;
        } else if (file.size > 1024 * 1024) {
            var fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
        } else {
            var fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
        }
        if (document.getElementById(displayFileName)) {
            document.getElementById(displayFileName).innerHTML = 'Name: ' + file.name;
        }
        if (document.getElementById(displayFileSize)) {
            document.getElementById(displayFileSize).innerHTML = 'Size: ' + fileSize;
        }
        if (document.getElementById(displayFileType)) {
            document.getElementById(displayFileType).innerHTML = 'Type: ' + file.type;
        }
    }
}

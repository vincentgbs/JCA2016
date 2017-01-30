<div class="row col-md-12" id="html_upload_container">
    <h3>Html</h3>
    <input type="hidden" id="type" value="html">
    <form enctype="multipart/form-data" method="post">
        <div>
            <label for="filename">Rename html:</label> <input type="text" id="filename" placeholder="optional" maxlength="99">
        </div>
        <div>
            <label for="fileToUpload">Select a File to Upload</label><br />
            <input type="file" name="fileToUpload" id="fileToUpload">
        </div>
    </form>
    <div id="fileName"></div><div id="fileSize"></div><div id="fileType"></div>
    <button class="btn btn-default" id="uploadCanceled">Cancel</button>
    <button class="btn btn-default" id="sendRequest">Upload</button>
    <div id="progressNumber"></div>
</div><!-- </div class="row col-md-12" id="html_upload_container"> -->
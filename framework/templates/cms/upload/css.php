<div class="row col-md-12" id="css_upload_container">
    <h3>Css</h3>
    <input type="hidden" id="type" value="css">
    <form enctype="multipart/form-data" method="post">
        <div>
            <label for="filename">Rename css:</label> <input type="text" id="filename" placeholder="optional" maxlength="99">
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
</div><!-- </div class="row col-md-12" id="css_upload_container"> -->

<div class="row col-md-12" id="directory_upload_container">
    <h3>Upload folder</h3>
    <p>To upload a folder, each individual file must be less than 5mb.</p>
    <input type='file' webkitdirectory id="upload_directory">
    <button class="btn btn-default" id="upload_directory_button">Upload</button>
</div><!-- </div class="row col-md-12" id="directory_upload_container"> -->

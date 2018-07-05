<p>
    Upload locations: <br>
    <ul>
        <li>&lt;link rel="stylesheet" type="text/css" href="/cache/css/style.css"&gt;</li>
        <li>&lt;script type="text/javascript" src="/cache/js/vanilla.js"&gt;&lt;/script&gt;</li>
        <li>&lt;img src="/cache/img/example.png"&gt;</li>
        <li>&lt;audio controls&gt;&lt;source src="/cache/aud/example.mp3" type="audio/mpeg"&gt;&lt;/audio&gt;</li>
    </ul>
    Html, css and js uploads can be edited from the "Edit Templates" page. <br>
    Image and audio files do not always upload consistently on a slow server. <br>
    You may have to reupload your image or audio if there is an error.<br>
    <br>
    Renaming a file on upload is optional. Filenames are restricted to alphanumerics and underscores. <br>
    <br>
    <a href="/cms/listuploads">view uploaded files</a>
</p>
<hr class="row col-md-12">
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/simpleChunking.js"></script>

<button class="btn btn-default get_upload_button" load="upload_html">Html</button>
<div class="row col-md-12 upload_form_div" id="upload_html">
</div> <!-- </div class="row col-md-12 upload_form_div" id="upload_html"> -->
<button class="btn btn-default get_upload_button" load="upload_css">Css</button>
<div class="row col-md-12 upload_form_div" id="upload_css">
</div> <!-- </div class="row col-md-12 upload_form_div" id="upload_css"> -->
<button class="btn btn-default get_upload_button" load="upload_js">Js</button>
<div class="row col-md-12 upload_form_div" id="upload_js">
</div> <!-- </div class="row col-md-12 upload_form_div" id="upload_js"> -->
<button class="btn btn-default get_upload_button" load="upload_img">Img</button>
<div class="row col-md-12 upload_form_div" id="upload_img">
</div> <!-- </div class="row col-md-12 upload_form_div" id="upload_img"> -->
<button class="btn btn-default get_upload_button" load="upload_aud">Aud</button>
<div class="row col-md-12 upload_form_div" id="upload_aud">
</div> <!-- </div class="row col-md-12 upload_form_div" id="upload_aud"> -->
<button class="btn btn-default get_upload_button" load="upload_file">File</button>
<div class="row col-md-12 upload_form_div" id="upload_file">
</div> <!-- </div class="row col-md-12 upload_form_div" id="upload_file"> -->

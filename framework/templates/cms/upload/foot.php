<script>
$(document).ready(function(){
    $(document).keyup(function(){
        limitInput($('#filename'), 'words');
    });

    $(".get_upload_button").on('click', function(){
        var id = $(this).attr('load');
        var type = id.replace('upload_', '');
        $.each($(".upload_form_div"), function(index, value) {
            value.innerHTML = ''; // clear all divs
        }); // each
        $.ajax({
            url: "?url=cms/upload",
            type: "POST",
            data: {
                upload_form: type
            },
            success: function(response) {
                $("#" + id).html(response);
            } // success
        }); // ajax
    });

    $(document).on('click', "#sendRequest", function(){
        var params = {};
        params['name'] = $("#filename").val();
        params['type'] = document.getElementById('type').value;
        if (document.getElementById('fileToUpload').files[0]) {
            params['filetype'] = document.getElementById('fileToUpload').files[0].type;
        }
        sendRequest('?url=cms/upload', params);
    });

    $(document).on('click', "#uploadCanceled", function(){
        uploadCanceled();
    });

    $(document).on('change', "#fileToUpload", function(){
        fileSelected();
    });
});
</script>

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
        var name = $("#filename").val();
        var type = $("#file").val();
        var filetype = $("#fileType").text();
        sendRequest('?url=cms/upload', {'name': name, 'type': type, 'filetype': filetype});
    });

    $(document).on('click', "#uploadCanceled", function(){
        uploadCanceled();
    });

    $(document).on('change', "#fileToUpload", function(){
        fileSelected();
    });
});
</script>

<script>
$(document).ready(function(){
    $(document).keyup(function(){
        if (typeof $('#filename') != 'undefined') {
            limitInput($('#filename'), 'words');
        }
    });

    $(".get_upload_button").on('click', function(){
        var id = $(this).attr('load');
        var type = id.replace('upload_', '');
        $.ajax({
            url: "?url=cms/upload",
            type: "POST",
            data: {
                upload_form: type
            },
            success: function(response) {
                $.each($(".upload_form_div"), function(index, value) {
                    value.innerHTML = ''; // clear all divs
                }); // each
                $("#" + id).html(response);
            } // success
        }); // ajax
    });

    $(document).on('click', "#sendRequest", function(){
        var name = $("#filename").val();
        var type = $("#type").val();
        sendRequest('?url=cms/upload', {'name': name, 'type': type});
    });

    $(document).on('click', "#uploadCanceled", function(){
        uploadCanceled();
    });

    $(document).on('change', "#fileToUpload", function(){
        fileSelected();
    });
});
</script>

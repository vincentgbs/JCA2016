<script>
$(document).ready(function(){
    $(".get_edit_button").on('click', function(){
        var id = $(this).attr('load');
        var type = id.replace('edit_', '');
        $.each($(".edit_form_div"), function(index, value) {
            value.innerHTML = ''; // clear all divs
        }); // each
        $.ajax({
            url: "?url=cms/edit",
            type: "POST",
            data: {
                edit_form: type
            },
            success: function(response) {
                $("#" + id).html(response);
            } // success
        }); // ajax
    });

});
</script>

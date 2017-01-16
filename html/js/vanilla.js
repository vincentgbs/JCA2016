function flashMessage(message, time = 999, color = ['09F', '000'])
{
    $("#flash_message").attr('style', 'background-color:#' + color[0] + ';color:#' + color[1]);
    $("#flash_message").html(message);
    setTimeout(
        function() {
            $("#flash_message").attr('style', '');
            $("#flash_message").html('');
        }, // function
        time
    ); // setTimeout
}
function limitInput(input, limit)
{
    if (limit == 'numbers') {
        $(input).val( $(input).val().replace(/\D/g, '') );
    } else if (limit == 'letters') {
        $(input).val( $(input).val().replace(/[^A-z]/g, '') );
        $(input).val( $(input).val().replace(/\^/g, '') );
    } else if (limit == 'alphanumeric') {
        $(input).val( $(input).val().replace(/[^A-z0-9]/g, '') );
        $(input).val( $(input).val().replace(/\^/g, '') );
    } else if (limit == 'words') { // includes underscores
        $(input).val( $(input).val().replace(/\W/g, '') );
    } else {
        $(input).val( $(input).val().replace(limit, '') );
        // $(input).val( $(input).val().replace(/\^/g, '') );
    }
}

$(document).ready(function() {
    $(".noscript_lightbox").attr('style', 'display:none');

    $(document).on('click', ".btn-warning", function(e){
        var button = $(this);
        var text = button.text();
        button.addClass('btn-danger');
        button.removeClass('btn-warning');
        button.text('Are you sure?');
        setTimeout(
            function() {
                button.addClass('btn-warning');
                button.removeClass('btn-danger');
                button.text(text);
            }, // function
            2499
        ); // setTimeout
        e.preventDefault();
    });
});

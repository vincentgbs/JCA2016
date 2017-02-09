</div> <!-- </div id="resource_container"> -->

<script>
function autocompleteNameAndId(input, url, display_id=false)
{
    $(input).autocomplete({
        // minLength: 3,
        source: function( request, response ) {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    search: 0,
                    name: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        }, // source
        focus: function( event, ui ) {
            return false;
        }, // focus
        select: function( event, ui ) {
            var id = $(this).attr('matching_id');
            if (id) { $('#'+id).val( ui.item.id ); }
            $(this).val( ui.item.name );
            return false;
        } // select
    }).each(function(index, element) {
        $(element).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $( "<li>" )
                .append( "<a>" + ((display_id) ? item.id + '. ' : '') + item.name + "</a>" )
                .appendTo( ul );
        };
});
}
$(document).ready(function(){
    $("#resource_name").keyup(function(e){
        autocompleteNameAndId(this, '?url=cms/edit');
    });

    $(".get_resource_button").on('click', function(){
        var type = $(this).attr('type');
        var name = $("#resource_name").val();
        $.ajax({
            url: "?url=cms/edit",
            type: "POST",
            data: {
                function: 'get_update_form',
                type: type,
                name: name.replace('.', '|')
            }, // data
            success: function(response) {
                $("#resource_container").html(response);
            } // success
        }); // ajax
    });

    $(document).on('click', ".update_resource_button", function(){
        var container = $(this).closest('.update_resource_container');
        var name = container.find('.update_resource_name').val();
        var type = container.find('.update_resource_type').val();
        var resource = container.find('.update_resource').val();
        $.ajax({
            url: "?url=cms/edit",
            type: "POST",
            data: {
                function: 'update_resource',
                type: type,
                name: name,
                resource: resource
            }, // data
            success: function(response) {
                flashMessage(response);
            } // success
        }); // ajax
    });

    $(document).on('click', ".btn-danger.delete_resource_button", function(){
        var container = $(this).closest('.update_resource_container');
        var name = container.find('.update_resource_name').val();
        var type = container.find('.update_resource_type').val();
        $.ajax({
            url: "?url=cms/edit",
            type: "POST",
            data: {
                function: 'delete_resource',
                type: type,
                name: name
            }, // data
            success: function(response) {
                flashMessage(response);
                if (response.trim().slice(-8) == 'deleted.') {
                    container.hide();
                }
            } // success
        }); // ajax
    });
});
</script>

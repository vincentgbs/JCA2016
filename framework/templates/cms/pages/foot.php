</div> <!-- </div id="page_container"> -->

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
    $("#page_name").keyup(function(e){
        autocompleteNameAndId(this, '?url=cms/pages');
    });

});
</script>

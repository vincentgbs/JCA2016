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
function getPage(name) {
    $.ajax({
        url: "?url=cms/pages",
        type: "POST",
        data: {
            function: 'read',
            name: name
        }, // data
        success: function(response) {
            $("#update_page_container").html(response);
            $(".delete_resource_button").hide();
        } // success
    }); // ajax
}
$(document).ready(function(){
    $("#page_name").keyup(function(e){
        limitInput(this, 'alphanumeric');
        autocompleteNameAndId(this, '?url=cms/pages');
    });

    $("#create_page_button").on('click', function(){
        var name = $("#page_name").val();
        if (name == '') { return flashMessage('No page name entered'); }
        $.ajax({
            url: "?url=cms/pages",
            type: "POST",
            data: {
                function: 'create',
                name: name
            }, // data
            success: function(response) {
                flashMessage(response);
                if (response.trim().slice(-8) == 'created.') {
                    getPage(name);
                }
            } // success
        }); // ajax
    });

    $("#read_page_button").on('click', function(){
        var name = $("#page_name").val();
        if (name == '') { return flashMessage('No page name entered'); }
        return getPage(name);
    });

    $(document).on('click', ".btn-danger#delete_page_button", function(){
        var name = $("#page_name").val();
        if (name == '') { return flashMessage('No page name entered'); }
        $.ajax({
            url: "?url=cms/pages",
            type: "POST",
            data: {
                function: 'delete',
                name: name
            }, // data
            success: function(response) {
                flashMessage(response);
            } // success
        }); // ajax
    });

    $(document).on('keyup', ".add_html_template", function(e){
        limitInput(this, 'alphanumeric');
    });

    $(document).on('click', ".insert_template_button", function(){
        var container = $(this).closest('.update_page_container');
        var page_order = container.find('.page_order').val();
        var page_id = container.find('.page_id').val();
        var html = container.find('.add_html_template').val();
        if (html == '') { return flashMessage('No resource name entered'); }
        $.ajax({
            url: "?url=cms/pages",
            type: "POST",
            data: {
                function: 'update_add',
                page_order: page_order,
                page_id: page_id,
                html_template: html
            }, // data
            success: function(response) {
                flashMessage(response);
                if (response.trim().slice(-14) == 'added to page.') {
                    getPage(container.find('.page_name').val());
                }
            } // success
        }); // ajax
    });

    $(document).on('click', ".btn-danger.remove_template_button", function(){
        var container = $(this).closest('.update_page_container');
        var page_order = container.find('.page_order').val();
        var page_id = container.find('.page_id').val();
        var html = container.find('.remove_html_template').val();
        if (html == '') { return flashMessage('No resource name entered'); }
        $.ajax({
            url: "?url=cms/pages",
            type: "POST",
            data: {
                function: 'update_remove',
                page_order: page_order,
                page_id: page_id,
                html_template: html
            }, // data
            success: function(response) {
                flashMessage(response);
                if (response.trim().slice(-18) == 'removed from page.') {
                    getPage(container.find('.page_name').val());
                }
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

});
</script>

<h3>EVENTS</h3>
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/simpleChunking.js"></script>
<style>
#event_title, #event_body {
    width: 100%;
}
#event_image {
    display: inline;
}
.event_image {
    max-height: 250px;
}
</style>

<div class="row col-md-12">
    <br>Date: <input type="text" value="<?php echo date('Y-m-d', strtotime("+2 weeks")); ?> 19:30:00" id="event_date">
    Image: <input type="file" id="event_image">
    <br>Title: <input type="text" id="event_title" maxlength="255">
    <br>Body: <textarea id="event_body" maxlength="2047"></textarea>
    <br><button class="btn btn-default" id="create_event_button">Create</button>
</div><!-- </div class="row col-md-12"> -->

<div class="row col-md-12">
    <pre id="event_container" title="Double click to edit a field.">
        <table id="event_table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Date</th>
                    <th>Delete</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($data as $event) {
                        echo "<tr class='event_row'><td><img src='data:image/jpg;base64,{$event->event_image}' class='event_image'/></td>";
                        echo "<td><input type='text' class='event_title' value='"
                            . html_entity_decode($event->event_title) . "' readonly></td>";
                        echo "<td><input type='text' class='event_body' value='"
                            . html_entity_decode($event->event_body) . "' readonly></td>";
                        echo "<td><input type='text' class='event_date' value='"
                            . html_entity_decode($event->event_date) . "' readonly></td>";
                        echo "<td><button class='btn btn-warning delete_event' event_id='{$event->event_id}'>Delete</button></td>";
                        echo "<td><button class='btn update_event' event_id='{$event->event_id}'>Update</button></td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </pre>
</div><!-- </div class="row col-md-12"> -->

<script>
$(document).ready(function(){
    $("#event_table").DataTable();

    $(".event_title, .event_body").keyup(function(e){
        limitInput(this, /[^A-z0-9 ]/g);
    });

    $(document).on('dblclick', ".event_title, .event_body, .event_date", function() {
        $(this).removeAttr('readonly');
    });

    $(document).on('click', ".delete_event.btn-danger", function(){
        var row = $(this).closest('tr');
        var event_id = $(this).attr('event_id');
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'events',
                delete: 0,
                event_id: event_id
            },
            success: function(response){
                flashMessage(response);
                if (response.trim() == 'Event deleted.') {
                    row.hide();
                }
            } //  success
        }); // ajax
    });

    $(document).on('click', "#create_event_button", function(){
        var file = document.getElementById('event_image').files[0];
        if (typeof file == 'undefined') {
            return flashMessage('No image was selected.');
        }
        if (file.size >= 4999999) {
            return flashMessage('This image is too large to upload (max 5mb).');
        }
        var filetype = file.type;
        var event_date = $("#event_date").val();
        var event_title = $("#event_title").val();
        var event_body = $("#event_body").val();
        if (event_date == '' || event_title == '' || event_body == '') {
            return flashMessage('Missing date, title and/or body.');
        }
        params = {
            function: 'events',
            filetype: filetype,
            event_date: event_date,
            event_title: event_title,
            event_body: event_body
        };
        sendRequest('?url=jca/edit', params, 'event_image', 5000000);
    });

    $(document).on('click', ".update_event", function(){
        var row = $(this).closest('tr');
        var event_id = $(this).attr('event_id');
        var event_title = row.find('.event_title').val();
        var event_body = row.find('.event_body').val();
        var event_date = row.find('.event_date').val();
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'events',
                update: 0,
                event_id: event_id,
                event_title: event_title,
                event_body: event_body,
                event_date: event_date
            },
            success: function(response){
                flashMessage(response);
            } //  success
        }); // ajax
    });
});
</script>

<h3>EVENTS</h3>
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/simpleChunking.js"></script>
<style>
#event_title, #event_body {
    width: 100%;
}
#event_image {
    display: inline;
}
</style>

<div class="row col-md-12">
    <br>Date: <input type="text" value="<?php echo date('Y-m-d', strtotime("+2 weeks")); ?>" id="event_date">
    Image: <input type="file" id="event_image">
    <br>Title: <input type="text" id="event_title" maxlength="255">
    <br>Body: <textarea id="event_body" maxlength="2047"></textarea>
    <br><button class="btn btn-default" id="create_event_button">Create</button>
</div><!-- </div class="row col-md-12"> -->

<div class="row col-md-12">
    <pre id="event_container">
        <table id="event_table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Date</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($data as $event) {
                        echo "<tr><td><img src='data:image/jpg;base64,{$event->event_image}'/></td>";
                        echo '<td>' . $event->event_title . '</td>';
                        echo '<td>' . $event->event_body . '</td>';
                        echo '<td>' . $event->event_date . '</td>';
                        echo "<td><button class='btn btn-warning delete_event' event_id='{$event->event_id}'>Delete</button></td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </pre>
</div><!-- </div class="row col-md-12"> -->

<script>
$(document).ready(function(){
    $("#event_table").DataTable();

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
        if (typeof file == 'undefined') { return flashMessage('No file was selected'); }
        if (file.size >= 5000000) {
            return flashMessage('This image is too large to upload');
        }
        var filetype = file.type;
        var filename = file.name;
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
        sendRequest('?url=jca/edit', params, 'event_image', 5000001);
    });
});
</script>

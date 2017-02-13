<h3>SERMONS</h3>
<!-- <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/simpleChunking.js"></script> -->
<style>
#sermon_audio, #sermon_speaker_container, #sermon_event_container {
    display: inline;
}
</style>

<div class="row col-md-12">
    <br>Date: <input type="text" value="<?php echo date('Y-m-d'); ?>" id="sermon_date">
    Speaker:
    <div id="sermon_speaker_container">
        <select id="sermon_speaker">
            <option value="Pastor Matt Ro">PMatt</option>
            <option value="Pastor David Moon">PDavid</option>
            <option value="Pastor Dandy Mejias">PDandy</option>
            <option value="Pastor Min Soo Choi">PMinsoo</option>
            <option value="Pastor Ben Ahn">PBen</option>
            <option value="other"> - Other - </option>
        </select>
    </div>
    Title: <input type="text" id="sermon_title" maxlength="255">
    Event:
    <div id="sermon_event_container">
        <select id="sermon_event">
            <option value="Lord's Day">Lord's Day</option>
            <option value="QUEST">QUEST</option>
            <option value="Retreat">Retreat</option>
            <option value="other"> - Other - </option>
        </select>
    </div>
    <br>Passage: <input type="text" id="sermon_passage" maxlength="255">
    Url: <input type="text" id="sermon_url" maxlength="99">
    Series: <input type="text" id="sermon_series" maxlength="255">
    <!-- Audio: <input type="file" id="sermon_audio"> -->
    <button class="btn btn-default" id="create_sermon_button">Create</button>
</div><!-- </div class="row col-md-12"> -->

<div class="row col-md-12">
    <pre id="event_container">
        <table id="event_table">
            <thead>
                <tr>
                    <th>Speaker</th>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Event</th>
                    <th>Passage</th>
                    <th>url</th>
                    <th>Series</th>
                    <th>Delete</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($data as $sermon) {
                        echo '<tr class="sermon_row"><td><input type="text" class="sermon_speaker" value="'
                            . html_entity_decode($sermon->sermon_speaker) . '" readonly></td>';
                        echo '<td><input type="text" class="sermon_date" value="'
                            . html_entity_decode($sermon->sermon_date) . '" readonly></td>';
                        echo '<td><input type="text" class="sermon_title" value="'
                            . html_entity_decode($sermon->sermon_title) . '" readonly></td>';
                        echo '<td><input type="text" class="sermon_event" value="'
                            . html_entity_decode($sermon->sermon_event) . '" readonly></td>';
                        echo '<td><input type="text" class="sermon_passage" value="'
                            . html_entity_decode($sermon->sermon_passage) . '" readonly></td>';
                        echo '<td><input type="text" class="sermon_url" value="'
                            . html_entity_decode($sermon->sermon_url) . '" readonly></td>';
                        echo '<td><input type="text" class="sermon_series" value="'
                            . html_entity_decode($sermon->sermon_series) . '" readonly></td>';
                        echo '<td><button class="btn btn-warning delete_sermon" sermon_id="'
                            . html_entity_decode($sermon->sermon_id) . '">Delete</button></td>';
                        echo '<td><button class="btn update_sermon" sermon_id="'
                            . $sermon->sermon_id . '">Update</button></td></tr>';
                    }
                ?>
            </tbody>
        </table>
    </pre>
</div><!-- </div class="row col-md-12"> -->

<script>
$(document).ready(function(){
    $("#event_table").DataTable({
        "pageLength": 25
    });

    $(document).on('dblclick', ".sermon_speaker, .sermon_date, .sermon_title, .sermon_event, .sermon_passage, .sermon_url, .sermon_series", function() {
        $(this).removeAttr('readonly');
    });

    $("#sermon_speaker").on('change', function(){
        if ($(this).val() == 'other') {
            var html = '<input type="text" id="sermon_speaker" maxlength="255">';
            $("#sermon_speaker_container").html(html);
        }
    });

    $("#sermon_event").on('change', function(){
        if ($(this).val() == 'other') {
            var html = '<input type="text" id="sermon_event" maxlength="255">';
            $("#sermon_event_container").html(html);
        }
    });

    $("#create_sermon_button").on('click', function(){
        var sermon_speaker = $("#sermon_speaker").val();
        var sermon_date = $("#sermon_date").val();
        var sermon_title = $("#sermon_title").val();
        var sermon_event = $("#sermon_event").val();
        var sermon_passage = $("#sermon_passage").val();
        var sermon_url = $("#sermon_url").val();
        var sermon_series = $("#sermon_series").val();
        if (sermon_speaker == '' || sermon_title == '' || sermon_url == '') {
            return flashMessage('Missing sermon speaker, title and/or url.');
        }
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'sermons',
                sermon_speaker: sermon_speaker,
                sermon_date: sermon_date,
                sermon_title: sermon_title,
                sermon_event: sermon_event,
                sermon_passage: sermon_passage,
                sermon_url: sermon_url,
                sermon_series: sermon_series
            },
            success: function(response){
                flashMessage(response);
            } //  success
        }); // ajax
    });

    $(document).on('click', ".update_sermon", function(){
        var row = $(this).closest('tr');
        var sermon_id = $(this).attr('sermon_id');
        var sermon_speaker = row.find(".sermon_speaker").val();
        var sermon_date = row.find(".sermon_date").val();
        var sermon_title = row.find(".sermon_title").val();
        var sermon_event = row.find(".sermon_event").val();
        var sermon_passage = row.find(".sermon_passage").val();
        var sermon_url = row.find(".sermon_url").val();
        var sermon_series = row.find(".sermon_series").val();
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'sermons',
                update: 0,
                sermon_id: sermon_id,
                sermon_speaker: sermon_speaker,
                sermon_date: sermon_date,
                sermon_title: sermon_title,
                sermon_event: sermon_event,
                sermon_passage: sermon_passage,
                sermon_url: sermon_url,
                sermon_series: sermon_series
            },
            success: function(response){
                flashMessage(response);
            } //  success
        }); // ajax
    });

    $(document).on('click', ".delete_sermon.btn-danger", function(){
        var row = $(this).closest('tr');
        var sermon_id = $(this).attr('sermon_id');
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'sermons',
                delete: 0,
                sermon_id: sermon_id
            },
            success: function(response){
                flashMessage(response);
                if (response.trim() == 'Sermon deleted.') {
                    row.hide();
                }
            } //  success
        }); // ajax
    });

});
</script>

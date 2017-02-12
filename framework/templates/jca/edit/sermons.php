<h3>SERMONS</h3>
<!-- <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/library/simpleChunking.js"></script> -->
<style>
#sermon_audio {
    display: inline;
}
</style>

<div class="row col-md-12">
    <br>Date: <input type="text" value="<?php echo date('Y-m-d'); ?>" id="sermon_date">
    <!-- Sermon: <input type="file" id="sermon_audio"> -->
    Speaker: <input type="text" id="sermon_speaker" maxlength="255">
    Title: <input type="text" id="sermon_title" maxlength="255">
    Event: <input type="text" id="sermon_event" value="Lord's Day" maxlength="255">
    <br>Passage: <input type="text" id="sermon_passage" maxlength="255">
    Url: <input type="text" id="sermon_url" maxlength="99">
    Series: <input type="text" id="sermon_series" maxlength="255">
    <button class="btn btn-default" id="create_sermon_button">Create</button>
</div><!-- </div class="row col-md-12"> -->

<div class="row col-md-12">
    <pre id="event_container">
        <table id="event_table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Delete</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($data as $sermon) {
                        echo "<tr><td>{$sermon->sermon_title}</td>";
                        echo "<td><input type='text' class='sermon_date' value='{$sermon->sermon_date}'></td>";
                        echo "<td><button class='btn btn-warning delete_sermon' sermon_id='{$sermon->sermon_id}'>Delete</button></td>";
                        echo "<td><button class='btn update_sermon' sermon_id='{$sermon->sermon_id}'>Update</button></td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </pre>
</div><!-- </div class="row col-md-12"> -->

<script>
$(document).ready(function(){
    $("#event_table").DataTable();

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

<h3>EVENTS</h3>
<style>
#event_title, #event_body {
    width: 100%;
}
</style>

<div class="row col-md-12">
    <br>Date: <input type="text" value="<?php echo date('Y-m-d', strtotime("+1 week")); ?>" id="event_date">
    Image: <input type="text">
    <br>Title: <input type="text" id="event_title" maxlength="255">
    <br>Body: <textarea id="event_body" maxlength="2047"></textarea>
    <br><button class="btn btn-default" id="update_event_button">Create</button>
</div><!-- </div class="row col-md-12"> -->

<div class="row col-md-12">
    <pre id="event_container">
        <?php
            foreach ($data as $event) {
                var_dump($event);
            }
        ?>
    </pre>
</div><!-- </div class="row col-md-12"> -->

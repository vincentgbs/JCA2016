<h3>FORMS</h3>
<pre>
    <table>
        <thead>
            <tr>
                <th>Form Name</th>
                <th>Email</th>
                <th>Spreadsheet Id</th>
                <th>Spreadsheet Range</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $form) {
                echo '<tr class="form_row"><td><input type="text" class="form_name" value="'
                    . html_entity_decode($form->form_name) . '" maxlength="99" disabled></td>';
                echo '<td><input type="text" class="email_notification" value="'
                    . html_entity_decode($form->email_notification) . '" maxlength="255"></td>';
                echo '<td><input type="text" class="google_spreadsheet_id" value="'
                    . html_entity_decode($form->google_spreadsheet_id) . '" maxlength="255" disabled></td>';
                echo '<td><input type="text" class="google_spreadsheet_range" value="'
                    . html_entity_decode($form->google_spreadsheet_range) . '" maxlength="255" disabled></td>';
                echo '<td><button class="btn btn-warning update_form" form_id="'
                    . $form->form_id . '">Update</button></td></tr>';
            } ?>
        </tbody>
    </table>
</pre>

<script>
$(document).ready(function(){
    $(document).on('click', ".update_form.btn-danger", function(){
        var row = $(this).closest('tr');
        var form_id = $(this).attr('form_id');
        var form_name = row.find(".form_name").val();
        var email_notification = row.find(".email_notification").val();
        var google_spreadsheet_id = row.find(".google_spreadsheet_id").val();
        var google_spreadsheet_range = row.find(".google_spreadsheet_range").val();
        $.ajax({
            url: "?url=jca/edit",
            type: "POST",
            data: {
                function: 'forms',
                update: 0,
                form_id: form_id,
                form_name: form_name,
                email_notification: email_notification,
                google_spreadsheet_id: google_spreadsheet_id,
                google_spreadsheet_range: google_spreadsheet_range
            },
            success: function(response) {
                flashMessage(response);
            } // success
        }); // ajax
    });
});
</script>

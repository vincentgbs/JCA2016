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
                    . html_entity_decode($form->form_name) . '"></td>';
                echo '<td><input type="text" class="email_notification" value="'
                    . html_entity_decode($form->email_notification) . '"></td>';
                echo '<td><input type="text" class="google_spreadsheet_id" value="'
                    . html_entity_decode($form->google_spreadsheet_id) . '"></td>';
                echo '<td><input type="text" class="google_spreadsheet_range" value="'
                    . html_entity_decode($form->google_spreadsheet_range) . '"></td>';
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
        var form_name = row.find(".form_name").val();
        var email_notification = row.find(".email_notification").val();
        var google_spreadsheet_id = row.find(".google_spreadsheet_id").val();
        var google_spreadsheet_range = row.find(".google_spreadsheet_range").val();
        // console.debug(row, form_name, email_notification, google_spreadsheet_range, google_spreadsheet_id);
    });
});
</script>

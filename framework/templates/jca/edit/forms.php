<p>
    The form name should correspond to the sheet on the Google Spreadsheet.<br>
    The email is the email(s) that an alert will be sent to when a form is submitted. You may enter multiple emails separated by a coma but the max character length for the combined emails is 255.<br>
    The spreadsheet id is the identifier of the Google Spreadsheet where the results are stored. The spreadsheet range is the sheet on the Google Spreadsheet that corresponds to the form.<br>
    <br>
    The form creator at the bottom of this page creates an <b>outline</b> for forms on congregation facing pages. That outline may be edited to fit a specific use case. The ajax created will submit the form through the default jca/forms page. It does not create a form on the site, it simply provides the html and js that you can then copy and past to a template in the cms. <br>
</p>
<hr class="row col-md-12">
<h3>FORMS</h3>
<style>
    #form_creator_html, #form_creator_js {
        width: 99%;
        min-height: 25vh;
    }
</style>

<div class="row col-md-12">
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
                        . html_entity_decode($form->form_name) . '" maxlength="99" readonly></td>';
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
</div><!-- </div class="row col-md-12"> -->
<div class="row col-md-12">
    <div class="row col-md-12">
        Form Name <input type="text" id="create_form_name">
        <button class="btn btn-default" id="create_form">Create form</button>
        <select id="add_input_selector">
            <option disabled selected> - choose - </option>
            <option value="name">name</option>
            <option value="email">email</option>
            <option value="message">message</option>
            <option value="radiobutton">radiobutton</option>
        </select>
        <button class="btn btn-default" id="add_input_button" disabled>Add Input</button>
        <button class="btn btn-default" id="close_form" disabled>Close form</button>
    </div><!-- </div class="row col-md-12"> -->
    <div class="col-md-6">
        Html
        <textarea id="form_creator_html"></textarea>
    </div><!-- </div class="col-md-6"> -->
    <div class="col-md-6">
        Js
        <textarea id="form_creator_js"></textarea>
    </div><!-- </div class="col-md-6"> -->
</div><!-- </div class="row col-md-12"> -->

<script>
$(document).ready(function(){
    $(".google_spreadsheet_id, .form_name").keyup(function(e){
        limitInput(this, 'alphanumeric');
    });

    $(".form_name").on('click', function(){
        $("#create_form_name").val($(this).val());
    });

    $("#add_input_button").on('click', function(){
        var value = $("#add_input_selector").val();
        if (value) {
            if (create_form[value]) {
                return flashMessage('This form already has this input.');
            } // else
            create_form[value] = value;
            var html = '\n  <input type="text" class="'+value+'">';
            var js = '\n  var '+value+' = form.find(".'+value+'").val();';
            $("#form_creator_html").val($("#form_creator_html").val() + html);
            $("#form_creator_js").val($("#form_creator_js").val() + js);
        }
    });

    $("#create_form").on('click', function(){
        var form_name = $("#create_form_name").val();
        if (form_name != '') {
            window.create_form = {'form_name': form_name};
            $("#form_creator_html").val('<div class="form_container">');
            $("#form_creator_js").val('$(".form_submit").on("click", function(){'
                +'\nvar form = $(this).closest(".form_container");');
            $("#add_input_button, #close_form").removeAttr('disabled');
        } else {
            flashMessage('Missing form name.');
        }
    });
    $("#close_form").on('click', function(){
        $("#form_creator_html").val($("#form_creator_html").val()
            + '\n<button class="form_submit">Submit</button></div>');
        var js = $("#form_creator_js").val()+'\n  $.ajax({\n    url:"?url=jca/forms", \n    type:"POST", \n    data:{';
            $.each(create_form, function( index, value ) {
                if (index == 'form_name') {
                    js += '\n      ' + index + ': "' + value + '",';
                } else {
                    js += '\n      ' + index + ': ' + value + ',';
                }
            });
            js += '\n    }, \n    success: function(response){'
                +'\n      console.debug(response)}\n    });\n  });';
        $("#form_creator_js").val(js);
        $("#add_input_button").attr('disabled', 'disabled');
    });

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

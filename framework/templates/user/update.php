<div class="row col-md-12">
    <input type="hidden" id="csrf_token" value="{{{@csrf_token}}}"/>
    <h3>Update Information</h3>
    <label for="password">Current Password</label>
        <input type="password" id="password"><br>
    <label for="username">Username:</label>
        <button class="btn input_button" input="username">
            <?php echo $_SESSION['USER']->username?></button>
        <input type="text" class="hide" id="username" value="<?php echo $_SESSION['USER']->username?>">
        <span class="alert" id="username_alert"></span><br>
    <label for="email">Email:</label>
        <button class="btn input_button" input="email">
            <?php echo $_SESSION['USER']->email?></button>
        <input type="text" class="hide" id="email" value="<?php echo $_SESSION['USER']->email?>">
        <span class="alert" id="email_alert"></span><br>
    <label for="new_password">New password</label>
        <input type="password" id="new_password">
        <span class="alert" id="new_password_alert"></span><br>
    <label for="confirm_password">Confirm password</label>
        <input type="password" id="confirm_password">
        <span class="alert" id="confirm_password_alert"></span><br>

    <button class="btn btn-default" id="update_button">Update</button>

    <script>
    $(document).ready(function(){
        $(".input_button").on('click', function(){
            $("#" + $(this).attr('input')).show();
            $(this).hide();
        });

        $('#username').keyup(function(e){
            limitInput(this, 'alphanumeric');
            if ($("#username").val().length < 3) {
                $("#username_alert").text('Your username must contain at least 3 characters.');
            } else {
                $.ajax({
                    url: "?url=user/register",
                    type: "POST",
                    data: {
                        username_search: $("#username").val()
                    },
                    success: function(response) {
                        if (response.trim() == 'Username is already taken.') {
                            $("#username_alert").text('That username is already taken.');
                        } else {
                            $("#username_alert").text('');
                        }
                    } // success
                }); // ajax
            }
        });

        $("#email").keyup(function(e) {
            var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!regex.test($("#email").val())) {
                $("#email_alert").text('Please enter a valid email address.');
            } else {
                $.ajax({
                    url: "?url=user/register",
                    type: "POST",
                    data: {
                        email_search: $("#email").val()
                    },
                    success: function(response) {
                        if (response.trim() == 'Email is already taken.') {
                            $("#email_alert").text('That email is already registered.');
                        } else {
                            $("#email_alert").text('');
                        }
                    } // success
                }); // ajax
            }
        });

        $('#new_password').keyup(function(e){
            if ($("#new_password").val().length > 0 && $("#new_password").val().length < 9) {
                $("#new_password_alert").text('Your new password must contain at least 9 characters.');
            } else {
                $("#new_password_alert").text('');
            }
        });

        $('#confirm_password').keyup(function(e){
            if ($("#confirm_password").val() != $("#new_password").val()) {
                $("#confirm_password_alert").text('Your passwords do not match.');
            } else {
                $("#confirm_password_alert").text('');
            }
        });

        $("#update_button").on('click', function(){
            var verify = true; var message = '';
            var password = $("#password").val();
            if ($("#username_alert").text() == '') {
                var username = $("#username").val();
            } else {
                verify = false;
                message += $("#username_alert").text() + '<br>';
            }
            if ($("#email_alert").text() == '') {
                var email = $("#email").val();
            } else {
                verify = false;
                message += $("#email_alert").text() + '<br>';
            }
            if ($("#new_password_alert").text() == '') {
                var new_password = $("#new_password").val();
            } else {
                verify = false;
                message += $("#new_password_alert").text() + '<br>';
            }
            if ($("#confirm_password_alert").text() == '') {
                var confirm_password = $("#confirm_password").val();
            } else {
                verify = false;
                message += $("#confirm_password_alert").text() + '<br>';
            }
            if (password == '') {
                return flashMessage('You must enter your password to change any information.', 2499);
            } else if (verify) {
                var data = {csrf_token: $("#csrf_token").val(),
                    password: SHA256(password),
                    username: username,
                    email: email}
                if (new_password != '') { data['new_password'] = SHA256(new_password); }
                $.ajax({
                    url: "?url=user/home",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        flashMessage(response);
                        if (response.trim() == 'Profile updated.') {
                            //
                        }
                    } // success
                }); // ajax
            } else {
                flashMessage(message, 2499);
            }
        });
    });
    </script>
</div>

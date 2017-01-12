<a href="/user/home"><button class="btn btn-default">Home</button></a>
<a href="/user/register"><button class="btn btn-default">Register</button></a>
<a href="/user/login"><button class="btn btn-default">Login</button></a>
<a href="/user/logout"><button class="btn btn-default">Logout</button></a>
<a href="/user/reset"><button class="btn btn-default">Reset Password</button></a>
<a href="/user/deactivate"><button class="btn btn-default">Deactivate</button></a>

<div class="row col-md-12">
    <input type="hidden" id="csrf_token" value="{{{@csrf_token}}}"/>
    <h3>Update Information</h3>
    <label for="password">Password</label>
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
        <input type="password" id="new_password"><br>
    <label for="confirm_password">Confirm password</label>
        <input type="password" id="confirm_password"><br>

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

        $("#update_button").on('click', function(){
            var password = $("#password").val();
            var username = $("#username").val();
            var email = $("#email").val();
            var new_password = $("#new_password").val();
            var confirm_password = $("#confirm_password").val();
            if (password == '') {
                return flashMessage('You must enter your password to change any information.', 2499);
            } else {
                console.debug(password, username, email, new_password, confirm_password);
            }
        });
    });
    </script>
</div>

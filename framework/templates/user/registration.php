<div class="row col-md-12" id="registration_form">
    <input type="hidden" id="csrf_token" value="{{{@csrf_token}}}"/>
    <h3>Registration form</h3>
    <div class="row col-md-12">
        <div class="col-md-4">
            <label for="username">Username</label>
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
            <input type="text" id="username" maxlength="99">
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
             <p class="alert" id="username_alert"></p>
         </div> <!-- </div class="col-md-4"> -->
    </div> <!-- </div class="row col-md-12"> -->
    <div class="row col-md-12">
        <div class="col-md-4">
            <label for="email">Email</label>
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
            <input type="text" id="email" maxlength="255">
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
             <p class="alert" id="email_alert"></p>
         </div> <!-- </div class="col-md-4"> -->
    </div> <!-- </div class="row col-md-12"> -->
    <div class="row col-md-12">
        <div class="col-md-4">
            <label for="password">Password</label>
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
            <input type="password" id="password" maxlength="99">
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
             <p class="alert" id="password_alert"></p>
         </div> <!-- </div class="col-md-4"> -->
    </div> <!-- </div class="row col-md-12"> -->
    <div class="row col-md-12">
        <div class="col-md-4">
            <label for="confirm">Confirm password</label>
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
            <input type="password" id="confirm" maxlength="99">
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
             <p class="alert" id="confirm_alert"></p>
         </div> <!-- </div class="col-md-4"> -->
    </div> <!-- </div class="row col-md-12"> -->
    <div class="row">
        <button class="btn btn-default" id="register_button">Register</button>
    </div><!-- </div class="row"> -->

    <script>
    $(document).ready(function(){
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
            function gmail(input) {
                if (input.val().search('@') == -1) {
                    return [input.val() + '@gmail.com'];
                } else {
                    return [];
                }
            }
            $("#email").autocomplete({
                source: gmail($("#email"))
            });

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

        $('#password').keyup(function(e){
            if ($("#password").val().length < 9) {
                $("#password_alert").text('Your password must contain at least 9 characters.');
            } else {
                $("#password_alert").text('');
            }
        });

        $('#confirm').keyup(function(e){
            if ($("#confirm").val() != $("#password").val()) {
                $("#confirm_alert").text('Your passwords do not match.');
            } else {
                $("#confirm_alert").text('');
            }
        });

        $("#register_button").on('click', function(){
            var verify = true; var message = '';
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
            if ($("#password_alert").text() == '') {
                var password = $("#password").val();
            } else {
                verify = false;
                message += $("#password_alert").text() + '<br>';
            }
            if ($("#confirm_alert").text() == '') {
                var confirm = $("#confirm").val();
            } else {
                verify = false;
                message += $("#confirm_alert").text() + '<br>';
            }
            if (verify) {
                var csrf_token = $("#csrf_token").val();
                $.ajax({
                    url: "?url=user/register",
                    type: "POST",
                    data: {
                        csrf_token: csrf_token,
                        username: username,
                        email: email,
                        password: SHA256(password)
                    },
                    success: function(response){
                        flashMessage(response, 4999);
                    }
                }); // ajax
            } else {
                flashMessage(message, 2499);
            }
        });
    });
    </script>
</div>

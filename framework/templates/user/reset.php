<div class="row" id="reset_form">
    <input type="hidden" id="csrf_token" value="{{{@csrf_token}}}"/>
    <h3>Reset form</h3>
    <div class="col-md-6">
        <label for="password">Password</label><br>
        <label for="confirm">Confirm password</label><br>
    </div> <!-- </div class="col-md-6"> -->
    <div class="col-md-6">
        <input type="password" id="password" maxlength="99" autofocus><br>
        <input type="password" id="confirm" maxlength="99"><br>
    </div> <!-- </div class="col-md-6"> -->
    <div class="row">
        <input type="hidden" id="reset_code" value="<?php echo (isset($data->reset_code)?$data->reset_code:NULL); ?>">
        <button class="btn btn-default" id="reset_button" <?php echo (isset($data->reset_code)?NULL:'disabled'); ?>>Reset Password</button>
        <a href="/user/login"><button class="btn btn-default hide" id="login_link">Login</button></a>
    </div><!-- </div class="row"> -->

    <script>
    $(document).ready(function(){
        $("#reset_button").on('click', function(){
            var csrf_token = $("#csrf_token").val();
            var verify = true; var message = '';
            var reset_code = $("#reset_code").val();
            var password = $("#password").val();
            if (password.length < 9) {
                message += 'Your password must be at least 9 characters long.<br>';
                verify = false;
            } else {
                var confirm = $("#confirm").val();
                if (confirm == password) {
                    password = SHA256(password);
                } else {
                    message += 'Your passwords do not match.<br>';
                    verify = false;
                }
            }
            if (verify) {
                $.ajax({
                    url: "?url=user/reset",
                    type: "POST",
                    data: {
                        csrf_token: csrf_token,
                        password: password,
                        reset_code: reset_code
                    },
                    success: function(response){
                        flashMessage(response, 4999);
                        if (response.trim() == 'Your password has been reset.') {
                            $('#login_link').show();
                        }
                    } //  success
                }); // ajax
            } else {
                flashMessage(message, 2499);
            }
        });
    });
    </script>
</div>

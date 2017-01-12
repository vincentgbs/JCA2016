<div class="row col-md-12">
    <h3>Login form</h3>
    <form method="POST" action="/user/login" id="login_form">
        <label for="username">Email or username</label>
            <input type="text" id="username" name="username" maxlength="255"><br>
        <label for="password">password</label>
            <input type="password" id="password" maxlength="99"><br>
        <button class="btn btn-default" id="login_button">Login</button>
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{@csrf_token}}}"/>
    </form>
    <script>
    $(document).ready(function(){
        $("#login_button").on('click', function(){
            var form = $("#login_form");
            var password = $("#password").val();
            $('<input>').attr({
                type: 'hidden',
                name: 'password',
                value: SHA256(password)
            }).appendTo(form);
        });
    });
    </script>
</div>

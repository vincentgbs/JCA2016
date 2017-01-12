<div class="row col-md-12">
    <h3>Deactivation Account</h3>
    <form method="POST" action="/user/deactivate" id="deactivate_form">
        <label for="username">Username:</label> <?php echo $_SESSION['USER']->username; ?><br>
        <label for="password">password</label>
            <input type="password" id="password" maxlength="99"><br>
        <button class="btn btn-warning" id="deactivate">Deactivate</button>
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{@csrf_token}}}"/>
    </form>

    <script>
    $(document).ready(function(){
        $(document).on('click', ".btn-danger#deactivate", function(e){
            var form = $("#deactivate_form");
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

<div class="row col-md-12">
    <input type="hidden" id="csrf_token" value="{{{@csrf_token}}}"/>
    <h3>Request reset link</h3>
    <p>Please note that the reset link will only be valid for 15 minutes.
        After that time you will need to request a new link.</p>
    <label for="username">Email or username</label>
        <input type="text" id="username" name="username" maxlength="255" autofocus><br>
    <button class="btn btn-default" id="request_button">Request</button>

    <script>
    $(document).ready(function(){
        function requestPassword()
        {
            var csrf_token = $("#csrf_token").val();
            var username = $("#username").val();
            if (username != '') {
                $.ajax({
                    url: "?url=user/reset",
                    type: "POST",
                    data: {
                        csrf_token: csrf_token,
                        username: username
                    },
                    success: function(response){
                        flashMessage(response, 4999);
                    }
                }); // ajax
            }
        }

        $("#request_button").on('click', function(){
            requestPassword();
        });
        $("#username").keydown(function(e){
            if (e.keyCode == 13) { // enter
                requestPassword();
            }
        });
    });
    </script>
</div>

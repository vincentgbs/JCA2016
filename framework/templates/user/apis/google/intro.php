<div>
    <h2>Google Login</h2>
    <button class="btn" id="google_login">Google Login</button>
    <div id="google_lightbox"><div id="google_login_window"></div></div>

    <style>
        #google_lightbox {
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            position: fixed;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 1;
            display: none;
        }
        #google_login_window {
            margin: 5% 25%;
            height: 10%;
            width: 50%;
            background-color: #FFF;
            z-index: 2;
            border-style: solid;
        }
    </style>

    <script>
    function onSignIn(googleUser) {
            var id_token = googleUser.getAuthResponse().id_token;
            var email = googleUser.getBasicProfile().getEmail();
            var google_csrf_token = $("#google_csrf_token").val();

            $.ajax({
                url: "?url=user/api",
                type: 'POST',
                data: {
                    login_method: 'google',
                    csrf_token: google_csrf_token,
                    email: email,
                    id_token: id_token
                },
                success: function(response) {
                     $("#google_lightbox").hide();
                     flashMessage(response, 4999);
                } // success
            }); // ajax
        }; // onSignIn

    $( document ).ready(function() {
        $("#google_login").on('click', function() {
            $.ajax({
                url: "?url=user/api",
                type: "POST",
                data: {
                    api: 'google'
                },
                success: function(response) {
                    $("#google_lightbox").show();//attr('style', 'display:block;');
                    $("#google_login_window").html(response);
                } // success
            }); // ajax
        });

        $(document).on('click', "#signOut", function() {
            var auth2 = gapi.auth2.getAuthInstance();
            auth2.signOut().then(function () {
                $.ajax({
                    url: "?url=user/logout",
                    type: 'GET',
                    success: function(response) {
                        flashMessage('User has signed out.', 2499);
                        $("#google_lightbox").hide();
                    } // success
                }); // ajax
            });
        });
        $(document).on('click', "#close_lightbox", function() {
            $("#google_lightbox").hide();
        });
        $(document).keyup(function(e) {
            if (e.keyCode == 27) { // escape
                $("#google_lightbox").hide();
            }
        });
    });
    </script>
</div>

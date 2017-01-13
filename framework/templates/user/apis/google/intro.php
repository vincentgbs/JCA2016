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
            var profile = googleUser.getBasicProfile();
            var id_token = googleUser.getAuthResponse().id_token;
            var name = profile.getName();
            var email = profile.getEmail();

    //         $.ajax({
    //             url: "?url=user/googleLogin",
    //             type: 'POST',
    //             data: {
    //         email: email,
    //                 id_token: id_token,
    //                 name: name
    //             },
    //             success: function(response) {
    //                 $("#message").html(response);
    //                  setTimeout(function(){
    //                      $("#google_lightbox").attr('style', 'display:none;');
    //                  }, 4999);
    //             } // success
    //         }); // ajax
        }; // onSignIn

    $( document ).ready(function() {
        $("#google_login").on('click', function() {
            $("#google_lightbox").attr('style', 'display:block;');
            $("#google_login_window").html('TESTING');
            return;
            // $.ajax({
            //     url: "?url=user/login",
            //     type: 'POST',
            //     data: {
            //         api: 'google',
            //         csrf_token: csrf_token
            //     }
            //     success: function(response) {
            //         $("#google_lightbox").attr('style', 'display:block;');
            //         $("#google_login_window").html(response);
            //     }
            // });
        });

        $(document).on('click', "#signOut", function() {
            var auth2 = gapi.auth2.getAuthInstance();
            auth2.signOut().then(function () {
                $.ajax({
                    url: "?url=user/logout",
                    type: 'GET',
                    success: function(response) {
                        flashMessage('User has signed out.');
                    }
                });
            });
        });
        $(document).on('click', "#close_lightbox", function() {
            $("#google_lightbox").attr('style', 'display:none;');
        });
        $(document).keyup(function(e) {
            if (e.keyCode == 27) { // escape
                $("#google_lightbox").attr('style', 'display:none;');
            }
        });
    });
    </script>
</div>

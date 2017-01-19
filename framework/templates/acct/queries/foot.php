    </tbody>
</table>

<script>
$(document).ready(function(){
    $('#new_nickname').keyup(function(){
        limitInput(this, 'alphanumeric');
    });

    $("#databases").dataTable();

    $("#new_database_button").on('click', function(){
        var nickname = $("#new_nickname").val();
        var host = $("#new_host").val();
        var username = $("#new_username").val();
        var password = $("#new_password").val();
        var database = $("#new_database").val();
        // console.debug(nickname, host, username, password, database);
        $.ajax({
            url: "?url=acct/queries",
            type: "POST",
            data: {
                nickname: nickname,
                host: host,
                username: username,
                password: password,
                database: database
            },
            success: function(response){
                flashMessage(response);
                if (response.trim() == 'Database added.') {
                    setTimeout(
                        function(){
                            location.reload();
                        }, 2499
                    ); // setTimeout
                }
            } // success
        }); // ajax
    });
});
</script>

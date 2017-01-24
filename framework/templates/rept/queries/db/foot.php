</tbody>
</table>
<hr class="row col-md-12">
</div> <!-- </div class="row col-md-12" id="database_container"> -->

<script>
$(document).ready(function(){
    $("#hide_database_container").on('click', function(){
        $("#database_container").hide();
    });

    $('#new_nickname').keyup(function(){
        limitInput(this, 'alphanumeric');
    });

    $("#databases").dataTable();

    $(document).on('click', ".delete_database_button.btn-danger", function(){
        var dbid = $(this).attr('dbid');
        var row = $(this).closest('tr');
        $.ajax({
            url: "?url=acct/queries",
            type: "POST",
            data: {
                function: 'delete',
                db_id: dbid
            },
            success: function(response) {
                flashMessage(response);
                if (response.trim() == 'Database deleted.') {
                    row.hide();
                }
            } // success
        }); // ajax
    });

    $(document).on('click', ".select_database_button", function(){
        var dbid = $(this).attr('dbid');
        $.ajax({
            url: "?url=acct/queries",
            type: "POST",
            data: {
                function: 'select',
                db_id: dbid
            },
            success: function(response) {
                flashMessage(response);
                if (response.trim() == 'Database selected.') {
                    setTimeout(
                        function(){
                            location.reload();
                        }, 1000
                    ); // setTimeout
                }
            } // success
        }); // ajax
    });

    $("#new_database_button").on('click', function(){
        var nickname = $("#new_nickname").val();
        var host = $("#new_host").val();
        var username = $("#new_username").val();
        var password = $("#new_password").val();
        var database = $("#new_database").val();
        if (nickname && host && username && password && database) {
            $.ajax({
                url: "?url=acct/queries",
                type: "POST",
                data: {
                    function: 'create',
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
                            }, 1000
                        ); // setTimeout
                    }
                } // success
            }); // ajax
        } else {
            flashMessage('Every connection must have a nickname, host, username, password and database name.', 2499, ['F00', '000']);
        }
    });
});
</script>

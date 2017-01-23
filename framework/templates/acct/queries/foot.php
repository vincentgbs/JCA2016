        </tbody>
    </table>
    <hr class="row col-md-12">
</div> <!-- </div class="row col-md-12" id="database_container"> -->

<div class="row col-md-12" id="query_container">
    <div class="select_date">WHERE order_date >= '<span id="start_date"></span>'
        AND order_date <= '<span id="end_date"></span>'
    </div>
    <div class="select_brand">AND brand_id IN (<span></span>)</div>
    <div class="select_channel">AND channel_id IN (<span></span>)</div>

    <hr class="row col-md-12">
    <div class="row col-md-12">
        <button class="last_date" id="last_month" time="1">Last month</button>
        <button class="last_date" id="last_six_months" time="6">Last six months</button>
        <button class="last_date" id="last_year" time="12">Last year</button>
        <button class="last_date" id="last_two_years" time="24">Last two years</button>
        Start: <input type="text" id="start_date" value="<?php echo date('Y'); ?>-01-01 00:00:00">
        End: <input type="text" id="end_date" value="<?php echo date('Y'); ?>-12-31 23:59:59">
    </div> <!-- </div class="row col-md-12"> -->
    <div class="col-md-6">
        <label for="brands">Brands:</label> <input type="text" id="brands">
        <ol class="selectable" id="select_brand">
            <?php
            foreach ($data['brands'] as $brand) {
                echo '<li class="ui-widget-content">' . $brand->brand_id . '. ' . $brand->brand . '</li>';
            }
            ?>
        </ol>
    </div> <!-- </div class="col-md-6"> -->
    <div class="col-md-6">
        <label for="channels">Channels:</label> <input type="text" id="channels">
        <ol class="selectable" id="select_channel">
            <?php
            foreach ($data['channels'] as $channel) {
                echo '<li class="ui-widget-content">' . $channel->channel_id . '. ' . $channel->channel . '</li>';
            }
            ?>
        </ol>
    </div> <!-- </div class="col-md-6"> -->
</div> <!-- </div class="row col-md-12" id="query_container"> -->

<style>
    .selectable { list-style-type: none; }
    .selectable .ui-selecting { background: #0AF; }
    .selectable .ui-selected { background: #05A; color: white; }
</style>

<script>
$(document).ready(function(){
    $(".last_date").on('click', function(){
        var months = $(this).attr('time');
        $(".select_date").html("WHERE order_date >= DATE_FORMAT(CURDATE(), '%Y-%m-%d') - INTERVAL "
        + months + " MONTH<span id=\"start_date\"></span><span id=\"end_date\"></span>");
    });

    $(".selectable").selectable({
        stop: function() {
            var string = '';
            $( ".ui-selected", this ).each(function() {
                var index = $(".selectable li").index( this );
                string += ( index + 1 ) + ", ";
            }); // each
            $('.' + $(this).attr('id') + ' > span').text( string.substr(0, string.length-2) );
        } // stop
    });

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

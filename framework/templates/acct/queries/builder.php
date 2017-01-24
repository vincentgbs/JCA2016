<style>.selectable{list-style-type:none;}.selectable .ui-selecting{background:#0AF;}.selectable .ui-selected{background:#05A;color:white;}</style>
<div class="row col-md-12" id="query_container">
    <h3>Query</h3>
    <div class="col-md-11">
        <div class="select_date"></div>
        <div class="select_brand">AND brand_id IN (<span></span>)</div>
        <div class="select_channel">AND channel_id IN (<span></span>)</div>
        <div class="group_by">GROUP BY YEAR(order_date), MONTH(order_date)</div>
    </div> <!-- </div class="col-md-11"> -->
    <div class="col-md-1">
        <button class="btn btn-default" id="hide_query_container">-</button>
    </div> <!-- </div class="col-md-1"> -->
    <hr class="row col-md-12">

    <div class="row col-md-12">
        <button class="last_date" time="1">Last month</button>
        <button class="last_date" time="6">Last six months</button>
        <button class="last_date" time="12">Last year</button>
        <button class="last_date" time="24">Last two years</button>
        Start: <input type="text" class="specific_dates" id="start_date" value="<?php echo date('Y'); ?>-01-01 00:00:00">
        End: <input type="text" class="specific_dates" id="end_date" value="<?php echo date('Y'); ?>-12-31 23:59:59">
    </div> <!-- </div class="row col-md-12"> -->
    <div class="col-md-6">
        <label for="brands">Brands:</label> <input type="text" id="brands">
        <ol class="selectable" id="select_brand">
            <?php
            foreach ($data['brands'] as $brand) {
                echo '<li class="ui-widget-content" index="' . $brand->brand_id . '">' . $brand->brand . ' (' . $brand->brand_id . ')</li>';
            }
            ?>
        </ol>
    </div> <!-- </div class="col-md-6"> -->
    <div class="col-md-6">
        <label for="channels">Channels:</label> <input type="text" id="channels">
        <ol class="selectable" id="select_channel">
            <?php
            foreach ($data['channels'] as $channel) {
                echo '<li class="ui-widget-content" index="' . $channel->channel_id . '">' . $channel->channel . ' (' . $channel->channel_id . ')</li>';
            }
            ?>
        </ol>
    </div> <!-- </div class="col-md-6"> -->
</div> <!-- </div class="row col-md-12" id="query_container"> -->

<script>
function sortList(ul, sortDescending) {
    if(typeof ul == "string")
    ul = document.getElementById(ul);
    var lis = ul.getElementsByTagName("LI");
    var vals = [];
    for(var i = 0, l = lis.length; i < l; i++) {
        vals.push(lis[i].innerHTML);
        vals.sort();
    }
    if(sortDescending) {
        vals.reverse();
    }
    for(var i = 0, l = lis.length; i < l; i++)
    lis[i].innerHTML = vals[i];
}
$(document).ready(function(){
    $("#hide_query_container").on('click', function(){
        $("#query_container").hide();
    });

    sortList('select_channel');
    sortList('select_brand');

    $(".last_date").on('click', function(){
        var months = $(this).attr('time');
        $(".select_date").html("WHERE order_date >= DATE_FORMAT(CURDATE(), '%Y-%m-%d') - INTERVAL "
        + months + " MONTH<span id=\"start_date\"></span><span id=\"end_date\"></span>");
    });

    $(".specific_dates").on('keyup', function(){
        $(".select_date").html("WHERE order_date >= '" + $("#start_date").val()
        + "' AND order_date <= '" +  $("#end_date").val() + "'");
    });

    $(".selectable").selectable({
        stop: function() {
            var string = '';
            $( ".ui-selected", this ).each(function() {
                var index = $( this ).attr('index');
                string += ( index ) + ", ";
            }); // each
            $('.' + $(this).attr('id') + ' > span').text( string.substr(0, string.length-2) );
        } // stop
    });
});
</script>

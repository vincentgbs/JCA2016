<div class="row col-md-12" id="database_container">
    <h3>Databases</h3>
    <div class="col-md-11" id="new_database_form">
        <div class="row col-md-12">
            <div class="col-md-4"><label for="new_nickname">Nickname:</label></div> <!-- </div class="col-md-4"> -->
            <div class="col-md-4"><input type="text" id="new_nickname" maxlength="64"></div> <!-- </div class="col-md-4"> -->
        </div> <!-- </div class="row col-md-12"> -->
        <div class="row col-md-12">
            <div class="col-md-4"><label for="new_host">Host:</label></div> <!-- </div class="col-md-4"> -->
            <div class="col-md-4"><input type="text" id="new_host" maxlength="255"></div> <!-- </div class="col-md-4"> -->
        </div> <!-- </div class="row col-md-12"> -->
        <div class="row col-md-12">
            <div class="col-md-4"><label for="new_username">Username:</label></div> <!-- </div class="col-md-4"> -->
            <div class="col-md-4"><input type="text" id="new_username" maxlength="255"></div> <!-- </div class="col-md-4"> -->
        </div> <!-- </div class="row col-md-12"> -->
        <div class="row col-md-12">
            <div class="col-md-4"><label for="new_password">Password:</label></div> <!-- </div class="col-md-4"> -->
            <div class="col-md-4"><input type="text" id="new_password" maxlength="255"></div> <!-- </div class="col-md-4"> -->
        </div> <!-- </div class="row col-md-12"> -->
        <div class="row col-md-12">
            <div class="col-md-4"><label for="new_database">Database:</label></div> <!-- </div class="col-md-4"> -->
            <div class="col-md-4"><input type="text" id="new_database" maxlength="255"></div> <!-- </div class="col-md-4"> -->
        </div> <!-- </div class="row col-md-12"> -->
        <button class="btn btn-default" id="new_database_button">Add database</button>
    </div><!-- </div class="row col-md-11" id="new_database_form"> -->
    <div class="col-md-1">
        <button class="btn btn-default" id="hide_database_container">-</button>
    </div><!-- </div class="col-md-1"> -->

    <hr class="row col-md-12">

    <table id="databases">
        <thead>
            <tr>
                <th>Nickname</th>
                <th>Host</th>
                <th>Username</th>
                <th>Password</th>
                <th>Database</th>
                <th> + </th>
                <th> - </th>
            </tr>
        </thead>
        <tbody>

<div class="row col-md-12">
    <div class="row col-md-12">
        <input type="hidden" id="csrf_token" value="{{{@csrf_token}}}"/>
        <div class="col-md-4">
            <label for="company_name">Company name</label>
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
            <input type="text" id="company_name" maxlength="99">
            <button class="btn btn-default" id="create_company_button">Create</button>
        </div> <!-- </div class="col-md-4"> -->
        <div class="col-md-4">
             <p class="alert" id="company_name_alert"></p>
         </div> <!-- </div class="col-md-4"> -->
    </div> <!-- </div class="row col-md-12"> -->
</div> <!-- </div class="row col-md-12"> -->

<hr class="row col-md-12">

<table id="company_table">
    <thead>
        <th>Company Name</th>
        <th>Company Data</th>
    </thead>
    <tbody>

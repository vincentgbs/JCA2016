    </tbody>
</table>

<script>
$(document).ready(function(){
    $("#company_table").dataTable();

    $('#company_name').keyup(function(){
        limitInput(this, /[^A-z0-9\ ]/g);
        if ($("#company_name").val().length > 1) {
            $.ajax({
                url: "?url=acct/company",
                type: "POST",
                // dataType: "json",
                data: {
                    function: 'company_search',
                    username_search: $("#company_name").val()
                },
                success: function(response) {
                    // if (response.trim() == 'Username is already taken.') {
                    //     $("#username_alert").text('That username is already taken.');
                    // } else {
                    //     $("#username_alert").text('');
                    // }
                } // success
            }); // ajax
        }
        });

    function createNewCompany()
    {
        if ($("#company_name_alert").val() == '') {
            var company_name = $("#company_name").val();
            console.debug(company_name);
        } else {
            flashMessage($("#company_name_alert").val());
        }
    }
    $("#create_company_button").on('click', function(){
        createNewCompany();
    });
});
</script>

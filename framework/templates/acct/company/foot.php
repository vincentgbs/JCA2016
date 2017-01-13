    </tbody>
</table>

<script>
$(document).ready(function(){
    $("#company_table").dataTable();

    function createNewCompany()
    {
        if ($("#company_name_alert").val() == '') {
            var company_name = $("#company_name").val();
        } else {
            flashMessage($("#company_name_alert").val());
        }
    }
    $("#create_company_button").on('click', function(){
        createNewCompany();
    });
});
</script>

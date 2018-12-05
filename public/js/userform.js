$(document).ready(function() {
    $('#groups').DataTable();
    $('#erase_group').click(function(){
        $('#fieldGroupGroupId').val('');
        $('#fieldGroupName').val('');
        $('#groups tr').removeClass("bg-secondary");
    });
    $('#groups tbody tr').click(function(){
        var group_id = $(this).find(".group_id").text();
        var name = $(this).find(".group_name").text();
        var year = $(this).find(".group_year").text();
        $('#groups tr').removeClass("bg-secondary");
        $(this).addClass("bg-secondary");
        $('#fieldGroupGroupId').val(group_id);
        $('#fieldGroupName').val(name+" "+year);
        $('#set_group').modal('toggle');
    });

    $('#fieldTitleBlock').hide();
    $('#fieldGroupBlock').hide();
    $('#fieldTitle').val("");
    $('#fieldGroupGroupId').val("");
    switch($('#fieldType').val()){
        case 'admin':{
            $('#fieldTitleBlock').hide();
            $('#fieldGroupBlock').hide();
            $('#fieldTitle').val("");
            $('#fieldGroupGroupId').val("");
        }break;
        case 'teach':{
            $('#fieldTitleBlock').show();
            $('#fieldGroupBlock').hide();
            $('#fieldGroupGroupId').val("");
        }break;
        case 'student':{
            $('#fieldTitleBlock').hide();
            $('#fieldGroupBlock').show();
            $('#fieldTitle').val("");
        }break;
        default: {
            $('#fieldTitleBlock').hide();
            $('#fieldGroupBlock').hide();
            $('#fieldTitle').val("");
            $('#fieldGroupGroupId').val("");
        }
    }
    $('#fieldType').change(function(){
        switch($(this).val()){
            case 'admin':{
                $('#fieldTitleBlock').hide();
                $('#fieldGroupBlock').hide();
                $('#fieldTitle').val("");
                $('#fieldGroupGroupId').val("");
            }break;
            case 'teach':{
                $('#fieldTitleBlock').show();
                $('#fieldGroupBlock').hide();
                $('#fieldGroupGroupId').val("");
            }break;
            case 'student':{
                $('#fieldTitleBlock').hide();
                $('#fieldGroupBlock').show();
                $('#fieldTitle').val("");
            }break;
            default: {
                $('#fieldTitleBlock').hide();
                $('#fieldGroupBlock').hide();
                $('#fieldTitle').val("");
                $('#fieldGroupGroupId').val("");
            }
        }
    });
} );
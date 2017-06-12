
$(function() {

    // Default select initialization
    $('.select').select2({
        minimumResultsForSearch: Infinity,
        placeholder: function(){
            $(this).data('placeholder');
        }
    });


    /**
     * Edit User
     */
    $('.edit_user').on('click', function() {
        var user_id = $(this).data('value');
        $.ajax({
            type: "GET",
            url: './admin/users/get',
            data: {"user_id": user_id},
            dataType: 'json',
            success: function (data) {
                $('#category_id').val(data.category_id);
                $('#category_title').val(data.category_title);
                $('#price').val(data.price);
                $('#btn_category_submit').removeClass('btn-danger');
                $('#btn_category_submit').addClass('btn-success');
                $('#btn_category_submit').html('Update');
                $('#btn_category_submit').data('value', 'edit');
                $('#modal_category').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    function fillmodalData(details){
        $('#fid').val(details[0]);
        $('#fname').val(details[1]);
        $('#lname').val(details[2]);
        $('#email').val(details[3]);
        $('#gender').val(details[4]);
        $('#country').val(details[5]);
        $('#salary').val(details[6]);
    }
});

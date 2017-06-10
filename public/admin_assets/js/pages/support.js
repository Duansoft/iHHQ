/* ------------------------------------------------------------------------------
 *
 *  # Modal dialogs and extensions
 *
 *  Specific JS code additions for components_modals.html page
 *
 *  Version: 1.0
 *  Latest update: Jan 25, 2016
 *
 * ---------------------------------------------------------------------------- */

$(function() {

    // Ajax Communication
    // ------------------------------

    //$('#create_ticket_form').submit(function(event){
    //    event.preventDefault();
    //    $.ajax({
    //        type: "post",
    //        url: './ticket/create',
    //        data: $("#create_ticket_form").serialize(),
    //        success: function (data) {
    //            swal({
    //                title: data,
    //                confirmButtonColor: "#2196F3"
    //            });
    //        },
    //        error: function (data) {
    //            console.log('Error:', data);
    //        }
    //    });
    //});

    // Default select initialization
    $('.select').select2({
        minimumResultsForSearch: Infinity,
        placeholder: function(){
            $(this).data('placeholder');
        }
    });
});

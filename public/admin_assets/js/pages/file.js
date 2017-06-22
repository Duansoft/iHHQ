
$(function() {

    // Default select initialization
    $('.select').select2({
        minimumResultsForSearch: Infinity,
        placeholder: function(){
            $(this).data('placeholder');
        }
    });

    $('.select1').select2({
        minimumResultsForSearch: Infinity,
        placeholder: function(){
            $(this).data('placeholder');
        }
    });

    // Custom tag class
    $('.tagsinput-custom-tag-class').tagsinput({
        tagClass: function(item){
            return 'label bg-success';
        }
    });


    //
    // Date range picker
    //

    // Basic initialization
    $('.daterange-basic').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default'
    });
});


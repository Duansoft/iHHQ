
$(function() {

    //
    // Loading remote data
    //

    // Format displayed data
    function formatRepo (repo) {
        if (repo.loading) return repo.text;

        var markup = repo.name + " (" + repo.passport_no + ")";

        return markup;
    }

    // Format selection
    function formatRepoSelection (repo) {
        return repo.name || repo.passport_no;
    }

    // Initialize
    $(".select-remote-clients").select2({
        ajax: {
            url: $('meta[name="_searchUser"]').attr('content'),
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term || '',
                    page: params.page || 1
                }
            },
            processResults: function (data, params) {

                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data.results,
                    pagination: {
                        more: (params.page * 50) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });


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

    // Select with search
    $('.select-search').select2({
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


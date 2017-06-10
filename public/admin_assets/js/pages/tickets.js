
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
    $(".select-remote-data").select2({
        ajax: {
            url: $('meta[name="_searchClient"]').attr('content'),
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
        placeholder: "search client",
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });

    // Initialize
    $(".select-remote-hhq").select2({
        ajax: {
            url: $('meta[name="_searchHHQ"]').attr('content'),
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
        placeholder: "search staff",
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });


    // Default initialization
    $('.select').select2({
        minimumResultsForSearch: Infinity
    });

    // Scroll to bottom of the chat on page load. Mainly for demo
    $('.chat-list, .chat-stacked').scrollTop($(this).height());

});


/* ------------------------------------------------------------------------------
 *
 *  # Basic datatables
 *
 *  Specific JS code additions for datatable_basic.html page
 *
 *  Version: 1.0
 *  Latest update: Aug 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function() {

    // Table setup
    // ------------------------------

    $('.datatable').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        columnDefs: [{
            orderable: false,
            width: "120px",
            targets: [ 7 ]
        },{
            visible: false,
            targets: [ 1, 2]
        },{
            render: function ( data, type, row ) {
                return '<div class="media-left media-middle"><a href="#"><img src="' + $('meta[name="_publicURL"]').attr('content') + '/' + row.logo + '" class="img-lg, img-rounded" alt=""></a></div><div class="media-left media-middle"> <h6 class="no-margin">' + row.courier + '</h6></div>';
            },
            targets: 0,
        }, {
            render: function ( data, type, row ) {
                return '<span class="no-margin text-size-large">' + row.name + '<small class="display-block text-muted text-size-small">' + row.description + '</small></span>';
            },
            targets: 3,
        },{
            render: function ( data, type, row ) {
                if (row.status == 0) // delivery
                    return '<span class="label bg-dashboard-user">DELIVERED</span>';
                else if (row.status == 1)
                    return '<span class="label label-success">RECEIVED</span>';
                else if (row.status ==2)
                    return '<span class="label label-danger">RETURNED</span>';
            },
            targets: 6,
        }],
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: $('meta[name="_search"]').attr('content'),
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        },
        "columns": [
            {data: 'logo', name: 'couriers.logo'},
            {data: 'courier', name: 'couriers.name'},
            {data: 'name', name: 'users.name'},
            {data: 'description', name: 'description'},
            {data: 'file_ref', name: 'dispatches.file_ref'},
            {data: 'updated_at', name: 'dispatches.updated_at'},
            {data: 'status', name: 'dispatches.status'},
            {data: 'action', name: 'action'},
        ]
    });


    /**
     * Client Data Table
     */

    $('.datatable-client').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        columnDefs: [{
            visible: false,
            targets: [ 1, 2 ]
        },{
            render: function ( data, type, row ) {
                return '<div class="media-left media-middle"><a href="#"><img src="' + $('meta[name="_publicURL"]').attr('content') + '/' + row.logo + '" class="img-lg, img-rounded" alt=""></a></div><div class="media-left media-middle"> <h6 class="no-margin">' + row.courier + '</h6></div>';
            },
            targets: 0,
        }, {
            render: function ( data, type, row ) {
                return '<span class="no-margin text-size-large">' + row.name + '<small class="display-block text-muted text-size-small">' + row.description + '</small></span>';
            },
            targets: 3,
        }, {
            render: function ( data, type, row ) {
                if (row.status == 0) // delivery
                    return '<span class="label bg-dashboard-user">DELIVERED</span>';
                else if (row.status == 1)
                    return '<span class="label label-success">RECEIVED</span>';
                else if (row.status ==2)
                    return '<span class="label label-danger">RETURNED</span>';
            },
            targets: 6,
        }],
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: $('meta[name="_search"]').attr('content'),
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        },
        "columns": [
            {data: 'logo', name: 'couriers.logo'},
            {data: 'courier', name: 'couriers.name'},
            {data: 'name', name: 'users.name'},
            {data: 'description', name: 'description'},
            {data: 'file_ref', name: 'dispatches.file_ref'},
            {data: 'updated_at', name: 'dispatches.updated_at'},
            {data: 'status', name: 'dispatches.status'}
        ]
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });



    //// Format displayed data
    //function formatRepo (repo) {
    //    if (repo.loading) return repo.text;
    //
    //    var markup = repo.name + " (" + repo.passport_no + ")";
    //
    //    return markup;
    //}
    //
    //// Format selection
    //function formatRepoSelection (repo) {
    //    return repo.name || repo.passport_no;
    //}
    //
    //$(".select-remote-client").select2({
    //    ajax: {
    //        url: $('meta[name="_searchClients"]').attr('content'),
    //        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
    //        dataType: 'json',
    //        delay: 250,
    //        data: function(params) {
    //            return {
    //                q: params.term || '',
    //                page: params.page || 1
    //            }
    //        },
    //        processResults: function (data, params) {
    //            params.page = params.page || 1;
    //
    //            return {
    //                results: data.results,
    //                pagination: {
    //                    more: (params.page * 50) < data.total_count
    //                }
    //            };
    //        },
    //        cache: true
    //    },
    //    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    //    minimumInputLength: 1,
    //    templateResult: formatRepo, // omitted for brevity, see the source of this page
    //    templateSelection: formatRepoSelection, // omitted for brevity, see the source of this page
    //    placeholder: 'Search client',
    //    allowClear: true
    //});

    // Default select initialization
    $('.select-file-ref').select2({
        ajax: {
            url: $('meta[name="_searchFiles"]').attr('content'),
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

                var select2Data = $.map(data.results, function (obj) {
                    obj.id = obj.file_ref;
                    obj.text = obj.file_ref;
                    return obj;
                });

                return {
                    results: select2Data,
                    pagination: {
                        more: (params.page * 50) < data.total_count
                    }
                };
            },
            cache: true
        },
        id: function(item) {return item.file_ref.toString();},
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: function (item) {
            if (item.loading) return item.text;
            return item.file_ref;
        },
        templateSelection: function (item) {return item.file_ref;},
        placeholder: "search file",
        allowClear: true,
    });

    // trigger after selecting from ajax
    $('.select-file-ref').on("select2:select", function(event) {
        $.ajax({
            type: "GET",
            url: $('meta[name="_searchClients"]').attr('content'),
            data: {"file_ref": event.currentTarget.value},
            dataType: 'json',
            success: function (data) {
                initializeClientRefs(data);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    // Default select initialization
    $('.select').select2({
        minimumResultsForSearch: Infinity,
        placeholder: function(){
            $(this).data('placeholder');
        }
    });

    var select2Client = $(".select-remote-client").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "select client",
    });

    function initializeClientRefs(data) {
        select2Client.empty();
        select2Client.select2({
            minimumResultsForSearch: Infinity,
            data: data,
        })

    }

    // Print SVG
    $('svg').on('click', function(){
        {
            var container = $('#container')[0];
            var width = parseFloat(300);
            var height = parseFloat(300);
            var printWindow = window.open('', 'PrintMap', 'width=' + width + ',height=' + height);
            printWindow.document.writeln(container.innerHTML);
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        };
        setTimeout(popUpAndPrint, 500);
    });

});

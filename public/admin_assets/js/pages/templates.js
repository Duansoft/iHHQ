
$(function() {

    //
    // admindatatable
    //

    $('.datatable').DataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            columnDefs: [{
                orderable: true,
                width: '20px',
                targets: [ 0 ]
            },{
                render: function ( data, type, row ) {
                    return '<div class="media-left media-middle"><a href="./templates/' + row.template_id + '/download" download><img src="' + $('meta[name="_publicURL"]').attr('content') + '/' + row.extension + '" class="img-xs" alt=""></a></div>';
                },
                width: '140px',
                targets: 1,
            },{
                visible: false,
                searable: false,
                targets: 2,
            },{
                render: function ( data, type, row ) {
                    return '<a href="./templates/' + row.template_id + '">' + data + '</a>';
                },
                targets: 3,
            }],
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            },
            ajax: {
                url: $('meta[name="_search"]').attr('content'),
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                dataType: 'json',
                cache: true
            },
            columns: [
                {data: 'template_id', name: 'template_id'},
                {data: 'extension', name: 'file_extensions.icon'},
                {data: 'path', name: 'path'},
                {data: 'name', name: 'users.name'},
                {data: 'category', name: 'template_categories.name'},
                {data: 'created_by', name: 'users.name'},
                {data: 'created_at', name: 'created_at'}],
            order: [[ 0, 'ASC' ]]
    });


    //
    // client datatable
    //

    $('.datatable-client').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        columnDefs: [{
            orderable: true,
            width: '20px',
            targets: [ 0 ]
        },{
            render: function ( data, type, row ) {
                return '<div class="media-left media-middle"><a href="./templates/' + row.template_id + '/download" download><img src="' + $('meta[name="_publicURL"]').attr('content') + '/' + row.extension + '" class="img-xs" alt=""></a></div>';
            },
            width: '140px',
            targets: 1,
        },{
            visible: false,
            searable: false,
            targets: 2,
        },{
            width: '150px',
            targets: 3,
        }],
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: $('meta[name="_search"]').attr('content'),
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType: 'json',
            cache: true
        },
        columns: [
            {data: 'template_id', name: 'template_id'},
            {data: 'extension', name: 'file_extensions.icon'},
            {data: 'path', name: 'path'},
            {data: 'category', name: 'template_categories.name'},
            {data: 'name', name: 'name'}],
        order: [[ 0, 'ASC' ]],
    });

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to Search...');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

});

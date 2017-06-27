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

    $('.view_detail').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: $(this).data('url'),
            success: function (data) {
                $('#modal_file_detail').html(data);
                $('#modal_file_detail').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.select').select2({
        minimumResultsForSearch: Infinity
    });

    $('.file-input').fileinput({
        browseLabel: 'Browse',
        browseIcon: '<i class="icon-file-plus"></i>',
        uploadIcon: '<i class="icon-file-upload2"></i>',
        removeIcon: '<i class="icon-cross3"></i>',
        browseClass: 'btn btn-default',
        showUpload: false,
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>'
        },
        initialCaption: "No Receipt selected"
    });

    $(document).on("click", '.btn_pay', function (e) {
        e.preventDefault();
        $('#modal_file_detail').modal('hide');

        $('#amount').val($(this).data('amount'));
        $('#payment_id').val($(this).data('id'));
    });

    $(document).on("click", '.btn-create-ticket', function (e) {
        e.preventDefault();
        $('#modal_file_detail').modal('hide');
        $('#modal_new_ticket').modal('show');
        $('#file_ref').val($(this).data('ref'));
    });

    // Table setup
    // ------------------------------

    $('.table-overview').DataTable({
        autoWidth: false,
        paging: false,
        processing: true,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        columnDefs: [{
            orderable: false,
            width: '100px',
            visible: true,
            targets: 4,
        }, {
            orderable: false,
            searchable: true,
            visible: false,
            targets: 5,
        }],
    });


    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    // Search Bar
    $('#search').on('keyup click', function () {
        $('.table-overview').DataTable().search(
            $('#search').val()
        ).draw();
    });

});

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


    //
    // jQuery animations
    //

    //// Open
    //$('.dropdown-fade, .btn-group-fade').on('show.bs.dropdown', function(e){
    //    $(this).find('.dropdown-menu').fadeIn(250);
    //});
    //
    //// Close
    //$('.dropdown-fade, .btn-group-fade').on('hide.bs.dropdown', function(e){
    //    $(this).find('.dropdown-menu').fadeOut(250);
    //});



    // Table setup
    // ------------------------------

    // Setting datatable defaults
    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{
            orderable: false,
            width: '100px',
            targets: [ 4 ]
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });


    // Basic datatable
    $('.table-overview').DataTable({
        searching: true,
        paging: false,
        bInfo: false,
        bJQueryUI: true,
        sDom: 'lfrtip',
    });


    // Datatable with saving state
    $('.datatable-save-state').DataTable({
        stateSave: true
    });


    // Scrollable datatable
    $('.datatable-scroll-y').DataTable({
        autoWidth: true,
        scrollY: 300
    });



    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


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

@extends("admin/admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/templates.js') }}"></script>
    <script type="text/javascript">
        $(function(){

            //
            // admin data table
            //
            $('.datatable').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                columnDefs: [{
                    orderable: true,
                    width: '50px',
                    targets: [ 0 ]
                },{
                    visible: false,
                    searable: false,
                    targets: 1,
                },{
                    render: function ( data, type, row ) {
                        return '<a href="./templates/' + row.template_id + '">' + data + '</a>';
                    },
                    targets: 3,
                },{
                    width: '120px',
                    targets: 5,
                },{
                    render: function ( data, type, row ) {
                        return '<div class="media-left media-middle"><a href="./templates/' + row.template_id + '/download" download><img src="' + $('meta[name="_publicURL"]').attr('content') + '/' + row.extension + '" class="img-xs" alt=""></a></div>';
                    },
                    width: '140px',
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
                    dataType: 'json',
                    cache: true
                },
                columns: [
                    {data: 'template_id', name: 'template_id'},
                    {data: 'path', name: 'path'},
                    {data: 'category', name: 'template_categories.name'},
                    {data: 'name', name: 'templates.name'},
                    {data: 'description', name: 'templates.description'},
                    {data: 'size', name: 'size'},
                    {data: 'extension', name: 'file_extensions.icon'}],
                order: [[ 0, 'ASC' ]]
            });

            // Add placeholder to the datatable filter option
            $('.dataTables_filter input[type=search]').attr('placeholder','Type to Search...');

            // Enable Select2 select for the length option
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2><span class="">Legal Templates</span></h2>
            </div>

            @permission('create-logistics')
            <div class="heading-elements">
                <a href="{{ url('admin/templates/create') }}"><button type="button" class="btn btn-default heading-btn"><i class="icon-add position-left"></i> New Template</button></a>
            </div>
            @endpermission
        </div>
    </div>
    <!-- /page header -->
@endsection

@section("content")
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="_search" content="{{ url('admin/templates/get') }}"/>
    <meta name="_publicURL" content="{{ url('') }}"/>

    <!-- Content area -->
    <div class="content col-lg-11">

        <!-- Error Message -->
        @if (count($errors) > 0)
            <div class="alert alert-danger no-border">
                <ul>
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                    @foreach ($errors->all() as $error)
                        <li>
                            <span class="text-semibold">{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if(Session::has('flash_message'))
            <div class="alert alert-success no-border">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">{{ Session::get('flash_message') }}</span>
            </div>
        @endif

        <div class="panel panel-flat">
            <table class="table datatable">
                <thead class="active alpha-grey">
                <tr>
                    <th>ID</th>
                    <th>Path</th>
                    <th>Category</th>
                    <th>File Name</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>Download</th>
                </tr>
                </thead>
            </table>
        </div>

    </div>
    <!-- /content area -->

    <!-- make payment modal -->
    <div id="modal_template" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Ticket</h5>
                </div>

                <div class="modal-body">
                    <form class="form" action="{{ url('admin/tickets/')}}" method="post">
                        {{ csrf_field() }}
                        <fieldset></fieldset>
                        <div class="text-right pt-10">
                            <button type="submit" class="btn btn-primary"> Save Changes<i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /make payment modal -->
@endsection
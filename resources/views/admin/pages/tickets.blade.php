@extends("admin.admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>

    <script type="text/javascript">

        $(function() {

            @if(isset($isCompletedTickets))
                var url = '{{ url('admin/tickets/complete/get') }}';
                var subfixUrl = "../tickets/"
            @elseif(isset($isPendingTickets))
                var url = '{{ url('admin/tickets/pending/get') }}';
                var subfixUrl = "../tickets/"
            @else
                var url = '{{ url('admin/tickets/get') }}';
                var subfixUrl = "./tickets/"
            @endif

            // Table setup
            // ------------------------------
            $('.datatable-basic').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                columnDefs: [{
                    orderable: true,
                    width: '50px',
                    targets: [ 0 ]
                },{
                    render: function ( data, type, row ) {
                        return '<a href="' + subfixUrl + row.ticket_id + '">' + data + '</a>';
                    },
                    targets: 1,
                },{
                    render: function ( data, type, row ) {
                        return '<span class="label label-info">' + data + '</span>';
                    },
                    width: '100px',
                    targets: 3,
                },{
                    width: '150px',
                    targets: [ 2, 4, 5, 6, 7 ]
                }],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                },
                ajax: {
                    url: url
                },
                "columns": [
                    {data: 'ticket_id', name: 'ticket_id'},
                    {data: 'subject', name: 'subject'},
                    {data: 'file_ref', name: 'file_ref'},
                    {data: 'status', name: 'ticket_statuses.name'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'agent', name: 'agents.name'},
                    {data: 'owner', name: 'owners.name'},
                    {data: 'category', name: 'ticket_categories.name'},
                ],
                "order": [[ 0, "desc" ]]
            });

            // Add placeholder to the datatable filter option
            $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

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
        <div class="page-header-content">
            <div class="page-title">
                <h2><span>Tickets</span></h2>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-component">
            <ul class="breadcrumb">
                <li><a href="#"><i class="icon-home2 position-left"></i> Ticket</a></li>
            </ul>

            <ul class="breadcrumb-elements">
                <li><a href="{{url('admin/tickets/create')}}"><i class="icon-add position-left"></i> New Ticket</a></li>
            </ul>
        </div>
    </div>
    <!-- /page header -->
@endsection


@section("content")
    <!-- Content area -->
    <div class="content">

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

        <!-- row area -->
        <div class="row">
            <div class="col-lg-3">
                <!-- Navigation -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">Tickets</h6>
                    </div>

                    <div class="list-group no-border mb-5">
                        @if(isset($isCompletedTickets))
                            <a href="{{ url('admin/tickets/') }}" class="list-group-item"><i class="icon-lifebuoy"></i> Active Tickets<span class="badge badge-success pull-right">{{ $activeTickets }}</span></a>
                            <a href="{{ url('admin/tickets/pending') }}" class="list-group-item"><i class="icon-question3"></i> Pending Tickets<span class="badge badge-danger pull-right">{{ $pendingTickets }}</span></a>
                            <div class="list-group-divider"></div>
                            <a href="{{ url('admin/tickets/complete') }}" class="list-group-item active"><i class="icon-close2"></i> Completed Tickets<span class="badge badge-default pull-right">{{ $completedTickets }}</span></a>
                        @elseif(isset($isPendingTickets))
                            <a href="{{ url('admin/tickets/') }}" class="list-group-item"><i class="icon-lifebuoy"></i> Active Tickets<span class="badge badge-success pull-right">{{ $activeTickets }}</span></a>
                            <a href="{{ url('admin/tickets/pending') }}" class="list-group-item active"><i class="icon-question3"></i> Pending Tickets<span class="badge badge-danger pull-right">{{ $pendingTickets }}</span></a>
                            <div class="list-group-divider"></div>
                            <a href="{{ url('admin/tickets/complete') }}" class="list-group-item"><i class="icon-close2"></i> Completed Tickets<span class="badge badge-default pull-right">{{ $completedTickets }}</span></a>
                        @else
                            <a href="{{ url('admin/tickets/') }}" class="list-group-item active"><i class="icon-lifebuoy"></i> Active Tickets<span class="badge badge-success pull-right">{{ $activeTickets }}</span></a>
                            <a href="{{ url('admin/tickets/pending') }}" class="list-group-item"><i class="icon-question3"></i> Pending Tickets<span class="badge badge-danger pull-right">{{ $pendingTickets }}</span></a>
                            <div class="list-group-divider"></div>
                            <a href="{{ url('admin/tickets/complete') }}" class="list-group-item"><i class="icon-close2"></i> Completed Tickets<span class="badge badge-default pull-right">{{ $completedTickets }}</span></a>
                        @endif
                    </div>
                </div>
                <!-- /navigation -->

            </div>

            <div class="col-lg-9">
                <div class="panel panel-flat">
                    <table class="table datatable-basic">
                        <thead class="active alpha-grey">
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>File Ref</th>
                            <th>Status</th>
                            <th>Last updated</th>
                            <th>Agent</th>
                            <th>Owner</th>
                            <th>Category</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- /row area -->

    </div>
    <!-- /content area -->
@endsection

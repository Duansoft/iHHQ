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
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2><span> Support</span></h2>
            </div>

            @role('admin')
            <div class="heading-elements">
                <a href="{{url('admin/tickets/create')}}"><button type="button" class="btn btn-default heading-btn"><i class="icon-add position-left"></i> New Ticket</button></a>
            </div>
            @endrole
        </div>
    </div>
    <!-- /page header -->
@endsection


@section("content")
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

        @role('admin')
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
        @endrole

        @if (Auth::user()->hasRole('lawyer') || Auth::user()->hasRole('staff'))
        <!-- Highlighted tabs -->
        @if (empty($tickets))
        <div class="row">
            <div class="col-lg-11 col-md-12">
                <div class="col-lg-3 no-padding">
                    <div class="panel panel-white">
                        <div class="panel-body no-padding">
                            <ul class="media-list media-list-linked media-list-bordered">
                                @foreach($tickets as $ticket)
                                    <li class="media border-left-orange border-left-lg">
                                        <a href="#" class="media-link">
                                            <div class="media-left">
                                                <img src="{{ asset('upload/avatars/' . $ticket->client->photo) }}" class="img-lg, img-circle" alt="{{ asset('admin_assets/images/avatars/avatar.png') }}">
                                                {{--<span class="badge bg-dashboard-user media-badge">5</span>--}}
                                            </div>
                                            <div class="media-body">
                                                <span class="media-heading text-semibold">{{ $ticket->client->name }}</span>
                                                <span class="text-muted">{{ $ticket->category->name }}</span>
                                                <span class="display-block">{{ $ticket->file_ref != '' ? 'File Ref - ' . $ticket->file_ref : '' }}{{ $ticket->subject }}</span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 no-padding">
                    <!-- Left annotation position -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h5 class="panel-title" style="margin-right: 100px;">{{ isset($ticket) ? $ticket->subject : 'Ticket' }}</h5>
                            <div class="heading-elements">
                                <div class="heading-btn no-margin-left">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-icon text-grey pt-5 pb-5 pl-15 pr-15"><i class="icon-cog7"></i></button>
                                        <button type="button" class="btn btn-default btn-icon text-grey pt-5 pb-5 pl-15 pr-15"><i class="icon-paperplane"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body">
                            <ul class="media-list chat-stacked content-group">
                                @foreach($messages as $message)
                                    <li class="media">
                                        <div class="media-left"><img src="{{ asset('upload/avatars/' . $message->photo) }}" class="img-lg, img-circle" alt=""></div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <a class="text-semibold text-grey">{{$message->name}}</a>
                                                <span class="media-annotation dotted">{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $message->created_at)->format('h:i: A - j M') }}</span>
                                            </div>
                                            {{$message->message}}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="media date-step content-divider mb-20">
                                <span>Reply</span>
                            </div>

                            <textarea name="enter-message" class="form-control content-group" rows="3" cols="1" placeholder="Enter your message..."></textarea>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary">Send<i class="icon-arrow-right14 position-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /left annotation position-->
                </div>
            </div>
        </div>
        @else
        <h6 class="text-grey text-italic text-size-large">There are no still relevant tickets</h6>
        @endif

        <!-- /highlighted tabs -->
        @endif

    </div>
    <!-- /content area -->
@endsection

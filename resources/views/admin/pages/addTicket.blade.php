@extends("admin.admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/tickets.js') }}"></script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2><span class="text-semibold">Support</span></h2>
            </div>

            <div class="heading-elements">
                <a href="{{url('admin/tickets')}}"><button type="button" class="btn btn-default heading-btn"><i class="icon-circle-left2 position-left"></i> BACK</button></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection


@section("content")
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="_searchClient" content="{{ url('admin/users/clients/search') }}"/>

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

        <!-- row area -->
        <div class="row">
            <div class="col-lg-3">
                <!-- Navigation -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">Tickets</h6>
                    </div>

                    <div class="list-group no-border mb-5">
                        <a href="{{ url('admin/tickets/') }}" class="list-group-item"><i class="icon-lifebuoy"></i> Active Tickets<span class="badge badge-success pull-right">{{ $activeTickets }}</span></a>
                        <a href="{{ url('admin/tickets/pending') }}" class="list-group-item"><i class="icon-question3"></i> Pending Tickets<span class="badge badge-danger pull-right">{{ $pendingTickets }}</span></a>
                        <div class="list-group-divider"></div>
                        <a href="{{ url('admin/tickets/complete') }}" class="list-group-item"><i class="icon-close2"></i> Completed Tickets<span class="badge badge-default pull-right">{{ $completedTickets }}</span></a>
                    </div>
                </div>
                <!-- /navigation -->
            </div>

            <div class="col-lg-9">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Create Ticket</h5>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{url('admin/tickets/create')}}" method="post">
                            {{ csrf_field() }}
                            <fieldset class="content-group">
                                <div class="form-group">
                                    <label class="form-label col-lg-2">Category</label>
                                    <div class="col-lg-10">
                                        <select class="select" name="category_id[]">
                                            @foreach($ticket_categories as $ticket_category)
                                                <option value="{{$ticket_category->id}}">{{$ticket_category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-2">Client</label>
                                    <div class="col-lg-10">
                                        <select class="select-remote-data" name="client_id" data-placeholder="search client"></select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label col-lg-2">File Ref<span>(optional)</span></label>
                                    <div class="col-lg-10">
                                        <select class="select" name="file_ref">
                                            <option>None selected</option>
                                            @foreach($files as $file)
                                                <option value="{{$file->file_ref}}">{{$file->file_ref}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label col-lg-2">Subject</label>
                                    <div class="col-lg-10">
                                        <textarea rows="5" cols="5" class="form-control" name="subject" placeholder="write your question." required>{{old('subject')}}</textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary"> Create Ticket<i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /content area -->
@endsection

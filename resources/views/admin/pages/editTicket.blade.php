@extends("admin.admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/bootbox.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/components_modals.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/tickets.js') }}"></script>
    <script type="text/javascript">
        // Scroll Bottom
        $('#chat_window').scrollTop($('#chat_window')[0].scrollHeight);
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2><span class="text-semibold">Tickets</span></h2>
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
    <meta name="_searchHHQ" content="{{ url('admin/users/hhq/search') }}"/>

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
                        <h4 class="panel-title">Tickets</h4>
                    </div>

                    <div class="list-group no-border mb-5">
                        <a href="{{ url('admin/tickets/') }}" class="list-group-item"><i class="icon-lifebuoy"></i> Active Tickets<span class="badge badge-success pull-right">{{ $activeTickets }}</span></a>
                        <a href="{{ url('admin/tickets/pending') }}" class="list-group-item"><i class="icon-question3"></i> Pending Tickets<span class="badge badge-danger pull-right">{{ $pendingTickets }}</span></a>
                        <div class="list-group-divider"></div>
                        <a href="{{ url('admin/tickets/complete') }}" class="list-group-item"><i class="icon-close2"></i> Completed Tickets<span class="badge badge-default pull-right">{{ $completedTickets }}</span></a>
                    </div>
                </div>
                <!-- /navigation -->

                <!-- Detail -->
                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">Ticket Details</h4>
                    </div>

                    <div class="panel-body">
                        <div class="col-lg-12 no-padding">
                            <div class="col-lg-6 no-padding">
                                <p> <strong>Ticket ID</strong>: {{ $ticket->ticket_id }}</p>
                                <p> <strong>File Ref</strong>: {{ $ticket->file_ref }}</p>
                                <p> <strong>Owner</strong>: {{ $ticket->owner }}</p>
                                <p> <strong>Agent</strong>: {{ $ticket->agent }}</p>
                            </div>
                            <div class="col-lg-6 no-padding">
                                <p> <strong>Category</strong>: {{ $ticket->category }}</p>
                                <p> <strong>Status</strong>: {{ $ticket->status }}</p>
                                <p> <strong>Created</strong>: {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $ticket->created_at)->diffForHumans() }}</p>
                                <p> <strong>Last Update</strong>: {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $ticket->updated_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="btn-group btn-group-justified pt-20">
                            <a href="#" data-toggle="modal" data-target="#modal_edit_ticket" class="btn btn-default">Edit</a>
                            @if($ticket->status_id == 0)
                                <a href="{{ url('admin/tickets/' . $ticket->ticket_id . '/open') }}" class="btn btn-default" onclick="return confirm('Are you sure?')">Reopen</a>
                            @else
                                <a href="{{ url('admin/tickets/' . $ticket->ticket_id . '/complete') }}" class="btn btn-default" onclick="return confirm('Are you sure?')">Complete</a>
                            @endif
                            <a href="{{ url('admin/tickets/' . $ticket->ticket_id . '/delete') }}" class="btn btn-default" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </div>
                </div>
                <!-- Detail -->
            </div>

            <div class="col-lg-9">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h4 class="panel-title">{{ $ticket->subject }}</h4>
                    </div>

                    <div class="panel-body">
                        <ul id="chat_window" class="media-list chat-stacked content-group">
                            @foreach($messages as $message)
                                <li class="media">
                                    <div class="media-left"><img src="{{ asset('upload/avatars/' . $message->sender_photo) }}" class="img-lg, img-circle" alt=""></div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a class="text-semibold text-grey">{{ $message->sender_name }}</a>
                                            <span class="media-annotation dotted">{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $message->created_at)->diffForHumans() !!}</span>
                                        </div>
                                        {{ $message->message }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="media date-step content-divider mb-20">
                            <span>Reply</span>
                        </div>

                        <form method="post">
                            <textarea name="message" class="form-control content-group" rows="3" cols="1" placeholder="Enter your message..." required></textarea>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Send Message<i class="icon-arrow-right14 position-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- make payment modal -->
        <div id="modal_edit_ticket" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-yellow-800">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">Edit Ticket</h5>
                    </div>

                    <div class="modal-body">
                        <form class="form" action="{{ url('admin/tickets/' . $ticket->ticket_id) }}" method="post">
                            {{ csrf_field() }}
                            <fieldset>
                                <label class="text-semibold text-danger text-size-large mb-10">Client: <span> {{ $ticket->owner }}</span></label>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="select" name="category_id[]">
                                                @foreach($ticket_categories as $ticket_category)
                                                    <option value="{{$ticket_category->id}}">{{$ticket_category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>File Ref<span>(optional)</span></label>
                                            <select class="select" name="file_id">
                                                <option>None selected</option>
                                                @foreach($files as $file)
                                                    <option value="{{$file->file_id}}">{{$file->file_ref}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Subject</label>
                                    <textarea rows="3" cols="3" class="form-control" name="subject" placeholder="write your question." required>{{ $ticket->subject }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Assigned Staff</label>
                                    <select class="select-remote-hhq" name="staff_id" data-placeholder="search client"></select>
                                </div>

                            </fieldset>
                            <div class="text-right pt-10">
                                <button type="submit" class="btn btn-primary"> Save Changes<i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /make payment modal -->

    </div>
    <!-- /content area -->
@endsection

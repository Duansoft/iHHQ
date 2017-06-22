@extends("admin.admin_app")

@section("css")
@endsection

@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/components_modals.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/support.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            $('#btn-create').on('click', function(e){
                e.preventDefault();
                var subject = $('#title').val();
                if (subject) {
                    $('#subject').val(subject);
                    $('#modal_new_ticket').modal('show');
                    $('#title').val("");
                } else {
                    alert('Required Subject to Create Ticket');
                }
            });

            // Scroll Bottom
            $('#chat_window').scrollTop($('#chat_window')[0].scrollHeight);
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <!-- Header content -->
        <div class="page-header-content">
            <div class="page-title">
                <h2> Support</h2>
            </div>
        </div>
        <!-- /header content -->
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

        <!-- Highlighted tabs -->
        @if(isset($ticket))
        <div class="row">
            <div class="col-lg-11 col-md-12">
                <div class="col-lg-3 no-padding">
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <div class="input-group">
                                <input id="title" type="text" class="form-control" placeholder="Create a new ticket" readonly>
                                <span class="input-group-btn">
                                    <button class="btn btn-default pl-20 pr-20"><i class="icon-plus3 text-grey"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="panel-body no-padding">
                            <ul class="media-list media-list-linked media-list-bordered">
                                @foreach($tickets as $ticket1)
                                <li class="media {{ $ticket1->ticket_id  == $ticket->ticket_id ? "border-left-orange-300 border-left-lg" : "" }}">
                                    <a href="{{ url('admin/tickets/'. $ticket1->ticket_id) }}" class="media-link">
                                        <div class="media-left">
                                            <img src="{{ asset('upload/avatars/' . $ticket1->photo) }}" class="img-lg, img-circle">
                                            {{--<span class="badge bg-dashboard-user media-badge">5</span>--}}
                                        </div>
                                        <div class="media-body">
                                            <span class="media-heading text-semibold">{{ $ticket1->name }}</span>
                                            <span class="text-muted">{{ $ticket1->category }}</span>
                                            <span class="display-block">{{ $ticket1->file_ref != '' ? 'File Ref - ' . $ticket1->file_ref : '' }}</span>
                                            <span class="display-block">{{ $ticket1->subject }}</span>
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
                        <div class="panel-heading" style="margin-bottom: 10px; margin-top: 10px;">
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
                            <ul id="chat_window" class="media-list chat-stacked content-group">
                                @foreach($messages as $message)
                                <li class="media">
                                    <div class="media-left"><img src="{{ asset('upload/avatars/' . $message->photo) }}" class="img-lg, img-circle" alt=""></div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a class="text-semibold text-grey">{{$message->name}}</a>
                                            <span class="media-annotation dotted">{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $message->created_at)->format('h:i: A - j M') }}</span>
                                        </div>
                                        @foreach(json_decode($message->message) as $key => $value)
                                            @if ($key == "text")
                                                {{ $value }}
                                            @elseif ($key == "attachments")
                                                @foreach($value as $val)
                                                    <div class="mt-10">
                                                        <a href="{{ url('support/download?path=' . $val->path . '&name=' . $val->name) }}" download><i class="icon-file-download"></i> {{ $val->name }}</a> <span class="text-muted"> - {{ $val->size }}</span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </li>
                                @endforeach
                            </ul>

                            <div class="media date-step content-divider mb-20">
                                <span>Reply</span>
                            </div>

                            <form action="{{ url('admin/tickets/'. $ticket->ticket_id .'/messages') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <textarea name="message" class="form-control content-group" rows="3" cols="1" placeholder="Enter your message..." required></textarea>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">Send<i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /left annotation position-->
                </div>
            </div>
        </div>
        @else
            <h6 class="text-grey text-italic text-size-large">There are no assigned tickets</h6>
        @endif
        <!-- /highlighted tabs -->
    </div>
    <!-- /content area -->
@endsection



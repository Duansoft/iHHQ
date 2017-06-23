@extends("app")

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
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/components_modals.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/support.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            $('.btn-create').on('click', function(e){
                e.preventDefault();
                $('#subject').val("");
                $('#modal_new_ticket').modal('show');
                $('#title').val("");
                $('#attachment').show();
                $('#file-uploader').hide();
                $('input:checkbox').attr('checked', false);
            });

            $('input:checkbox').change(function() {
                if ($(this).is(':checked')) {
                    $('#attachment').hide();
                    $('#file-uploader').show();
                } else {
                    $('#attachment').show();
                    $('#file-uploader').hide();
                }
            });

            // file-input
            $('.file-input').fileinput({
                browseLabel: 'Browse',
                browseIcon: '<i class="icon-file-plus"></i>',
                uploadIcon: '<i class="icon-file-upload2"></i>',
                removeIcon: '<i class="icon-cross3"></i>',
                browseClass: 'btn btn-default',
                showUpload: false,
                maxFileCount: 3,
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>'
                },
                initialCaption: "No file selected"
            });

            // Scroll Bottom
            try {
                $('#chat_window').scrollTop($('#chat_window')[0].scrollHeight);
            }
            catch(err) {
            }
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <!-- Header content -->
        <div class="page-header-content">
            <div class="page-title">
                <h2>Support Tickets</h2>
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
    <div class="row">
        <div class="col-lg-11 col-md-12">
            <div class="col-lg-3 no-padding">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <div class="input-group">
                            <button class="form-control btn btn-default btn-create"> <span class="text-muted">Create a new ticket</span></button>
                            <span class="input-group-btn">
                                <button class="btn btn-default pl-20 pr-20 btn-create" type="button" data-toggle="modal"><i class="icon-plus3 text-grey"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="panel-body no-padding">
                        <ul class="media-list media-list-linked media-list-bordered">
                            @foreach($tickets as $ticket1)
                            <li class="media {{ $ticket1->ticket_id  == $ticket->ticket_id ? "border-left-orange-300 border-left-lg" : "" }}">
                                <a href="{{ url('support/tickets/'. $ticket1->ticket_id) }}" class="media-link">
                                    <div class="media-left">
                                        <img src="{{ asset('upload/avatars/' . $ticket1->client->photo) }}" class="img-lg, img-circle">
                                        {{--<span class="badge bg-dashboard-user media-badge">5</span>--}}
                                    </div>
                                    <div class="media-body">
                                        <span class="media-heading text-semibold">{{ $ticket1->client->name }}</span>
                                        <span class="text-muted">{{ $ticket1->category->name }}</span>
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
                    <div class="panel-heading mt-10">
                        <h5 class="panel-title">{{ isset($ticket) ? $ticket->subject : 'Ticket' }}</h5>
                    </div>

                    @if(sizeof($messages) > 0)
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

                        @if (isset($ticket))
                        <div class="media date-step content-divider mb-20">
                            <span>Reply</span>
                        </div>

                        <form action="{{ url('support/tickets/'. $ticket->ticket_id .'/messages') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <textarea name="message" class="form-control content-group" rows="3" cols="1" placeholder="Enter your message..." required></textarea>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Send<i class="icon-arrow-right14 position-right"></i></button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
                <!-- /left annotation position-->
            </div>
        </div>
    </div>
    <!-- /highlighted tabs -->
</div>
<!-- /content area -->


<!-- New Ticket Modal Dialog -->
<div id="modal_new_ticket" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow-800">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">New Support Ticket</h5>
            </div>

            <form id="create_ticket_form" class="form-horizontal" action="{{url('support/tickets/create')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <fieldset class="ml-20 mr-20 p-10">
                    <div class="form-group">
                        <label>Department</label>
                        <select class="select form-control" name="department_id">
                            @foreach($departments as $department)
                                <option value="{{$department->department_id}}">{{$department->department_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>File Ref
                            <small class="text-grey"> (optional)</small>
                        </label>
                        <select class="select form-control" name="file_ref">
                            <option value="0">None</option>
                            @foreach($files as $file)
                                <option value="{{$file->file_ref}}">{{$file->file_ref}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Subject</label>
                        <input id="subject" type="text" name="subject" class="form-control" placeholder="" required>
                    </div>

                    <div class="form-group mb-10">
                        <textarea name="message" class="form-control" rows="3" cols="1" placeholder="Write your question..." required></textarea>
                    </div>

                    <div class="form-group mb-10" id="attachment">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Have an attachment?
                            </label>
                        </div>
                    </div>

                    <div id="file-uploader" class="form-group mt-20 mb-20" hidden>
                        <input type="file" class="file-input" name="attachments[]"  data-show-caption="true" multiple="multiple">
                    </div>
                </fieldset>

                <div class="form-group bg-grey-F8FAFC no-margin p-10 text-grey-300">
                    <label class="control-label col-md-8">Messages are kept confidential</label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success form-control">Create Ticket</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /New Ticket Modal Dialog -->
@endsection



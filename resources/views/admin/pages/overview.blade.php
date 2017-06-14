@extends("admin.admin_app")

@section("css")
    <style>
        .dataTables_filter {
            display:none;
        }
    </style>
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/loaders/progressbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/velocity/velocity.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/velocity/velocity.ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/buttons/spin.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/buttons/ladda.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/jquery.matchHeight.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/overview.js') }}"></script>
    <script type="text/javascript">
        $(function() {

            $('.view_detail').on('click', function(e){
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
                showUpload: false,
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>'
                },
                initialCaption: "No Receipt selected"
            });


            $('.btn_pay').on('click', function(e){
                e.preventDefault();
                $('#modal_file_detail').modal('hide');

                $('#amount').val($(this).data('amount'));
                $('#payment_id').val($(this).data('id'));
            });

        });
    </script>
    @endsection


@section("page-header")
@endsection


@section("content")
    <!-- Content area -->
    <div class="content no-padding-top">

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
                <div class="col-md-9">
                    <div class="page-title">
                        <h2>Hi,<span class="text-warning text-capitalize"> {{ Auth::user()->name }}</span></h2>
                    </div>
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h3 class="panel-title">My Files
                                <small class="ml-20 pl-20 border-left text-grey">{{sizeof($files)}} active</small>
                            </h3>
                            <div class="heading-elements">
                                <form class="heading-form" action="#">
                                    <div class="form-group has-feedback">
                                        <input id="search" type="search" class="form-control" placeholder="Search by file ref, name or tags">
                                        <div class="form-control-feedback">
                                            <i class="icon-search4 text-size-base text-muted"></i>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-overview">
                            <thead class="active alpha-grey">
                            <tr>
                                <th>File</th>
                                <th>Updated</th>
                                <th>Billing</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($files as $file)
                                <tr>
                                    <td>
                                        <h6 class="no-margin">{{$file->project_name}}
                                            <small class="display-block text-muted text-size-small">File Ref. {{$file->file_ref}}</small>
                                        </h6>
                                    </td>
                                    <td>
                                <span class="no-margin">
                                    {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $file->updated_at)->toFormattedDateString() !!}
                                    <small class="display-block text-muted text-size-small">
                                        {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $file->updated_at)->diffForHumans() !!}
                                    </small>
                                </span>
                                    </td>
                                    <td>
                                        <span class="no-margin">RM{{$file->outstanding_amount - $file->paid_amount}}<small class="display-block text-warning text-size-small">Due</small></span>
                                    </td>
                                    <td>
                                        @if ($file->percent == 100)
                                            <span class="no-margin">Completed</span>
                                            <div>
                                                <div class="progress progress-rounded progress-xxs">
                                                    <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                                                </div>
                                                <small>100% Complete</small>
                                            </div>
                                        @else
                                            <span class="no-margin">Progress</span>
                                            <div>
                                                <div class="progress progress-rounded progress-xxs">
                                                    <div class="progress-bar progress-bar-success" style="width: {!! $file->percent !!}%"></div>
                                                </div>
                                                <small>{{ $file->percent }}% Complete</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-fade">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ url('admin/files/' . $file->file_id) . '/detail' }}">View Detail</a></li>
                                                {{--@role('lawyer')--}}
                                                {{--<li><a class="view_detail" data-url="{{ url('admin/overview/detail/?id=' . $file->file_id) }}">View Detail</a></li>--}}
                                                {{--@endrole--}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="page-title">
                        <h3>News & Announcement</h3>
                    </div>
                    @if(count($announcements) > 0)
                        <div class="panel">
                            <div class="panel-body no-padding">
                                <div class="list-group no-padding no-border">
                                    @foreach($announcements as $announcement)
                                        <a class="list-group-item">
                                            <div class="list-group-item-heading">
                                                <h6><i class="icon-mail-read text-success"></i><span class="pl-20">{{ $announcement->title }}</span></h6>
                                            </div>
                                            <div class="list-group-item-text">
                                                <span class="text-grey">{{ $announcement->content }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        <!-- /highlighted tabs -->
    </div>
    <!-- /content area -->

    <!-- Upload Modal Dialog -->
    <div id="modal_file_detail" class="modal fade">
    </div>
    <!-- /Upload Modal Dialog -->

    <!-- Milestone CRUD Modal Dialog -->
    <div id="modal_milestone" class="modal fade"></div>
    <!-- /Milestone CRUD Modal Dialog -->

    <!-- Upload Modal Dialog -->
    <div id="modal_complete_case" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload A New Document</h4>
                </div>

                <form id="modal_upload" class="form-horizontal" action="{{ url('admin/files/' . $file->file_id . '/cases/documents') }}"  method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input id="index" type="hidden" name="index" value="">

                    <fieldset class="ml-20 mr-20 p-10">
                        <div class="form-group">
                            <label>Activity</label>
                            <input id="input_activity" class="form-control reset_control" placeholder="activity" readonly>
                        </div>

                        <div class="form-group">
                            <label>File Name</label>
                            <input class="form-control reset_control" name="name" placeholder="Name of document" required>
                        </div>

                        <div class="form-group">
                            <label>File Ref</label>
                            <input class="form-control" name="file_ref" value="{{ $file->file_ref }}" readonly="readonly">
                        </div>

                        <div class="form-group">
                            <label>File</label>
                            <input type="file" class="file-input reset_control" name="file" accept=".pdf, .doc, .docx" data-allowed-file-extensions='["pdf", "doc", "docx"]' data-show-caption="true">
                        </div>
                    </fieldset>
                    <div class="form-group bg-grey-F8FAFC no-margin p-15 text-grey-300">
                        <div class="col-md-4 col-md-offset-4">
                            <button type="submit" class="btn btn-success form-control text-size-large">Upload Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Upload Modal Dialog -->

    <!-- New Milestone Modal Dialog -->
    <div id="modal_create_milestone" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create New Milestone</h4>
                </div>

                <form class="form-horizontal" action="{{ url('admin/files/' . $file->file_id . '/milestone') }}"  method="post">
                    {{ csrf_field() }}

                    <fieldset class="ml-20 mr-20 p-10">
                        <div class="form-group">
                            <label>Activity Description</label>
                            <input type="text" class="form-control reset_control" name="activity" placeholder="" required>
                        </div>

                        <div class="form-group">
                            <label>Milestone</label>
                            <input type="number" step="1" class="form-control reset_control" name="milestone" placeholder="RM 1,000.00" required>
                        </div>

                        <div class="form-group">
                            <label>Duration</label>
                            <input type="number" step="1" class="form-control reset_control" name="duration" placeholder="5 days" required>
                        </div>

                        <div class="form-group">
                            <label>File Ref</label>
                            <input class="form-control" name="file_ref" value="{{ $file->file_ref }}" readonly="readonly">
                        </div>
                    </fieldset>
                    <div class="form-group bg-grey-F8FAFC no-margin p-15 text-grey-300">
                        <div class="col-md-4 col-md-offset-4">
                            <button type="submit" class="btn btn-success form-control text-size-large">Create Milestone</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Upload Modal Dialog -->

    <!-- Upload Dco Modal Dialog -->
    <div id="modal_complete_case" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload A New Document</h4>
                </div>

                <form id="modal_upload" class="form-horizontal" action="{{ url('admin/files/' . $file->file_id . '/cases/documents') }}"  method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input id="index" type="hidden" name="index" value="">

                    <fieldset class="ml-20 mr-20 p-10">
                        <div class="form-group">
                            <label>Activity</label>
                            <input id="input_activity" class="form-control reset_control" placeholder="activity" readonly>
                        </div>

                        <div class="form-group">
                            <label>File Name</label>
                            <input class="form-control reset_control" name="name" placeholder="Name of document" required>
                        </div>

                        <div class="form-group">
                            <label>File Ref</label>
                            <input class="form-control" name="file_ref" value="{{ $file->file_ref }}" readonly="readonly">
                        </div>

                        <div class="form-group">
                            <label>File</label>
                            <input type="file" class="file-input reset_control" name="file" accept=".pdf, .doc, .docx" data-allowed-file-extensions='["pdf", "doc", "docx"]' data-show-caption="true">
                        </div>
                    </fieldset>
                    <div class="form-group bg-grey-F8FAFC no-margin p-15 text-grey-300">
                        <div class="col-md-4 col-md-offset-4">
                            <button type="submit" class="btn btn-success form-control text-size-large">Upload Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Upload Doc Modal Dialog -->

    <!-- upload receipt modal -->
    <div id="modal_upload_receipt" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Upload Receipt</h5>
                </div>

                <form id="upload_form" action="#" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="modal-body">
                        <div class="form-group">
                            <label>File Ref</label>
                            <input id="file_ref" type="text" placeholder="" name="file_ref" class="form-control" readonly required>
                        </div>

                        <div class="form-group">
                            <label>Amount</label>
                            <input id="amount" type="text" placeholder="" name="amount" class="form-control" readonly required>
                        </div>

                        <div class="form-group">
                            <label>Receipt Name</label>
                            <input id="name" type="text" placeholder="" name="name" class="form-control" required>
                        </div>

                        <div class="form-group file-receipt">
                            <label>Receipt</label>
                            <input id="receipt" type="file" class="file-input reset_control" name="receipt" accept=".pdf" data-allowed-file-extensions='["pdf"]' data-show-caption="true">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success form-control">Upload Receipt</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /upload receipt modal -->

@endsection
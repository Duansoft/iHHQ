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
                                                <li><a class="view_detail" data-url="{{ url('admin/overview/detail/?id=' . $file->file_id) }}">View Detail</a></li>
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

@endsection
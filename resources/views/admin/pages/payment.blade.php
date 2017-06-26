@extends("admin.admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/velocity/velocity.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/velocity/velocity.ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/buttons/spin.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/buttons/ladda.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/components_modals.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            $('#search').on( 'keyup click', function (e) {
               var key = e.target.value.toLowerCase();
               $('.file_panel').each(function(){
                    if (key == "") {
                        $(this).show();
                    } else {
                        var file_ref = $(this).attr("data-fileRef").toLowerCase();
                        var project_name = $(this).attr("data-pName").toLowerCase();
                        var tags = $(this).attr("data-tags").toLowerCase();

                        if (isContain(key, file_ref, project_name, tags)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
               });
            });

            function isContain(key, fileRef, name, tags)
            {
                return fileRef.match(key) || name.match(key) || tags.match(key)
            }

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

            $('.btn_upload').on('click', function(){
                $('#file_ref').val($(this).data('ref'));
                $('#amount').val($(this).data('amount'));
                //$('#receipt').fileupload('clear');
                $('#name').val('');
                $('#upload_form').attr('action', $(this).data('url'));
            });
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <!-- Header content -->
        <div class="page-header-content col-md-11">
            <div class="page-title">
                <h2><span>Billing & Payment</span></h2>
            </div>
            <div class="heading-elements">
                <form class="heading-form" action="#">

                    <div class="form-group has-feedback">
                        <input id="search" type="search" class="form-control" placeholder="Search by file ref, name or tags">
                        <div class="form-control-feedback">
                            <i class="icon-search4 text-size-base text-muted"></i>
                        </div>
                    </div>
                    @role('admin')
                    <button type="button" class="btn btn-default ml-5"><i class="icon-plus2"></i> Create Payment</button>
                    @endrole
                </form>
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
            <div class="alert alert-danger no-border col-md-11">
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
            <div class="alert alert-success no-border col-md-11">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">{{ Session::get('flash_message') }}</span>
            </div>
        @endif

        <!-- Highlighted tabs -->
        <div class="row">
            <div class="col-lg-11">
                @foreach($files as $key => $file)
                <div class="panel panel-white file_panel {{ $key == 0 ? "panel-collapse" : "panel-collapsed" }}" data-fileRef="{!! $file->file_ref !!}", data-tags="{!! $file->tags !!}" data-pName="{!! $file->project_name !!}">
                    <div class="panel-heading">
                        <h4 class="panel-title no-margin-bottom"><span>{{ $file->project_name }}</span></h4>
                        <span class="no-margin text-muted">File Ref - {{ $file->file_ref }}</span>
                        <div class="heading-elements">
                            <form class="heading-form pr-5" action="#">
                                <div class="form-group">
                                    <span class="{{ $file->outstanding_amount > 0 ? "text-danger-400" : "" }}">{{$file->currency}}{{$file->outstanding_amount}}</span>
                                    <span class="display-block text-muted text-size-small">Total Outstanding</span>
                                </div>
                            </form>
                            <form class="heading-form pr-5" style="min-width: 80px;" action="#">
                                <div class="form-group">
                                    <span>{{$file->currency}}{{$file->paid_amount}}</span>
                                    <span class="display-block text-muted text-size-small">Total Paid</span>
                                </div>
                            </form>
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>

                    <table class="table datatable-basic">
                        <thead class="active alpha-grey">
                        <tr>
                            <th class="col-md-2">Purpose</th>
                            <th class="col-md-2">Date Issued</th>
                            <th class="col-md-4">Remarks</th>
                            <th class="col-md-2">Amount</th>
                            <th class="col-md-2">Status</th>
                            <th class="col-md-1">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach ($file->payments as $payment)
                                <tr>
                                    <td>
                                        <span class="no-margin">{{ $payment->purpose }}</span>
                                    </td>
                                    <td>
                                        <span class="no-margin">{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $payment->created_at)->toFormattedDateString() !!}</span>
                                    </td>
                                    <td>
                                        <span class="no-margin">{{$payment->remarks}}</span>
                                    </td>
                                    <td>
                                        <span class="no-margin">RM{{$payment->amount}}</span>
                                    </td>
                                    <td>
                                        <span class="label {{ $payment->getStatusClass() }}">{{ $payment->status }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-fade">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ url('admin/payments/' . $payment->payment_id . '/invoice/download')}}" download> View Invoice</a></li>
                                                @if ($payment->status == "BANK DEPOSIT")
                                                    <li><a href="{{ url('admin/payments/' . $payment->payment_id . '/download')}}" download> View Receipt</a></li>
                                                    <li><a href="{{ url('admin/files/' . $file->file_id . '/payments/' . $payment->payment_id) }}">Confirmed</a></li>
                                                @elseif ($payment->status == "RECEIVED")
                                                    @if ($payment->receipt == null)
                                                    <li><a class="btn_upload" data-toggle="modal" data-target="#modal_upload_receipt" data-ref="{{$payment->file_ref}}" data-amount="{{$payment->amount}}" data-url="{{ url('admin/payments/' . $payment->payment_id . '/upload')}}">Upload Receipt</a></li>
                                                    @else
                                                    <li><a href="{{ url('admin/payments/' . $payment->payment_id . '/download')}}" download> View Receipt</a></li>
                                                    @endif
                                                @elseif ($payment->status == "REQUEST")
                                                    <li><a href="{{ url('admin/payments/' . $payment->payment_id . '/resend')}}"> Resend Request</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
        <!-- /highlighted tabs -->
        <span class="text-grey text-italic pl-10">Note: All payments shall subject to verification by our Accounts department. Kindly allow 2-3 working days for processing and changes to be reflected on your dashboard.</span>
    </div>
    <!-- /content area -->

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




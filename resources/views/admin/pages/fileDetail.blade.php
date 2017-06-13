@extends("admin/admin_app")

@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}" xmlns="http://www.w3.org/1999/html"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    {{--<script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript">
    $(function() {
        // Basic example
        $('.file-input').fileinput({
            browseLabel: 'Browse',
            browseIcon: '<i class="icon-file-plus"></i>',
            uploadIcon: '<i class="icon-file-upload2"></i>',
            removeIcon: '<i class="icon-cross3"></i>',
            showUpload: false,
            layoutTemplates: {
                icon: '<i class="icon-file-check"></i>'
            },
            initialCaption: "No Document selected"
        });

        // When upload Upload Document, init Modal Dialog
        $('.btn-dlg').on('click', function(){
            $('.reset_control').val("");
        });

        $('.btn-upload-doc').on('click', function(){
            var index = $(this).data("info");
            var activity = $(this).data("activity");
            $('#input_activity').val(activity);
            $('#modal_complete_case #index').val(index);
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
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2>Files</h2>
            </div>

            <div class="heading-elements">
                <a href="{{url('admin/files')}}"><button type="button" class="btn btn-default heading-btn"><i class="icon-circle-left2 position-left"></i> BACK</button></a>
            </div>
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

        <div class="row">
            <div class="col-lg-9">
                <!-- My messages -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title"><span class="text-capitalize">{{$file->project_name}}</span> <span class="text-muted"> ( File Ref. {{$file->file_ref}} )</span></h6>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-lg nav-tabs nav-justified nav-tabs-bottom no-margin no-border-radius ">
                        <li class="active">
                            <a href="#tab-status" class="text-size-small text-uppercase" data-toggle="tab">
                                Status
                            </a>
                        </li>

                        <li>
                            <a href="#tab-payment" class="text-size-small text-uppercase" data-toggle="tab">
                                Payment
                            </a>
                        </li>

                        <li>
                            <a href="#tab-document" class="text-size-small text-uppercase" data-toggle="tab">
                                Docs
                            </a>
                        </li>
                    </ul>
                    <!-- /tabs -->


                    <!-- Tabs content -->
                    <div class="tab-content">
                        <div class="tab-pane active fade in has-padding" id="tab-status">
                            <table class="table text-nowrap">
                                @foreach(json_decode($file->cases) as $index => $case)
                                <tr class="no-border">
                                    <td class="no-border"><span class="text-size-large">{{ $case->activity }}</span></td>
                                    <td class="no-border"><span>RM{{ $case->milestone }}</span></td>
                                    <td class="no-border pull-right">
                                        @if ($case->status == "Completed")
                                            <button type="button" class="btn pl-10 pr-10 bg-slate" readonly="readonly"> Completed</button>
                                        @else
                                            <button type="button" class="btn-upload-doc btn-dlg btn pl-20 pr-20 bg-danger-400" data-toggle="modal" data-target="#modal_complete_case" data-info="{{$index}}" data-activity="{{ $case->activity }}"> Upload</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            <div class="text-right">
                                <button type="button" class="btn btn-dlg btn-success pl-20 pr-20 mt-20" data-toggle="modal" data-target="#modal_create_milestone"><i class="icon-plus22 position-left"></i> Add Milestone</button>
                            </div>
                        </div>

                        <!-- Payment Tab -->
                        <div class="tab-pane fade has-padding" id="tab-payment">
                            <table class="table text-nowrap">
                                @foreach($file->payments as $payment)
                                    <tr class="no-border">
                                        <td class="no-border"><span class="text-size-large"> {{ $payment->purpose }}</span></td>
                                        <td class="no-border"><span> {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $payment->created_at)->toFormattedDateString() !!}</span></td>
                                        <td class="no-border"><span> {{ $payment->remarks }}</span></td>
                                        <td class="no-border"><span> RM{{ $payment->amount }}</span></td>
                                        <td class="no-border text-center"><span class="label {{ $payment->getStatusClass() }}"> {{ $payment->status }}</span></td>
                                        <td class="no-border pull-right">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
                                                @if ($payment->status == "BANK DEPOSIT")
                                                    <ul class="dropdown-menu">
                                                        <li><a href="{{ url('admin/payments/' . $payment->payment_id . '/download')}}">Download Receipt</a></li>
                                                        <li><a href="{{ url('admin/files/' . $file->file_id . '/payments/' . $payment->payment_id) }}">Confirmed</a></li>
                                                    </ul>
                                                @elseif ($payment->status == "RECEIVED")
                                                    <ul class="dropdown-menu">
                                                        @if ($payment->receipt == null)
                                                            <li><a class="btn_upload" data-toggle="modal" data-target="#modal_upload_receipt" data-ref="{{$payment->file_ref}}" data-amount="{{$payment->amount}}" data-url="{{ url('admin/payments/' . $payment->payment_id . '/upload')}}">Upload Receipt</a></li>
                                                        @else
                                                            <li><a href="{{ url('admin/payments/' . $payment->payment_id . '/download')}}">Download Receipt</a></li>
                                                        @endif
                                                    </ul>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="text-right">
                                <button type="button" class="btn btn-dlg btn-success pl-20 pr-20 mt-20" data-toggle="modal" data-target="#modal_create_payment"><i class="icon-plus22 position-left"></i> Request Payment</button>
                            </div>
                        </div>
                        <!-- /Payment Tab -->

                        <!-- Document Tab -->
                        <div class="tab-pane fade has-padding" id="tab-document">
                            <table class="table text-nowrap">
                                <tbody>
                                @foreach($documents as $document)
                                    <tr class="no-border">
                                        <td class="no-border" style="width: 36px;">
                                            @if ($document->extension == "pdf")
                                                <a href="{{url('admin/files/documents/'.$document->document_id .'/download')}}" download><img src="{{ asset('admin_assets\images\extensions\pdf.png') }}" class="img-sm" alt=""></a>
                                            @else
                                                <a href="{{url('admin/files/documents/'.$document->document_id .'/download')}}" download><img src="{{ asset('admin_assets\images\extensions\doc.png') }}" class="img-sm" alt=""></a>
                                            @endif
                                        </td>
                                        <td class="no-border">
                                            <div class="text-default display-inline-block">
                                                <span class="text-semibold">{{ $document->name }}</span>
                                                <span class="display-block text-muted">By {{ $document->owner }}</span>
                                            </div>
                                        </td>
                                        <td class="no-border"><span> {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $document->created_at)->toFormattedDateString() !!}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-right">
                                <button type="button" class="btn btn-dlg btn-success pl-20 pr-20 mt-20" data-toggle="modal" data-target="#modal_upload_document"><i class="icon-plus22 position-left"></i> Upload</button>
                            </div>
                        </div>
                        <!-- /Document Tab -->
                    </div>
                    <!-- /tabs content -->
                </div>
                <!-- /my messages -->
            </div>
            <div class="col-lg-3">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">File Information</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <p class="col-md-6">Lawyer</p>
                            <div class="col-md-6">
                                <ul class="list-condensed list-unstyled text-right">
                                    @foreach($participants as $participant)
                                        @if ($participant->role == "lawyer")
                                            <li class="text-size-large">{{ $participant->name }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <p class="col-md-6">Staff</p>
                            <div class="col-md-6">
                                <ul class="list-condensed list-unstyled text-right">
                                    @foreach($participants as $participant)
                                        @if ($participant->role == "staff")
                                            <li class="text-size-large">{{ $participant->name }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <legend class="text-bold p-5 mb-10"></legend>

                        <div class="row">
                            <p class="col-md-6">Client</p>
                            <div class="col-md-6">
                                <ul class="list-condensed list-unstyled text-right">
                                    @foreach($participants as $participant)
                                        @if ($participant->role == "client")
                                            <li class="text-size-large">{{ $participant->name }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <p class="col-md-6">Spectator</p>
                            <div class="col-md-6">
                                <ul class="list-condensed list-unstyled text-right">
                                    @foreach($participants as $participant)
                                        @if ($participant->role == "spectator")
                                        <li class="text-size-large">{{ $participant->name }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <legend class="text-bold p-5 mb-10"></legend>

                        <div class="row">
                            <p class="col-md-6">Total Outstanding</p>
                            <p class="col-md-6 text-right">RM{{ $file->outstanding_amount }}</p>
                        </div>
                        <div class="row">
                            <p class="col-md-6">Total Paid</p>
                            <p class="col-md-6 text-right">RM{{ $file->paid_amount }}</p>
                        </div>

                        <legend class="text-bold p-5 mb-10"></legend>

                        <div class="row">
                            <p class="col-md-6">Tags</p>
                            <p class="col-md-6 text-right">{{$file->tags}}</p>
                        </div>
                        <div class="row">
                            <p class="col-md-6">Open Date</p>
                            <p class="col-md-6 text-right">{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $file->created_at)->toFormattedDateString() !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

    <!-- Upload Modal Dialog -->
    <div id="modal_upload_document" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload A New Document</h4>
                </div>

                <form id="modal_upload" class="form-horizontal" action="{{ url('admin/files/' . $file->file_id . '/documents') }}"  method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <fieldset class="ml-20 mr-20 p-10">
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

    <!-- Create Payment Dialog -->
    <div id="modal_create_payment" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Request Payment</h4>
                </div>

                <form id="modal_upload" class="form-horizontal" action="{{ url('admin/files/' . $file->file_id . '/payments') }}"  method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <fieldset class="ml-20 mr-20 p-10">
                        <div class="form-group">
                            <label>Purpose</label>
                            <input class="form-control reset_control" name="purpose" placeholder="" required/>
                        </div>

                        <div class="form-group">
                            <label>Amount</label>
                            <input class="form-control reset_control" name="amount" required/>
                        </div>

                        <div class="form-group">
                            <label>Remarks<span class="text-muted"> (optional)</span></label>
                            <input class="form-control reset_control" name="remarks" placeholder="">
                        </div>

                        <div class="form-group">
                            <label>Reconfirm Ref File</label>
                            <input class="form-control" name="file_ref" value="{{ $file->file_ref }}" readonly required>
                        </div>
                    </fieldset>

                    <div class="form-group bg-grey-F8FAFC no-margin p-15 text-grey-300">
                        <div class="col-md-4 col-md-offset-4">
                            <button type="submit" class="btn btn-success form-control text-size-large">Create</button>
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


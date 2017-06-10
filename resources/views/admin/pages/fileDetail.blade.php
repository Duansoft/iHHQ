@extends("admin/admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
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
    });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Files</h2>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-component">
            <ul class="breadcrumb">
                <li><a href="{{ url('admin/files') }}"><i class="icon-home2 position-left"></i> Files</a></li>
            </ul>

            <ul class="breadcrumb-elements">
                <li><a href="{{ url('admin/files/create') }}"><i class="icon-add position-left"></i> New File</a></li>
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

        <div class="row">
            <div class="col-lg-8">
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

                        </div>

                        <div class="tab-pane fade has-padding" id="tab-payment">
                            <table class="table text-nowrap">
                                @foreach($file->payments as $payment)
                                    <tr class="no-border">
                                        <td class="no-border"><span class="text-size-large"> {{ $payment->purpose }}</span></td>
                                        <td class="no-border"><span> {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $payment->created_at)->toFormattedDateString() !!}</span></td>
                                        <td class="no-border"><span> {{ $payment->remarks }}</span></td>
                                        <td class="no-border"><span> RM{{ $payment->amount }}</span></td>
                                        <td class="no-border text-center"><span class="label bg-blue"> {{ $payment->status }}</span></td>
                                        <td class="no-border pull-right">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" data-toggle="modal" data-target="#modal_make_payment">Make Payment</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#modal_request_payment">Request Payment</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="text-right">
                                <button type="button" class="btn btn-success pl-20 pr-20 mt-20" data-toggle="modal" data-target="#modal_create_payment"><i class="icon-plus3 position-left"></i> Request Payment</button>
                            </div>
                        </div>

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
                                        <td class="no-border pull-right">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" data-toggle="modal" data-target="#modal_make_payment">Make Payment</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#modal_request_payment">Request Payment</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--<ul class="media-list">--}}
                                {{--@foreach($documents as $document)--}}
                                    {{--<li class="media">--}}
                                        {{--<div class="media-left">--}}
                                            {{--@if ($document->extension == "pdf")--}}
                                                {{--<a href="{{url('admin/files/documents/'.$document->document_id .'/download')}}" download><img src="{{ asset('admin_assets\images\extensions\pdf.png') }}" class="img-sm" alt=""></a>--}}
                                            {{--@else--}}
                                                {{--<a href="{{url('admin/files/documents/'.$document->document_id .'/download')}}" download><img src="{{ asset('admin_assets\images\extensions\doc.png') }}" class="img-sm" alt=""></a>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}

                                        {{--<div class="media-body text-size-large">--}}
                                            {{--{{ $document->name }}--}}
                                            {{--<span class="display-block text-muted"> By {{ $document->owner }}</span>--}}
                                            {{--<span class="media-annotation pull-right"> {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $document->created_at)->toFormattedDateString() !!}</span>--}}
                                        {{--</div>--}}
                                    {{--</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                            <div class="text-right">
                                <button type="button" class="btn btn-success pl-20 pr-20 mt-20" data-toggle="modal" data-target="#modal_upload_document"><i class="icon-plus3 position-left"></i> Upload</button>
                            </div>
                        </div>
                    </div>
                    <!-- /tabs content -->
                </div>
                <!-- /my messages -->
            </div>
            <div class="col-lg-4">
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
                                            <li class="text-size-large text-success-800">{{ $participant->name }}</li>
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
                                            <li class="text-size-large text-success-800">{{ $participant->name }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <p class="col-md-6">Client</p>
                            <div class="col-md-6">
                                <ul class="list-condensed list-unstyled text-right">
                                    @foreach($participants as $participant)
                                        @if ($participant->role == "client")
                                            <li class="text-size-large text-success-800">{{ $participant->name }}</li>
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
                                        <li class="text-size-large text-success-800">{{ $participant->name }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <legend class="text-bold p-5 mb-10"></legend>

                        <div class="row">
                            <p class="col-md-6">Total Outstanding</p>
                            <p class="col-md-6 text-right text-warning-800">RM{{ $file->outstanding_amount }}</p>
                        </div>
                        <div class="row">
                            <p class="col-md-6">Total Paid</p>
                            <p class="col-md-6 text-right text-success-800">RM{{ $file->paid_amount }}</p>
                        </div>

                        <legend class="text-bold p-5 mb-10"></legend>

                        <div class="row">
                            <p class="col-md-6">Tags</p>
                            <p class="col-md-6 text-right text-primary-800">{{$file->tags}}</p>
                        </div>
                        <div class="row">
                            <p class="col-md-6">Open Date</p>
                            <p class="col-md-6 text-right text-primary-800">{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $file->created_at)->toFormattedDateString() !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                <input class="form-control" name="name" placeholder="Name of document" required>
                            </div>

                            <div class="form-group">
                                <label>File Ref</label>
                                <input class="form-control" name="file_ref" value="{{ $file->file_ref }}" readonly="readonly">
                            </div>

                            <div class="form-group">
                                <label>File</label>
                                <input type="file" class="file-input" name="file" accept=".pdf, .doc, .docx" data-allowed-file-extensions='["pdf", "doc", "docx"]' data-show-caption="true">
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
                                <input class="form-control" name="purpose" placeholder="" required>
                            </div>

                            <div class="form-group">
                                <label>Amount</label>
                                <input class="form-control" name="amount" required>
                            </div>

                            <div class="form-group">
                                <label>Remarks<span class="text-muted"> (optional)</span></label>
                                <input class="form-control" name="remarks" placeholder="">
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
    </div>
    <!-- /content area -->
@endsection


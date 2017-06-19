@extends("app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/components_modals.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            $('.file-input').fileinput({
                browseLabel: 'Browse',
                browseIcon: '<i class="icon-file-plus"></i>',
                uploadIcon: '<i class="icon-file-upload2"></i>',
                removeIcon: '<i class="icon-cross3"></i>',
                browseClass: 'btn btn-default',
                showUpload: false,
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>'
                },
                initialCaption: "No Receipt selected"
            });

            // Search function
            $('#search').on('keyup click', function(e){
                var key = e.target.value;
                $('.file_panel').each(function(){
                    if (key == "") {
                        $(this).show();
                    } else {
                        var fileRef = $(this).attr("data-fileRef");
                        var name = $(this).attr("data-pName");
                        var tags = $(this).attr("data-tags");

                        if (fileRef.match(key) || name.match(key) || tags.match(key)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
            });

            // trigger after selecting from ajax
            $('.payment_option').on("select2:select", function(event) {
                if (event.currentTarget.value == "BillPlz") {
                    $('.file-receipt').hide();
                } else if (event.currentTarget.value == "Bank") {
                    $('.file-receipt').show();
                }
            });

            $('.btn_pay').on('click', function(){
                $('#amount').val($(this).data('amount'));
                $('#payment_id').val($(this).data('id'));
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
                @foreach($files as $file)
                    <div class="panel panel-white panel-collapsed file_panel" data-fileRef="{!! $file->file_ref !!}", data-tags="{!! $file->tags !!}" data-pName="{!! $file->project_name !!}">
                        <div class="panel-heading">
                            <h4 class="panel-title no-margin-bottom"><span>{{ $file->project_name }}</span></h4>
                            <span class="no-margin text-muted">File Ref - {{ $file->file_ref }}</span>
                            <div class="heading-elements">
                                <form class="heading-form pr-5" action="#">
                                    <div class="form-group">
                                        <span>{{$file->currency}}{{$file->outstanding_amount}}</span>
                                        <span class="display-block text-muted text-size-small">Total Outstanding</span>
                                    </div>
                                </form>
                                <form class="heading-form pr-5" action="#">
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
                                <th>Actions</th>
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
                                            @if ($payment->status == "REQUEST")
                                                <ul class="dropdown-menu">
                                                    <li><a class="btn_pay" href="#" data-toggle="modal" data-target="#modal_make_payment" data-amount="{{$payment->amount}}" data-id="{{$payment->payment_id}}">Make Payment</a></li>
                                                </ul>
                                            @else
                                                @if (!empty($payment->receipt))
                                                <ul class="dropdown-menu">
                                                    <li><a class="text-muted" onclick="return false;">Download Receipt</a></li>
                                                </ul>
                                                @endif
                                            @endif
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


        <!-- make payment modal -->
        <div id="modal_make_payment" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-yellow-800">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Make Payment</h4>
                    </div>

                    <form action="{{ url('payment/pay') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" id="payment_id" name="payment_id" >

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Select Method</label>
                                <select class="select payment_option" name="method">
                                    <option value="bank">Bank</option>
                                    {{--<option value="billplz">BillPlz</option>--}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" id="amount" class="form-control" name="amount" step="0.01" placeholder="RM1000.00" readonly required>
                            </div>

                            <div class="form-group file-receipt">
                                <label>Receipt</label>
                                <input type="file" class="file-input reset_control" name="receipt" accept=".pdf" data-allowed-file-extensions='["pdf"]' data-show-caption="true">
                            </div>

                            <div class="form-group">
                                <label>User Password</label>
                                <input type="password" name="password" placeholder="password" class="form-control" required>
                            </div>

                            <div class="form-group p-10 mb-20 well">
                                <label class="text-grey">Bank Deposite</label>
                                <label class="no-margin-bottom">If you have selected Bank Deposit as payment method please prepare to attach a copy of the bank receipt.</label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success form-control">Proceed to pay</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /make payment modal -->

    </div>
    <!-- /content area -->
@endsection




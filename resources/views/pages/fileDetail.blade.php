<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-yellow-800">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{$file->project_name}} ( File Ref. {{$file->file_ref}} )</h4>
        </div>

        <div class="row no-padding no-margin mt-20 ml-10 mr-10">
            <div class="col-lg-8">
                <!-- My messages -->
                <div class="panel panel-white" style="min-height: 600px;">
                    <!-- Tabs -->
                    <ul class="nav nav-lg nav-tabs nav-justified nav-tabs-bottom no-margin no-border-radius mt-15">
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

                        <li>
                            <a href="#tab-ticket" class="text-size-small text-uppercase" data-toggle="tab">
                                Tickets
                            </a>
                        </li>
                    </ul>
                    <!-- /tabs -->

                    <!-- Tabs content -->
                    <div class="tab-content">
                        <div class="tab-pane active fade in has-padding no-padding" id="tab-status">
                            <table class="table">
                                <thead>
                                <tr class="no-border active">
                                    <th>Activities</th>
                                    <th class="text-center col-lg-1">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(json_decode($file->cases) as $index => $case)
                                    <tr class="no-border">
                                        <td class="no-border"><span>{{ $case->activity }}</span></td>
                                        <td class="no-border pull-right">
                                            @if ($case->status == "Completed")
                                                <button type="button" class="btn bg-slate" readonly="readonly"> Completed</button>
                                            @else
                                                <button type="button" class="btn bg-dashboard-user"> {{$case->status}}</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade in has-padding no-padding" id="tab-payment">
                            <table class="table">
                                <thead>
                                <tr class="no-border active">
                                    <th>Activities</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th class="text-center" >Status</th>
                                    <th class="text-center col-lg-1">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($file->payments as $payment)
                                    <tr class="no-border">
                                        <td class="no-border"><span class="text-size-large"> {{ $payment->purpose }}</span></td>
                                        <td class="no-border"><span> {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $payment->created_at)->toFormattedDateString() !!}</span></td>
                                        <td class="no-border"><span> RM{{ $payment->amount }}</span></td>
                                        <td class="no-border text-center"><span class="label {{ $payment->getStatusClass() }}"> {{ $payment->status }}</span></td>
                                        <td class="no-border pull-right">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                @if ($payment->status == "REQUEST")
                                                    <li><a href="{{ url('payments/' . $payment->payment_id . '/invoice/download')}}" download> Download Invoice</a></li>
                                                    <li><a class="btn_pay" href="#" data-dismiss="modal" data-toggle="modal" data-target="#modal_make_payment" data-amount="{{$payment->amount}}" data-id="{{$payment->payment_id}}">Make Payment</a></li>
                                                @else
                                                    @if (!empty($payment->receipt))
                                                        <li><a href="{{ url('payment/' . $payment->payment_id . '/download') }}"> Download Receipt</a></li>
                                                    @endif
                                                @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade has-padding no-padding" id="tab-document">
                            <table class="table">
                                <thead>
                                <tr class="no-border active">
                                    <th>File</th>
                                    <th>Detail</th>
                                    <th>Date</th>
                                    <th class="text-center col-lg-1">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($documents as $document)
                                    <tr class="no-border">
                                        <td class="no-border" style="width: 36px;">
                                            @if ($document->extension == "pdf")
                                                <a href="{{url('overview/documents/'.$document->document_id .'/download')}}" download><img src="{{ asset('admin_assets\images\extensions\pdf.png') }}" class="img-sm" alt=""></a>
                                            @else
                                                <a href="{{url('overview/documents/'.$document->document_id .'/download')}}" download><img src="{{ asset('admin_assets\images\extensions\doc.png') }}" class="img-sm" alt=""></a>
                                            @endif
                                        </td>
                                        <td class="no-border">
                                            <div class="text-default display-inline-block">
                                                <span class="text-semibold">{{ $document->name }}</span>
                                                <span class="display-block text-muted">By {{ $document->owner }}</span>
                                            </div>
                                        </td>
                                        <td class="no-border"><span> {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $document->created_at)->toFormattedDateString() !!}</span></td>
                                        <td class="no-border pull-right"><a href="{{url('overview/documents/'.$document->document_id .'/download')}}" download><button type="button" class="btn btn-default"> Download</button></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @role('lawyer')
                            <div class="text-right">
                                <button type="button" class="btn btn-dlg btn-success pl-20 pr-20 mt-20 mr-20" data-toggle="modal" data-target="#modal_upload_document"><i class="icon-plus22 position-left"></i> Upload Document</button>
                            </div>
                            @endrole
                            @role('staff')
                            <div class="text-right">
                                <button type="button" class="btn btn-dlg btn-success pl-20 pr-20 mt-20 mr-20" data-toggle="modal" data-target="#modal_upload_document"><i class="icon-plus22 position-left"></i> Upload Document</button>
                            </div>
                            @endrole
                        </div>

                        <div class="tab-pane fade has-padding no-padding" id="tab-ticket">
                            <table class="table">
                                <thead>
                                <tr class="no-border active">
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th class="text-center col-lg-1">Detail</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($tickets as $ticket)
                                    <tr class="no-border">
                                        <td class="no-border">
                                            <span>{{ $ticket->subject }}</span>
                                        </td>
                                        <td class="no-border"><span> {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $ticket->created_at)->toFormattedDateString() !!}</span></td>
                                        <td class="no-border pull-right"><a href="{{url('support/tickets/'.$ticket->ticket_id)}}"><button type="button" class="btn btn-default"> Detail</button></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @role('client')
                            <div class="text-right">
                                {{--<a class="btn-create-ticket" data-dismiss="modal" data-toggle="modal" data-target="#modal_new_ticket"><button type="button" class="btn btn-success pl-20 pr-20 mt-20 mr-20" ><i class="icon-plus22 position-left"></i> Create Ticket</button></a>--}}
                                <a class="btn-create-ticket" data-dismiss="modal" data-ref="{{$file->file_ref}}"><button type="button" class="btn btn-success pl-20 pr-20 mt-20 mr-20" ><i class="icon-plus22 position-left"></i> Create Ticket</button></a>
                            </div>
                            @endrole

                        </div>
                    </div>
                    <!-- /tabs content -->
                </div>
                <!-- /my messages -->
            </div>
            <div class="col-lg-4">
                <div class="panel panel-white" style="min-height: 600px;">
                    <div class="panel-heading" style="padding-top: 24px; padding-bottom: 10px;">
                        <span class="panel-title">File Information</span>
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
                        {{--<div class="row">--}}
                            {{--<p class="col-md-6">Spectator</p>--}}
                            {{--<div class="col-md-6">--}}
                                {{--<ul class="list-condensed list-unstyled text-right">--}}
                                    {{--@foreach($participants as $participant)--}}
                                        {{--@if ($participant->role == "spectator")--}}
                                            {{--<li class="text-size-large">{{ $participant->name }}</li>--}}
                                        {{--@endif--}}
                                    {{--@endforeach--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</div>--}}

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
</div>
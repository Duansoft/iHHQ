@extends("admin.admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/loaders/progressbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/bootstrap_select.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/logistics.js') }}"></script>
    <script type="text/javascript">
    </script>
@endsection



@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2>Logistics</h2>
            </div>

            <div class="heading-elements">
                <a href="{{url('admin/logistics')}}"><button type="button" class="btn btn-default heading-btn"><i class="icon-circle-left2 position-left"></i> BACK</button></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection



@section("content")
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="_searchClients" content="{{ url('admin/files/clients') }}"/>
    <meta name="_searchFiles" content="{{ url('admin/files/seek') }}"/>

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

        <div class="panel panel-flat">
            <div class="panel-body">
                <form class="form-horizontal" action="{{ isset($dispatch) ? url("admin/logistics/" .$dispatch->dispatch_id. "") : url('admin/logistics/create') }}" method="post">
                    {{ csrf_field() }}

                    <fieldset class="content-group">
                        @if (isset($dispatch))
                            <input type="hidden" name="qr_code" value="{{$dispatch->qr_code}}"/>
                        @else
                            <input type="hidden" name="qr_code" value="{{$code}}"/>
                        @endif

                        <div class="form-group">
                            <div class="text-center">
                                <div id="container" class="display-inline-block">
                                    @if (isset($dispatch))
                                        {!! \QrCode::size(300)->generate($dispatch->qr_code) !!}
                                    @else
                                        {!! \QrCode::size(300)->generate($code) !!}
                                    @endif
                                </div>
                                <p>Click QR code to print</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">File Ref</label>
                            <div class="col-lg-10">
                                @if (isset($dispatch))
                                    <input type="text" class="form-control" name="file_ref" value="{{$dispatch->file_ref}}" readonly>
                                @else
                                    <select class="select-file-ref" name="file_ref" data-placeholder="search file" readonly></select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Client</label>
                            <div class="col-lg-10">
                                @if (isset($dispatch))
                                    <input type="text" class="form-control" value="{{$user->name}}" readonly required>
                                    <input type="hidden" class="form-control" name="client_id" value="{{$user->id}}" readonly required>
                                @else
                                    <select class="select-remote-client" name="client_id" data-placeholder="select client"></select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Receiver</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="receiver" placeholder="receiver" value="{{ isset($dispatch) ? $dispatch->receiver : old('receiver') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Description</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="description" maxlength="20" placeholder="tracking ID12213" value="{{ isset($dispatch) ? $dispatch->description : old('description') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Service Provider</label>
                            <div class="col-lg-10">
                                <select class="select" name="courier_id">
                                    @foreach($couriers as $courier)
                                        @if (isset($dispatch))
                                            <option value="{{ $courier->courier_id }}" {{$courier->courier_id == $dispatch->courier_id ? 'selected' : ''}}>{{ $courier->name }}</option>
                                        @else
                                            <option value="{{ $courier->courier_id }}">{{ $courier->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Address</label>
                            <div class="col-lg-10">
                                <input class="form-control" name="address" value="{{ isset($dispatch) ? $dispatch->address : old('address') }}" required>
                            </div>
                        </div>

                        {{--<div class="form-group">--}}
                            {{--<label class="control-label col-lg-2">Delivery By</label>--}}
                            {{--<div class="col-lg-10">--}}
                                {{--<select class="select" name="delivery_by">--}}
                                    {{--@if (isset($dispatch))--}}
                                    {{--<option value="Ground" {{$dispatch->delivery_by == "Ground" ? 'selected' : ''}}>Ground</option>--}}
                                    {{--<option value="Airplane" {{$dispatch->delivery_by == "Airplane" ? 'selected' : ''}}>Airplane</option>--}}
                                    {{--@else--}}
                                    {{--<option value="Ground">Ground</option>--}}
                                    {{--<option value="Airplane">Airplane</option>--}}
                                    {{--@endif--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="form-group">
                            <label class="control-label col-lg-2">Status</label>
                            <div class="col-lg-10">
                                <select class="select" name="status">
                                    @if (isset($dispatch))
                                        <option value="0" {{$dispatch->status == 0 ? "selected" : ""}}>Delivered</option>
                                        <option value="1" {{$dispatch->status == 1 ? "selected" : ""}}>Received</option>
                                        <option value="2" {{$dispatch->status == 2 ? "selected" : ""}}>Returned</option>
                                    @else
                                        <option value="0">Delivered</option>
                                        <option value="1">Received</option>
                                        <option value="2">Returned</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <div class="text-right">
                        @if (isset($dispatch))
                        <a href="{{ url('/admin/logistics/' . $dispatch->dispatch_id . '/delete') }}" onclick="confirm('Are you sure to remove?')" class="mr-10"><button type="button" class="btn btn-danger">Delete<i class="icon-arrow-right14 position-right"></i></button></a>
                        @endif
                        <button type="submit" class="btn btn-primary">Submit<i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


<!-- Add Edit User Modal Dialog -->

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-yellow-800">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">User</h5>
        </div>

        <div class="modal-body">
            <form action="{{url('/admin/users')}}" enctype="multipart/form-data" method="post">

                {{csrf_field()}}

                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email address"
                           value="{{old('email')}}" required>
                    @if ($errors->has('email'))
                        <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label">
                                Name
                                <small class="text-grey">(as per NRIC/Passport)</small>
                            </label>
                            <input type="text" class="form-control" name="name" placeholder="Your Name" value="{{old('name')}}" required>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('passport_no') ? ' has-error' : '' }}">
                            <label class="control-label">NRIC/Passport No.</label>
                            <input type="text" class="form-control" name="passport_no" placeholder="0000-00-0000" pattern="^\d{4}-\d{2}-\d{4}$"
                                   value="{{old('passport_no')}}" required>
                            @if ($errors->has('passport_no'))
                                <span class="help-block">
                                        {{ $errors->first('passport_no') }}
                                    </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Your Password" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="control-label">Confirm</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmation" required>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                        {{ $errors->first('password_confirmation') }}
                                    </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }}">
                            <label class="control-label">Country</label>
                            <select class="select form-control" name="country_id">
                                @foreach($countries as $country)
                                    <option value="{{$country->country_id}}">{{$country->country_name}} ({{$country->phone_code}})</option>
                                @endforeach
                            </select>
                            @if ($errors->has('country_id'))
                                <span class="help-block">
                                        {{ $errors->first('country_id') }}
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                            <label class="control-label">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile" placeholder="0123456789"
                                   value="{{old('mobile')}}" required>
                            @if ($errors->has('mobile'))
                                <span class="help-block">
                                        {{ $errors->first('mobile') }}
                                    </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin">
                    <button type="submit" class="btn bg-success btn-block">Save Change</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /Add Edit User Modal Dialog -->
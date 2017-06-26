<div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header bg-yellow-800">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Conflict Check</h4>
    </div>

    <div class="row m-20">
        <div class="table-responsive content-group no-margin">
            <table class="table table-framed">
                <thead>
                <tr>
                    <th style="width: 20px;">No</th>
                    <th class="col-xs-1">File Ref</th>
                    <th>Project Name</th>
                    <th class="col-xs-2">Date</th>
                    <th class="col-xs-2">Client Name</th>
                    <th class="col-xs-2">NRIC/Passport No</th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $index => $file)
                <tr>
                    <td>{{$index + 1}}</td>
                    <td><span class="text-semibold">{{ $file->file_ref }}</span></td>
                    <td><span class="text-semibold">{{ $file->project_name }}</span></td>
                    <td>
                        <div class="input-group input-group-transparent">
                            <div class="input-group-addon">
                                <i class="icon-calendar22 position-left"></i>
                            </div>
                            <span>{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $file->created_at)->toFormattedDateString() !!}</span>
                        </div>
                    </td>
                    <td>{{ $file->name }}</td>
                    <td>{{ $file->passport_no }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
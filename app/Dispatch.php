<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $table = "dispatches";

    protected $primaryKey = "dispatch_id";

    protected $fillable = ['file_ref', 'client_id', 'courier_id', 'status', 'qr_code', 'description', 'created_by'];
}

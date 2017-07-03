<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $table = "dispatches";

    protected $primaryKey = "dispatch_id";

    protected $fillable = ['file_ref', 'client_id', 'courier_id', 'status', 'receiver', 'address', 'qr_code', 'description', 'created_by'];

//    public function getStatusAttribute($value)
//    {
//        if ($value == 0) {
//            return "DELIVERED";
//        } else if ($value == 1) {
//            return "RECEIVED";
//        } else if ($value == 2) {
//            return "RETURN";
//        }
//    }
//
//    public function getStatusClass()
//    {
//        $value = $this->status;
//
//        if ($value == "DELIVERED") {
//            return 'label-primary';
//        } else if ($value == "RECEIVED") {
//            return 'label-success';
//        } else if ($value == "RETURN") {
//            return 'label-warning';
//        }
//    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'courier_id');
    }
}

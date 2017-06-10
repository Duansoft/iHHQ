<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'payment_id';

    protected $fillable = ['file_ref', 'purpose', 'amount', 'status', 'created_by', 'remarks'];

    public function file()
    {
        return $this->belongsToMany(File::class, 'file_ref', 'file_ref');
    }

    public function getStatusAttribute($value)
    {
        if ($value == 0) {
            return "REQUEST";
        } else if ($value == 1) {
            return "RECEIVED";
        } else if ($value == 2) {
            return "BANK DEPOSIT";
        } else if ($value == 3) {
            return "DUE NOW";
        }
    }

    public function getStatusClass()
    {
        $value = $this->status;

        if ($value == "REQUEST") {
            return 'label-primary';
        } else if ($value == "RECEIVED") {
            return 'label-success';
        } else if ($value == "BANK DEPOSIT") {
            return 'label-warning';
        } else if ($value == "DUE NOW") {
            return 'label-danger';
        }
    }
}

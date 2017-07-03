<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $primaryKey = 'courier_id';

    protected $table = 'couriers';

    protected $fillable = ['name', 'logo'];

    public $timestamps = null;

    public function dispatches()
    {
        return $this->hasMany(Dispatch::class, 'courier_id', 'courier_id');
    }
}

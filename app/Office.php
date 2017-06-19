<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'offices';

    protected $primaryKey = 'office_id';

    protected $fillable = ['name', 'location'];

    public $timestamps = null;
}

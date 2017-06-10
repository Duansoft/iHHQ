<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $primaryKey = 'country_id';

    protected $fillable = ['country_name', 'phone_code'];

    public $timestamps = null;
}

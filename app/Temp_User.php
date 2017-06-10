<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temp_User extends Model
{
    protected $table = 'temp_users';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'email', 'password', 'passport_no', 'mobile', 'country_id', 'code', 'token', 'attempt'];

    public function setVerificationCodeAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }
}

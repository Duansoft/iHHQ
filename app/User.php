<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;


class User extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'passport_no', 'mobile', 'country_id', 'verified', 'is_allow', 'is_review', 'is_enable_push', 'is_enable_email', 'address',
        'department_id', 'office_id', 'company_number', 'device_type', 'device_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setVerificationCodeAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    /**
     * Documents of file
     */
    public function fileDocuments()
    {
        return $this->hasMany(File_Document::class, 'id', 'created_by');
    }
}

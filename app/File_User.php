<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_User extends Model
{
    protected $table = 'file_users';

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = ['user_id', 'file_ref', 'role'];

    public $timestamps = null;

    public function file()
    {
        return $this->belongsToMany(File::class, 'file_ref', 'file_ref');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_Extension extends Model
{
    protected $table = 'file_extensions';

    protected $primaryKey = 'id';

    protected $fillable = ['icon', 'extension'];

    public $timestamps = null;
}

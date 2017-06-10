<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_Type extends Model
{
    protected $table = 'file_types';

    protected $primaryKey = 'type_id';

    protected $fillable = ['name', 'display_name'];

    public $timestamps = null;
}

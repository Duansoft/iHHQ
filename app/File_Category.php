<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_Category extends Model
{
    protected $table = "file_categories";

    protected $primaryKey = "category_id";

    protected $fillable = ['category_id', 'name'];

    public $timestamps = null;
}
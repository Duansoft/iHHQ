<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_Subcategory extends Model
{
    protected $table = "file_subcategories";

    protected $primaryKey = "subcategory_id";

    protected $fillable = ['category_id', 'title'];

    public $timestamps = null;
}

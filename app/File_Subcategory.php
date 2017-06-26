<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_Subcategory extends Model
{
    protected $table = "file_subcategories";

    protected $primaryKey = "subcategory_id";

    protected $fillable = ['category_id', 'name', 'template'];

    public $timestamps = null;

    public function category()
    {
        return $this->belongsTo(File_Category::class, 'category_id', 'category_id');
    }
}

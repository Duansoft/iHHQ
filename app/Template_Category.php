<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template_Category extends Model
{
    protected $table = 'template_categories';

    protected $primaryKey = 'category_id';

    protected $fillable = ['name'];

    public $timestamps = null;
}

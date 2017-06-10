<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'templates';

    protected $primaryKey = 'template_id';

    protected $fillable = ['category_id', 'extension_id', 'name', 'path', 'created_by'];

}

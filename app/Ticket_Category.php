<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_Category extends Model
{
    protected $primaryKey = 'category_id';

    protected $table = 'ticket_categories';

    protected $fillable = ['name'];

    public $timestamps = null;

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id', 'category_id');
    }
}

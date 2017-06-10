<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $primaryKey = 'ticket_id';

    protected $fillable = ['client_id', 'staff_id', 'category_id', 'status_id', 'file_ref', 'subject'];

    public function staff()
    {
        return $this->hasOne(User::class, 'id', 'staff_id');
    }

    public function client()
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

    public function category()
    {
        return $this->belongsTo(Ticket_Category::class, 'category_id', 'category_id');
    }
}

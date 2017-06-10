<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_Status extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'ticket_statuses';

    protected $fillable = ['name'];
}

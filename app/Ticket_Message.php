<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_Message extends Model
{
    protected $primaryKey = 'message_id';

    protected $table = 'ticket_messages';

    /**
     * status = 0: unread, status = 1: read
     */
    protected $fillable = ['ticket_id', 'type', 'sender_id', 'client_id', 'message', 'status'];

}

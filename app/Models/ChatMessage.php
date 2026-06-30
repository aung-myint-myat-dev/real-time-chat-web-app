<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'group_id',
    'sender_id',
    'message',
    'message_type',
    'file_url',
    'is_edited',
    'is_system',
])]
class ChatMessage extends Model
{
    //
}

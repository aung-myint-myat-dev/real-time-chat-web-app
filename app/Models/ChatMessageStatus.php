<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'message_id',
    'user_id',
    'status',
    'updated_at'
])]
class ChatMessageStatus extends Model
{
    //
}

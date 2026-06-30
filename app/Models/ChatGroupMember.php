<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'group_id',
    'user_id',
    'role',
    'is_active',
])]
class ChatGroupMember extends Model
{
    //
}

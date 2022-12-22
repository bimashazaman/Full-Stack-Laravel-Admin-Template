<?php

namespace App\Models\App\Chat;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['message_id', 'path'];
}

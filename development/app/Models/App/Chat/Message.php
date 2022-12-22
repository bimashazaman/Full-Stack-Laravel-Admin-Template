<?php

namespace App\Models\App\Chat;

use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'message', 'type'];

    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){
            static::creating(function($model){
                return $model->fill([
                    'sender_id' => $model->sender_id ?: auth()->id()
                ]);
            });
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

}

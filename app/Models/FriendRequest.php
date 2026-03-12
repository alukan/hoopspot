<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'inviter_id',
        'invitee_id',
        'status',
    ];

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->with('user')->oldest();
    }
}

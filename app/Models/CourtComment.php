<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtComment extends Model
{
    protected $fillable = [
        'user_id',
        'court_id',
        'replies_to',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CourtComment::class, 'replies_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(CourtComment::class, 'replies_to');
    }
}

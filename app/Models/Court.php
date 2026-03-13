<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    use HasFactory;

    const COVERAGES = ['outdoor', 'indoor'];
    const RIM_TYPES = ['standard', 'breakaway', 'chain', 'bent'];
    const STATUSES  = ['pending', 'active'];

    protected $fillable = [
        'city_id',
        'creator_id',
        'name',
        'address',
        'description',
        'coverage',
        'rim_type',
        'images',
    ];

     protected $casts = [];

    protected function images(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): array {
                $items = $value ? json_decode($value, true) : [];
                return array_map(function (string $img) {
                    if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                        return $img;
                    }
                    return asset('storage/' . $img);
                }, $items ?? []);
            },
            set: fn ($value) => $value !== null ? json_encode($value) : null,
        );
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CourtComment::class);
    }
}

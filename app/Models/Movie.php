<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movie extends Model
{
    protected $table = 'my_favorite_subject';

    protected $fillable = [
        'title',
        'image',
        'description',
        'director',
        'release_year',
        'genre',
        'rating',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'release_year' => 'integer',
            'rating' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

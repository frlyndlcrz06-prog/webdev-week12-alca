<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    public const STATUSES = ['to-read', 'reading', 'finished'];

    protected $fillable = ['title', 'author', 'status', 'rating'];

    protected $attributes = ['status' => 'to-read'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

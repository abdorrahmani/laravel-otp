<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpGeneration extends Model
{
    protected $fillable = [
        'code',
        'token',
        'expire_at',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::creating(static function ($model) {
            $model->expire_at = now()->addMinutes(2);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'expire_at' => 'timestamp',
        ];
    }
}

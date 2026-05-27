<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class LoginOtp extends Model
{
    //

    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
        'used_at',
        'attempts',
        'ip_address',
    ];

     protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

      public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

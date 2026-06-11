<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletHistory extends Model
{
   protected $table;

public function __construct(array $attributes = [])
{
    parent::__construct($attributes);

    $this->table = config(
        'sms.tables.wallet_history',
        'wallet_history'
    );
}

    public $updated_at = false;

    protected $fillable = [
        'wallet_id',
        'reference',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'network',
        'vendor',
        'created_by',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}

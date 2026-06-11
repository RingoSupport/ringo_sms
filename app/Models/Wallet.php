<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{

protected $table;

public function __construct(array $attributes = [])
{
    parent::__construct($attributes);

    $this->table = config(
        'sms.tables.wallets',
        'wallets'
    );
}
    protected $fillable = [
        'client_id',
        'balance',
        'status',
        'alert_threshold',
        'last_funded_at',
        'notes',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'alert_threshold' => 'decimal:2',
        'last_funded_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class, 'client_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(WalletHistory::class, 'wallet_id');
    }
}

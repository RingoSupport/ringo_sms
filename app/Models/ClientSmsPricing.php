<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientSmsPricing extends Model
{
    protected $table;

public function __construct(array $attributes = [])
{
    parent::__construct($attributes);

    $this->table = config(
        'sms.tables.client_sms_pricing',
        'client_sms_pricing'
    );
}

    protected $fillable = [
        'client_id',
        'network',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }
}

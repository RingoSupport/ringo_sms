<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApiClient;

class Message extends Model
{
    protected $table = 'messages';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'msisdn',
        'pages',
        'text',
        'response',
        'dlr_status',
        'dlr_report',
        'dlr',
        'status',
        'senderid',
        'counter',
        'dlr_request',
        'dlr_results',
        'network',
        'vendor',
        'cost',
        'meta',
    ];

    protected $casts = [
        'pages' => 'integer',
        'dlr_report' => 'integer',
        'cost' => 'decimal:2',
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function client()
{
    return $this->belongsTo(ApiClient::class, 'client_id');
}

public function scopeForClient($query, $clientId)
{
    return $query->where('client_id', $clientId);
}

public function getDeliveryStateAttribute(): string
{
    return match (strtoupper($this->dlr_status ?? '')) {

        'DELIVRD' => 'Delivered',

        'EXPIRD' => 'Expired',

        '' => 'Pending',

        default => 'Unknown',
    };
}
public function getDeliveryColorAttribute(): string
{
    return match (strtoupper($this->dlr_status ?? '')) {

        'DELIVRD' => 'green',

        'EXPIRD' => 'red',

        '' => 'yellow',

        default => 'gray',
    };
}

public function getSubmissionStateAttribute(): string
{
    return match ((string) $this->status) {

        '1' => 'Submitted',

        '0' => 'Queued',

        default => 'Unknown',
    };
}

public function getSubmissionColorAttribute(): string
{
    return match ((string) $this->status) {

        '1' => 'green',

        '0' => 'yellow',

        default => 'gray',
    };
}
}

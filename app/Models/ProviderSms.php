<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderSms extends Model
{
    //
     protected $table;

public function __construct(array $attributes = [])
{
    parent::__construct($attributes);

    $this->table = config(
        'sms.tables.provider_sms',
        'provider_sms'
    );
}

    protected $fillable = [
        'provider',
        'amount',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderSms extends Model
{
    //
      protected $table = 'provider_sms';

    protected $fillable = [
        'provider',
        'amount',
        'status',
    ];
}

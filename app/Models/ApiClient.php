<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ApiClient extends Authenticatable
{
    protected $table = 'api_clients';

    protected $fillable = [
        'client_name',
        'username',
        'password',
        'portal_password',
        'status',
        'email',
        'phone',
        'company_name',
        'webhook_url',
        'webhook_enabled',


    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
    'password' => 'hashed',
];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'client_id');
    }

    public function messages()
        {
            return $this->hasMany(Message::class, 'client_id');
        }

        public function smsPricing(): HasMany
{
    return $this->hasMany(
        ClientSmsPricing::class,
        'client_id'
    );
}
}

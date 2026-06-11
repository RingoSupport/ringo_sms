<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ApiClient extends Authenticatable
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config(
            'sms.tables.api_clients',
            'api_clients'
        );
    }

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
        'portal_password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'portal_password' => 'hashed',
        'webhook_enabled' => 'boolean',
    ];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'client_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(
            Message::class,
            'client_id'
        );
    }

    public function smsPricing(): HasMany
    {
        return $this->hasMany(
            ClientSmsPricing::class,
            'client_id'
        );
    }
}

<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
class ApiClient extends Authenticatable
{
    protected $table = 'api_clients';

    protected $fillable = [
        'client_name',
        'username',
        'password',
        'status',
        'email',
        'phone',
        'company_name',
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
}

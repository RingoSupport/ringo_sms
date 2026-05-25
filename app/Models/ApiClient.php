<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Message;

class ApiClient extends Model
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

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'client_id');
    }

    public function messages()
        {
            return $this->hasMany(Message::class, 'client_id');
        }
}

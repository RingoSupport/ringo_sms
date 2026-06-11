<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
//use Spatie\Activitylog\LogsActivity;
//use Spatie\Activitylog\LogOptions;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config(
            'sms.tables.users',
            'users'
        );
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'client_id',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isOperations(): bool
    {
        return $this->role === 'operations';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}

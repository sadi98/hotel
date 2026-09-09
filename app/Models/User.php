<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_STAFF = 'staff';

    public const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'username',
        'google_id',
        'email',
        'phone',
        'avatar',
        'gender',
        'date_of_birth',
        'role',
        'email_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isStaffOrAdmin(): bool
    {
        return in_array(
            $this->role,
            [
                self::ROLE_ADMIN,
                self::ROLE_STAFF,
            ],
            true
        );
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array(
            $this->role,
            $roles,
            true
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function createdOrders(): HasMany
    {
        return $this->hasMany(
            Order::class,
            'created_by'
        );
    }

    public function processedPayments(): HasMany
    {
        return $this->hasMany(
            Payment::class,
            'processed_by'
        );
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function tableReservations(): HasMany
    {
        return $this->hasMany(
            TableReservation::class
        );
    }
}

<?php

namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isSuperAdmin():bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

        public function isAdmin():bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isManager():bool
    {
        return $this->role ===UserRole::Manager;
    }

        public function isCustomer():bool
    {
        return $this->role === UserRole::Customer;
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array($this->role,[
            UserRole::SuperAdmin,
            UserRole::Admin,
            UserRole::Manager
        ],true);
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return $this->canAccessAdminPanel();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role'=>UserRole::class
        ];
    }

    public function orders():HasMany
    {
        return $this->hasMany(Order::class);
    }
}

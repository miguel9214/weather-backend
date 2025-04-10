<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Search;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles,HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'sanctum'; // Necesario para Spatie

    /**
     * Relación: un usuario tiene muchas búsquedas.
     */
    public function searches(): HasMany
    {
        return $this->hasMany(Search::class);
    }
}

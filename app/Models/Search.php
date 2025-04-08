<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Search extends Model
{
    protected $fillable = [
        'user_id',
        'city',
        'data',
        'favorite',
    ];

    protected $casts = [
        'data'     => 'array',    // JSON con info del clima
        'favorite' => 'boolean',
    ];

    /**
     * Relación: cada búsqueda pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

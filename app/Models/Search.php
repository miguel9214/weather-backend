<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Search extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city',
        'data',
        'favorite',
    ];

    protected $casts = [
        'data' => 'array',       // Cast para almacenar JSON como array
        'favorite' => 'boolean', // Cast explícito para boolean
    ];

    /**
     * Relación: una búsqueda pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

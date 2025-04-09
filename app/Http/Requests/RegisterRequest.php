<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Autoriza el request (¡esto debe ser true!)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para el registro
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'locale'   => 'nullable|string|in:en,es',
            'role'     => 'nullable|string|in:admin,user', // <- validación de rol
        ];
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleSmart
{
    public function handle(Request $request, Closure $next)
    {
        // Idiomas soportados
        $supportedLocales = ['en', 'es'];

        // 1. Intentar con el usuario autenticado
        $locale = $request->user()->locale ?? null;

        // 2. Si no hay usuario o locale, intentar con Accept-Language
        if (!$locale) {
            $localeHeader = $request->header('Accept-Language');
            $locale = substr($localeHeader, 0, 2); // ej: "es-ES" → "es"
        }

        // 3. Establecer el idioma si es válido
        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}

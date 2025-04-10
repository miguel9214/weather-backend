<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Weather API",
 *     version="1.0.0",
 *     description="Documentación de la API de clima con autenticación, búsquedas y favoritos",
 *     @OA\Contact(
 *         email="tucorreo@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor API"
 * )
 */
class SwaggerController extends Controller {}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Exception;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'locale'   => $request->locale ?? 'en',
            ]);

            // Asignar rol (user/admin)
            $role = $request->role ?? 'user';

            // Verificamos que el rol exista antes de asignar
            if (!\Spatie\Permission\Models\Role::where('name', $role)->exists()) {
                Log::error("El rol '{$role}' no existe.");
                return response()->json(['message' => "Rol '{$role}' no existe"], 400);
            }

            $user->assignRole($role);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully',
                'user'    => $user,
                'token'   => $token,
            ], 201);
        } catch (Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            return response()->json(['message' => 'Error registering user'], 500);
        }
    }




    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user'    => $user,
                'token'   => $token,
            ]);
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['message' => 'Error during login'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out']);
        } catch (Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json(['message' => 'Error during logout'], 500);
        }
    }
}

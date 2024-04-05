<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function register(AuthRequest $request): JsonResponse
    {
        try {
            $userData = $request->validated();

            $userData['password'] = Hash::make($userData['password']);
            $user = User::create($userData);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuário criado com sucesso!',
                'access_token' => $token
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error("Erro ao registrar usuário: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao registrar usuário.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Não autorizado, email ou senha incorreto.'], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::find(Auth::id());

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['access_token' => $token, 'message' => 'Seja Bem vindo, ' . $user->name . '!!'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('erro ao fazer login: ' . $e->getMessage());

            return response()->json(['error' => 'Erro ao fazer login.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => 'Logout bem-sucedido'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Erro ao fazer logout: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao fazer logout.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

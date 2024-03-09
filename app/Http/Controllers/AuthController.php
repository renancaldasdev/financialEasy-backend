<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        try {
            $user = User::create($request->validated());

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuário criado com sucesso!',
                'access_token' => $token
            ], 201);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            dd($errors);
            return response()->json(['error' => $errors], 400);
        }
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['user' => $user, 'access_token' => $token, 'message' => 'Seja Bem vindo ' . $user->name]);
        }
        return response()->json(['message' => 'Não autorizado, email ou senha incorretos.'], 401);
    }
}

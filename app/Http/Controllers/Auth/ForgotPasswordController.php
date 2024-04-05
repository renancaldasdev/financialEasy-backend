<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => 'E-mail enviado com sucesso!'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Não foi possível enviar o e-mail'], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de redefinição de senha: ' . $e->getMessage());

            return response()->json(['error' => 'Erro ao enviar e-mail de redefinição de senha.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

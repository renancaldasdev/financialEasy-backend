<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email_remember' => 'required|email',
            ], [
                'email_remember.required' => 'O campo de email é obrigatório.',
                'email_remember.email' => 'O campo de email deve ser um endereço de e-mail válido.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = DB::table('users')->where('email', $request->email_remember)->first();
            if (!$user) {
                return response()->json(['message' => 'E-mail não encontrado'], Response::HTTP_NOT_FOUND);
            }

            $status = Password::sendResetLink(['email' => $request->email_remember]);

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

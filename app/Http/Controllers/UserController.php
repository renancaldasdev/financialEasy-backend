<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = User::all();
        return response()->json(['data' => $user]);
    }

    public function updateUser(UserRequest $request, $id): JsonResponse
    {
        try {
            $authenticatedUserId = Auth::id();
            if ($authenticatedUserId != $id) {
                return response()->json(['message' => 'Você não tem permissão para atualizar este usuário'], 403);
            }

            $user = User::findOrFail($id);

            $user->update($request->all());

            return response()->json(['message' => 'Dados do usuário atualizados com sucesso', 'user' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Ocorreu um erro interno ao processar a solicitação'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountCreateOrUpdateRequest;
use App\Models\Account;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use LDAP\Result;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountCreateOrUpdateRequest $request): JsonResponse
    {
        try {
            Account::create([
                'user_id' => $request->user()->id,
                'account_name' => $request->account_name,
                'opening_balance' => $request->opening_balance,
                'balance' => $request->balance
            ]);

            return response()->json(['Conta criada com sucesso'], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            return response()->json(['error' => $errors], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $accounts = $user->accounts()->where('user_id', $id)->get();

            if ($accounts->isEmpty()) {
                return response()->json(['message' => 'Nenhuma conta encontrada para o usuário ' . $user->name], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['accounts' => $accounts]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao buscar contas: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showAccount($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $account = $user->accounts()->findOrFail($id);

            return response()->json(['account' => $account]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar a conta: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountCreateOrUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $authenticatedUserId = Auth::id();

            $account = Account::findOrFail($id);

            dd($account);

            if ($authenticatedUserId != $account->user_id) {
                return response()->json(['message' => 'Você não tem permissão para atualizar esta conta'], Response::HTTP_FORBIDDEN);
            }

            $account->update([
                'account_name' => $request->account_name,
            ]);

            return response()->json(['message' => 'A Conta ' . $account->account_name . ' foi atualizada com sucesso']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Conta não encontrada'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar a conta'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $accountDelete = $user->accounts()->findOrFail($id);

            $accountDelete->delete();

            return response()->json(['message' => 'Conta deletada com sucesso']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Conta não encontrada'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            // Se ocorrer outra exceção não prevista, você pode tratar aqui
            return response()->json(['message' => 'Erro ao deletar a conta'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

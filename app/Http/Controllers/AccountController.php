<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountCreateOrUpdateRequest;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
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
        $user = Auth::user();
        $accounts = $user->accounts()->where('user_id', $id)->get();

        if ($accounts->isEmpty()) {
            return response()->json(['message' => 'Nenhuma conta encontrada para o usuário ' . $user->name], 404);
        }

        return response()->json(['accounts' => $accounts]);
    }

    public function showAccount($id): JsonResponse
    {
        $user = Auth::user();
        $account = $user->accounts()->where('id', $id)->first();

        if (empty($account)) {
            return response()->json(['message' => 'Conta não encontrada no usuário ' . $user->name], 404);
        }

        return response()->json(['account' => $account]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

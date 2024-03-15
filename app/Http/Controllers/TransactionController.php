<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionCreateOrUpdateRequest;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionCreateOrUpdateRequest $request)
    {
        try {
            Transaction::create([
                'user_id' => $request->user()->id,
                'account_id' => $request->account_id,
                'category_id' => $request->category_id,
                'type' => $request->type,
                'value' => $request->value,
                'description' => $request->description,
                'date' => $request->date
            ]);
            return response()->json(['Transação criada com sucesso'], Response::HTTP_CREATED);
        } catch (ValidationException  $e) {
            $errors = $e->validator->errors()->toArray();
            return response()->json(['error' => $errors], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = Auth::user();
            $transactions = $user->transactions()->find($id);
            return response()->json(['transactions' => $transactions]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao buscar transações: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showalltransaction()
    {
        try {
            $user = Auth::user();
            $transactions = $user->transactions()->get();
            return response()->json(['transactions' => $transactions]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao buscar transações: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $authenticatedUserId = Auth::user()->id;
            $transaction = Transaction::findOrFail($id);

            if ($authenticatedUserId !== $transaction->user_id) {
                return response()->json(['message' => 'Transação inválida'], Response::HTTP_NOT_FOUND);
            };
            $transaction->update([
                'account_id' => $request->account_id,
                'category_id' => $request->category_id,
                'type' => $request->type,
                'value' => $request->value,
                'description' => $request->description,
                'date' => $request->date
            ]);

            return response()->json(['Transação atualizada com sucesso']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao atualizar transação: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            $transaction = $user->transactions()->findOrFail($id);
            $transaction->delete();
            return response()->json(['Transação excluída com sucesso']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao excluir transação: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir transação: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

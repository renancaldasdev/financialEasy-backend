<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoalsCreateOrUpdateRequest;
use App\Models\Goals;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GoalController extends Controller
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
    public function store(GoalsCreateOrUpdateRequest $request): JsonResponse
    {
        try {
            Goals::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'goal_value' => $request->goal_value,
                'deadline' => $request->deadline
            ]);
            return response()->json(['Goal criado com sucesso'], Response::HTTP_CREATED);
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
            $goals = $user->goals()->where('user_id', $id)->get();

            if ($goals->isEmpty()) {
                return response()->json(['message' => 'Nenhuma meta encontrada para o usuário ' . $user->name], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['goals' => $goals]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao buscar metas: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showGoal(string $id): JsonResponse
    {
        try {
            $goals = Goals::where('id', $id)->get();

            if ($goals->isEmpty()) {
                return response()->json(['message' => 'Nenhuma meta encontrada'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['goals' => $goals]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao buscar metas: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GoalsCreateOrUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $authenticatedUserId = Auth::id();

            $goal = Goals::findOrFail($id);

            if ($goal->user_id != $authenticatedUserId) {
                return response()->json(['message' => 'Meta não encontrada'], Response::HTTP_NOT_FOUND);
            }

            $goal->update([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'goal_value' => $request->goal_value,
                'deadline' => $request->deadline
            ]);

            return response()->json(['message' => 'A Meta ' . $goal->name . ' foi atualizada com sucesso']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao buscar metas: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            Goals::destroy($id);
            return response()->json(['message' => 'Meta excluída com sucesso']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Meta não encontrada'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir a meta'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

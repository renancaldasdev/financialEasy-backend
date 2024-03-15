<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateOrUpdateRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
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
    public function store(CategoryCreateOrUpdateRequest $request)
    {
        try {
            Category::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'type' => $request->type
            ]);
            return response()->json(['Categoria ' . $request->name . ' criada com sucesso'], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
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
            $categories = $user->categories()->find($id);
            return response()->json(['categories' => $categories]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao buscar contas: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showallcategory()
    {
        try {
            $user = Auth::user();
            $categories = $user->categories()->get();
            return response()->json(['categories' => $categories]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao buscar contas: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $authenticatedUserId = Auth::user()->id;
            $category = Category::findOrFail($id);

            if ($authenticatedUserId != $category->user_id) {
                return response()->json(['message' => 'Erro ao atualizar a categoria.'], Response::HTTP_FORBIDDEN);
            }

            $category->update([
                'user_id' => $authenticatedUserId,
                'name' => $request->name,
                'type' => $request->type
            ]);

            return response()->json(['message' => 'Categoria ' . $category->name . ' atualizada com sucesso.'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Erro ao atualizar a categoria: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar a categoria: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            $categoryDelete = $user->categories()->findOrFail($id);

            $categoryDelete->delete();

            return response()->json(['message' => 'Categoria deletada com sucesso']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Categoria não encontrada'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar a categoria'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

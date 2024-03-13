<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateOrUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            return response()->json(['Conta criada com sucesso'], Response::HTTP_CREATED);
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
        //
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

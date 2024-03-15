<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionCreateOrUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:revenue,expense',
            'value' => 'required|numeric',
            'description' => 'required|string',
            'date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Usuário obrigatório',
            'account_id.required' => 'Conta obrigatória',
            'category_id.required' => 'Categória obrigatória',
            'type.required' => 'Tipo obrigatório',
            'category.required' => 'Categória obrigatória',
            'value.required' => 'Valor obrigatório',
            'description.required' => 'Descrição obrigatória',
            'date.required' => 'Data obrigatória',
        ];
    }
}

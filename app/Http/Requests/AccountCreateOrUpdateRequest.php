<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountCreateOrUpdateRequest extends FormRequest
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
            'account_name' => 'string|required|max:255|unique:accounts',
        ];
    }

    public function messages(): array
    {
        return [
            'account_name.required' => 'Nome da conta é obrigatório',
            'account_name.unique' => 'Nome da conta já existe',
        ];
    }
}

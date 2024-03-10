<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        $accountId = $this->route('id'); // Obtém o ID da conta da rota

        return [
            'account_name' => [
                'required',
                Rule::unique('accounts')->ignore($accountId),
            ],
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

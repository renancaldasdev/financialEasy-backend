<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nome do usuário é obrigatório',
            'name.min' =>  'O nome deve ter pelo menos 3 caracteres',
            'email.required' => 'E-mail é obrigatório',
            'email.unique' => 'E-mail já cadastrado',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve possuir pelo menos 8 caracteres'
        ];
    }
}

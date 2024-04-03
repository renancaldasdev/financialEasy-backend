<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // ou false, dependendo de suas necessidades
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'O campo de email é obrigatório.',
            'email.email' => 'Por favor, insira um endereço de email válido.',
            'password.required' => 'O campo de senha é obrigatório.',
        ];
    }
}

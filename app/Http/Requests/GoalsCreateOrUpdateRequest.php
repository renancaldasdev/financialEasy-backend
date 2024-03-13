<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoalsCreateOrUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'goal_value' => 'required|numeric',
            'deadline' => 'required|date',
        ];
    }

    public function messages()
    {
        return  [
            'user_id.required' => 'O campo user_id é obrigatório.',
            'user_id.exists' => 'O user_id fornecido é inválido.',
            'name.required' => 'O campo name é obrigatório.',
            'name.string' => 'O campo name deve ser uma string.',
            'goal_value.required' => 'O campo goal_value é obrigatório.',
            'goal_value.numeric' => 'O campo goal_value deve ser um número.',
            'deadline.required' => 'O campo deadline é obrigatório.',
            'deadline.date' => 'O campo deadline deve ser uma data válida.',
        ];
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testForgotPassword()
    {
        // Crie um usuário de teste
        $user = User::factory()->create(['email' => 'test@example.com']);

        // Simule uma solicitação POST para a rota forgotPassword
        $response = $this->postJson('/api/forgotpassword', ['email' => $user->email]);

        // Verifique se a resposta foi bem-sucedida (código HTTP 200)
        $response->assertStatus(200);

        // Verifique se a resposta contém a mensagem esperada
        $response->assertJson(['message' => 'E-mail enviado com sucesso!']);
    }
}

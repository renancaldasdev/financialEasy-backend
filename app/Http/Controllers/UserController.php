<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Middleware de autenticação aplicado a todos os métodos deste controlador
    }

    public function index(Request $request)
    {
    }
}

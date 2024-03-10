<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group([
    'prefix' => 'users',
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::put('/update/{id}', [UserController::class, 'updateUser']);
});

Route::group([
    'prefix' => 'accounts',
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/', [AccountController::class, 'index']);
    Route::post('/store', [AccountController::class, 'store']);
    Route::get('/show/{id}', [AccountController::class, 'show']);
    Route::get('/showAccount/{id}', [AccountController::class, 'showAccount']);
});

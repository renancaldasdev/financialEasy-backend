<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TransactionController;
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
    Route::post('/update/{id}', [AccountController::class, 'update']);
    Route::get('/show/{id}', [AccountController::class, 'show']);
    Route::get('/showAccount/{id}', [AccountController::class, 'showAccount']);
    Route::delete('/delete/{id}', [AccountController::class, 'destroy']);
});

Route::group(['prefix' => 'goals', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [GoalController::class, 'index']);
    Route::get('/show/{id}', [GoalController::class, 'show']);
    Route::get('/showallgoals/{id}', [GoalController::class, 'showgoal']);
    Route::post('/store', [GoalController::class, 'store']);
    Route::post('/update/{id}', [GoalController::class, 'update']);
    Route::delete('/delete/{id}', [GoalController::class, 'destroy']);
});

Route::group(
    [
        'prefix' => 'category',
        'middleware' => 'auth:sanctum',
    ],
    function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/show/{id}', [CategoryController::class, 'show']);
        Route::get('/showallcategory/{id}', [CategoryController::class, 'showallcategory']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::post('/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    }
);


Route::group(
    [
        'prefix' => 'transaction',
        'middleware' => 'auth:sanctum',
    ],
    function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/show/{id}', [TransactionController::class, 'show']);
        Route::get('/showalltransaction/{id}', [TransactionController::class, 'showgoal']);
        Route::post('/store', [TransactionController::class, 'store']);
        Route::post('/update/{id}', [TransactionController::class, 'update']);
        Route::delete('/delete/{id}', [TransactionController::class, 'destroy']);
    }
);

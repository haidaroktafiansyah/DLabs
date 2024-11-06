<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['jwt_protected'])->group(function () {
    Route::get('/users', [UserController::class, 'get']);
    Route::get('/users/{id}', [UserController::class, 'getById']);
    Route::put('/users/{id}', [UserController::class, 'put']);
    Route::delete('/users/{id}', [UserController::class, 'delete']);
});

Route::post('/users', [UserController::class, 'post']);
Route::post('/login', [AuthController::class, 'login']);

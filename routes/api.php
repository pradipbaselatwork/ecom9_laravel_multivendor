<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/register',[UserController::class, 'register'])->name('user-register');
Route::post('/user/login',[UserController::class, 'login'])->name('user-login');
Route::post('/user/forgot',[UserController::class, 'forgotPassword'])->name('user-forgot');
Route::get( '/user/confirm/{code}', [UserController::class, 'confirmUser'])->name('user-confirm');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/account',[UserController::class, 'updateAccount'])->name('user-account');
    Route::post('/user/update-password',[UserController::class, 'updatePassword'])->name('user-update-password');
    Route::post('/user/logout',[UserController::class, 'logout'])->name('user-logout');
    Route::get('/user/account',[UserController::class, 'updateAccount'])->name('user-account');
});
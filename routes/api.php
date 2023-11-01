<?php

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

Route::any('/telegram_webhook', \App\Http\Controllers\TelegramController::class);

Route::any('/regUser', function (\App\Domain\User\Services\UserService $service) {
    $service->register(new \App\Domain\User\Dto\RegistrationDto(
        name: 'Ivan',email: 'ivandenviii@gmail.com',active: false
    ));
});

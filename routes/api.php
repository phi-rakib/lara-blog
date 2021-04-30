<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::resource('posts', PostController::class);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/registration', [AuthController::class, 'registration']);



Route::fallback(function () {
    return response()->json(
        [
            'message' => 'Page Not Found. If error persists, contact administrator of the site',
        ],
        Response::HTTP_NOT_FOUND
    );
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::name('api.')->group(function () {
    Route::name('user.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/registration', [AuthController::class, 'registration'])->name('registration');
    });
});

Route::apiResource('posts', PostController::class);
Route::apiResource('posts.comments', CommentController::class)
    ->except(['show'])
    ->shallow();

Route::fallback(function () {
    return response()->json(
        [
            'message' => 'Page Not Found. If error persists, contact administrator of the site',
        ],
        Response::HTTP_NOT_FOUND
    );
});

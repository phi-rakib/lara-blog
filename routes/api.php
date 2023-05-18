<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::name('api.')->group(function () {
    Route::name('user.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/registration', [AuthController::class, 'registration'])->name('registration');
    });
});

Route::apiResource('posts', PostController::class);

Route::get('post/comments/{postId}', [CommentController::class, 'commentsByPostId']);

Route::apiResource('comments', CommentController::class);

Route::apiResource('profiles', ProfileController::class)
    ->except(['index', 'delete']);

Route::fallback(function () {
    return response()->json(
        [
            'message' => 'Page Not Found. If error persists, contact administrator of the site',
        ],
        404
    );
});

<?php

use App\Http\Controllers\API\V1\CommentController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/posts', [PostController::class, 'index']);

    Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments', [CommentController::class, 'store']);

    Route::get('/users/report', [UserController::class, 'report']);
});
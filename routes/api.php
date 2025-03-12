<?php

use App\Http\Controllers\Auth\UserLogin;
use App\Http\Controllers\Auth\UserLogOut;
use App\Http\Controllers\Auth\UserRegister;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






Route::post('/register', UserRegister::class);
Route::post('/login', UserLogin::class);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', UserLogOut::class);
    Route::apiResource('/posts', PostController::class);
    Route::apiResource('/comments', CommentController::class);
    Route::post('create-likes', [LikeController::class, 'store']);
    Route::delete('delete-likes/{id}', [LikeController::class, 'destroy']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostBookmarkController;

Route::controller(PostController::class)->group(function () {
    Route::prefix('posts')->group(function () {
        Route::get('/', 'get');
        Route::post('/store', 'store');
        Route::get('/edit/{id}', 'edit');
        Route::put('/update/{id}', 'update');
    });
});


Route::controller(PostBookmarkController::class)->group(function () {
    Route::prefix('bookmarks')->group(function () {
        Route::get('user/{id}', 'get');
        Route::post('/store/{userId}', 'store');
    });
});

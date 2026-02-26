<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class);

Route::resource('comments', CommentController::class);
Route::resource('users', UserController::class);

Route::get('/', function () {
    return view('home');
});

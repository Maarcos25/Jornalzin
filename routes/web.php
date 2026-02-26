<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;

Route::resource('comments', CommentController::class);
Route::resource('users', UserController::class);

Route::get('/', function () {
    return view('home');
});

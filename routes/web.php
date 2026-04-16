<?php

use App\Http\Controllers\Auth\GoogleCompletarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SolicitacaoEditorController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Auth\GoogleController;

// Rotas autenticadas + verificadas
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('comments', CommentController::class);

    // Listar todos os posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    // Mostrar formulário de criação
    Route::get('/nova-postagem', [PostController::class, 'create'])->name('posts.create');

    // Salvar novo post
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Mostrar um post específico
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    // Mostrar formulário de edição
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

    // Atualizar um post
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::patch('/posts/{post}', [PostController::class, 'update']);

    // Deletar um post
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/comments/{id}/aprovar', [CommentController::class, 'aprovar'])->name('comments.aprovar');
    Route::post('/comments/{id}/ocultar', [CommentController::class, 'ocultar'])->name('comments.ocultar');

    Route::post('/posts/{post}/aprovar',  [PostController::class, 'aprovar'])->name('posts.aprovar');
    Route::post('/posts/{post}/rejeitar', [PostController::class, 'rejeitar'])->name('posts.rejeitar');
    Route::post('/posts/{post}/votar',    [PostController::class, 'votar'])->name('posts.votar');
    Route::delete('/posts/{post}/midia',  [PostController::class, 'removerMidia'])->name('posts.removerMidia');
});

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Home pública
Route::get('/', [HomeController::class, 'index'])->name('home');

// Post público
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Registro de usuário — qualquer um pode criar conta
Route::resource('users', UserController::class)->only(['create', 'store']);

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/like/{post}', [LikeController::class, 'altera_like'])->name('posts.like');

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/alterar-senha', [ProfileController::class, 'updatePassword'])->name('senha.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.deleteAvatar');

    // Solicitação de editor (qualquer autenticado)
    Route::post('/solicitar-editor', [SolicitacaoEditorController::class, 'store'])->name('editor.solicitar');
});



// ✅ Rotas ADMIN — só quem tem tipo = 'admin' entra
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Gestão de usuários
    Route::resource('users', UserController::class)->except(['create', 'store']);

    // Solicitações de editor
    Route::get('/admin/solicitacoes', [SolicitacaoEditorController::class, 'index'])->name('admin.solicitacoes');
    Route::post('/admin/solicitacoes/{id}/aprovar', [SolicitacaoEditorController::class, 'aprovar'])->name('editor.aprovar');
    Route::post('/admin/solicitacoes/{id}/rejeitar', [SolicitacaoEditorController::class, 'rejeitar'])->name('editor.rejeitar');
    Route::post('/admin/editores/{id}/remover', [SolicitacaoEditorController::class, 'removerEditor'])->name('editor.remover');
});

require __DIR__.'/auth.php';

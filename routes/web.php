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
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\DMController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\FavoritoController;

// Rotas autenticadas + verificadas
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('comments', CommentController::class);

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/nova-postagem', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::patch('/posts/{post}', [PostController::class, 'update']);
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
Route::get('/auth/google/completar',  [GoogleCompletarController::class, 'create'])->name('auth.google.completar');
Route::post('/auth/google/completar', [GoogleCompletarController::class, 'store'])->name('auth.google.completar.store');

// Login / Logout
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Home pública
Route::get('/', [HomeController::class, 'index'])->name('home');

// Post público
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Registro
Route::resource('users', UserController::class)->only(['create', 'store']);

// Rotas autenticadas gerais
Route::middleware('auth')->group(function () {
    Route::post('/like/{post}', [LikeController::class, 'altera_like'])->name('posts.like');

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/alterar-senha', [ProfileController::class, 'updatePassword'])->name('senha.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.deleteAvatar');

    Route::post('/solicitar-editor', [SolicitacaoEditorController::class, 'store'])->name('editor.solicitar');

    // DM
    Route::get('/dm', [DMController::class, 'index'])->name('dm.index');
    Route::get('/dm/abrir/{user}', [DMController::class, 'abrir'])->name('dm.abrir');
    Route::get('/dm/{conversa}', [DMController::class, 'conversa'])->name('dm.conversa');
    Route::post('/dm/{conversa}/enviar', [DMController::class, 'enviar'])->name('dm.enviar');

    // Notificações
    Route::get('/notificacoes', [NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::post('/notificacoes/lidas', [NotificacaoController::class, 'marcarTodasLidas'])->name('notificacoes.lidas');

    // Denúncias
    Route::post('/denuncias', [DenunciaController::class, 'store'])->name('denuncias.store');
    Route::get('/admin/denuncias', [DenunciaController::class, 'index'])->name('admin.denuncias');
    Route::post('/admin/denuncias/{denuncia}/lida', [DenunciaController::class, 'marcarLida'])->name('admin.denuncias.lida');
    Route::delete('/admin/denuncias/{denuncia}', [DenunciaController::class, 'destroy'])->name('admin.denuncias.destroy');
    Route::delete('/admin/denuncias/{denuncia}/excluir-post', [DenunciaController::class, 'excluirPost'])->name('admin.denuncias.excluir-post');
    Route::delete('/admin/denuncias/{denuncia}/excluir-comentario', [DenunciaController::class, 'excluirComentario'])->name('admin.denuncias.excluir-comentario');
});

// Admin
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::get('/admin/solicitacoes', [SolicitacaoEditorController::class, 'index'])->name('admin.solicitacoes');
    Route::post('/admin/solicitacoes/{id}/aprovar', [SolicitacaoEditorController::class, 'aprovar'])->name('editor.aprovar');
    Route::post('/admin/solicitacoes/{id}/rejeitar', [SolicitacaoEditorController::class, 'rejeitar'])->name('editor.rejeitar');
    Route::post('/admin/editores/{id}/remover', [SolicitacaoEditorController::class, 'removerEditor'])->name('editor.remover');
});

// Perfil público e seguir
Route::get('/u/{user}', [UserController::class, 'perfil'])->name('users.perfil');
Route::post('/u/{user}/seguir', [UserController::class, 'seguir'])->name('users.seguir')->middleware('auth');
Route::get('/u/{user}/seguidores', [UserController::class, 'seguidores'])->name('users.seguidores');
Route::get('/u/{user}/seguindo', [UserController::class, 'seguindo'])->name('users.seguindo');
Route::delete('/u/{user}/seguidores/{seguidor}/remover', [UserController::class, 'removerSeguidor'])
    ->name('users.removerSeguidor')
    ->middleware('auth');

    Route::middleware('auth')->group(function () {
        Route::post('/favorito/{post}', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
        Route::get('/favoritos', [FavoritoController::class, 'index'])->name('favoritos.index');
    });
    Route::get('/favoritos', [\App\Http\Controllers\FavoritoController::class, 'index'])->name('favoritos.index')->middleware('auth');
    Route::post('/favorito/{post}', [\App\Http\Controllers\FavoritoController::class, 'toggle'])->name('favoritos.toggle')->middleware('auth');
require __DIR__.'/auth.php';

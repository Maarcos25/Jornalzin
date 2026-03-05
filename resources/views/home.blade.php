@extends('layouts.app')

@section('conteudo')

<div class="hero">
    <h1 class="titulo">Jornalzin</h1>
    <p class="subtitulo">Informação que transforma.</p>
    <a href="{{ route('posts.index') }}" class="btn-entrar">Entrar</a>
</div>

<style>
body {
    margin: 0;
    background: linear-gradient(135deg, #111, #222);
    color: white;
    font-family: Arial, sans-serif;
    overflow: hidden;
}

.hero {
    height: 80vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

/* Animação do título */
.titulo {
    font-size: 4rem;
    opacity: 0;
    transform: translateY(-40px);
    animation: aparecer 1.5s ease-out forwards;
}

/* Subtitulo */
.subtitulo {
    font-size: 1.5rem;
    margin-top: 10px;
    opacity: 0;
    animation: aparecer 1.5s ease-out forwards;
    animation-delay: 1s;
}

/* Botão */
.btn-entrar {
    margin-top: 30px;
    padding: 12px 25px;
    background: #e63946;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    opacity: 0;
    animation: aparecer 1.5s ease-out forwards;
    animation-delay: 2s;
}

/* Keyframes */
@keyframes aparecer {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

@endsection

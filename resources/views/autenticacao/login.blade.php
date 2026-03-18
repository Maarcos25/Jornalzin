@extends('layouts.autenticacao')

@section('conteudo')
    <h4 class="text-center mb-4">Entrar</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif
    <div class="text-center mb-4">
        <a href="{{ route('home') }}" class="text-decoration-none text-dark">
            <h2>📰 Jornalzin</h2>
        </a>
    <p class="text-muted">insira seu email e senha</p>
</div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3 position-relative">

            <label>Senha</label>

            <input
                type="password"
                name="password"
                id="password"
                class="form-control"
                required
            >

            <span onclick="toggleSenha()"
                style="position: absolute; right: 10px; top: 38px; cursor: pointer;">
                👁️
            </span>

        </div>
        <div class="d-grid mb-3">
            <button class="btn btn-primary">
                Entrar
            </button>
        </div>
        <div class="text-center mb-3">
            <a href="{{ url('/users/create') }}" class="btn btn-outline-primary rounded-pill px-4">
                Criar conta
            </a>
        </div>

    <div class="text-center mt-5">
        <a href="{{ route('password.request') }}"
            class="text-sm text-gray-600 hover:text-gray-900 underline">
            Esqueceu a senha?
        </a>
    </div>
            </div>


        </div>

        </div>

    </form>
@endsection

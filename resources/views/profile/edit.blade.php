@extends('layouts.autenticacao')

@section('conteudo')

<div class="container mt-4" style="max-width: 800px;">

<h4 class="text-center mb-4">Configurações da Conta</h4>

<div class="card shadow mb-4">
    <div class="card-body">

        <h5 class="mb-3">Informações do Perfil</h5>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control"
                value="{{ auth()->user()->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                value="{{ auth()->user()->email }}" required>
            </div>

            <button class="btn btn-primary">Salvar</button>

        </form>

    </div>
</div>


<div class="card shadow mb-4">
    <div class="card-body">

        <h5 class="mb-3">Alterar Senha</h5>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Senha atual</label>
                <input type="password"
                       name="current_password"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Nova senha</label>
                <input type="password"
                       name="password"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar nova senha</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control">
            </div>

            <button class="btn btn-primary">
                Atualizar senha
            </button>

        </form>

    </div>
</div>


<div class="card shadow border-danger">
    <div class="card-body">

        <h5 class="text-danger mb-3">Excluir Conta</h5>

        <p class="text-muted">
            Após excluir sua conta, todos os dados serão removidos permanentemente.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}"
        onsubmit="return confirm('Tem certeza que deseja excluir sua conta?')">

            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">
                Excluir Conta
            </button>

        </form>

    </div>
</div>

@endsection

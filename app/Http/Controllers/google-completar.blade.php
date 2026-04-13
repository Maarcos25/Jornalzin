@extends('layouts.autenticacao')

@section('conteudo')
<div class="login-header text-center mb-4">
    <h2><span>📰</span> Jornalzin</h2>
    <p class="text-muted small">Só mais alguns dados para completar seu cadastro</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius:10px;">
        <small>⚠️ {{ $errors->first() }}</small>
    </div>
@endif

<form method="POST" action="{{ route('auth.google.completar.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">RA</label>
        <input type="text" name="ra" class="form-control"
               placeholder="Ex: 12345" value="{{ old('ra') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control"
               placeholder="18912345678" value="{{ old('telefone') }}"
               maxlength="11"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
    </div>  

    <div class="mb-4">
        <label class="form-label">Data de Nascimento</label>
        <input type="date" name="data_nascimento" class="form-control"
               value="{{ old('data_nascimento') }}" required>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-entrar">
            Concluir Cadastro
        </button>
    </div>
</form>
@endsection

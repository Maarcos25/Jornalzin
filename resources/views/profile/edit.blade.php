@extends('layouts.autenticacao')

@section('conteudo')

<div class="container">

<div class="text-center mb-3">
<a href="/" style="text-decoration:none;font-size:28px;font-weight:bold;color:black;">
📰 Jornalzin
</a>
</div>

<h4 class="text-center mb-4">Configurações da Conta</h4>


<!-- ABAS -->
<ul class="nav nav-tabs mb-3" id="configTabs">

<li class="nav-item">
<button class="nav-link active" data-bs-toggle="tab" data-bs-target="#perfil">
Perfil
</button>
</li>

<li class="nav-item">
<button class="nav-link" data-bs-toggle="tab" data-bs-target="#senha">
Segurança
</button>
</li>

<li class="nav-item">
<button class="nav-link" data-bs-toggle="tab" data-bs-target="#conta">
Conta
</button>
</li>

</ul>


<div class="tab-content">

<!-- PERFIL -->
<div class="tab-pane fade show active" id="perfil">

<div class="card shadow">
<div class="card-body">

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

</div>


<!-- SENHA -->
<div class="tab-pane fade" id="senha">

<div class="card shadow">
<div class="card-body">

<form method="POST" action="{{ route('password.update') }}">
@csrf
@method('PUT')

<div class="mb-3">
<label class="form-label">Senha atual</label>
<input type="password" name="current_password" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Nova senha</label>
<input type="password" name="password" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Confirmar nova senha</label>
<input type="password" name="password_confirmation" class="form-control">
</div>

<button class="btn btn-primary">
Atualizar senha
</button>

</form>

</div>
</div>

</div>


<!-- CONTA -->
<div class="tab-pane fade" id="conta">

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

</div>

</div>

</div>

@endsection

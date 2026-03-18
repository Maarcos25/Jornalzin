@extends('layouts.autenticacao')

@section('conteudo')

<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
<!-- Foto do Perfil -->
<div class="mb-3 text-center">
    <div style="position: relative; display: inline-block;">
        <img
            id="avatarPreview"
            src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('img/default-avatar.png') }}"
            alt="Avatar"
            style="width:100px; height:100px; min-width:100px; min-height:100px; border-radius:50%; object-fit:cover; border:2px solid #ccc;"
            onclick="document.getElementById('avatarInput').click();">

        <!-- Lápis de edição -->
        <span
            onclick="document.getElementById('avatarInput').click();"
            style="position:absolute; bottom:0; right:0; background:#0d6efd; color:white; border-radius:50%; padding:4px; cursor:pointer;">
            ✏️
        </span>

        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewAvatar(event)">
    </div>
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if(file){
        document.getElementById('avatarPreview').src = URL.createObjectURL(file);
    }
}
</script>


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

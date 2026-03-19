@extends('layouts.autenticacao')

@section('conteudo')

<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
<!-- Foto do Perfil -->
<!-- Foto do Perfil -->
<div class="mb-3 text-center">

<div class="mb-3 text-center" style="position: relative; width:100px; height:100px; margin:auto; cursor:pointer;">

    @if(auth()->user()->avatar)
        <img
            id="avatarPreview"
            src="{{ asset('storage/' . auth()->user()->avatar) }}"
            style="width:100%; height:100%; border-radius:50%; object-fit:cover; border:2px solid #ccc;"
            onclick="document.getElementById('avatarInput').click();">
    @else
        <div id="cameraPlaceholder" style="width:100%; height:100%; border-radius:50%; background:#eee; border:2px solid #ccc; position:relative;" onclick="document.getElementById('avatarInput').click();">
            <span id="cameraIcon" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); font-size:28px; color:#666;">📷</span>
        </div>
        <img id="avatarPreview" src="" style="width:100%; height:100%; border-radius:50%; object-fit:cover; display:none; position:absolute; top:0; left:0; border:2px solid #ccc;">
    @endif

    <!-- Input agora 100% dentro do form -->
    <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="previewAvatar(event)" style="position:absolute; width:100%; height:100%; top:0; left:0; opacity:0; cursor:pointer;">
</div>

<script>
function previewAvatar(event){
    const file = event.target.files[0];
    if(!file) return;

    const preview = document.getElementById('avatarPreview');
    const icon = document.getElementById('cameraIcon');
    const placeholder = document.getElementById('cameraPlaceholder');

    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';

    if(icon) icon.style.display = 'none';
    if(placeholder) placeholder.style.background = 'transparent';
}
</script>

</div>
<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if(!file) return;

        const preview = document.getElementById('avatarPreview');
        const icon = document.getElementById('cameraIcon');

        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        icon.style.display = 'none';
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
<input type="text" name="nome" class="form-control"
value="{{ auth()->user()->nome }}" required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control"
value="{{ auth()->user()->email }}" required>
</div>

<button class="btn btn-primary">Salvar</button>
</form>
@if(auth()->user()->avatar)
    <div class="text-center mt-2">
        <form method="POST" action="{{ route('profile.deleteAvatar') }}">
            @csrf
            @method('DELETE')

            <button type="submit" onclick="return confirm('Remover foto?')">
                🗑️ Remover foto
            </button>
        </form>
    </div>
@endif
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

@extends('layouts.site')

@section('conteudo')
<style>
    footer { display: none !important; }

    .page-content {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        height: auto;
        overflow: visible;
        padding: 2rem 1rem;
    }

    .profile-wrap {
        max-width: 750px;
        width: 100%;
        background: var(--surface);
        border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,.2);
        padding: 1.5rem 2rem;
        max-height: none;
        overflow-y: visible;
    }

    .profile-page-header { text-align: center; margin-bottom: 0.5rem; position: relative; }
    .profile-page-header .logo {
        font-family: 'UnifrakturMaguntia', cursive;
        font-size: 3rem;
        font-weight: 400;
        color: var(--text);
        letter-spacing: .02em;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .profile-page-header p { color: var(--muted); font-size: .9rem; margin: .3rem 0 0; }

    .btn-fechar { position: absolute; top: 50%; right: 0; transform: translateY(-50%); width: 36px; height: 36px; background: #fef2f2; border: 2px solid #fecaca; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 900; color: var(--danger); text-decoration: none; transition: all .2s; }
    .btn-fechar:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

    .avatar-section { display: flex; justify-content: center; margin-bottom: 0.5rem; }
    .avatar-wrapper { position: relative; width: 110px; height: 110px; cursor: pointer; }
    .avatar-img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid var(--brand); }
    .avatar-placeholder { width: 100%; height: 100%; border-radius: 50%; background: var(--surface-2); border: 2px dashed var(--border); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--muted); }
    .pencil-icon { position: absolute; bottom: 4px; right: 4px; background: var(--brand); color: #fff; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: 3px solid var(--surface); }

    .profile-tabs { display: flex; justify-content: center; gap: .5rem; margin-bottom: 0.6rem; }
    .profile-tab-btn { display: inline-flex; align-items: center; gap: .35rem; padding: .55rem 1.4rem; border-radius: 50px; border: 1.5px solid var(--border); background: var(--surface); color: var(--muted); font-weight: 600; font-size: .9rem; cursor: pointer; transition: all .2s; }
    .profile-tab-btn:hover { border-color: var(--brand); color: var(--brand); }
    .profile-tab-btn.active { background: linear-gradient(135deg, var(--brand), var(--brand-dark)); color: #fff; border-color: transparent; }

    .profile-card { background: var(--surface); border-radius: 14px; border: 1px solid var(--border); overflow: hidden; }
    .profile-card-header { background: linear-gradient(135deg, var(--brand), var(--brand-dark)); padding: 1rem 1.5rem; color: #fff; font-size: 1rem; font-weight: 800; }
    .profile-card-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; background: var(--surface); }
    .profile-card-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border); background: var(--surface-2); display: flex; gap: .6rem; align-items: center; }

    .form-group label { display: block; font-size: .8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .4rem; }
    .form-group input { width: 100%; padding: .7rem 1rem; border: 1.5px solid var(--border); border-radius: 10px; font-size: 1rem; color: var(--text); background: var(--surface); transition: border .2s; box-sizing: border-box; }
    .form-group input:focus { outline: none; border-color: var(--brand); }
    .form-error { color: var(--danger); font-size: .8rem; margin-top: .25rem; }

    .alert-ok  { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 10px; padding: .75rem 1rem; font-size: .9rem; }
    .alert-err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 10px; padding: .75rem 1rem; font-size: .9rem; }

    .btn-salvar { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.6rem; border-radius: 50px; background: linear-gradient(135deg, var(--brand), var(--brand-dark)); color: #fff; font-weight: 700; font-size: .95rem; border: none; cursor: pointer; transition: all .2s; }
    .btn-salvar:hover { transform: translateY(-1px); }
    .btn-remover-foto { background: none; border: none; cursor: pointer; color: var(--danger); font-size: .88rem; font-weight: 600; margin-left: auto; text-decoration: underline; }

    .danger-card { background: var(--surface); border-radius: 14px; border: 1px solid var(--border); border-left: 4px solid var(--danger); overflow: hidden; }
    .danger-card-body { padding: 2.5rem 2rem; }
    .danger-card-body h5 { color: var(--danger); font-weight: 800; font-size: 1.3rem; margin: 0 0 .8rem; }
    .danger-card-body p  { color: var(--muted); font-size: 1rem; margin: 0 0 1.5rem; line-height: 1.6; }
    .btn-excluir-conta { width: 100%; padding: 1rem; border: 1.5px solid var(--danger); background: #fef2f2; color: var(--danger); border-radius: 10px; font-weight: 700; cursor: pointer; transition: all .2s; font-size: 1rem; }
    .btn-excluir-conta:hover { background: var(--danger); color: #fff; }

    html.dark .profile-wrap        { background: var(--surface); }
    html.dark .profile-card        { background: var(--surface); border-color: var(--border); }
    html.dark .profile-card-body   { background: var(--surface); }
    html.dark .profile-card-footer { background: var(--surface-2); border-color: var(--border); }
    html.dark .form-group input    { background: var(--surface-2); border-color: var(--border); color: var(--text); }
    html.dark .profile-tab-btn     { background: var(--surface); border-color: var(--border); color: var(--muted); }
    html.dark .danger-card         { background: var(--surface); border-color: var(--border); }
    html.dark .btn-fechar          { background: #2d1b1b; border-color: #7f1d1d; }
    html.dark .alert-ok            { background: #14532d; border-color: #166534; color: #bbf7d0; }
    html.dark .alert-err           { background: #450a0a; border-color: #7f1d1d; color: #fecaca; }

    #senhaMsg { font-size: .82rem; font-weight: 700; margin-top: .3rem; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }
    #tab-senha .profile-card-body { gap: 0.5rem; padding: 0.8rem 1.5rem; }
    #tab-senha .form-group input { padding: .45rem .9rem; }
    #tab-senha .profile-card-footer { padding: 0.6rem 1.5rem; }
    .container.mt-4 { margin-top: 0 !important; }
</style>

<div class="profile-wrap">

    <div class="profile-page-header">
        <a href="/" class="logo">📰 Jornalzin</a>
        <p>Gerencie suas informações e segurança</p>
        <a href="{{ route('home') }}" class="btn-fechar" title="Voltar para o início">✕</a>
    </div>

    <div class="avatar-section">
        <div class="avatar-wrapper" onclick="document.getElementById('avatarInput').click()">
            @if(auth()->user()->avatar)
                <img id="avatarPreview" class="avatar-img"
                     src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
            @else
                <div id="cameraPlaceholder" class="avatar-placeholder">📷</div>
                <img id="avatarPreview" class="avatar-img" src="" style="display:none; position:absolute; top:0; left:0;">
            @endif
            <div class="pencil-icon">✏️</div>
        </div>
    </div>

    @php
        $abaAtiva = 'perfil';
        if (session('senha_success') || $errors->has('current_password') || $errors->has('password')) {
            $abaAtiva = 'senha';
        }
    @endphp

    <div class="profile-tabs">
        <button class="profile-tab-btn {{ $abaAtiva === 'perfil' ? 'active' : '' }}" onclick="trocarAba('perfil', this)">👤 Perfil</button>
        <button class="profile-tab-btn {{ $abaAtiva === 'senha'  ? 'active' : '' }}" onclick="trocarAba('senha', this)">🔒 Segurança</button>
        <button class="profile-tab-btn" onclick="trocarAba('conta', this)">⚙️ Conta</button>
    </div>

    <div class="tab-pane {{ $abaAtiva === 'perfil' ? 'active' : '' }}" id="tab-perfil">
        <div class="profile-card">
            <div class="profile-card-header">👤 Informações do Perfil</div>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PATCH')
                <input type="file" name="avatar" id="avatarInput" accept="image/*"
                       onchange="previewAvatar(event); this.form.submit();" style="display:none;">
                <div class="profile-card-body">
                    @if(session('success'))
                        <div class="alert-ok">✅ {{ session('success') }}</div>
                    @endif
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="nome" value="{{ old('nome', auth()->user()->nome) }}" required>
                        @error('nome') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="profile-card-footer">
                    <button type="submit" class="btn-salvar">💾 Salvar Alterações</button>
                </div>
            </form>
            @if(auth()->user()->avatar)
                <form method="POST" action="{{ route('profile.deleteAvatar') }}"
                      style="padding:.8rem 2.5rem;background:var(--surface-2);display:flex;border-top:1px solid var(--border);">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-remover-foto" onclick="return confirm('Remover foto?')">🗑️ Remover foto atual</button>
                </form>
            @endif
        </div>
    </div>

    <div class="tab-pane {{ $abaAtiva === 'senha' ? 'active' : '' }}" id="tab-senha">
        <div class="profile-card">
            <div class="profile-card-header">🔒 Alterar Senha</div>
            <form method="POST" action="{{ route('senha.update') }}">
                @csrf @method('PUT')
                <div class="profile-card-body">
                    @if(session('senha_success'))
                        <div class="alert-ok">✅ {{ session('senha_success') }}</div>
                    @endif
                    @foreach(['current_password','password'] as $f)
                        @if($errors->has($f))
                            <div class="alert-err">❌ {{ $errors->first($f) }}</div>
                        @endif
                    @endforeach
                    <div class="form-group">
                        <label>Senha Atual</label>
                        <input type="password" name="current_password" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label>Nova Senha</label>
                        <input type="password" name="password" id="novaSenha" placeholder="Mínimo 8 caracteres" oninput="verificarSenhas()">
                    </div>
                    <div class="form-group">
                        <label>Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation" id="confirmarSenha" placeholder="Repita a senha" oninput="verificarSenhas()">
                        <div id="senhaMsg"></div>
                    </div>
                </div>
                <div class="profile-card-footer">
                    <button type="submit" class="btn-salvar" id="btnSalvarSenha">🔑 Atualizar Senha</button>
                </div>
            </form>
        </div>
    </div>

    <div class="tab-pane" id="tab-conta">
        <div class="danger-card">
            <div class="danger-card-body">
                <h5>⚠️ Zona de Perigo</h5>
                <p>Ao excluir sua conta, todos os seus dados (posts, comentários e perfil) serão apagados permanentemente.</p>
                <form method="POST" action="{{ route('profile.destroy') }}"
                      onsubmit="return confirm('Esta ação não pode ser desfeita. Confirmar exclusão?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-excluir-conta">Excluir Minha Conta</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function trocarAba(aba, btn) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.profile-tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + aba).classList.add('active');
        btn.classList.add('active');
    }
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;
        const preview = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('cameraPlaceholder');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
    }
    function verificarSenhas() {
        const nova = document.getElementById('novaSenha').value;
        const confirmar = document.getElementById('confirmarSenha').value;
        const msg = document.getElementById('senhaMsg');
        const btn = document.getElementById('btnSalvarSenha');
        if (confirmar === '') { msg.textContent = ''; btn.disabled = false; return; }
        if (nova.length > 0 && nova.length < 8) {
            msg.textContent = '⚠️ Mínimo de 8 caracteres';
            msg.style.color = '#f59e0b'; btn.disabled = true; return;
        }
        if (nova === confirmar) {
            msg.textContent = '✅ As senhas coincidem';
            msg.style.color = '#16a34a'; btn.disabled = false;
        } else {
            msg.textContent = '❌ As senhas não coincidem';
            msg.style.color = '#ef4444'; btn.disabled = true;
        }
    }
</script>
@endpush

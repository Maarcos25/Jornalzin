@extends('layouts.autenticacao')

@section('conteudo')
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background: #f1f5f9 !important;
}

    :root {
        --brand:      #4f46e5;
        --brand-dark: #3730a3;
        --surface:    #ffffff;
        --surface-2:  #f8fafc;
        --border:     #e2e8f0;
        --text:       #1e293b;
        --muted:      #64748b;
        --danger:     #ef4444;
        --radius:     14px;
        --shadow:     0 2px 12px rgba(0,0,0,.07);
    }

    body {
    display: flex;
    align-items: center;
    justify-content: center;
    zoom: 0.95;
}

.profile-wrap {
    max-width: 960px;
    width: 100%;
    margin: 0 auto;
    padding: 1rem 1.5rem;
}

    /* Header */
    .profile-page-header {
    text-align: center;
    margin-bottom: 1rem;
}
    .profile-page-header .logo {
        font-size: 1.9rem; font-weight: 800;
        color: var(--text); letter-spacing: .03em;
        text-decoration: none; display: inline-flex;
        align-items: center; gap: .4rem;
    }
    .profile-page-header p {
        color: var(--muted); font-size: .9rem; margin: .3rem 0 0;
    }

    /* Avatar */
    .avatar-section {
    display: flex; justify-content: center; margin-bottom: 1rem;
}
    .avatar-wrapper {
        position: relative; width: 120px; height: 120px;
        cursor: pointer; transition: transform .2s;
    }
    .avatar-wrapper {
    position: relative; width: 130px; height: 130px;
    cursor: pointer; transition: transform .2s;
}
    .avatar-img {
        width: 100%; height: 100%; border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--brand);
    }
    .avatar-placeholder {
        width: 100%; height: 100%; border-radius: 50%;
        background: var(--surface-2);
        border: 2px dashed var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; color: var(--muted);
    }
    .pencil-icon {
        position: absolute; bottom: 4px; right: 4px;
        background: var(--brand); color: #fff;
        border-radius: 50%; width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; border: 3px solid #f1f5f9;
    }

    /* Tabs */
    .profile-tabs {
    display: flex; justify-content: center;
    gap: .5rem; margin-bottom: 1rem;
}
    .profile-tab-btn {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .55rem 1.4rem; border-radius: 50px;
        border: 1.5px solid var(--border);
        background: var(--surface); color: var(--muted);
        font-weight: 600; font-size: .9rem;
        cursor: pointer; transition: all .2s;
    }
    .profile-tab-btn:hover { border-color: var(--brand); color: var(--brand); }
    .profile-tab-btn.active {
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color: #fff; border-color: transparent;
        box-shadow: 0 4px 14px rgba(79,70,229,.3);
    }

    /* Card */
    .profile-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }
    .profile-card-header {
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        padding: 1.2rem 2rem;
        color: #fff; font-size: 1.1rem; font-weight: 800;
    }
    .profile-card-body {
    padding: 1.5rem 2.5rem;
    display: flex; flex-direction: column; gap: 1rem;
}

    /* Campos */
    .form-group label {
        display: block;
        font-size: .8rem; font-weight: 700;
        color: var(--muted); text-transform: uppercase;
        letter-spacing: .04em; margin-bottom: .4rem;
    }
    .form-group input {
        width: 100%; padding: .7rem 1rem;
        border: 1.5px solid var(--border); border-radius: 10px;
        font-size: 1rem; color: var(--text);
        background: var(--surface); transition: border .2s;
        box-sizing: border-box;
    }
    .form-group input:focus {
        outline: none; border-color: var(--brand);
    }
    .form-error { color: var(--danger); font-size: .8rem; margin-top: .25rem; }

    /* Alerts */
    .alert-ok {
        background: #f0fdf4; border: 1px solid #bbf7d0;
        color: #166534; border-radius: 10px;
        padding: .75rem 1rem; font-size: .9rem;
    }
    .alert-err {
        background: #fef2f2; border: 1px solid #fecaca;
        color: #991b1b; border-radius: 10px;
        padding: .75rem 1rem; font-size: .9rem;
    }

    /* Footer do card */
    .profile-card-footer {
        padding: 1rem 2.5rem;
        border-top: 1px solid var(--border);
        background: var(--surface-2);
        display: flex; gap: .6rem; align-items: center;
    }
    .btn-salvar {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.6rem; border-radius: 50px;
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color: #fff; font-weight: 700; font-size: .95rem;
        border: none; cursor: pointer;
        box-shadow: 0 4px 14px rgba(79,70,229,.3);
        transition: all .2s;
    }
    .btn-salvar:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); }
    .btn-salvar:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    .btn-remover-foto {
        background: none; border: none; cursor: pointer;
        color: var(--danger); font-size: .88rem; font-weight: 600;
        margin-left: auto; text-decoration: underline;
    }

    /* Zona de perigo */
    .danger-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        border-left: 4px solid var(--danger);
        overflow: hidden;
    }
    .danger-card-body { padding: 2rem 2.5rem; }
    .danger-card-body h5 { color: var(--danger); font-weight: 800; margin: 0 0 .4rem; font-size: 1.1rem; }
    .danger-card-body p  { color: var(--muted); font-size: .9rem; margin: 0 0 1rem; }
    .btn-excluir-conta {
        width: 100%; padding: .7rem;
        border: 1.5px solid var(--danger);
        background: #fef2f2; color: var(--danger);
        border-radius: 10px; font-weight: 700; font-size: .95rem;
        cursor: pointer; transition: all .2s;
    }
    .btn-excluir-conta:hover { background: var(--danger); color: #fff; }

    #senhaMsg { font-size: .82rem; font-weight: 700; margin-top: .3rem; }

    .tab-pane { display: none; }
    .tab-pane.active { display: block; }
</style>

<div class="profile-wrap">

    {{-- Logo --}}
    <div class="profile-page-header">
        <a href="/" class="logo">📰 Jornalzin</a>
        <p>Gerencie suas informações e segurança</p>
    </div>

    {{-- Avatar --}}
    <div class="avatar-section">
        <div class="avatar-wrapper" onclick="document.getElementById('avatarInput').click()">
            @if(auth()->user()->avatar)
                <img id="avatarPreview" class="avatar-img"
                     src="{{ asset('storage/' . auth()->user()->avatar) }}"
                     alt="Avatar">
            @else
                <div id="cameraPlaceholder" class="avatar-placeholder">📷</div>
                <img id="avatarPreview" class="avatar-img"
                     src="" style="display:none; position:absolute; top:0; left:0;">
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

    {{-- Tabs --}}
    <div class="profile-tabs">
        <button class="profile-tab-btn {{ $abaAtiva === 'perfil' ? 'active' : '' }}"
                onclick="trocarAba('perfil', this)">👤 Perfil</button>
        <button class="profile-tab-btn {{ $abaAtiva === 'senha' ? 'active' : '' }}"
                onclick="trocarAba('senha', this)">🔒 Segurança</button>
        <button class="profile-tab-btn"
                onclick="trocarAba('conta', this)">⚙️ Conta</button>
    </div>

    {{-- ABA PERFIL --}}
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
                      style="padding: .8rem 2.5rem; background: var(--surface-2); display:flex; border-top: 1px solid var(--border);">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-remover-foto"
                            onclick="return confirm('Remover foto?')">🗑️ Remover foto atual</button>
                </form>
            @endif
                </div>
            </form>
        </div>
    </div>

    {{-- ABA SEGURANÇA --}}
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
                        <input type="password" name="password" id="novaSenha"
                               placeholder="Mínimo 8 caracteres" oninput="verificarSenhas()">
                    </div>
                    <div class="form-group">
                        <label>Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation" id="confirmarSenha"
                               placeholder="Repita a senha" oninput="verificarSenhas()">
                        <div id="senhaMsg"></div>
                    </div>
                </div>

                <div class="profile-card-footer">
                    <button type="submit" class="btn-salvar" id="btnSalvarSenha">🔑 Atualizar Senha</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ABA CONTA --}}
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

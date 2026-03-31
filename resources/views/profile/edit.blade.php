    @extends('layouts.autenticacao')

    @section('conteudo')

    {{-- Foto do Perfil --}}
    <div class="mb-3 text-center">
        <div style="position: relative; width:100px; height:100px; margin:auto; cursor:pointer;"
            onclick="document.getElementById('avatarInput').click()">

            @if(auth()->user()->avatar)
                <img
                    id="avatarPreview"
                    src="{{ asset('storage/' . auth()->user()->avatar) }}"
                    style="width:100%; height:100%; border-radius:50%; object-fit:cover; border:2px solid #ccc;">
            @else
                <div id="cameraPlaceholder" style="width:100%; height:100%; border-radius:50%; background:#eee; border:2px solid #ccc; position:relative;">
                    <span id="cameraIcon" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); font-size:28px; color:#666;">📷</span>
                </div>
                <img id="avatarPreview" src="" style="width:100%; height:100%; border-radius:50%; object-fit:cover; display:none; position:absolute; top:0; left:0; border:2px solid #ccc;">
            @endif

            {{-- Lápis ✏️ --}}
            <span style="
                position: absolute;
                bottom: 4px;
                right: 0px;
                background: #fff;
                border-radius: 50%;
                width: 26px;
                height: 26px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.2);
                pointer-events: none;
            ">✏️</span>

        </div>
    </div>

    <div class="text-center mb-3">
        <a href="/" style="text-decoration:none;font-size:28px;font-weight:bold;color:black;">
            📰 Jornalzin
        </a>
    </div>

    <h4 class="text-center mb-4">Configurações da Conta</h4>

    {{-- Define qual aba deve abrir --}}
    @php
        $abaAtiva = 'perfil';
        if (session('senha_success') || $errors->has('current_password') || $errors->has('password')) {
            $abaAtiva = 'senha';
        }
    @endphp

    {{-- ABAS --}}
    <ul class="nav nav-tabs mb-3" id="configTabs">
        <li class="nav-item">
            <button class="nav-link {{ $abaAtiva === 'perfil' ? 'active' : '' }}"
                    data-bs-toggle="tab" data-bs-target="#perfil">Perfil</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $abaAtiva === 'senha' ? 'active' : '' }}"
                    data-bs-toggle="tab" data-bs-target="#senha">Segurança</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#conta">Conta</button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ABA PERFIL --}}
        <div class="tab-pane fade {{ $abaAtiva === 'perfil' ? 'show active' : '' }}" id="perfil">
            <div class="card shadow">
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <input type="file" name="avatar" id="avatarInput" accept="image/*"
                            onchange="previewAvatar(event); this.form.submit();"
                            style="display:none;">

                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control"
                                value="{{ old('nome', auth()->user()->nome) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', auth()->user()->email) }}" required>
                        </div>

                        <button class="btn btn-primary">Salvar</button>

                    </form>

                    @if(auth()->user()->avatar)
                        <div class="text-center mt-3">
                            <form method="POST" action="{{ route('profile.deleteAvatar') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                    onclick="return confirm('Remover foto?')">
                                    🗑️ Remover foto
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ABA SEGURANÇA --}}
        <div class="tab-pane fade {{ $abaAtiva === 'senha' ? 'show active' : '' }}" id="senha">
            <div class="card shadow">
                <div class="card-body">

                    @if(session('senha_success'))
                        <div class="alert alert-success">✅ {{ session('senha_success') }}</div>
                    @endif

                    @if($errors->has('current_password'))
                        <div class="alert alert-danger">❌ {{ $errors->first('current_password') }}</div>
                    @endif

                    @if($errors->has('password'))
                        <div class="alert alert-danger">❌ {{ $errors->first('password') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Senha atual</label>
                            <input type="password" name="current_password" class="form-control"
                                placeholder="Digite sua senha atual">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nova senha</label>
                            <input type="password" name="password" id="novaSenha" class="form-control"
                                placeholder="Mínimo 8 caracteres" oninput="verificarSenhas()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar nova senha</label>
                            <input type="password" name="password_confirmation" id="confirmarSenha" class="form-control"
                                placeholder="Repita a nova senha" oninput="verificarSenhas()">
                            <div id="senhaMsg" style="font-size:.85rem; margin-top:.3rem;"></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="btnSalvarSenha">Atualizar senha</button>

                    </form>

                </div>
            </div>
        </div>

        {{-- ABA CONTA --}}
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
                        <button type="submit" class="btn btn-danger">Excluir Conta</button>
                    </form>

                </div>
            </div>
        </div>

    </div>

    @endsection

    @push('scripts')
    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;
            const preview     = document.getElementById('avatarPreview');
            const icon        = document.getElementById('cameraIcon');
            const placeholder = document.getElementById('cameraPlaceholder');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            if (icon) icon.style.display = 'none';
            if (placeholder) placeholder.style.background = 'transparent';
        }

        function verificarSenhas() {
            const nova      = document.getElementById('novaSenha').value;
            const confirmar = document.getElementById('confirmarSenha').value;
            const msg       = document.getElementById('senhaMsg');
            const btn       = document.getElementById('btnSalvarSenha');

            if (confirmar === '') {
                msg.textContent = '';
                btn.disabled = false;
                return;
            }

            if (nova.length > 0 && nova.length < 8) {
                msg.textContent = '⚠️ A senha deve ter pelo menos 8 caracteres.';
                msg.style.color = 'orange';
                btn.disabled = true;
                return;
            }

            if (nova === confirmar) {
                msg.textContent = '✅ As senhas coincidem!';
                msg.style.color = 'green';
                btn.disabled = false;
            } else {
                msg.textContent = '❌ As senhas não coincidem.';
                msg.style.color = 'red';
                btn.disabled = true;
            }
        }
    </script>
    @endpush

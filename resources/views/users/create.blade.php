@extends('layouts.site')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Página sem scroll */
    .page-content {
        overflow: hidden !important;
        height: calc(100vh - 60px) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0.5rem 1rem !important;
    }

.user-creator-wrap {
    max-width: 860px;
    width: 100%;
    margin: 0 !important;
    padding: 0 1rem;
    font-family: 'Nunito', sans-serif;
}

.creator-card {
    background: var(--surface);
    border-radius: 14px !important;
    box-shadow: 0 4px 24px rgba(99,102,241,.15);
    border: 1px solid var(--border);
    max-height: calc(100vh - 145px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.user-creator-wrap {
    max-width: 860px;
    width: 100%;
    margin: 0 auto;
    padding: 0 1rem; /* adiciona espaço nas laterais */
    font-family: 'Nunito', sans-serif;
}

.page-content {
    overflow: hidden !important;
    height: calc(100vh - 60px) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 1rem !important;
    margin-top: 0 !important;
}
    .creator-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        padding: 1rem 1.8rem;
        display: flex; align-items: center; gap: .75rem;
        flex-shrink: 0;
    }
    .creator-header h4 { color: #fff; margin: 0; font-weight: 700; font-size: 1.2rem; }

    .creator-body {
        padding: 1rem 1.8rem;
        overflow-y: auto;
        flex: 1;
    }
    .creator-body::-webkit-scrollbar { width: 5px; }
    .creator-body::-webkit-scrollbar-track { background: transparent; }
    .creator-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

    .creator-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.8rem 1.8rem;
        border-top: 1px solid var(--border);
        background: var(--surface-2);
        flex-shrink: 0;
    }

.validation-alert {
    background: #fef2f2 !important;
    border: 1px solid #fecaca !important;
    border-radius: 10px;
    padding: .4rem .8rem;
    margin-bottom: 0.5rem;
    color: #b91c1c !important;
    font-size: .8rem;
}

    .validation-alert ul { margin: .2rem 0 0 1rem; padding: 0; }
.validation-alert ul li { line-height: 1.3; }

    .fields-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
        margin-bottom: 0.6rem;
    }
    @media(max-width: 560px) { .fields-row { grid-template-columns: 1fr; } }

    .form-section { margin-bottom: 0.4rem; }
    .form-section > label {
        display: block; font-size: .83rem; font-weight: 700;
        color: var(--muted); text-transform: uppercase;
        letter-spacing: .04em; margin-bottom: .3rem;
    }

    .form-control-styled {
        width: 100%; padding: .55rem .9rem;
        border: 2px solid var(--border); border-radius: 10px;
        font-size: .93rem; color: var(--text); background: var(--surface);
        transition: border-color .2s, box-shadow .2s;
        font-family: inherit; box-sizing: border-box;
    }
    .form-control-styled:focus {
        outline: none; border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .form-control-styled.is-invalid { border-color: #ef4444; }

    .info-box {
        display: flex; align-items: center; gap: .6rem;
        background: #eef2ff; border: 1px solid #c7d2fe;
        border-radius: 10px; padding: .5rem 1rem;
        color: #4f46e5; font-size: .85rem;
        margin-bottom: 0.8rem;
    }

    .section-divider { border: none; border-top: 1px solid var(--border); margin: 0.6rem 0; }

    .btn-voltar {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .55rem 1.2rem; border-radius: 10px;
        border: 2px solid var(--border); background: var(--surface);
        color: var(--muted); font-weight: 700; font-size: .9rem;
        text-decoration: none; transition: all .2s;
    }
    .btn-voltar:hover { border-color: var(--muted); color: var(--text); }

    .btn-salvar {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .6rem 1.6rem; border-radius: 10px; border: none;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; font-weight: 700; font-size: .95rem;
        cursor: pointer; transition: all .2s;
        box-shadow: 0 4px 14px rgba(99,102,241,.35);
    }
    .btn-salvar:hover { transform: translateY(-1px); }

    .senha-wrap { position: relative; }
    .senha-wrap .form-control-styled { padding-right: 2.8rem; }
    .btn-olho {
        position: absolute; right: 10px; top: 50%;
        transform: translateY(-50%);
        background: none; border: none;
        cursor: pointer; color: var(--muted);
        font-size: 1.1rem; line-height: 1; padding: 0;
    }
    .btn-olho:hover { color: #6366f1; }

    /* Dark mode */
    html.dark .creator-card        { background: var(--surface) !important; border-color: var(--border) !important; }
    html.dark .creator-body        { background: var(--surface) !important; }
    html.dark .creator-footer      { background: var(--surface-2) !important; border-color: var(--border) !important; }
    html.dark .form-control-styled { background: var(--surface-2) !important; border-color: var(--border) !important; color: var(--text) !important; }
    html.dark .form-control-styled::placeholder { color: var(--muted) !important; }
    html.dark .form-section > label { color: var(--muted) !important; }
    html.dark .info-box            { background: #1e1f3a !important; border-color: #3730a3 !important; color: #a5b4fc !important; }
    html.dark .section-divider     { border-color: var(--border) !important; }
    html.dark .btn-voltar          { background: var(--surface-2) !important; border-color: var(--border) !important; color: var(--muted) !important; }
html.dark .validation-alert {
    background: #3b0a0a !important;
    border-color: #991b1b !important;
    color: #fca5a5 !important;
}
</style>
@endpush

@section('conteudo')
<div class="user-creator-wrap">
    <div class="creator-card">

        <div class="creator-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.85)" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h4>Criar Usuário</h4>
        </div>

        <div class="creator-body">

            @if ($errors->any())
                <div class="validation-alert">
                    <strong>Por favor, corrija os erros:</strong>
                    <ul>
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="info-box">
                    ℹ️ O nível de acesso é definido automaticamente pelo e-mail cadastrado.
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}" id="userForm">
                @csrf

                <div class="fields-row">
                    <div class="form-section">
                        <label>Nome</label>
                        <input type="text" name="nome"
                               class="form-control-styled @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" placeholder="João">
                    </div>
                    <div class="form-section">
                        <label>Sobrenome</label>
                        <input type="text" name="sobrenome"
                               class="form-control-styled @error('sobrenome') is-invalid @enderror"
                               value="{{ old('sobrenome') }}" placeholder="Silva">
                    </div>
                </div>

                <div class="fields-row">
                    <div class="form-section">
                        <label>Email</label>
                        <input type="email" name="email"
                               class="form-control-styled @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="usuario@gmail.com">
                    </div>
                    <div class="form-section">
                        <label>RA</label>
                        <input type="text" name="ra"
                               class="form-control-styled @error('ra') is-invalid @enderror"
                               value="{{ old('ra') }}" placeholder="Ex: 12345">
                    </div>
                </div>

                <div class="fields-row">
                    <div class="form-section">
                        <label>Telefone</label>
                        <input type="text" name="telefone" id="telefone"
                               class="form-control-styled @error('telefone') is-invalid @enderror"
                               value="{{ old('telefone') }}" maxlength="15"
                               placeholder="(18) 91234-5678">
                    </div>
                    <div class="form-section">
                        <label>Data de Nascimento</label>
                        <input type="date" name="data_nascimento"
                               class="form-control-styled @error('data_nascimento') is-invalid @enderror"
                               value="{{ old('data_nascimento') }}"
                               min="1900-01-01"
                               max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <hr class="section-divider">

                <div class="fields-row">
                    <div class="form-section">
                        <label>Senha</label>
                        <div class="senha-wrap">
                            <input type="password" name="password" id="senha"
                                   class="form-control-styled @error('password') is-invalid @enderror"
                                   placeholder="Mínimo 6 caracteres">
                            <button type="button" class="btn-olho" id="olho1" onclick="toggleSenha('senha','olho1')">👁</button>
                        </div>
                    </div>
                    <div class="form-section">
                        <label>Confirmar Senha</label>
                        <div class="senha-wrap">
                            <input type="password" name="password_confirmation" id="senha2"
                                   class="form-control-styled"
                                   placeholder="Repita a senha">
                            <button type="button" class="btn-olho" id="olho2" onclick="toggleSenha('senha2','olho2')">👁</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="creator-footer">
            <a href="{{ url()->previous() }}" class="btn-voltar">← Cancelar</a>
            <button type="submit" form="userForm" class="btn-salvar">✓ Salvar Usuário</button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('telefone').addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, '').substring(0, 11);
        if (v.length > 10) {
            v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
        } else if (v.length > 6) {
            v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
        } else if (v.length > 2) {
            v = v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
        } else {
            v = v.replace(/^(\d*)$/, '($1');
        }
        e.target.value = v;
    });

    function toggleSenha(inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn   = document.getElementById(btnId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁';
        }
    }
</script>
@endpush
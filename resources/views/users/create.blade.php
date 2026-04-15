@extends('layouts.site')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --brand:       #6366f1;
        --brand-light: #818cf8;
        --brand-dark:  #4f46e5;
        --surface:     #ffffff;
        --surface-2:   #f8fafc;
        --border:      #e2e8f0;
        --text:        #1e293b;
        --muted:       #64748b;
        --danger:      #ef4444;
        --radius:      14px;
        --shadow:      0 4px 24px rgba(99,102,241,.10);
    }

    .user-creator-wrap {
        max-width: 760px;
        margin: 2rem auto;
        font-family: 'Nunito', sans-serif;
    }

    .creator-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .creator-header {
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
        padding: 1.4rem 1.8rem;
        display: flex; align-items: center; gap: .75rem;
    }
    .creator-header h4 { color: #fff; margin: 0; font-weight: 700; font-size: 1.2rem; }

    .creator-body { padding: 1.8rem; }

    .validation-alert {
        background: #fef2f2; border: 1px solid #fecaca;
        border-radius: 10px; padding: .85rem 1.1rem;
        color: #b91c1c; font-size: .88rem; margin-bottom: 1.4rem;
    }
    .validation-alert ul { margin: .3rem 0 0 1rem; padding: 0; }

    .fields-row {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 1rem; margin-bottom: 1.1rem;
    }
    @media(max-width: 560px) { .fields-row { grid-template-columns: 1fr; } }

    .form-section { margin-bottom: 1.1rem; }
    .form-section > label {
        display: block; font-size: .83rem; font-weight: 700;
        color: var(--muted); text-transform: uppercase;
        letter-spacing: .04em; margin-bottom: .4rem;
    }

    .form-control-styled {
        width: 100%; padding: .65rem .9rem;
        border: 2px solid var(--border); border-radius: 10px;
        font-size: .93rem; color: var(--text); background: var(--surface);
        transition: border-color .2s, box-shadow .2s;
        font-family: inherit; box-sizing: border-box;
    }
    .form-control-styled:focus {
        outline: none; border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .form-control-styled.is-invalid { border-color: var(--danger); }
    .invalid-msg { color: var(--danger); font-size: .8rem; margin-top: .3rem; }

    .info-box {
        display: flex; align-items: center; gap: .6rem;
        background: #eef2ff; border: 1px solid #c7d2fe;
        border-radius: 10px; padding: .75rem 1rem;
        color: var(--brand-dark); font-size: .85rem;
        margin-bottom: 1.3rem;
    }

    .section-divider { border: none; border-top: 1px solid var(--border); margin: 1.4rem 0; }

    .creator-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.2rem 1.8rem; border-top: 1px solid var(--border);
        background: var(--surface-2);
    }

    .btn-voltar {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.2rem; border-radius: 10px;
        border: 2px solid var(--border); background: #fff;
        color: var(--muted); font-weight: 700; font-size: .9rem;
        text-decoration: none; transition: all .2s;
    }
    .btn-voltar:hover { border-color: var(--muted); color: var(--text); }

    .btn-salvar {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .65rem 1.6rem; border-radius: 10px; border: none;
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color: #fff; font-weight: 700; font-size: .95rem;
        cursor: pointer; transition: all .2s;
        box-shadow: 0 4px 14px rgba(99,102,241,.35);
    }
    .btn-salvar:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.45); }
    .btn-salvar:active { transform: translateY(0); }

    body {
    height: 100vh;
    overflow: hidden;
}

.user-creator-wrap {
    height: calc(100vh - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.creator-card {
    width: 100%;
    max-width: 760px;
    height: 90vh; /* 👈 controla tudo */
    display: flex;
    flex-direction: column;
}

.creator-body {
    flex: 1;
    overflow: hidden; /* 👈 remove scroll interno */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.creator-body::-webkit-scrollbar {
    display: none; /* Chrome, Edge */
}
.creator-header {
    flex-shrink: 0;
}

.creator-footer {
    flex-shrink: 0;
}
.user-creator-wrap {
    padding-bottom: 40px;
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
            @endif

            <div class="info-box">
                ℹ️ O nível de acesso é definido automaticamente pelo e-mail cadastrado.
            </div>

            <form method="POST" action="{{ route('users.store') }}" id="userForm">
                @csrf

                {{-- Nome e Sobrenome --}}
                <div class="fields-row">
                    <div class="form-section">
                        <label>Nome</label>
                        <input type="text" name="nome"
                               class="form-control-styled @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" placeholder="João">
                        @error('nome') <div class="invalid-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-section">
                        <label>Sobrenome</label>
                        <input type="text" name="sobrenome"
                               class="form-control-styled @error('sobrenome') is-invalid @enderror"
                               value="{{ old('sobrenome') }}" placeholder="Silva">
                        @error('sobrenome') <div class="invalid-msg">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Email e RA --}}
                <div class="fields-row">
                    <div class="form-section">
                        <label>Email</label>
                        <input type="email" name="email"
                               class="form-control-styled @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="usuario@gmail.com">
                        @error('email') <div class="invalid-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-section">
                        <label>RA</label>
                        <input type="text" name="ra"
                               class="form-control-styled @error('ra') is-invalid @enderror"
                               value="{{ old('ra') }}" placeholder="Ex: 12345">
                        @error('ra') <div class="invalid-msg">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Telefone e Data de Nascimento --}}
                <div class="fields-row">
                    <div class="form-section">
                        <label>Telefone</label>
                        <input type="text" name="telefone"
                               class="form-control-styled @error('telefone') is-invalid @enderror"
                               value="{{ old('telefone') }}"
                               maxlength="11"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               placeholder="18912345678">
                        @error('telefone') <div class="invalid-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-section">
                        <label>Data de Nascimento</label>
                        <input type="date" name="data_nascimento"
                               class="form-control-styled @error('data_nascimento') is-invalid @enderror"
                               value="{{ old('data_nascimento') }}">
                        @error('data_nascimento') <div class="invalid-msg">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="section-divider">

                {{-- Senha e Confirmação --}}
                <div class="fields-row">
                    <div class="form-section">
                        <label>Senha</label>
                        <input type="password" name="password"
                               class="form-control-styled @error('password') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres">
                        @error('password') <div class="invalid-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-section">
                        <label>Confirmar Senha</label>
                        <input type="password" name="password_confirmation"
                               class="form-control-styled" placeholder="Repita a senha">
                    </div>
                </div>

            </form>
        </div>

        <div class="creator-footer">
            <a href="{{ route('users.index') }}" class="btn-voltar">← Cancelar</a>
            <button type="submit" form="userForm" class="btn-salvar">✓ Salvar Usuário</button>
        </div>

    </div>
</div>
@endsection

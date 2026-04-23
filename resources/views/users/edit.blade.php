@extends('layouts.site')

@push('styles')
<style>
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

    body { background: #f1f5f9 !important; }

    .edit-wrap {
        max-width: 620px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
    }

    .edit-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .edit-header {
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        padding: 1.5rem;
        color: #fff;
        font-size: 1.2rem;
        font-weight: 800;
    }

    .edit-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .form-group label {
        display: block;
        font-size: .8rem;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: .35rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: .55rem .9rem;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: .95rem;
        color: var(--text);
        background: var(--surface);
        transition: border .2s;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--brand);
    }

    .form-error {
        color: var(--danger);
        font-size: .8rem;
        margin-top: .25rem;
    }

    /* ── Seletor de tipo visual ── */
    .tipo-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .6rem;
    }

    .tipo-opt { display: none; }

    .tipo-opt + label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .3rem;
        padding: .75rem .5rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        cursor: pointer;
        font-size: .82rem;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .04em;
        transition: all .2s;
        background: var(--surface-2);
    }

    .tipo-opt + label:hover { border-color: #818cf8; color: var(--brand); }

    .tipo-opt + label .tipo-icon { font-size: 1.4rem; }

    #tipo_leitor:checked       + label { border-color: #22c55e; background: #f0fdf4; color: #15803d; }
    #tipo_editor:checked       + label { border-color: #f59e0b; background: #fffbeb; color: #b45309; }
    #tipo_administrador:checked + label{ border-color: #ef4444; background: #fef2f2; color: #b91c1c; }

    .edit-footer {
        display: flex;
        gap: .6rem;
        padding: 1.1rem 1.5rem;
        border-top: 1px solid var(--border);
        background: var(--surface-2);
    }

    .btn-voltar {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .55rem 1.2rem; border-radius: 50px;
        border: 1.5px solid var(--border);
        background: var(--surface); color: var(--muted);
        font-weight: 700; font-size: .9rem;
        text-decoration: none; transition: all .2s;
    }
    .btn-voltar:hover { border-color: var(--muted); color: var(--text); }

    .btn-salvar {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .55rem 1.4rem; border-radius: 50px;
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color: #fff; font-weight: 700; font-size: .9rem;
        border: none; cursor: pointer;
        box-shadow: 0 4px 14px rgba(79,70,229,.3);
        transition: all .2s;
    }
    .btn-salvar:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); }
</style>
@endpush

@section('conteudo')
<div class="edit-wrap">
    <div class="edit-card">

        <div class="edit-header">✏️ Editar Usuário</div>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="edit-body">

                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" value="{{ old('nome', $user->nome) }}">
                    @error('nome') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Sobrenome</label>
                    <input type="text" name="sobrenome" value="{{ old('sobrenome', $user->sobrenome) }}">
                    @error('sobrenome') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}">
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>RA</label>
                    <input type="text" name="ra" value="{{ old('ra', $user->ra) }}">
                    @error('ra') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" name="telefone" value="{{ old('telefone', $user->telefone) }}">
                    @error('telefone') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Data de Nascimento</label>
                    <input type="date" name="data_nascimento"
                           value="{{ old('data_nascimento', $user->data_nascimento) }}">
                    @error('data_nascimento') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- ── Nível de acesso ── --}}
                <div class="form-group">
                    <label>Nível de Acesso</label>
                    <div class="tipo-grid">
                        <div>
                            <input type="radio" name="tipo" value="leitor"
                                   id="tipo_leitor" class="tipo-opt"
                                   {{ old('tipo', $user->tipo) === 'leitor' ? 'checked' : '' }}>
                            <label for="tipo_leitor">
                                <span class="tipo-icon">👤</span>
                                Leitor
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="tipo" value="editor"
                                   id="tipo_editor" class="tipo-opt"
                                   {{ old('tipo', $user->tipo) === 'editor' ? 'checked' : '' }}>
                            <label for="tipo_editor">
                                <span class="tipo-icon">✍️</span>
                                Editor
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="tipo" value="administrador"
                                   id="tipo_administrador" class="tipo-opt"
                                   {{ old('tipo', $user->tipo) === 'administrador' ? 'checked' : '' }}>
                            <label for="tipo_administrador">
                                <span class="tipo-icon">🛡️</span>
                                Admin
                            </label>
                        </div>
                    </div>
                    @error('tipo') <div class="form-error">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="edit-footer">
                <a href="{{ route('users.index') }}" class="btn-voltar">← Voltar</a>
                <button type="submit" class="btn-salvar">✓ Salvar</button>
            </div>
        </form>

    </div>
</div>
@endsection

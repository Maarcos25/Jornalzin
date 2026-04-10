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

    .user-detail-wrap {
        max-width: 620px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
    }

    .user-detail-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .user-detail-top {
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        padding: 2rem 1.5rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
    }
    .user-detail-avatar {
        width: 72px; height: 72px; border-radius: 50%;
        background: rgba(255,255,255,.2);
        color: #fff; font-size: 1.8rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        border: 3px solid rgba(255,255,255,.35);
    }
    .user-detail-avatar img {
        width: 100%; height: 100%;
        border-radius: 50%; object-fit: cover;
    }
    .user-detail-name {
        color: #fff; font-size: 1.3rem;
        font-weight: 800; margin: 0 0 .2rem;
    }
    .user-detail-email {
        color: rgba(255,255,255,.75);
        font-size: .88rem; margin: 0;
    }
    .user-detail-badge { margin-left: auto; align-self: flex-start; }
    .badge-tipo {
        display: inline-block; padding: .3rem .95rem;
        border-radius: 50px; font-size: .78rem; font-weight: 700;
        text-transform: capitalize;
        background: rgba(255,255,255,.2); color: #fff;
        border: 1.5px solid rgba(255,255,255,.35);
    }

    .user-detail-body { padding: 1.4rem 1.5rem; }
    .detail-row {
        display: flex; align-items: center; gap: .8rem;
        padding: .75rem 0; border-bottom: 1px solid var(--border);
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: #eef2ff; color: var(--brand);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .detail-label {
        font-size: .78rem; font-weight: 700;
        color: var(--muted); text-transform: uppercase;
        letter-spacing: .04em; margin-bottom: .1rem;
    }
    .detail-value { font-size: .95rem; font-weight: 600; color: var(--text); }

    .user-detail-footer {
        display: flex; gap: .6rem; flex-wrap: wrap;
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
    .btn-editar {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .55rem 1.2rem; border-radius: 50px;
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color: #fff; font-weight: 700; font-size: .9rem;
        text-decoration: none; border: none;
        box-shadow: 0 4px 14px rgba(79,70,229,.3); transition: all .2s;
    }
    .btn-editar:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); color: #fff; }
    .btn-excluir {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .55rem 1.2rem; border-radius: 50px;
        border: 1.5px solid #fecaca;
        background: #fef2f2; color: var(--danger);
        font-weight: 700; font-size: .9rem;
        cursor: pointer; transition: all .2s; margin-left: auto;
    }
    .btn-excluir:hover { background: var(--danger); color: #fff; border-color: var(--danger); }
</style>
@endpush

@section('conteudo')
<div class="user-detail-wrap">
    <div class="user-detail-card">

        <div class="user-detail-top">
            <div class="user-detail-avatar">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->nome }}">
                @else
                    {{ strtoupper(substr($user->nome ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div>
                <p class="user-detail-name">{{ $user->nome }} {{ $user->sobrenome }}</p>
                <p class="user-detail-email">{{ $user->email }}</p>
            </div>
            <div class="user-detail-badge">
                <span class="badge-tipo">{{ ucfirst($user->tipo ?? 'leitor') }}</span>
            </div>
        </div>

        <div class="user-detail-body">

            @if(auth()->user()->tipo === 'administrador')
            <div class="detail-row">
                <div class="detail-icon">🎓</div>
                <div>
                    <div class="detail-label">RA</div>
                    <div class="detail-value">{{ $user->ra ?? '—' }}</div>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-icon">📞</div>
                <div>
                    <div class="detail-label">Telefone</div>
                    <div class="detail-value">{{ $user->telefone ?? '—' }}</div>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-icon">🎂</div>
                <div>
                    <div class="detail-label">Data de Nascimento</div>
                    <div class="detail-value">
                        {{ $user->data_nascimento ? \Carbon\Carbon::parse($user->data_nascimento)->format('d/m/Y') : '—' }}
                    </div>
                </div>
            </div>
            @endif

            <div class="detail-row">
                <div class="detail-icon">🏷️</div>
                <div>
                    <div class="detail-label">Tipo de Conta</div>
                    <div class="detail-value">{{ ucfirst($user->tipo ?? 'leitor') }}</div>
                </div>
            </div>

        </div>

        <div class="user-detail-footer">
            <a href="{{ route('users.index') }}" class="btn-voltar">← Voltar</a>

            @if(auth()->user()->tipo === 'administrador')
                <a href="{{ route('users.edit', $user->id) }}" class="btn-editar">✏️ Editar</a>

                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                      onsubmit="return confirm('Excluir este usuário?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-excluir">🗑️ Excluir</button>
                </form>
            @endif
        </div>

    </div>
</div>
@endsection

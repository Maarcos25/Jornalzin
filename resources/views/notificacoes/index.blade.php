@extends('layouts.site')

@push('styles')
<style>
    .notif-wrap { max-width: 720px; margin: 0 auto; padding: 2rem 1rem; }
    .notif-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .notif-header h2 { font-weight: 800; font-size: 1.4rem; color: var(--text); margin: 0; }

    .notif-tabs { display: flex; gap: .45rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .notif-tab {
        padding: .4rem 1rem; border-radius: 50px;
        border: 1.5px solid var(--border); background: var(--surface-2);
        color: var(--muted); font-size: .83rem; font-weight: 600;
        text-decoration: none; transition: all .15s;
    }
    .notif-tab:hover,
    .notif-tab.active { border-color: var(--brand); color: var(--brand); background: #eef2ff; }

    .notif-card {
        display: flex; align-items: center; gap: 1rem;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; padding: .95rem 1.2rem;
        margin-bottom: .6rem; text-decoration: none; color: inherit;
        transition: box-shadow .2s, transform .15s;
        position: relative;
    }
    .notif-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); transform: translateY(-1px); }
    .notif-card.nao-lida { border-left: 3.5px solid var(--brand); }

    .notif-icone {
        font-size: 1.5rem; flex-shrink: 0;
        width: 44px; height: 44px;
        border-radius: 50%; background: var(--surface-2);
        border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
    }
    .notif-avatar {
        width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #4f46e5, #3730a3);
        color: #fff; font-size: 1rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .notif-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .notif-info { flex: 1; min-width: 0; }
    .notif-msg  { font-size: .93rem; color: var(--text); font-weight: 500; line-height: 1.4; }
    .notif-time { font-size: .76rem; color: var(--muted); margin-top: .2rem; }

    .notif-dot {
        width: 9px; height: 9px; border-radius: 50%;
        background: var(--brand); flex-shrink: 0;
    }

    .notif-empty { text-align: center; padding: 4rem 1rem; color: var(--muted); }
    .notif-empty .icon { font-size: 3rem; margin-bottom: 1rem; }

    .btn-marcar-lidas {
        padding: .4rem 1rem; border-radius: 8px;
        border: 1.5px solid var(--border); background: var(--surface-2);
        color: var(--muted); font-size: .82rem; font-weight: 600;
        cursor: pointer; transition: all .15s; text-decoration: none;
    }
    .btn-marcar-lidas:hover { border-color: var(--brand); color: var(--brand); }

    html.dark .notif-tab.active,
    html.dark .notif-tab:hover { background: #1e1f3a !important; }
    html.dark .notif-card.nao-lida { border-left-color: var(--brand-light) !important; }
</style>
@endpush

@section('conteudo')
<div class="notif-wrap">

    <div class="notif-header">
        <h2>🔔 Notificações</h2>
        <form method="POST" action="{{ route('notificacoes.lidas') }}">
            @csrf
            <button type="submit" class="btn-marcar-lidas">✓ Marcar todas como lidas</button>
        </form>
    </div>

    {{-- Tabs --}}
    <div class="notif-tabs">
        @php
            $tabs = [
                'todas'      => '🔔 Todas',
                'like'       => '❤️ Curtidas',
                'comentario' => '💬 Comentários',
                'seguidor'   => '👤 Seguidores',
                'mensagem'   => '✉️ Mensagens',
                'post'       => '📰 Posts',
            ];
        @endphp
        @foreach($tabs as $key => $label)
            <a href="{{ route('notificacoes.index', ['tipo' => $key === 'todas' ? null : $key]) }}"
               class="notif-tab {{ ($tipo === $key || ($key === 'todas' && !$tipo)) ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Lista --}}
    @forelse($notificacoes as $notif)
        <a href="{{ $notif->link ?? '#' }}" class="notif-card {{ !$notif->lida ? 'nao-lida' : '' }}">

            {{-- Avatar do ator --}}
            <div class="notif-avatar">
                @if($notif->ator && $notif->ator->avatar)
                    <img src="{{ asset('storage/' . $notif->ator->avatar) }}" alt="">
                @else
                    {{ strtoupper(substr($notif->ator->nome ?? 'U', 0, 1)) }}
                @endif
            </div>

            {{-- Ícone do tipo --}}
            <div class="notif-icone">{{ $notif->icone }}</div>

            {{-- Texto --}}
            <div class="notif-info">
                <div class="notif-msg">{{ $notif->mensagem }}</div>
                <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
            </div>

            {{-- Ponto azul se não lida --}}
            @if(!$notif->lida)
                <div class="notif-dot"></div>
            @endif

        </a>
    @empty
        <div class="notif-empty">
            <div class="icon">🔕</div>
            <p>Nenhuma notificação {{ $tipo && $tipo !== 'todas' ? 'deste tipo ' : '' }}ainda.</p>
        </div>
    @endforelse

    <div class="mt-3">{{ $notificacoes->links() }}</div>

</div>
@endsection

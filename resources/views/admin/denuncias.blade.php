@extends('layouts.site')

@push('styles')
<style>
    .den-wrap { max-width: 900px; margin: 0 auto; padding: 2rem 1rem; }
    .den-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .den-header h2 { font-weight: 800; font-size: 1.4rem; color: var(--text); margin: 0; }
    .den-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; padding: 1.2rem 1.4rem;
        margin-bottom: .8rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
    }
    .den-card.lida { opacity: .55; }
    .den-card-top { display: flex; gap: 1rem; align-items: flex-start; }
    .den-badge {
        flex-shrink: 0; padding: .25rem .7rem;
        border-radius: 20px; font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .04em;
    }
    .den-badge.post       { background: #fce7f3; color: #9d174d; }
    .den-badge.comentario { background: #dbeafe; color: #1d4ed8; }
    .den-motivo { font-weight: 700; font-size: .97rem; color: var(--text); }
    .den-desc   { font-size: .88rem; color: var(--muted); margin-top: .2rem; }
    .den-meta   { font-size: .78rem; color: var(--muted); margin-top: .35rem; }
    .den-actions { margin-left: auto; display: flex; gap: .5rem; flex-shrink: 0; align-items: center; }

    /* Botões topo */
    .btn-den-lida {
        padding: .35rem .85rem; border-radius: 8px;
        border: 1.5px solid var(--border); background: var(--surface-2);
        color: var(--muted); font-size: .82rem; font-weight: 600;
        cursor: pointer; transition: all .15s;
    }
    .btn-den-lida:hover { border-color: #22c55e; color: #22c55e; }
    .btn-den-del {
        padding: .35rem .7rem; border-radius: 8px;
        border: 1.5px solid #fecaca; background: #fef2f2;
        color: #ef4444; font-size: .82rem; font-weight: 600;
        cursor: pointer; transition: all .15s;
    }
    .btn-den-del:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    /* Barra de ações do conteúdo */
    .den-content-actions {
        margin-top: .9rem;
        padding-top: .9rem;
        border-top: 1px dashed var(--border);
        display: flex; gap: .6rem; align-items: center; flex-wrap: wrap;
    }
    .den-content-actions span {
        font-size: .8rem; color: var(--muted); font-weight: 600;
    }
    .btn-ver-conteudo {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .38rem .9rem; border-radius: 8px;
        border: 1.5px solid var(--border); background: var(--surface-2);
        color: var(--text); font-size: .83rem; font-weight: 600;
        text-decoration: none; transition: all .15s;
    }
    .btn-ver-conteudo:hover { border-color: var(--brand); color: var(--brand); }
    .btn-excluir-conteudo {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .38rem .9rem; border-radius: 8px;
        border: 1.5px solid #fecaca; background: #fef2f2;
        color: #ef4444; font-size: .83rem; font-weight: 600;
        cursor: pointer; transition: all .15s;
    }
    .btn-excluir-conteudo:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    .den-nova-dot {
        width: 9px; height: 9px; border-radius: 50%;
        background: #ef4444; flex-shrink: 0; margin-top: .4rem;
    }
    .empty-den { text-align: center; padding: 4rem 1rem; color: var(--muted); }
    .empty-den .icon { font-size: 3rem; margin-bottom: 1rem; }
    .alert-success-den {
        background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;
        border-radius: 10px; padding: .75rem 1rem; margin-bottom: 1rem;
        font-size: .92rem; font-weight: 600;
    }
    .den-tabs { display: flex; gap: .5rem; margin-bottom: 1.5rem; }
    .den-tab {
        padding: .45rem 1.1rem; border-radius: 50px;
        border: 1.5px solid var(--border); background: var(--surface-2);
        color: var(--muted); font-size: .85rem; font-weight: 600;
        text-decoration: none; transition: all .15s;
    }
    .den-tab.active,
    .den-tab:hover { border-color: var(--brand); color: var(--brand); background: #eef2ff; }

    /* Dark */
    html.dark .den-badge.post       { background: #4a1942 !important; color: #f9a8d4 !important; }
    html.dark .den-badge.comentario { background: #1e3a5f !important; color: #93c5fd !important; }
    html.dark .btn-ver-conteudo { background: var(--surface-2) !important; border-color: var(--border) !important; color: var(--text) !important; }
    html.dark .btn-excluir-conteudo { background: #2d1b1b !important; border-color: #7f1d1d !important; }
    html.dark .btn-den-del { background: #2d1b1b !important; border-color: #7f1d1d !important; }
</style>
@endpush

@section('conteudo')
<div class="den-wrap">

    <div class="den-header">
        <h2>🚩 Denúncias</h2>
        <span style="color:var(--muted);font-size:.9rem;">{{ $denuncias->total() }} no total</span>
    </div>

    @if(session('success'))
        <div class="alert-success-den">✅ {{ session('success') }}</div>
    @endif

    <div class="den-tabs">
        <a href="{{ route('admin.denuncias') }}"
           class="den-tab {{ !request('filtro') ? 'active' : '' }}">Todas</a>
        <a href="{{ route('admin.denuncias', ['filtro' => 'novas']) }}"
           class="den-tab {{ request('filtro') === 'novas' ? 'active' : '' }}">🔴 Não lidas</a>
        <a href="{{ route('admin.denuncias', ['filtro' => 'lidas']) }}"
           class="den-tab {{ request('filtro') === 'lidas' ? 'active' : '' }}">✅ Lidas</a>
    </div>

    @forelse($denuncias as $den)
        <div class="den-card {{ $den->lida ? 'lida' : '' }}">

            {{-- Linha topo --}}
            <div class="den-card-top">
                @if(!$den->lida)
                    <div class="den-nova-dot" title="Não lida"></div>
                @else
                    <div style="width:9px;flex-shrink:0;"></div>
                @endif

                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;margin-bottom:.3rem;">
                        <span class="den-badge {{ $den->tipo }}">{{ $den->tipo }}</span>
                        <span class="den-motivo">{{ $den->motivo }}</span>
                    </div>

                    @if($den->descricao)
                        <p class="den-desc">"{{ $den->descricao }}"</p>
                    @endif

                    <div class="den-meta">
                        <span>👤 {{ $den->usuario->nome ?? 'Usuário #'.$den->user_id }}</span>
                        <span style="margin:0 .4rem;">·</span>
                        <span>🕐 {{ $den->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                {{-- Botões: marcar lida + remover denúncia --}}
                <div class="den-actions">
                    @if(!$den->lida)
                        <form method="POST" action="{{ route('admin.denuncias.lida', $den->id) }}">
                            @csrf
                            <button type="submit" class="btn-den-lida" title="Marcar como lida">✓ Lida</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.denuncias.destroy', $den->id) }}"
                          onsubmit="return confirm('Remover apenas esta denúncia?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-den-del" title="Remover denúncia">🗑</button>
                    </form>
                </div>
            </div>

            {{-- Linha de ações sobre o CONTEÚDO denunciado --}}
            <div class="den-content-actions">
                <span>Conteúdo denunciado:</span>

                @if($den->tipo === 'post')
                    <a href="{{ route('posts.show', $den->referencia_id) }}" target="_blank"
                       class="btn-ver-conteudo">
                        👁 Ver post #{{ $den->referencia_id }}
                    </a>
                    <form method="POST" action="{{ route('admin.denuncias.excluir-post', $den->id) }}"
                          onsubmit="return confirm('Excluir o post #{{ $den->referencia_id }} permanentemente? Esta ação não pode ser desfeita.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-excluir-conteudo">
                            🗑 Excluir post
                        </button>
                    </form>

                @else
                    <span style="font-size:.83rem;color:var(--text);">Comentário #{{ $den->referencia_id }}</span>
                    <form method="POST" action="{{ route('admin.denuncias.excluir-comentario', $den->id) }}"
                          onsubmit="return confirm('Excluir o comentário #{{ $den->referencia_id }} permanentemente?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-excluir-conteudo">
                            🗑 Excluir comentário
                        </button>
                    </form>
                @endif
            </div>

        </div>
    @empty
        <div class="empty-den">
            <div class="icon">🏳️</div>
            <p>Nenhuma denúncia encontrada.</p>
        </div>
    @endforelse

    <div class="mt-3">{{ $denuncias->links() }}</div>

</div>
@endsection
@extends('layouts.site')

@push('styles')
<style>
.sol-wrap { max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }

.sol-wrap h1 {
    font-size: 1.7rem; font-weight: 800;
    color: var(--text); margin-bottom: 1.5rem;
}
.sol-wrap h2 {
    font-size: 1.3rem; font-weight: 800;
    color: var(--text); margin-top: 2.5rem; margin-bottom: 1rem;
}

.alert-ok {
    background: #f0fdf4; border: 1px solid #bbf7d0;
    color: #166534; border-radius: 10px;
    padding: .8rem 1.1rem; font-size: .92rem; margin-bottom: 1.4rem;
}
html.dark .alert-ok { background: #052e16; border-color: #166534; color: #86efac; }

/* ── Card de solicitação ── */
.sol-card {
    background: var(--surface);
    border-radius: 14px;
    border: 1px solid var(--border);
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
}
.sol-user-row {
    display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;
}
.sol-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5, #3730a3);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.1rem; flex-shrink: 0;
}
.sol-name { font-weight: 700; color: var(--text); }
.sol-meta { font-size: .82rem; color: var(--muted); }
.badge-pendente-sol {
    margin-left: auto;
    background: #fef3c7; color: #92400e;
    padding: .25rem .8rem; border-radius: 50px;
    font-size: .75rem; font-weight: 700;
}
html.dark .badge-pendente-sol { background: #451a03; color: #fcd34d; }

.sol-field-label {
    font-size: .75rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .04em; margin-bottom: .3rem;
}
.sol-field-value { color: var(--text); font-size: .93rem; word-wrap: break-word; margin-bottom: .75rem; }

.sol-actions { display: flex; gap: .6rem; margin-top: .5rem; }
.btn-aprovar-sol {
    background: #10b981; color: #fff; border: none;
    border-radius: 50px; padding: .5rem 1.2rem;
    font-weight: 700; cursor: pointer; transition: background .2s;
}
.btn-aprovar-sol:hover { background: #059669; }
.btn-rejeitar-sol {
    background: #fef2f2; color: #ef4444;
    border: 1.5px solid #fecaca; border-radius: 50px;
    padding: .5rem 1.2rem; font-weight: 700;
    cursor: pointer; transition: all .2s;
}
.btn-rejeitar-sol:hover { background: #ef4444; color: #fff; border-color: #ef4444; }
html.dark .btn-rejeitar-sol { background: #2d0a0a; color: #fca5a5; border-color: #7f1d1d; }
html.dark .btn-rejeitar-sol:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

/* ── Card de editor ── */
.editor-card {
    background: var(--surface);
    border-radius: 14px;
    border: 1px solid var(--border);
    padding: 1.2rem 1.5rem;
    margin-bottom: .75rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    display: flex; align-items: center; gap: 1rem;
}
.editor-link {
    display: flex; align-items: center; gap: 1rem;
    text-decoration: none; flex: 1;
}
.editor-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.1rem; flex-shrink: 0; overflow: hidden;
}
.editor-avatar img { width: 100%; height: 100%; object-fit: cover; }
.editor-name { font-weight: 700; color: var(--text); }
.editor-email { font-size: .82rem; color: var(--muted); }
.badge-editor-sol {
    background: #d1fae5; color: #065f46;
    padding: .25rem .8rem; border-radius: 50px;
    font-size: .75rem; font-weight: 700; white-space: nowrap;
}
html.dark .badge-editor-sol { background: #052e16; color: #86efac; }

.btn-remover-editor {
    background: #fef2f2; color: #ef4444;
    border: 1.5px solid #fecaca; border-radius: 50px;
    padding: .45rem 1.1rem; font-weight: 700;
    cursor: pointer; font-size: .88rem;
    white-space: nowrap; transition: all .2s;
}
.btn-remover-editor:hover { background: #ef4444; color: #fff; border-color: #ef4444; }
html.dark .btn-remover-editor { background: #2d0a0a; color: #fca5a5; border-color: #7f1d1d; }
html.dark .btn-remover-editor:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

/* ── Vazio ── */
.empty-sol {
    text-align: center; padding: 3rem; color: var(--muted);
    background: var(--surface); border-radius: 14px;
    border: 1px solid var(--border);
}
.empty-sol .icon { font-size: 2.5rem; margin-bottom: .5rem; }
</style>
@endpush

@section('conteudo')
<div class="sol-wrap">

    <h1>📋 Solicitações de Editor</h1>

    @if(session('success'))
        <div class="alert-ok">✅ {{ session('success') }}</div>
    @endif

    {{-- SOLICITAÇÕES PENDENTES --}}
    @if($solicitacoes->isEmpty())
        <div class="empty-sol">
            <div class="icon">📭</div>
            <p>Nenhuma solicitação pendente.</p>
        </div>
    @else
        @foreach($solicitacoes as $s)
        <div class="sol-card">
            <div class="sol-user-row">
                <div class="sol-avatar">
                    {{ strtoupper(substr($s->user->nome ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="sol-name">{{ $s->user->nome }} {{ $s->user->sobrenome }}</div>
                    <div class="sol-meta">{{ $s->user->email }} · {{ $s->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <span class="badge-pendente-sol">⏳ Pendente</span>
            </div>

            <div class="sol-field-label">Motivo</div>
            <p class="sol-field-value">{{ $s->motivo }}</p>

            @if($s->experiencia)
                <div class="sol-field-label">Experiência</div>
                <p class="sol-field-value">{{ $s->experiencia }}</p>
            @endif

            <div class="sol-actions">
                <form method="POST" action="{{ route('editor.aprovar', $s->id) }}">
                    @csrf
                    <button type="submit" class="btn-aprovar-sol">✅ Aprovar</button>
                </form>
                <form method="POST" action="{{ route('editor.rejeitar', $s->id) }}"
                      onsubmit="return confirm('Rejeitar esta solicitação?')">
                    @csrf
                    <button type="submit" class="btn-rejeitar-sol">❌ Rejeitar</button>
                </form>
            </div>
        </div>
        @endforeach
    @endif

    {{-- EDITORES APROVADOS --}}
    <h2>✏️ Editores Ativos</h2>

    @if($editores->isEmpty())
        <div class="empty-sol">
            <div class="icon">👤</div>
            <p>Nenhum editor cadastrado.</p>
        </div>
    @else
        @foreach($editores as $editor)
        <div class="editor-card">
            <a href="{{ route('users.show', $editor->id) }}" class="editor-link">
                <div class="editor-avatar">
                    @if($editor->avatar)
                        <img src="{{ asset('storage/' . $editor->avatar) }}" alt="">
                    @else
                        {{ strtoupper(substr($editor->nome ?? 'U', 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="editor-name">{{ $editor->nome }} {{ $editor->sobrenome }}</div>
                    <div class="editor-email">{{ $editor->email }}</div>
                </div>
            </a>

            <span class="badge-editor-sol">✏️ Editor</span>

            <form method="POST" action="{{ route('editor.remover', $editor->id) }}"
                  onsubmit="return confirm('Remover {{ $editor->nome }} dos editores?')">
                @csrf
                <button type="submit" class="btn-remover-editor">🗑️ Remover Editor</button>
            </form>
        </div>
        @endforeach
    @endif

</div>
@endsection

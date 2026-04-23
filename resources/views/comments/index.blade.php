@extends('layouts.site')

@push('styles')
<style>
.wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.8rem; }
.page-header h1 { font-size: 1.9rem; font-weight: 800; color: var(--text); margin: 0; }

.alert-ok {
    background: #f0fdf4; border: 1px solid #bbf7d0;
    color: #166534; border-radius: 10px;
    padding: .8rem 1.1rem; font-size: .92rem; margin-bottom: 1.4rem;
}
html.dark .alert-ok { background: #052e16; border-color: #166534; color: #86efac; }

.comments-card {
    background: var(--surface); border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    border: 1px solid var(--border); overflow: hidden;
}
.comments-table { width: 100%; border-collapse: collapse; }
.comments-table thead { background: var(--surface-2); border-bottom: 2px solid var(--border); }
.comments-table thead th {
    padding: .85rem 1.2rem; font-size: .78rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em; text-align: left;
}
.comments-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
.comments-table tbody tr:last-child { border-bottom: none; }
.comments-table tbody tr:hover { background: var(--surface-2); }
.comments-table tbody td { padding: .9rem 1.2rem; font-size: .93rem; color: var(--text); vertical-align: middle; }

.badge { display: inline-block; padding: .28rem .85rem; border-radius: 50px; font-size: .78rem; font-weight: 700; }
.badge-aprovado { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.badge-pendente { background: #fefce8; color: #92400e; border: 1px solid #fde68a; }
.badge-oculto   { background: var(--surface-2); color: var(--muted); border: 1px solid var(--border); }
html.dark .badge-aprovado { background: #052e16; color: #86efac; border-color: #166534; }
html.dark .badge-pendente { background: #451a03; color: #fcd34d; border-color: #92400e; }

.actions { display: flex; align-items: center; gap: .4rem; flex-wrap: wrap; }
.btn-act {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .32rem .8rem; border-radius: 50px;
    font-size: .8rem; font-weight: 600; border: none;
    cursor: pointer; text-decoration: none; transition: all .15s;
}
.btn-aprovar  { background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; }
.btn-aprovar:hover  { background: #22c55e; color: #fff; border-color: #22c55e; }
.btn-ocultar  { background: #fefce8; color: #92400e; border: 1.5px solid #fde68a; }
.btn-ocultar:hover  { background: #f59e0b; color: #fff; border-color: #f59e0b; }
.btn-excluir  { background: #fef2f2; color: #ef4444; border: 1.5px solid #fecaca; }
.btn-excluir:hover  { background: #ef4444; color: #fff; border-color: #ef4444; }
html.dark .btn-aprovar { background: #052e16; color: #86efac; border-color: #166534; }
html.dark .btn-aprovar:hover { background: #22c55e; color: #fff; border-color: #22c55e; }
html.dark .btn-ocultar { background: #2d1f02; color: #fcd34d; border-color: #78350f; }
html.dark .btn-ocultar:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
html.dark .btn-excluir { background: #2d0a0a; color: #fca5a5; border-color: #7f1d1d; }
html.dark .btn-excluir:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

.empty-state { text-align: center; padding: 3.5rem 1rem; color: var(--muted); }
.empty-state div { font-size: 2.8rem; margin-bottom: .8rem; }
</style>
@endpush

@section('conteudo')
<div class="wrap">
    <div class="page-header">
        <h1>💬 Comentários</h1>
    </div>

    @if(session('success'))
        <div class="alert-ok">✅ {{ session('success') }}</div>
    @endif

    <div class="comments-card">
        <table class="comments-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Comentário</th>
                    <th>Usuário</th>
                    <th>Post</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                <tr>
                    <td style="color:var(--muted);font-size:.85rem;">{{ $comment->id }}</td>
                    <td>{{ Str::limit($comment->texto, 60) }}</td>
                    <td>{{ $comment->user->nome ?? '—' }}</td>
                    <td>{{ $comment->post->titulo ?? '—' }}</td>
                    <td>
                        @php
                            $status = $comment->status ?? 'pendente';
                            $badgeClass = match($status) {
                                'aprovado' => 'badge-aprovado',
                                'oculto'   => 'badge-oculto',
                                default    => 'badge-pendente',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </td>
                    <td style="font-size:.85rem;color:var(--muted);">
                        {{ $comment->created_at?->format('d/m/Y H:i') ?? '—' }}
                    </td>
                    <td>
                        <div class="actions">
                            <form action="{{ route('comments.aprovar', $comment->id) }}" method="POST">
                                @csrf
                                <button class="btn-act btn-aprovar">✅ Aprovar</button>
                            </form>
                            <form action="{{ route('comments.ocultar', $comment->id) }}" method="POST">
                                @csrf
                                <button class="btn-act btn-ocultar">🙈 Ocultar</button>
                            </form>
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                onsubmit="return confirm('Excluir este comentário?')">
                                @csrf @method('DELETE')
                                <button class="btn-act btn-excluir">🗑️ Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div>💬</div>
                                <p>Nenhum comentário encontrado.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

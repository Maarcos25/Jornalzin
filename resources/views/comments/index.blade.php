@extends('layouts.site')

@section('conteudo')
<style>
    :root {
        --brand: #4f46e5; --brand-dark: #3730a3;
        --surface: #ffffff; --surface-2: #f8fafc;
        --border: #e2e8f0; --text: #1e293b;
        --muted: #64748b; --danger: #ef4444;
        --success: #22c55e; --warn: #f59e0b;
        --radius: 14px; --shadow: 0 2px 12px rgba(0,0,0,.07);
    }
    body { background: #f1f5f9 !important; }
    .wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.8rem; }
    .page-header h1 { font-size: 1.9rem; font-weight: 800; color: var(--text); margin: 0; }
    .alert-ok { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 10px; padding: .8rem 1.1rem; font-size: .92rem; margin-bottom: 1.4rem; }
    .card { background: var(--surface); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: var(--surface-2); border-bottom: 2px solid var(--border); }
    thead th { padding: .85rem 1.2rem; font-size: .78rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; text-align: left; }
    tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f8faff; }
    tbody td { padding: .9rem 1.2rem; font-size: .93rem; color: var(--text); vertical-align: middle; }
    .badge { display: inline-block; padding: .28rem .85rem; border-radius: 50px; font-size: .78rem; font-weight: 700; }
    .badge-aprovado  { background: #f0fdf4; color: #15803d; }
    .badge-pendente  { background: #fefce8; color: #92400e; }
    .badge-oculto    { background: #f1f5f9; color: #64748b; }
    .actions { display: flex; align-items: center; gap: .4rem; flex-wrap: wrap; }
    .btn { display: inline-flex; align-items: center; gap: .3rem; padding: .32rem .8rem; border-radius: 50px; font-size: .8rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: all .15s; }
    .btn-aprovar  { background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; }
    .btn-aprovar:hover  { background: #22c55e; color: #fff; border-color: #22c55e; }
    .btn-ocultar  { background: #fefce8; color: #92400e; border: 1.5px solid #fde68a; }
    .btn-ocultar:hover  { background: #f59e0b; color: #fff; border-color: #f59e0b; }
    .btn-excluir  { background: #fef2f2; color: var(--danger); border: 1.5px solid #fecaca; }
    .btn-excluir:hover  { background: var(--danger); color: #fff; border-color: var(--danger); }
    .empty-state { text-align: center; padding: 3.5rem 1rem; color: var(--muted); }
    .empty-state div { font-size: 2.8rem; margin-bottom: .8rem; }
</style>

<div class="wrap">
    <div class="page-header">
        <h1>💬 Comentários</h1>
    </div>

    @if(session('success'))
        <div class="alert-ok">✅ {{ session('success') }}</div>
    @endif

    <div class="card">
        <table>
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
                            {{-- Aprovar --}}
                            <form action="{{ route('comments.aprovar', $comment->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-aprovar">✅ Aprovar</button>
                            </form>

                            {{-- Ocultar --}}
                            <form action="{{ route('comments.ocultar', $comment->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-ocultar">🙈 Ocultar</button>
                            </form>

                            {{-- Excluir --}}
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                  onsubmit="return confirm('Excluir este comentário?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-excluir">🗑️ Excluir</button>
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

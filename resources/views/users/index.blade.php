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
            --success:    #22c55e;
            --radius:     14px;
            --shadow:     0 2px 12px rgba(0,0,0,.07);
        }

        body { background: #f1f5f9 !important; }

        .users-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }

        .users-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.8rem; flex-wrap: wrap; gap: 1rem;
        }
        .users-header h1 {
            font-size: 1.9rem; font-weight: 800; color: var(--text);
            margin: 0; letter-spacing: -.02em;
        }
        .btn-novo {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .6rem 1.4rem; border-radius: 50px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff; font-weight: 700; font-size: .95rem;
            text-decoration: none; border: none; cursor: pointer;
            box-shadow: 0 4px 14px rgba(79,70,229,.3); transition: all .2s;
        }
        .btn-novo:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); color: #fff; }

        .alert-ok {
            background: #f0fdf4; border: 1px solid #bbf7d0;
            color: #166534; border-radius: 10px;
            padding: .8rem 1.1rem; font-size: .92rem; margin-bottom: 1.4rem;
        }

        .users-card {
            background: var(--surface); border-radius: var(--radius);
            box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden;
        }

        .users-table { width: 100%; border-collapse: collapse; }
        .users-table thead { background: var(--surface-2); border-bottom: 2px solid var(--border); }
        .users-table thead th {
            padding: .85rem 1.2rem; font-size: .78rem; font-weight: 700;
            color: var(--muted); text-transform: uppercase; letter-spacing: .05em; text-align: left;
        }
        .users-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
        .users-table tbody tr:last-child { border-bottom: none; }
        .users-table tbody tr:hover { background: #f8faff; }
        .users-table tbody td { padding: .9rem 1.2rem; font-size: .93rem; color: var(--text); vertical-align: middle; }

        .user-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff; font-weight: 800; font-size: .85rem;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-right: .6rem;
        }
        .user-name-wrap { display: flex; align-items: center; }
        .user-name { font-weight: 600; color: var(--text); }
        .user-email { font-size: .8rem; color: var(--muted); }

        .badge-tipo {
            display: inline-block; padding: .28rem .85rem;
            border-radius: 50px; font-size: .78rem; font-weight: 700; text-transform: capitalize;
        }
        .badge-administrador { background: #ede9fe; color: #6d28d9; }
        .badge-leitor        { background: #f0fdf4; color: #15803d; }
        .badge-editor        { background: #fff7ed; color: #c2410c; }
        .badge-default       { background: var(--surface-2); color: var(--muted); }

        .actions-wrap { display: flex; align-items: center; gap: .4rem; }
        .btn-ver {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .32rem .8rem; border-radius: 50px; font-size: .8rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface-2); color: var(--muted);
            text-decoration: none; transition: all .15s;
        }
        .btn-ver:hover { border-color: var(--brand); color: var(--brand); background: #eef2ff; }
        .btn-editar {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .32rem .8rem; border-radius: 50px; font-size: .8rem; font-weight: 600;
            border: 1.5px solid #fde68a; background: #fefce8; color: #92400e;
            text-decoration: none; transition: all .15s;
        }
        .btn-editar:hover { background: #fde68a; color: #78350f; }
        .btn-excluir {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .32rem .8rem; border-radius: 50px; font-size: .8rem; font-weight: 600;
            border: 1.5px solid #fecaca; background: #fef2f2; color: var(--danger);
            cursor: pointer; transition: all .15s;
        }
        .btn-excluir:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

        .pagination-wrap { padding: 1rem 1.2rem; border-top: 1px solid var(--border); }

        .empty-state { text-align: center; padding: 3.5rem 1rem; color: var(--muted); }
        .empty-state div { font-size: 2.8rem; margin-bottom: .8rem; }
    </style>
    @endpush

    @section('conteudo')
    <div class="users-wrap">

        <div class="users-header">
            <h1>👥 Usuários</h1>
            <a href="{{ route('users.create') }}" class="btn-novo">➕ Criar Novo Usuário</a>
        </div>

        @if(session('success'))
            <div class="alert-ok">✅ {{ session('success') }}</div>
        @endif

        <div class="users-card">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="color:var(--muted);font-size:.85rem;">{{ $user->id }}</td>

                        <td>
                            <div class="user-name-wrap">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->nome ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->nome }} {{ $user->sobrenome }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td>
                            @php
                                $tipo = $user->tipo ?? 'leitor';
                                $badgeClass = match($tipo) {
                                    'administrador' => 'badge-administrador',
                                    'editor'        => 'badge-editor',
                                    'leitor'        => 'badge-leitor',
                                    default         => 'badge-default',
                                };
                            @endphp
                            <span class="badge-tipo {{ $badgeClass }}">{{ ucfirst($tipo) }}</span>
                        </td>

                        <td>
                            <div class="actions-wrap">
                                <a href="{{ route('users.show', $user->id) }}" class="btn-ver">👁 Ver</a>
                                @if(auth()->user()->tipo === 'administrador')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn-editar">✏️ Editar</a>
                            @endif

                                {{-- Botão excluir: apenas administradores veem e conseguem excluir --}}
                                @if(auth()->user()->tipo === 'administrador')
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Excluir este usuário?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-excluir">🗑️ Excluir</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div>👤</div>
                                    <p>Nenhum usuário encontrado.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="pagination-wrap">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
    @endsection

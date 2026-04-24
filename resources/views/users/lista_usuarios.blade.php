@extends('layouts.site')

@section('conteudo')
<div style="max-width:600px;margin:0 auto;padding:2rem 1.5rem;">

    <div style="margin-bottom:1rem;">
        <a href="{{ route('users.perfil', $user->id) }}" style="color:var(--muted);text-decoration:none;font-size:.9rem;font-weight:600;">
            ← Voltar ao perfil de {{ $user->nome }}
        </a>
    </div>

    <h2 style="font-size:1.4rem;font-weight:800;color:var(--text);margin-bottom:1.5rem;">
        {{ $tipo === 'seguidores' ? '👥 Seguidores' : '👣 Seguindo' }} de {{ $user->nome }}
    </h2>

    @if ($lista->count())
        @foreach ($lista as $u)
            <div style="
                display:flex;align-items:center;gap:1rem;
                background:var(--surface);border:1px solid var(--border);
                border-radius:12px;padding:1rem 1.2rem;
                margin-bottom:.75rem;
                transition:box-shadow .2s, transform .2s;
            ">
                {{-- Avatar --}}
                <a href="{{ route('users.perfil', $u->id) }}" style="text-decoration:none;flex-shrink:0;">
                    @if ($u->avatar)
                        <img src="{{ asset('storage/' . $u->avatar) }}"
                             style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--brand);">
                    @else
                        <div style="
                            width:48px;height:48px;border-radius:50%;
                            background:linear-gradient(135deg,var(--brand),var(--brand-dark));
                            display:flex;align-items:center;justify-content:center;
                            font-size:1.2rem;font-weight:800;color:#fff;
                        ">{{ strtoupper(substr($u->nome, 0, 1)) }}</div>
                    @endif
                </a>

                {{-- Info --}}
                <a href="{{ route('users.perfil', $u->id) }}" style="text-decoration:none;color:inherit;flex:1;">
                    <div style="font-weight:700;font-size:1rem;color:var(--text);">
                        {{ $u->nome }} {{ $u->sobrenome }}
                    </div>
                    <div style="font-size:.8rem;color:var(--muted);margin-top:.15rem;">
                        {{ $u->posts()->where('aprovado', true)->count() }} postagens
                    </div>
                </a>

                {{-- Badge tipo --}}
                <span style="
                    padding:.2rem .7rem;border-radius:50px;font-size:.72rem;font-weight:700;
                    text-transform:uppercase;flex-shrink:0;
                    background:{{ $u->tipo === 'administrador' ? '#fef3c7' : ($u->tipo === 'editor' ? '#dcfce7' : '#e0f2fe') }};
                    color:{{ $u->tipo === 'administrador' ? '#b45309' : ($u->tipo === 'editor' ? '#15803d' : '#0369a1') }};
                ">{{ ucfirst($u->tipo) }}</span>

                {{-- Botão remover (só para o dono do perfil na aba seguidores) --}}
                @auth
                    @if ($tipo === 'seguidores' && auth()->id() === $user->id)
                        <form method="POST" action="{{ route('users.removerSeguidor', [$user->id, $u->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Remover {{ $u->nome }} dos seus seguidores?')"
                                style="
                                    padding:.35rem .85rem;border-radius:50px;
                                    border:1.5px solid #ef4444;background:transparent;
                                    color:#ef4444;font-size:.78rem;font-weight:700;
                                    cursor:pointer;transition:all .2s;white-space:nowrap;
                                "
                                onmouseover="this.style.background='#ef4444';this.style.color='#fff'"
                                onmouseout="this.style.background='transparent';this.style.color='#ef4444'">
                                ✕ Remover
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        @endforeach

        <div class="mt-4">{{ $lista->links() }}</div>
    @else
        <div style="text-align:center;padding:4rem 1rem;color:var(--muted);">
            <div style="font-size:3rem;margin-bottom:1rem;">👥</div>
            <p>{{ $tipo === 'seguidores' ? 'Nenhum seguidor ainda.' : 'Não está seguindo ninguém ainda.' }}</p>
        </div>
    @endif

</div>
@endsection

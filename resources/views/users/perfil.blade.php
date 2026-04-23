@extends('layouts.site')

@push('styles')
<style>
    .perfil-wrap { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }

    .perfil-header {
        background: var(--surface);
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .perfil-avatar {
        width: 100px; height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--brand);
        flex-shrink: 0;
    }

    .perfil-avatar-placeholder {
        width: 100px; height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; font-weight: 800; color: #fff;
        flex-shrink: 0;
    }

    .perfil-info h2 {
        font-size: 1.6rem; font-weight: 800;
        color: var(--text); margin: 0 0 .3rem;
    }

    .perfil-badge {
        display: inline-block;
        padding: .2rem .75rem;
        border-radius: 50px;
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .badge-leitor        { background: #e0f2fe; color: #0369a1; }
    .badge-editor        { background: #dcfce7; color: #15803d; }
    .badge-administrador { background: #fef3c7; color: #b45309; }

    .perfil-stats { display: flex; gap: 1.5rem; margin-top: .8rem; }

    .perfil-stat { text-align: center; }

    .perfil-stat strong {
        display: block; font-size: 1.3rem;
        font-weight: 800; color: var(--text);
    }

    .perfil-stat span { font-size: .8rem; color: var(--muted); }

    .perfil-posts-title {
        font-size: 1.2rem; font-weight: 800;
        color: var(--text); margin-bottom: 1rem;
    }

    .perfil-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    @media(max-width: 768px) { .perfil-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width: 480px) { .perfil-grid { grid-template-columns: 1fr; } }

    .perfil-card {
        background: var(--surface);
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
        overflow: hidden;
        transition: box-shadow .2s, transform .2s;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .perfil-card:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,.12);
        transform: translateY(-2px);
    }

    .perfil-card-thumb {
        width: 100%; aspect-ratio: 16/9;
        object-fit: cover; display: block;
    }

    .perfil-card-thumb-placeholder {
        width: 100%; aspect-ratio: 16/9;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        display: flex; align-items: center;
        justify-content: center; font-size: 2rem;
    }

    .perfil-card-body { padding: .85rem 1rem; }

    .perfil-card-title {
        font-size: .95rem; font-weight: 700;
        color: var(--text); margin-bottom: .4rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .perfil-card-meta {
        display: flex; gap: .8rem;
        font-size: .82rem; color: var(--muted);
    }

    .perfil-vazio {
        text-align: center; padding: 4rem 1rem; color: var(--muted);
    }

    .perfil-vazio div { font-size: 3rem; margin-bottom: 1rem; }

    .btn-seguir {
        margin-top: 1rem;
        padding: .55rem 1.4rem;
        border-radius: 50px;
        font-weight: 700; font-size: .9rem;
        cursor: pointer; transition: all .2s;
        border: 2px solid var(--brand);
        background: var(--brand);
        color: #fff;
    }

    .btn-seguir.seguindo {
        background: transparent;
        border-color: var(--border);
        color: var(--muted);
    }

    .btn-seguir:hover { opacity: .85; }
    /* Paginação dark mode */
.pagination .page-link {
    background: var(--surface) !important;
    border-color: var(--border) !important;
    color: var(--text) !important;
}

.pagination .page-item.active .page-link {
    background: var(--brand) !important;
    border-color: var(--brand) !important;
    color: #fff !important;
}

.pagination .page-link:hover {
    background: var(--surface-2) !important;
    color: var(--brand) !important;
}

.pagination .page-item.disabled .page-link {
    background: var(--surface) !important;
    border-color: var(--border) !important;
    color: var(--muted) !important;
}
/* Paginação - funciona no dark e light */
.pagination .page-link {
    background-color: transparent !important;
    border-color: var(--border, #e2e8f0) !important;
    color: var(--text, #1e293b) !important;
}

.pagination .page-item.active .page-link {
    background-color: var(--brand, #4f46e5) !important;
    border-color: var(--brand, #4f46e5) !important;
    color: #fff !important;
}

.pagination .page-link:hover {
    background-color: var(--brand, #4f46e5) !important;
    border-color: var(--brand, #4f46e5) !important;
    color: #fff !important;
}

.pagination .page-item.disabled .page-link {
    background-color: transparent !important;
    border-color: var(--border, #e2e8f0) !important;
    color: var(--muted, #64748b) !important;
}
</style>
@endpush

@section('conteudo')
<div class="perfil-wrap">

    {{-- Voltar --}}
    <div style="margin-bottom:1rem;">
        <a href="{{ route('home') }}" style="color:var(--muted);text-decoration:none;font-size:.9rem;font-weight:600;">
            ← Voltar para Home
        </a>
    </div>

    {{-- HEADER --}}
    <div class="perfil-header">
        @if ($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" class="perfil-avatar" alt="{{ $user->nome }}">
        @else
            <div class="perfil-avatar-placeholder">
                {{ strtoupper(substr($user->nome, 0, 1)) }}
            </div>
        @endif

        <div class="perfil-info" style="flex:1;">
            <h2>{{ $user->nome }} {{ $user->sobrenome }}</h2>

            <span class="perfil-badge badge-{{ $user->tipo }}">
                {{ ucfirst($user->tipo) }}
            </span>

            <div class="perfil-stats">
                <div class="perfil-stat">
                    <strong>{{ $posts->total() }}</strong>
                    <span>Postagens</span>
                </div>
                <a href="{{ route('users.seguidores', $user->id) }}" style="text-decoration:none;color:inherit;">
                    <div class="perfil-stat">
                        <strong>{{ $user->seguidores()->count() }}</strong>
                        <span>Seguidores</span>
                    </div>
                </a>
                <a href="{{ route('users.seguindo', $user->id) }}" style="text-decoration:none;color:inherit;">
                    <div class="perfil-stat">
                        <strong>{{ $user->seguindo()->count() }}</strong>
                        <span>Seguindo</span>
                    </div>
                </a>
                <div class="perfil-stat">
                    <strong>{{ $user->posts()->where('aprovado', true)->sum('visualizacoes') }}</strong>
                    <span>Visualizações</span>
                </div>
            </div>

            @auth
                @if (auth()->id() !== $user->id)
                    @php $jaSigo = auth()->user()->seguindo()->where('seguido_id', $user->id)->exists(); @endphp
                    <form method="POST" action="{{ route('users.seguir', $user->id) }}">
                        @csrf
                        <button type="submit" class="btn-seguir {{ $jaSigo ? 'seguindo' : '' }}">
                            {{ $jaSigo ? '✓ Seguindo' : '+ Seguir' }}
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>

    {{-- POSTS --}}
    <div class="perfil-posts-title">📝 Postagens de {{ $user->nome }}</div>

    @if ($posts->count())
        <div class="perfil-grid">
            @foreach ($posts as $post)
                @php
                    $thumb = null;
                    if ($post->tipo === 'imagem') {
                        $img = $post->imagens->first();
                        $thumb = $img ? Storage::url($img->caminho) : ($post->imagem ? Storage::url($post->imagem) : null);
                    }
                @endphp

                <a href="{{ route('posts.show', $post->id) }}" class="perfil-card">
                    @if ($thumb)
                        <img src="{{ $thumb }}" class="perfil-card-thumb" alt="{{ $post->titulo }}">
                        @elseif ($post->tipo === 'video')
                        @php
                            $isYtP = str_contains($post->video ?? '', 'youtube') || str_contains($post->video ?? '', 'youtu.be');
                            $ytIdP = null;
                            if ($isYtP) { preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video, $mp); $ytIdP = $mp[1] ?? null; }
                        @endphp
                        @if ($isYtP && $ytIdP)
                            <div style="position:relative;">
                                <img src="https://img.youtube.com/vi/{{ $ytIdP }}/hqdefault.jpg" class="perfil-card-thumb" alt="{{ $post->titulo }}">
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.3);">
                                    <div style="width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.9);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">▶️</div>
                                </div>
                            </div>
                        @else
                            <div class="perfil-card-thumb-placeholder" style="background:linear-gradient(135deg,#0f0f0f,#1a1a2e);color:#fff;flex-direction:column;gap:.3rem;">
                                <span style="font-size:2rem;">🎬</span>
                                <span style="font-size:.75rem;color:#aaa;">Clique para assistir</span>
                            </div>
                        @endif
                        @elseif ($post->tipo === 'enquete')
                        <div class="perfil-card-thumb-placeholder" style="flex-direction:column;gap:.5rem;padding:1rem;text-align:center;">
                            <span style="font-size:1.5rem;">📊</span>
                            @php $opcoes = array_filter([1,2,3,4], fn($i) => $post->{'opcao'.$i}); @endphp
                            @foreach(array_slice($opcoes, 0, 3) as $i)
                                <div style="background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);border-radius:6px;padding:.25rem .6rem;font-size:.75rem;color:#a5b4fc;width:100%;text-align:left;">
                                    {{ Str::limit($post->{'opcao'.$i}, 25) }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="perfil-card-thumb-placeholder" style="flex-direction:column;gap:.4rem;padding:1rem;text-align:left;align-items:flex-start;">
                            <span style="font-size:1.2rem;">📝</span>
                            @if($post->texto)
                                <p style="font-size:.78rem;color:#64748b;line-height:1.5;margin:0;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ Str::limit($post->texto, 120) }}
                                </p>
                            @else
                                <p style="font-size:.78rem;color:#64748b;margin:0;">Sem prévia disponível</p>
                            @endif
                        </div>
                    @endif

                    <div class="perfil-card-body">
                        <div class="perfil-card-title">{{ $post->titulo }}</div>
                        <div class="perfil-card-meta">
                            <span>👁 {{ $post->visualizacoes }}</span>
                            <span>❤️ {{ $post->likes_count }}</span>
                            <span>💬 {{ $post->comments_count }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $posts->links() }}</div>
    @else
        <div class="perfil-vazio">
            <div>📭</div>
            <p>Este usuário ainda não tem postagens publicadas.</p>
        </div>
    @endif

</div>
@endsection

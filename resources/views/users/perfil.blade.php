@extends('layouts.site')

@push('styles')
<style>
/* ── Perfil estilo Instagram ── */
.ig-wrap { max-width: 935px; margin: 0 auto; padding: 2rem 1.2rem 4rem; }

/* Header do perfil */
.ig-header {
    display: flex; align-items: flex-start; gap: 3.5rem;
    padding-bottom: 2rem; border-bottom: 1px solid var(--border);
    margin-bottom: 2rem;
}

.ig-avatar-col { flex-shrink: 0; }
.ig-avatar-ring {
    width: 150px; height: 150px; border-radius: 50%;
    background: linear-gradient(45deg, #f09433,#e6683c,#dc2743,#cc2366,#bc1888);
    padding: 3px; display: flex; align-items: center; justify-content: center;
}
.ig-avatar-inner {
    width: 100%; height: 100%; border-radius: 50%; overflow: hidden;
    border: 3px solid var(--surface); background: var(--surface-2);
    display: flex; align-items: center; justify-content: center;
}
.ig-avatar-inner img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.ig-avatar-letter {
    font-size: 3.5rem; font-weight: 800;
    background: linear-gradient(135deg, var(--brand), var(--brand-dark));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}

.ig-info-col { flex: 1; min-width: 0; }

.ig-top-row {
    display: flex; align-items: center; gap: 1rem;
    margin-bottom: 1rem; flex-wrap: wrap;
}
.ig-username {
    font-size: 1.5rem; font-weight: 300; color: var(--text);
    letter-spacing: -.01em;
}
.ig-badge {
    display: inline-block; padding: .25rem .85rem;
    border-radius: 50px; font-size: .78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .04em;
}
.ig-badge-leitor        { background: #e0f2fe; color: #0369a1; }
.ig-badge-editor        { background: #dcfce7; color: #15803d; }
.ig-badge-administrador { background: #fef3c7; color: #b45309; }

/* Botão engrenagem */
.btn-settings {
    display: inline-flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 8px;
    border: 1.5px solid var(--border); background: var(--surface-2);
    color: var(--muted); cursor: pointer; transition: all .2s;
    text-decoration: none; font-size: 1.1rem; flex-shrink: 0;
}
.btn-settings:hover { border-color: var(--brand); color: var(--brand); background: #eef2ff; }

/* Botão seguir */
.btn-seguir-ig {
    padding: .5rem 1.5rem; border-radius: 8px;
    font-weight: 600; font-size: .9rem;
    cursor: pointer; transition: all .2s;
    border: 1.5px solid transparent;
    background: var(--brand); color: #fff;
}
.btn-seguir-ig.seguindo {
    background: transparent;
    border-color: var(--border);
    color: var(--text);
}
.btn-seguir-ig:hover { opacity: .85; }

/* Stats */
.ig-stats {
    display: flex; gap: 2.5rem; margin-bottom: 1rem;
}
.ig-stat { display: flex; align-items: center; gap: .35rem; font-size: .95rem; color: var(--text); }
.ig-stat strong { font-weight: 700; }
.ig-stat a { color: inherit; text-decoration: none; }
.ig-stat a:hover { text-decoration: underline; }

/* Grid de posts */
.ig-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 3px;
}

.ig-post-item {
    position: relative; aspect-ratio: 1; overflow: hidden;
    background: var(--surface-2); cursor: pointer;
    display: block;
}
.ig-post-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .3s; }
.ig-post-item:hover img { transform: scale(1.05); }

.ig-post-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,.45);
    display: flex; align-items: center; justify-content: center;
    gap: 1.5rem; opacity: 0; transition: opacity .25s;
}
.ig-post-item:hover .ig-post-overlay { opacity: 1; }
.ig-overlay-stat {
    display: flex; align-items: center; gap: .4rem;
    color: #fff; font-size: .95rem; font-weight: 700;
}

.ig-post-placeholder {
    width: 100%; height: 100%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: .5rem; padding: 1rem; text-align: left;
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
}
.ig-post-placeholder .icon { font-size: 1.8rem; }
.ig-post-placeholder p {
    font-size: .72rem; color: #475569; line-height: 1.4; margin: 0;
    display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;
}

.ig-tipo-badge {
    position: absolute; top: 6px; right: 6px;
    font-size: .7rem; padding: .15rem .45rem; border-radius: 4px;
    font-weight: 700; backdrop-filter: blur(4px);
}
.tipo-imagem-badge { background: rgba(252,231,243,.9); color: #9d174d; }
.tipo-video-badge  { background: rgba(220,252,231,.9); color: #166534; }
.tipo-enquete-badge{ background: rgba(254,243,199,.9); color: #92400e; }
.tipo-texto-badge  { background: rgba(219,234,254,.9); color: #1d4ed8; }

.ig-vazio {
    grid-column: 1/-1; text-align: center;
    padding: 4rem 1rem; color: var(--muted);
}
.ig-vazio div { font-size: 3rem; margin-bottom: 1rem; }

@media(max-width: 640px) {
    .ig-header { flex-direction: column; align-items: center; gap: 1.5rem; text-align: center; }
    .ig-top-row { justify-content: center; }
    .ig-stats { justify-content: center; gap: 1.5rem; }
    .ig-avatar-ring { width: 100px; height: 100px; }
    .ig-avatar-letter { font-size: 2.5rem; }
    .ig-grid { grid-template-columns: repeat(3, 1fr); }
}

html.dark .profile-wrap  { background: var(--surface) !important; }
html.dark .profile-card  { background: var(--surface) !important; border-color: var(--border) !important; }
html.dark .profile-card-body { background: var(--surface) !important; }
html.dark .profile-card-footer { background: var(--surface-2) !important; border-color: var(--border) !important; }
html.dark .form-group input  { background: var(--surface-2) !important; border-color: var(--border) !important; color: var(--text) !important; }
html.dark .profile-tab-btn   { background: var(--surface) !important; border-color: var(--border) !important; color: var(--muted) !important; }
html.dark .danger-card { background: var(--surface) !important; border-color: var(--border) !important; }
</style>
@endpush

@section('conteudo')
<div class="ig-wrap">

    {{-- Voltar --}}
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('home') }}" style="color:var(--muted);text-decoration:none;font-size:.9rem;font-weight:600;">
            ← Voltar para Home
        </a>
    </div>

    {{-- HEADER ── estilo Instagram --}}
    <div class="ig-header">

        {{-- Avatar --}}
        <div class="ig-avatar-col">
            <div class="ig-avatar-ring">
                <div class="ig-avatar-inner">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->nome }}">
                    @else
                        <span class="ig-avatar-letter">{{ strtoupper(substr($user->nome, 0, 1)) }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="ig-info-col">
            <div class="ig-top-row">
                <span class="ig-username">{{ $user->nome }} {{ $user->sobrenome }}</span>

                <span class="ig-badge ig-badge-{{ $user->tipo }}">
                    {{ ucfirst($user->tipo) }}
                </span>

                @auth
                    @if (auth()->id() === $user->id)
                        {{-- Engrenagem de ajustes --}}
                        <a href="{{ route('profile.edit') }}" class="btn-settings" title="Configurações do perfil">
                            ⚙️
                        </a>
                    @else
                        @php $jaSigo = auth()->user()->seguindo()->where('seguido_id', $user->id)->exists(); @endphp
                        <form method="POST" action="{{ route('users.seguir', $user->id) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-seguir-ig {{ $jaSigo ? 'seguindo' : '' }}">
                                {{ $jaSigo ? '✓ Seguindo' : '+ Seguir' }}
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            {{-- Stats --}}
            <div class="ig-stats">
                <div class="ig-stat">
                    <strong>{{ $posts->total() }}</strong> <span>postagens</span>
                </div>
                <div class="ig-stat">
                    <a href="{{ route('users.seguidores', $user->id) }}">
                        <strong>{{ $user->seguidores()->count() }}</strong> <span>seguidores</span>
                    </a>
                </div>
                <div class="ig-stat">
                    <a href="{{ route('users.seguindo', $user->id) }}">
                        <strong>{{ $user->seguindo()->count() }}</strong> <span>seguindo</span>
                    </a>
                </div>
                <div class="ig-stat">
                    <strong>{{ $user->posts()->where('aprovado', true)->sum('visualizacoes') }}</strong>
                    <span>visualizações</span>
                </div>
            </div>

            {{-- Nome completo --}}
            <div style="font-size:.95rem;color:var(--text);font-weight:600;">
                {{ $user->nome }} {{ $user->sobrenome }}
            </div>
        </div>
    </div>

    {{-- Separador de posts --}}
    <div style="display:flex;align-items:center;gap:.8rem;margin-bottom:1.2rem;">
        <span style="font-size:.88rem;font-weight:700;color:var(--text);text-transform:uppercase;letter-spacing:.06em;border-top:2px solid var(--text);padding-top:.5rem;">
            ⊞ Postagens
        </span>
    </div>

    {{-- GRID de posts estilo Instagram --}}
    <div class="ig-grid">
        @forelse ($posts as $post)
            @php
                $thumb = null;
                if ($post->tipo === 'imagem') {
                    $img = $post->imagens->first();
                    $thumb = $img ? Storage::url($img->caminho) : ($post->imagem ? Storage::url($post->imagem) : null);
                }
                if ($post->tipo === 'video' && ($isYtP = str_contains($post->video ?? '', 'youtube') || str_contains($post->video ?? '', 'youtu.be'))) {
                    preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video ?? '', $mp);
                    $ytThumb = isset($mp[1]) ? "https://img.youtube.com/vi/{$mp[1]}/hqdefault.jpg" : null;
                }
                $tipoLabels = ['texto'=>'tipo-texto-badge','imagem'=>'tipo-imagem-badge','video'=>'tipo-video-badge','enquete'=>'tipo-enquete-badge'];
            @endphp

            <a href="{{ route('posts.show', $post->id) }}" class="ig-post-item">
                @if ($thumb)
                    <img src="{{ $thumb }}" alt="{{ $post->titulo }}">
                @elseif ($post->tipo === 'video' && isset($ytThumb) && $ytThumb)
                    <img src="{{ $ytThumb }}" alt="{{ $post->titulo }}" style="filter:brightness(.9);">
                @elseif ($post->tipo === 'texto')
                    <div class="ig-post-placeholder" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                        <span class="icon">✍️</span>
                        @if($post->texto)
                            <p>{{ Str::limit($post->texto, 100) }}</p>
                        @endif
                    </div>
                @elseif ($post->tipo === 'enquete')
                    <div class="ig-post-placeholder" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                        <span class="icon">📊</span>
                        <p>{{ $post->{'opcao1'} ?? '' }}</p>
                    </div>
                @elseif ($post->tipo === 'video')
                    <div class="ig-post-placeholder" style="background:linear-gradient(135deg,#0f0f0f,#1a1a2e);">
                        <span class="icon">🎬</span>
                        <p style="color:#aaa;">Vídeo</p>
                    </div>
                @else
                    <div class="ig-post-placeholder">
                        <span class="icon">📄</span>
                    </div>
                @endif

                <span class="ig-tipo-badge {{ $tipoLabels[$post->tipo] ?? '' }}">
                    {{ ['texto'=>'✍️','imagem'=>'🖼️','video'=>'🎬','enquete'=>'📊'][$post->tipo] ?? '' }}
                </span>

                <div class="ig-post-overlay">
                    <div class="ig-overlay-stat">❤️ {{ $post->likes_count }}</div>
                    <div class="ig-overlay-stat">💬 {{ $post->comments_count }}</div>
                    <div class="ig-overlay-stat">👁 {{ $post->visualizacoes }}</div>
                </div>
            </a>
        @empty
            <div class="ig-vazio">
                <div>📭</div>
                <p>Nenhuma postagem publicada ainda.</p>
            </div>
        @endforelse
    </div>

    @if ($posts->hasPages())
        <div style="margin-top:2rem;">{{ $posts->links() }}</div>
    @endif

</div>
@endsection
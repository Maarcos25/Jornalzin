@extends('layouts.site')

@push('styles')
<style>
    .fav-wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }

    .fav-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.8rem; flex-wrap: wrap; gap: 1rem;
    }
    .fav-title {
        font-size: 1.9rem; font-weight: 800; color: var(--text);
        margin: 0; letter-spacing: -.02em;
    }

    /* Grid de cards */
    .fav-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.2rem;
    }

    .fav-card {
        background: var(--surface);
        border-radius: 14px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        overflow: hidden;
        transition: box-shadow .2s, transform .2s;
        display: flex; flex-direction: column;
    }
    .fav-card:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,.12);
        transform: translateY(-3px);
    }

    /* Mídia */
    .fav-media { width: 100%; }
    .fav-media img {
        width: 100%; height: 200px; object-fit: cover; display: block;
    }
    .fav-media-placeholder {
        width: 100%; height: 140px;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem;
    }

    /* Corpo */
    .fav-body { padding: .9rem 1rem .5rem; flex: 1; }
    .fav-tipo {
        display: inline-block; font-size: .72rem; font-weight: 700;
        letter-spacing: .04em; text-transform: uppercase;
        padding: .2rem .65rem; border-radius: 20px; margin-bottom: .5rem;
    }
    .tipo-texto   { background: #dbeafe; color: #1d4ed8; }
    .tipo-imagem  { background: #fce7f3; color: #9d174d; }
    .tipo-video   { background: #dcfce7; color: #166534; }
    .tipo-enquete { background: #fef3c7; color: #92400e; }
    html.dark .tipo-texto   { background: #1e3a5f; color: #93c5fd; }
    html.dark .tipo-imagem  { background: #4a1942; color: #f9a8d4; }
    html.dark .tipo-video   { background: #14532d; color: #86efac; }
    html.dark .tipo-enquete { background: #451a03; color: #fcd34d; }

    .fav-post-title {
        display: block; font-size: 1rem; font-weight: 700;
        color: var(--text); text-decoration: none;
        margin-bottom: .35rem; line-height: 1.4; transition: color .15s;
    }
    .fav-post-title:hover { color: var(--brand, #4f46e5); }

    .fav-excerpt {
        font-size: .88rem; color: var(--muted); line-height: 1.55; margin: 0;
    }

    /* Footer */
    .fav-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: .65rem 1rem .75rem;
        border-top: 1px solid var(--border);
        flex-wrap: wrap; gap: .4rem;
    }
    .fav-meta {
        display: flex; align-items: center; gap: .35rem;
        font-size: .82rem; color: var(--muted);
    }
    .fav-meta img { width: 22px; height: 22px; border-radius: 50%; object-fit: cover; }

    .btn-desfavoritar {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .32rem .8rem; border-radius: 50px;
        font-size: .8rem; font-weight: 600;
        border: 1.5px solid #fecaca; background: #fef2f2; color: #ef4444;
        cursor: pointer; transition: all .15s;
    }
    .btn-desfavoritar:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    .btn-ver-post {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .32rem .8rem; border-radius: 50px;
        font-size: .8rem; font-weight: 600;
        border: 1.5px solid var(--border); background: var(--surface-2);
        color: var(--muted); text-decoration: none; transition: all .15s;
    }
    .btn-ver-post:hover { border-color: #4f46e5; color: #4f46e5; background: #eef2ff; }

    /* Vazio */
    .fav-vazio {
        text-align: center; padding: 5rem 1rem; color: var(--muted);
    }
    .fav-vazio div { font-size: 3.5rem; margin-bottom: 1rem; }
    .fav-vazio p { font-size: 1rem; margin-bottom: 1.5rem; }
    .btn-ir-home {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .65rem 1.6rem; border-radius: 50px;
        background: linear-gradient(135deg, #4f46e5, #3730a3);
        color: #fff; font-weight: 700; font-size: .95rem;
        text-decoration: none; transition: all .2s;
        box-shadow: 0 4px 14px rgba(79,70,229,.3);
    }
    .btn-ir-home:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); color: #fff; }
</style>
@endpush

@section('conteudo')
<div class="fav-wrap">

    <div class="fav-header">
        <h1 class="fav-title">🔖 Salvos</h1>
        <span style="color:var(--muted);font-size:.9rem;">
            {{ $posts->total() }} {{ $posts->total() === 1 ? 'post salvo' : 'posts salvos' }}
        </span>
    </div>

    @if($posts->isEmpty())
        <div class="fav-vazio">
            <div>🔖</div>
            <p>Você ainda não salvou nenhum post.</p>
            <a href="{{ route('home') }}" class="btn-ir-home">📰 Explorar posts</a>
        </div>
    @else
        <div class="fav-grid">
            @foreach($posts as $post)
            @php
                $imgs = $post->imagens->count()
                    ? $post->imagens->map(fn($i) => Storage::url($i->caminho))->toArray()
                    : ($post->imagem ? [Storage::url($post->imagem)] : []);
                $pilltypes = ['texto'=>'tipo-texto','imagem'=>'tipo-imagem','video'=>'tipo-video','enquete'=>'tipo-enquete'];
                $pillicons = ['texto'=>'✍️','imagem'=>'🖼️','video'=>'🎬','enquete'=>'📊'];
            @endphp
            <div class="fav-card">

                {{-- Mídia --}}
                @if($post->tipo === 'imagem' && count($imgs))
                    <div class="fav-media">
                        <img src="{{ $imgs[0] }}" alt="{{ $post->titulo }}">
                    </div>
                @elseif($post->tipo === 'video' && $post->video)
                    @php
                        $isYt = str_contains($post->video,'youtube') || str_contains($post->video,'youtu.be');
                        $ytThumb = null;
                        if($isYt) { preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video, $m); $ytThumb = isset($m[1]) ? "https://img.youtube.com/vi/{$m[1]}/hqdefault.jpg" : null; }
                    @endphp
                    @if($ytThumb)
                        <div class="fav-media"><img src="{{ $ytThumb }}" alt="{{ $post->titulo }}" style="filter:brightness(.85);"></div>
                    @else
                        <div class="fav-media-placeholder" style="background:linear-gradient(135deg,#0f0f0f,#1a1a2e);">🎬</div>
                    @endif
                @elseif($post->tipo === 'texto')
                    <div class="fav-media-placeholder" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">✍️</div>
                @elseif($post->tipo === 'enquete')
                    <div class="fav-media-placeholder" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">📊</div>
                @endif

                {{-- Corpo --}}
                <div class="fav-body">
                    <span class="fav-tipo {{ $pilltypes[$post->tipo] ?? '' }}">
                        {{ $pillicons[$post->tipo] ?? '' }} {{ ucfirst($post->tipo) }}
                    </span>
                    <a href="{{ route('posts.show', $post->id) }}" class="fav-post-title">
                        {{ $post->titulo }}
                    </a>
                    @if($post->tipo === 'texto' && $post->texto)
                        <p class="fav-excerpt">{{ Str::limit($post->texto, 100) }}</p>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="fav-footer">
                    <div class="fav-meta">
                        @if($post->usuario && $post->usuario->avatar)
                            <img src="{{ asset('storage/'.$post->usuario->avatar) }}" alt="">
                        @else
                            <span>✍️</span>
                        @endif
                        {{ $post->usuario->nome ?? 'Anônimo' }}
                        <span style="color:var(--border)">·</span>
                        <span>❤️ {{ $post->likes_count }}</span>
                        <span>💬 {{ $post->comments_count }}</span>
                    </div>

                    <div style="display:flex;gap:.4rem;">
                        <a href="{{ route('posts.show', $post->id) }}" class="btn-ver-post">👁 Ver</a>
                        <button class="btn-desfavoritar"
                                onclick="toggleFavorito(this, {{ $post->id }})"
                                data-favoritado="1"
                                title="Remover dos salvos">
                            🔖 Salvo
                        </button>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Paginação --}}
        @if($posts->hasPages())
            <div style="margin-top:2rem;">
                {{ $posts->links() }}
            </div>
        @endif
    @endif

</div>
@endsection

@push('scripts')
<script>
function toggleFavorito(btn, postId) {
    fetch(`/favorito/${postId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.favoritado) {
            // Remove o card da lista com animação
            const card = btn.closest('.fav-card');
            card.style.transition = 'opacity .3s, transform .3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(.95)';
            setTimeout(() => {
                card.remove();
                // Verifica se ficou vazio
                const grid = document.querySelector('.fav-grid');
                if (grid && grid.children.length === 0) {
                    location.reload();
                }
            }, 300);
        }
    });
}
</script>
@endpush

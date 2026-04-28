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
        --radius:     14px;
        --shadow:     0 2px 12px rgba(0,0,0,.07);
    }

    body { font-family: 'Segoe UI', sans-serif; }
    .show-wrap { max-width: 720px; margin: 0 auto; padding: 2rem 1.2rem 4rem;background: var(--bg); }

    .post-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.4rem;
    }

    /* ── Carrossel ── */
    .carousel-wrap {
        position: relative;
        background: #000;
        aspect-ratio: 16/9;
        overflow: hidden;
    }
    .carousel-track {
        display: flex;
        height: 100%;
        transition: transform .35s ease;
    }
    .carousel-slide {
        min-width: 100%;
        height: 100%;
    }
    .carousel-slide img {
        width: 100%; height: 100%;
        object-fit: contain;
        display: block;
        cursor: zoom-in;
        background: #111;
    }
    .carousel-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        background: rgba(0,0,0,.55); color: #fff; border: none;
        width: 38px; height: 38px; border-radius: 50%;
        font-size: 1.3rem; cursor: pointer; z-index: 2;
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
    }
    .carousel-btn:hover { background: rgba(0,0,0,.85); }
    .carousel-btn.prev { left: 10px; }
    .carousel-btn.next { right: 10px; }

    .carousel-dots {
        display: flex; justify-content: center; gap: .4rem;
        padding: .5rem 0 .2rem; background: #000;
    }
    .carousel-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: rgba(255,255,255,.35); border: none;
        cursor: pointer; transition: background .2s; padding: 0;
    }
    .carousel-dot.active { background: #fff; }

    /* Miniaturas */
    .carousel-thumbs {
        display: flex; gap: 6px; padding: .6rem .8rem;
        overflow-x: auto; background: var(--surface-2);
        border-bottom: 1px solid var(--border);
    }
    .carousel-thumbs::-webkit-scrollbar { height: 4px; }
    .carousel-thumbs::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }
    .carousel-thumb {
        width: 64px; height: 64px; flex-shrink: 0;
        border-radius: 8px; overflow: hidden;
        border: 2.5px solid transparent; cursor: pointer;
        transition: border-color .2s, transform .15s;
        background: #000;
    }
    .carousel-thumb:hover { transform: translateY(-2px); }
    .carousel-thumb.active { border-color: var(--brand); }
    .carousel-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

    /* Imagem única */
    .post-media img {
        width: 100%; display: block;
        max-height: 520px; object-fit: cover; cursor: zoom-in;
    }

    /* Vídeo */
    .video-wrap { background: #000; }
    .video-wrap iframe,
    .video-wrap video { width: 100%; display: block; aspect-ratio: 16/9; border: none; }

    /* Corpo */
    .post-body { padding: 1.1rem 1.3rem .5rem; }
    .post-title { font-size: 1.35rem; font-weight: 800; color: var(--text); margin: 0 0 .5rem; line-height: 1.3; }
    .post-tipo {
        display: inline-block; font-size: .72rem; font-weight: 700;
        letter-spacing: .04em; text-transform: uppercase;
        padding: .22rem .7rem; border-radius: 20px; margin-bottom: .6rem;
    }
    .tipo-texto   { background: #dbeafe; color: #1d4ed8; }
    .tipo-imagem  { background: #fce7f3; color: #9d174d; }
    .tipo-video   { background: #dcfce7; color: #166534; }
    .tipo-enquete { background: #fef3c7; color: #92400e; }
    .post-text { font-size: .97rem; color: var(--muted); line-height: 1.65; margin: 0; white-space: pre-line; }

    /* Enquete */
    .poll-wrap { margin-top: .7rem; }
    .poll-label { font-size: .88rem; color: var(--muted); margin-bottom: .5rem; font-weight: 600; }
    .poll-opt {
        display: flex; align-items: center; gap: .5rem;
        padding: .6rem .85rem; border-radius: 8px;
        border: 1.5px solid var(--border); cursor: pointer;
        font-size: .95rem; margin-bottom: .45rem; transition: all .15s;
    }
    .poll-opt:hover { border-color: var(--brand); background: #eef2ff; }
    .poll-opt input { accent-color: var(--brand); }
    .btn-vote { margin-top: .4rem; padding: .5rem 1.2rem; background: var(--brand); color: #fff; border: none; border-radius: 8px; font-size: .92rem; font-weight: 700; cursor: pointer; }
    .poll-res-row { margin-bottom: .5rem; }
    .poll-res-label { display: flex; justify-content: space-between; font-size: .88rem; color: var(--text); margin-bottom: .2rem; }
    .poll-res-bg { height: 9px; background: var(--surface-2); border-radius: 99px; overflow: hidden; border: 1px solid var(--border); }
    .poll-res-bar { height: 100%; background: var(--brand); border-radius: 99px; transition: width .5s; }
    .poll-res-bar.win { background: #10b981; }

    /* Footer */
    .post-footer {
        display: flex; align-items: center; padding: .65rem 1.3rem .75rem;
        border-top: 1px solid var(--border); gap: .6rem; flex-wrap: wrap;
    }
    .post-meta { display: flex; align-items: center; gap: .35rem; font-size: .85rem; color: var(--muted); }
    .avatar-mini { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border); }
    .avatar-mini-placeholder {
        width: 28px; height: 28px; border-radius: 50%;
        background: var(--brand); color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .72rem; font-weight: 800; flex-shrink: 0;
    }
    .post-actions { margin-left: auto; display: flex; gap: .5rem; align-items: center; }
    .btn-like, .btn-back, .btn-report-post {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .42rem 1rem; border-radius: 50px;
        border: 1.5px solid var(--border); background: var(--surface-2);
        color: var(--muted); font-size: .9rem; font-weight: 600;
        cursor: pointer; transition: all .18s; text-decoration: none;
    }
    .btn-like:hover, .btn-like.liked { border-color: #f43f5e; background: #fff1f2; color: #f43f5e; }
    .btn-back:hover { border-color: var(--brand); background: #eef2ff; color: var(--brand); }
    .btn-report-post:hover { border-color: #f97316; background: #fff7ed; color: #f97316; }

    /* Comentários */
    .comments-card {
        background: var(--surface); border-radius: var(--radius);
        box-shadow: var(--shadow); border: 1px solid var(--border);
        overflow: hidden; margin-bottom: 1.2rem;
    }
    .comments-header { padding: 1rem 1.3rem; border-bottom: 1px solid var(--border); font-weight: 800; font-size: 1rem; color: var(--text); }
    .comment-item { display: flex; gap: .7rem; align-items: flex-start; padding: .85rem 1.3rem; border-bottom: 1px solid var(--border); }
    .comment-item:last-child { border-bottom: none; }
    .comment-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--brand); color: #fff; display: flex; align-items: center; justify-content: center; font-size: .82rem; font-weight: 800; flex-shrink: 0; }
    .comment-author { font-size: .88rem; font-weight: 700; color: var(--text); margin-right: .4rem; }
    .comment-date { font-size: .78rem; color: var(--muted); }
    .comment-text { font-size: .92rem; color: var(--muted); margin-top: .15rem; display: block; }
    .empty-comments { padding: 1.5rem 1.3rem; color: var(--muted); font-size: .93rem; text-align: center; }

    .comment-form-card {
        background: var(--surface); border-radius: var(--radius);
        box-shadow: var(--shadow); border: 1px solid var(--border);
        padding: 1.2rem 1.3rem;
    }
    .comment-form-card h5 { font-weight: 800; font-size: .95rem; color: var(--text); margin: 0 0 .8rem; }
    .comment-form { display: flex; gap: .5rem; }
    .comment-form textarea {
        flex: 1; padding: .6rem .95rem;
        border: 1.5px solid var(--border); border-radius: 12px;
        font-size: .92rem; outline: none; resize: none;
        background: var(--surface); transition: border-color .2s;
        font-family: inherit; min-height: 70px;
    }
    .comment-form textarea:focus { border-color: var(--brand); }
    .btn-comentar {
        padding: .6rem 1.3rem; border-radius: 12px; background: var(--brand);
        color: #fff; border: none; font-size: .92rem; font-weight: 700;
        cursor: pointer; transition: background .2s; align-self: flex-end;
    }
    .btn-comentar:hover { background: var(--brand-dark); }

    .alert-success {
        background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;
        border-radius: 10px; padding: .75rem 1rem; margin-bottom: 1rem;
        font-size: .92rem; font-weight: 600;
    }

    /* Lightbox */
    #lightbox {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.9); z-index: 9999;
        align-items: center; justify-content: center; cursor: zoom-out;
    }
    #lightbox.open { display: flex; }
    #lightbox img { max-width: 92vw; max-height: 92vh; border-radius: 10px; object-fit: contain; }

    /* Modal denúncia */
    #modalDenunciaShow {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.6); z-index: 9998;
        align-items: center; justify-content: center;
    }
    #modalDenunciaShow.open { display: flex; }
    .denuncia-box {
        background: var(--surface); border-radius: 16px; padding: 2rem;
        max-width: 420px; width: 90%; box-shadow: 0 8px 32px rgba(0,0,0,.2);
    }
    .denuncia-box h5 { font-weight: 800; color: var(--text); margin: 0 0 .5rem; }
    .denuncia-box p  { color: var(--muted); font-size: .88rem; margin-bottom: 1rem; }
    .denuncia-opcao {
        display: flex; align-items: center; gap: .6rem;
        padding: .55rem .85rem; border: 1.5px solid var(--border);
        border-radius: 8px; cursor: pointer; font-size: .93rem;
        color: var(--text); margin-bottom: .4rem;
    }
    .denuncia-opcao input { accent-color: #ef4444; }

    /* Swipe touch */
    .carousel-wrap { touch-action: pan-y; }
</style>
@endpush

@section('conteudo')

@php
    $votos      = [];
    $totalVotos = 0;
    $jaVotou    = false;
    if ($post->tipo === 'enquete') {
        $votos      = $post->votos()->get()->groupBy('opcao')->map->count()->toArray();
        $totalVotos = array_sum($votos);
        $jaVotou    = auth()->check() && $post->votos()->where('id_usuario', auth()->id())->exists();
    }
    $maxVotos = $totalVotos > 0 ? max($votos) : 0;

    $jaLikei    = auth()->check() && $post->likes()->where('user_id', auth()->id())->exists();
    $totalLikes = $post->likes()->count();

    $imgs = $post->imagens->count()
        ? $post->imagens->map(fn($i) => Storage::url($i->caminho))->toArray()
        : ($post->imagem
            ? [str_starts_with($post->imagem, '/storage/') ? asset($post->imagem) : Storage::url($post->imagem)]
            : []);
    $imgCount = count($imgs);
@endphp

<div class="show-wrap">

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    <div class="post-card">

        {{-- ── MÍDIA ── --}}
        @if($post->tipo === 'imagem' && $imgCount)
            @if($imgCount === 1)
                <div class="post-media">
                    <img src="{{ $imgs[0] }}" alt="{{ $post->titulo }}"
                         onclick="abrirImagem('{{ $imgs[0] }}')">
                </div>
            @else
                {{-- Carrossel --}}
                <div class="carousel-wrap" id="carousel-{{ $post->id }}">
                    <div class="carousel-track" id="track-{{ $post->id }}">
                        @foreach($imgs as $src)
                            <div class="carousel-slide">
                                <img src="{{ $src }}" alt=""
                                     onclick="abrirImagem('{{ $src }}')">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-btn prev" onclick="moverCarrossel({{ $post->id }}, -1)">&#8249;</button>
                    <button class="carousel-btn next" onclick="moverCarrossel({{ $post->id }}, 1)">&#8250;</button>
                    <div class="carousel-counter">
                        <span id="cc-current-{{ $post->id }}">1</span>/{{ $imgCount }}
                    </div>
                </div>
                {{-- Dots --}}
                <div class="carousel-dots" id="dots-{{ $post->id }}" style="background:var(--surface-2);padding:.5rem 0 .3rem;">
                    @foreach($imgs as $i => $src)
                        <button class="carousel-dot {{ $i===0 ? 'active' : '' }}"
                                onclick="irParaSlide({{ $post->id }}, {{ $i }})"></button>
                    @endforeach
                </div>
                {{-- Miniaturas --}}
                <div class="carousel-thumbs" id="thumbs-{{ $post->id }}">
                    @foreach($imgs as $i => $src)
                        <div class="carousel-thumb {{ $i===0 ? 'active' : '' }}"
                             onclick="irParaSlide({{ $post->id }}, {{ $i }})">
                            <img src="{{ $src }}" alt="imagem {{ $i+1 }}">
                        </div>
                    @endforeach
                </div>
            @endif

        @elseif($post->tipo === 'video' && $post->video)
            @php
                $isYt    = str_contains($post->video,'youtube') || str_contains($post->video,'youtu.be');
                $isVimeo = str_contains($post->video,'vimeo');
            @endphp
            <div class="video-wrap">
                @if($isYt)
                    @php preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video, $m); @endphp
                    <iframe src="https://www.youtube.com/embed/{{ $m[1] ?? '' }}"
                            frameborder="0" allowfullscreen></iframe>
                @elseif($isVimeo)
                    @php preg_match('/vimeo\.com\/(\d+)/', $post->video, $m); @endphp
                    <iframe src="https://player.vimeo.com/video/{{ $m[1] ?? '' }}"
                            frameborder="0" allowfullscreen></iframe>
                @else
                    <video controls>
                        <source src="{{ str_starts_with($post->video,'/storage/') ? asset($post->video) : Storage::url($post->video) }}">
                    </video>
                @endif
            </div>
        @endif

        {{-- ── CORPO ── --}}
        <div class="post-body">
            @php
                $pilltypes = ['texto'=>'tipo-texto','imagem'=>'tipo-imagem','video'=>'tipo-video','enquete'=>'tipo-enquete'];
                $pillicons = ['texto'=>'✍️','imagem'=>'🖼️','video'=>'🎬','enquete'=>'📊'];
            @endphp
            <span class="post-tipo {{ $pilltypes[$post->tipo] ?? '' }}">
                {{ $pillicons[$post->tipo] ?? '' }} {{ ucfirst($post->tipo) }}
            </span>
            <h1 class="post-title">{{ $post->titulo }}</h1>

            @if($post->tipo !== 'enquete' && $post->texto)
                <p class="post-text">{{ $post->texto }}</p>
            @endif

            @if($post->tipo === 'enquete')
                <div class="poll-wrap">
                    <p class="poll-label">📊 {{ $totalVotos }} {{ $totalVotos === 1 ? 'voto' : 'votos' }}</p>
                    @if($jaVotou)
                        @foreach(range(1,8) as $i)
                            @php $opcao = $post->{'opcao'.$i}; if(!$opcao) continue; @endphp
                            @php
                                $qtd = $votos[$i] ?? 0;
                                $pct = $totalVotos > 0 ? round($qtd / $totalVotos * 100) : 0;
                                $win = $qtd === $maxVotos && $maxVotos > 0;
                            @endphp
                            <div class="poll-res-row">
                                <div class="poll-res-label">
                                    <span>{{ $opcao }} @if($win) 🏆 @endif</span>
                                    <span>{{ $pct }}% ({{ $qtd }})</span>
                                </div>
                                <div class="poll-res-bg">
                                    <div class="poll-res-bar {{ $win ? 'win' : '' }}" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <form method="POST" action="{{ route('posts.votar', $post->id) }}">
                            @csrf
                            @foreach(range(1,8) as $i)
                                @php $opcao = $post->{'opcao'.$i}; if(!$opcao) continue; @endphp
                                <label class="poll-opt">
                                    <input type="radio" name="opcao" value="{{ $i }}" required>
                                    {{ $opcao }}
                                </label>
                            @endforeach
                            <button type="submit" class="btn-vote">🗳️ Votar</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── FOOTER ── --}}
        <div class="post-footer">
            <div class="post-meta">
                @if($post->usuario && $post->usuario->avatar)
                    <img src="{{ asset('storage/'.$post->usuario->avatar) }}" class="avatar-mini" alt="">
                @else
                    <div class="avatar-mini-placeholder">
                        {{ strtoupper(substr($post->usuario->nome ?? 'U', 0, 1)) }}
                    </div>
                @endif
                <a href="{{ route('users.perfil', $post->usuario->id ?? 0) }}"
                   style="color:inherit;text-decoration:none;font-weight:600;">
                    {{ $post->usuario->nome ?? 'Anônimo' }}
                </a>
                <span style="color:var(--border)">·</span>
                <span>👁 {{ $post->visualizacoes }}</span>
                <span style="color:var(--border)">·</span>
                <span>{{ \Carbon\Carbon::parse($post->data)->locale('pt_BR')->isoFormat('D MMM YYYY') }}</span>
            </div>

            <div class="post-actions">
                @auth
                <button class="btn-report-post" onclick="abrirDenunciaPost()" title="Denunciar post">
                    🚩 Denunciar
                </button>
                @endauth
                <button class="btn-like {{ $jaLikei ? 'liked' : '' }}"
                        onclick="toggleLike(this, {{ $post->id }})">
                    <span class="like-icon">{{ $jaLikei ? '❤️' : '🤍' }}</span>
                    <span class="like-count">{{ $totalLikes }}</span>
                </button>
                <a href="javascript:history.back()" class="btn-back">← Voltar</a>
            </div>
        </div>
    </div>

    {{-- ── COMENTÁRIOS ── --}}
<div class="comments-card" id="comentarios">
        <div class="comments-header">
            💬 Comentários ({{ $post->comments->count() }})
        </div>

        @forelse($post->comments as $comentario)
            <div class="comment-item">
                <div class="comment-avatar">
                    {{ strtoupper(substr($comentario->user->nome ?? $comentario->user->name ?? 'U', 0, 1)) }}
                </div>
                <div style="flex:1;">
                    <span class="comment-author">
                        {{ $comentario->user->nome ?? $comentario->user->name ?? 'Anônimo' }}
                    </span>
                    <span class="comment-date">
                        {{ $comentario->created_at?->format('d/m/Y H:i') }}
                    </span>
                    <span class="comment-text">{{ $comentario->texto }}</span>
                </div>
                @auth
                <button onclick="abrirDenunciaComentario({{ $comentario->id }})"
                    title="Denunciar comentário"
                    style="background:none;border:none;cursor:pointer;color:var(--muted);font-size:.85rem;padding:.3rem .5rem;flex-shrink:0;border-radius:6px;transition:all .15s;"
                    onmouseover="this.style.color='#ef4444';this.style.background='#fef2f2';"
                    onmouseout="this.style.color='var(--muted)';this.style.background='none';">
                    🚩
                </button>
                @endauth
            </div>
        @empty
            <div class="empty-comments">Nenhum comentário ainda. Seja o primeiro! 💬</div>
        @endforelse
    </div>

    {{-- ── FORM COMENTÁRIO ── --}}
    @auth
        <div class="comment-form-card">
            <h5>Adicionar Comentário</h5>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <div class="comment-form">
                    <textarea name="texto" placeholder="Digite seu comentário..." required></textarea>
                    <button type="submit" class="btn-comentar">💾 Comentar</button>
                </div>
            </form>
        </div>
    @else
        <div class="comment-form-card" style="text-align:center;color:var(--muted);font-size:.92rem;">
            <a href="{{ route('login') }}" style="color:var(--brand);font-weight:700;">Entre</a> para comentar.
        </div>
    @endauth

</div>

{{-- ── Lightbox ── --}}
<div id="lightbox" onclick="this.classList.remove('open')">
    <img id="lightbox-img" src="" alt="">
</div>

{{-- ── Modal Denúncia ── --}}
<div id="modalDenunciaShow">
    <div class="denuncia-box">
        <h5>🚩 Denunciar conteúdo</h5>
        <p>Selecione o motivo. A denúncia será enviada ao administrador.</p>
        <form id="formDenunciaShow" method="POST" action="/denuncias">
            @csrf
            <input type="hidden" name="tipo" id="dsTipo">
            <input type="hidden" name="referencia_id" id="dsRefId">
            @foreach(['Conteúdo impróprio','Discurso de ódio','Spam','Informação falsa','Outro'] as $motivo)
            <label class="denuncia-opcao">
                <input type="radio" name="motivo" value="{{ $motivo }}" required> {{ $motivo }}
            </label>
            @endforeach
            <textarea name="descricao" placeholder="Detalhes adicionais (opcional)..." rows="3"
                style="width:100%;padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.9rem;background:var(--surface);color:var(--text);resize:none;margin:1rem 0;"></textarea>
            <div style="display:flex;gap:.6rem;justify-content:flex-end;">
                <button type="button" onclick="fecharDenunciaShow()"
                    style="padding:.5rem 1.2rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;color:var(--muted);font-weight:600;cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit"
                    style="padding:.5rem 1.4rem;border-radius:8px;border:none;background:#ef4444;color:#fff;font-weight:700;cursor:pointer;">
                    Enviar denúncia
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Lightbox ──
function abrirImagem(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.add('open');
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.getElementById('lightbox').classList.remove('open');
        fecharDenunciaShow();
    }
});

// ── Like ──
function toggleLike(btn, postId) {
    fetch(`/like/${postId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        btn.querySelector('.like-icon').textContent  = data.liked ? '❤️' : '🤍';
        btn.querySelector('.like-count').textContent = data.total;
        data.liked ? btn.classList.add('liked') : btn.classList.remove('liked');
    });
}

// ── Carrossel ──
const carouselIndex = {};

function moverCarrossel(id, dir) {
    const track = document.getElementById('track-' + id);
    const total = track.children.length;
    carouselIndex[id] = ((carouselIndex[id] ?? 0) + dir + total) % total;
    atualizarCarrossel(id);
}

function irParaSlide(id, idx) {
    carouselIndex[id] = idx;
    atualizarCarrossel(id);
}

function atualizarCarrossel(id) {
    const idx    = carouselIndex[id] ?? 0;
    const track  = document.getElementById('track-' + id);
    const dots   = document.querySelectorAll('#dots-'   + id + ' .carousel-dot');
    const thumbs = document.querySelectorAll('#thumbs-' + id + ' .carousel-thumb');
    const counter = document.getElementById('cc-current-' + id);

    track.style.transform = `translateX(-${idx * 100}%)`;
    if (counter) counter.textContent = idx + 1;
    dots.forEach((d, i)   => d.classList.toggle('active', i === idx));
    thumbs.forEach((t, i) => t.classList.toggle('active', i === idx));
    if (thumbs[idx]) thumbs[idx].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
}

// ── Swipe touch no carrossel ──
(function() {
    const wrap = document.getElementById('carousel-{{ $post->id }}');
    if (!wrap) return;
    let startX = 0;
    wrap.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
    wrap.addEventListener('touchend', e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) moverCarrossel({{ $post->id }}, diff > 0 ? 1 : -1);
    });
})();

// ── Denúncia ──
function abrirDenunciaPost() {
    document.getElementById('dsTipo').value  = 'post';
    document.getElementById('dsRefId').value = {{ $post->id }};
    document.getElementById('modalDenunciaShow').classList.add('open');
}
function abrirDenunciaComentario(id) {
    document.getElementById('dsTipo').value  = 'comentario';
    document.getElementById('dsRefId').value = id;
    document.getElementById('modalDenunciaShow').classList.add('open');
}
function fecharDenunciaShow() {
    document.getElementById('modalDenunciaShow').classList.remove('open');
    document.getElementById('formDenunciaShow').reset();
}
document.getElementById('modalDenunciaShow').addEventListener('click', function(e) {
    if (e.target === this) fecharDenunciaShow();
});
</script>

@endsection

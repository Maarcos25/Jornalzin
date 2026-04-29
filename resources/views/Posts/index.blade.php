@extends('layouts.site')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<style>
:root {
    --ink:      var(--text);
    --ink-2:    var(--muted);
    --accent:   #6366f1;
    --accent-d: #4f46e5;
    --accent-lt:#eef2ff;
    --success:  #10b981;
    --warn:     #f59e0b;
    --radius:   16px;
}
*, *::before, *::after { box-sizing: border-box; }
body { font-family: 'DM Sans', sans-serif; }
.feed-wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 1rem 4rem; }
.feed-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.2rem; }
.feed-title { font-family: 'Playfair Display', serif; font-size: 1.9rem; color: var(--text); letter-spacing: -.02em; }
.btn-new {
    display: inline-flex; align-items: center; gap: .4rem;
    background: var(--accent); color: #fff; font-weight: 600; font-size: .875rem;
    padding: .55rem 1.1rem; border-radius: 40px; text-decoration: none;
    transition: all .2s; box-shadow: 0 4px 14px rgba(99,102,241,.35);
}
.btn-new:hover { background: var(--accent-d); transform: translateY(-1px); color: #fff; }
.search-bar { position: relative; margin-bottom: 1.4rem; }
.search-bar input {
    width: 100%; padding: .75rem 3.5rem .75rem 1.2rem;
    border: 2px solid var(--border); border-radius: 50px;
    font-size: .95rem; font-family: inherit;
    background: var(--surface); color: var(--text);
    outline: none; transition: border-color .2s, box-shadow .2s;
}
.search-bar input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
.search-bar input::placeholder { color: var(--muted); }
.search-bar button {
    position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
    background: var(--accent); color: #fff; border: none;
    border-radius: 50px; padding: .45rem 1rem;
    font-size: .88rem; font-weight: 600; cursor: pointer; transition: background .2s;
}
.search-bar button:hover { background: var(--accent-d); }
.search-bar .clear-btn {
    position: absolute; right: 90px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: var(--muted);
    font-size: 1rem; cursor: pointer; padding: .2rem .4rem; transition: color .15s;
}
.search-bar .clear-btn:hover { color: var(--danger); }
.search-info { font-size: .88rem; color: var(--muted); margin-bottom: 1rem; display: flex; align-items: center; gap: .4rem; }
.alert-success-box {
    background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;
    border-radius: 12px; padding: .85rem 1.1rem; font-size: .9rem;
    font-weight: 500; margin-bottom: 1.4rem; display: flex; align-items: center; gap: .5rem;
}
html.dark .alert-success-box { background: #052e16; border-color: #166534; color: #86efac; }
.posts-grid { columns: 2; column-gap: 1.25rem; }
.post-card {
    background: var(--surface); border-radius: var(--radius);
    border: 1px solid var(--border); overflow: hidden;
    transition: box-shadow .25s, transform .25s;
    break-inside: avoid; margin-bottom: 1.25rem;
    display: inline-block; width: 100%; cursor: pointer;
}
.post-card:hover { box-shadow: 0 8px 32px rgba(15,23,42,.1); transform: translateY(-2px); }
html.dark .post-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,.4); }
.card-head {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 1.1rem 1.25rem .6rem; gap: .75rem;
}
.card-meta { display: flex; align-items: center; gap: .65rem; flex: 1; min-width: 0; }
.tipo-pill { flex-shrink: 0; font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; padding: .22rem .65rem; border-radius: 20px; }
.tipo-texto   { background: #dbeafe; color: #1d4ed8; }
.tipo-imagem  { background: #fce7f3; color: #9d174d; }
.tipo-video   { background: #dcfce7; color: #166534; }
.tipo-enquete { background: #fef3c7; color: #92400e; }
html.dark .tipo-texto   { background: #1e3a5f; color: #93c5fd; }
html.dark .tipo-imagem  { background: #4a1942; color: #f9a8d4; }
html.dark .tipo-video   { background: #14532d; color: #86efac; }
html.dark .tipo-enquete { background: #451a03; color: #fcd34d; }
.card-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--text); line-height: 1.3; flex: 1; min-width: 0; }
.card-date { font-size: .76rem; color: var(--muted); white-space: nowrap; flex-shrink: 0; padding-top: .15rem; }
.card-author {
    display: flex; align-items: center; gap: .5rem;
    padding: .5rem 1.25rem; border-bottom: 1px solid var(--border);
    background: var(--surface-2);
}
.author-avatar {
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--accent); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 800; flex-shrink: 0; overflow: hidden;
}
.author-avatar img { width: 100%; height: 100%; object-fit: cover; }
.author-name { font-size: .82rem; font-weight: 600; color: var(--text); }
.author-date { font-size: .75rem; color: var(--muted); margin-left: auto; }
.status-badge { display: inline-flex; align-items: center; gap: .3rem; padding: .22rem .7rem; border-radius: 20px; font-size: .72rem; font-weight: 700; }
.status-aprovado { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.status-pendente { background: #fefce8; color: #92400e; border: 1px solid #fde68a; }
html.dark .status-aprovado { background: #052e16; color: #86efac; border-color: #166534; }
html.dark .status-pendente { background: #451a03; color: #fcd34d; border-color: #92400e; }
.card-actions {
    display: flex; gap: .4rem; padding: .6rem 1.25rem;
    border-top: 1px solid var(--border); background: var(--surface-2);
    flex-wrap: wrap; align-items: center;
}
.btn-action {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .78rem; font-weight: 600; padding: .35rem .75rem;
    border-radius: 8px; border: none; cursor: pointer;
    text-decoration: none; transition: all .18s;
}
.btn-edit     { background: #fef9c3; color: #713f12; border: 1.5px solid #fde68a; }
.btn-edit:hover { background: #fde68a; }
.btn-del      { background: #fef2f2; color: var(--danger); border: 1.5px solid #fecaca; }
.btn-del:hover { background: var(--danger); color: #fff; border-color: var(--danger); }
.btn-aprovar  { background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; }
.btn-aprovar:hover { background: #22c55e; color: #fff; border-color: #22c55e; }
.btn-rejeitar { background: #fef2f2; color: var(--danger); border: 1.5px solid #fecaca; }
.btn-rejeitar:hover { background: var(--danger); color: #fff; border-color: var(--danger); }
html.dark .btn-edit    { background: #2d1f02; color: #fcd34d; border-color: #78350f; }
html.dark .btn-edit:hover { background: #78350f; color: #fff; }
html.dark .btn-del     { background: #2d0a0a; color: #fca5a5; border-color: #7f1d1d; }
html.dark .btn-del:hover { background: var(--danger); color: #fff; }
html.dark .btn-aprovar { background: #052e16; color: #86efac; border-color: #166534; }
html.dark .btn-aprovar:hover { background: #22c55e; color: #fff; border-color: #22c55e; }
.card-divider { height: 1px; background: var(--border); margin: 0 1.25rem; }
.card-body { padding: 1rem 1.25rem 1.25rem; }
.post-text { color: var(--muted); font-size: .95rem; line-height: 1.65; }
.video-wrap { border-radius: 12px; overflow: hidden; background: #000; margin-top: .2rem; }
.video-wrap iframe, .video-wrap video { width: 100%; display: block; aspect-ratio: 16/9; }
.poll-wrap { margin-top: .2rem; }
.poll-label { font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: .75rem; }
.result-row { display: flex; flex-direction: column; gap: .3rem; margin-bottom: .5rem; }
.result-label { display: flex; justify-content: space-between; font-size: .88rem; font-weight: 500; color: var(--text); }
.result-bar-bg { background: var(--surface-2); border-radius: 99px; height: 8px; overflow: hidden; border: 1px solid var(--border); }
.result-bar { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--accent), var(--accent-d)); transition: width 1s; }
.result-bar.winner { background: linear-gradient(90deg, var(--success), #059669); }
.poll-total { font-size: .75rem; color: var(--muted); margin-top: .4rem; }

/* Lightbox */
#imgModal {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.92); justify-content: center; align-items: center;
    z-index: 99999; cursor: zoom-out; backdrop-filter: blur(6px);
}
#imgModal.open { display: flex; }
#imgModal img { max-width: 92vw; max-height: 92vh; border-radius: 12px; box-shadow: 0 24px 80px rgba(0,0,0,.5); object-fit: contain; }

.empty-state { text-align: center; padding: 4rem 1rem; color: var(--muted); }
.empty-state .icon { font-size: 3rem; margin-bottom: 1rem; }
@media(max-width: 700px) { .posts-grid { columns: 1; } }
</style>
@endpush

@section('conteudo')
<div class="feed-wrap">

    <div class="feed-header">
        <h1 class="feed-title">📰 Postagens</h1>
        <a href="{{ route('posts.create') }}" class="btn-new">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Novo Post
        </a>
    </div>

    <form method="GET" action="{{ route('posts.index') }}" class="search-bar">
        <input type="text" name="pesquisa"
               placeholder="Pesquisar por título, texto ou tipo..."
               value="{{ request('pesquisa') }}" autocomplete="off">
        @if(request('pesquisa'))
            <a href="{{ route('posts.index') }}" class="clear-btn" title="Limpar">✕</a>
        @endif
        <button type="submit">🔍 Buscar</button>
    </form>

    @if(request('pesquisa'))
        <div class="search-info">
            🔎 Resultados para <strong>"{{ request('pesquisa') }}"</strong>
            — {{ $posts->total() }} post(s) encontrado(s)
        </div>
    @endif

    @if(session('success'))
        <div class="alert-success-box">✅ {{ session('success') }}</div>
    @endif

    @if($posts->isEmpty())
        <div class="empty-state">
            <div class="icon">{{ request('pesquisa') ? '🔍' : '📭' }}</div>
            <p>{{ request('pesquisa') ? 'Nenhum post encontrado.' : 'Nenhum post ainda. Que tal criar o primeiro?' }}</p>
        </div>
    @endif

    <div class="posts-grid">
    @foreach($posts as $post)
    @php
        $votos = []; $totalVotos = 0;
        if ($post->tipo === 'enquete') {
            $votos = $post->votos()->get()->groupBy('opcao')->map->count()->toArray();
            $totalVotos = array_sum($votos);
        }
        $maxVotos = $totalVotos > 0 ? max($votos) : 0;
        $isAdmin  = auth()->user()->tipo === 'administrador';
    @endphp

    <div class="post-card" onclick="window.location='{{ route('posts.show', $post->id) }}'">

        <div class="card-head">
            <div class="card-meta">
                @php
                    $pilltypes = ['texto'=>'tipo-texto','imagem'=>'tipo-imagem','video'=>'tipo-video','enquete'=>'tipo-enquete'];
                    $pillicons = ['texto'=>'✍️','imagem'=>'🖼️','video'=>'🎬','enquete'=>'📊'];
                @endphp
                <span class="tipo-pill {{ $pilltypes[$post->tipo] ?? '' }}">
                    {{ $pillicons[$post->tipo] ?? '' }} {{ ucfirst($post->tipo) }}
                </span>
                <span class="card-title">{{ $post->titulo }}</span>
            </div>
            @if($post->data)
                <span class="card-date">{{ \Carbon\Carbon::parse($post->data)->format('d/m/Y') }}</span>
            @endif
        </div>

        <div class="card-author" onclick="event.stopPropagation()">
            <div class="author-avatar">
                @if($post->usuario && $post->usuario->avatar)
                    <img src="{{ asset('storage/' . $post->usuario->avatar) }}" alt="">
                @else
                    {{ strtoupper(substr($post->usuario->nome ?? 'U', 0, 1)) }}
                @endif
            </div>
            @if($post->usuario)
                <a href="{{ route('users.perfil', $post->usuario->id) }}"
                   style="text-decoration:none;color:inherit;font-weight:600;font-size:.82rem;"
                   onclick="event.stopPropagation()">
                    {{ $post->usuario->nome }}
                </a>
            @else
                <span class="author-name">Desconhecido</span>
            @endif
            <span class="status-badge {{ $post->aprovado ? 'status-aprovado' : 'status-pendente' }}">
                {{ $post->aprovado ? '✅ Publicado' : '⏳ Pendente' }}
            </span>
            <span class="author-date">{{ $post->created_at?->format('d/m/Y H:i') }}</span>
        </div>

        <div class="card-divider"></div>

        <div class="card-body">

            @if($post->tipo === 'texto')
                <p class="post-text">{{ Str::limit($post->texto, 200) }}</p>
            @endif

            @if($post->tipo === 'imagem')
                @php
                    $imgs = $post->imagens->count()
                        ? $post->imagens->map(fn($i) => Storage::url($i->caminho))->toArray()
                        : ($post->imagem ? [str_starts_with($post->imagem,'/storage/') ? asset($post->imagem) : Storage::url($post->imagem)] : []);
                    $count = count($imgs);
                    $cid = 'carousel-idx-'.$post->id;
                @endphp
                @if($count === 1)
                    <div style="border-radius:12px;overflow:hidden;aspect-ratio:16/9;" onclick="event.stopPropagation()">
                        <img src="{{ $imgs[0] }}"
                             style="width:100%;height:100%;object-fit:cover;display:block;cursor:zoom-in;"
                             onclick="abrirImagem('{{ $imgs[0] }}')">
                    </div>
                @elseif($count > 1)
                    <div onclick="event.stopPropagation()">
                        <div style="position:relative;border-radius:12px;overflow:hidden;aspect-ratio:16/9;background:#000;" id="{{ $cid }}">
                            <div class="idx-track" style="display:flex;height:100%;transition:transform .35s ease;">
                                @foreach($imgs as $src)
                                    <div style="min-width:100%;height:100%;">
                                        <img src="{{ $src }}"
                                             style="width:100%;height:100%;object-fit:cover;display:block;cursor:zoom-in;"
                                             onclick="abrirImagem('{{ $src }}')">
                                    </div>
                                @endforeach
                            </div>
                            <button onclick="moverIdx('{{ $cid }}',-1)"
                                    style="position:absolute;left:8px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,.5);color:#fff;border:none;border-radius:50%;width:32px;height:32px;font-size:1.1rem;cursor:pointer;z-index:2;">&#8249;</button>
                            <button onclick="moverIdx('{{ $cid }}',1)"
                                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,.5);color:#fff;border:none;border-radius:50%;width:32px;height:32px;font-size:1.1rem;cursor:pointer;z-index:2;">&#8250;</button>
                            <div style="position:absolute;top:8px;right:10px;background:rgba(0,0,0,.5);color:#fff;font-size:.72rem;font-weight:700;padding:2px 8px;border-radius:20px;z-index:2;">
                                <span class="idx-current">1</span>/{{ $count }}
                            </div>
                            <div style="position:absolute;bottom:8px;left:50%;transform:translateX(-50%);display:flex;gap:5px;z-index:2;">
                                @foreach($imgs as $i => $src)
                                    <span onclick="irIdxSlide('{{ $cid }}',{{ $i }})"
                                          class="idx-dot"
                                          style="width:7px;height:7px;border-radius:50%;background:{{ $i===0?'#fff':'rgba(255,255,255,.45)' }};cursor:pointer;transition:background .2s;display:inline-block;"></span>
                                @endforeach
                            </div>
                        </div>
                        <div style="display:flex;gap:5px;margin-top:5px;overflow-x:auto;padding-bottom:2px;">
                            @foreach($imgs as $i => $src)
                                <div onclick="irIdxSlide('{{ $cid }}',{{ $i }})"
                                     class="idx-thumb"
                                     data-carousel="{{ $cid }}"
                                     style="flex-shrink:0;width:52px;height:52px;border-radius:6px;overflow:hidden;border:2px solid {{ $i===0?'#6366f1':'transparent' }};cursor:pointer;transition:border-color .2s;">
                                    <img src="{{ $src }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p style="color:var(--muted);font-size:.88rem;"><em>Sem imagem</em></p>
                @endif
            @endif

            @if($post->tipo === 'video' && $post->video)
                @php
                    $isYt = str_contains($post->video,'youtube') || str_contains($post->video,'youtu.be');
                    $isVimeo = str_contains($post->video,'vimeo');
                @endphp
                <div class="video-wrap" onclick="event.stopPropagation()">
                    @if($isYt)
                        @php preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video, $m); @endphp
                        <iframe src="https://www.youtube.com/embed/{{ $m[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                    @elseif($isVimeo)
                        @php preg_match('/vimeo\.com\/(\d+)/', $post->video, $m); @endphp
                        <iframe src="https://player.vimeo.com/video/{{ $m[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        @php $vsrc = str_starts_with($post->video,'/storage/') ? asset($post->video) : Storage::url($post->video); @endphp
                        <video controls><source src="{{ $vsrc }}"></video>
                    @endif
                </div>
            @endif

            @if($post->tipo === 'enquete')
                <div class="poll-wrap">
                    <p class="poll-label">📊 Enquete · {{ $totalVotos }} {{ $totalVotos === 1 ? 'voto' : 'votos' }}</p>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        @foreach(range(1,8) as $i)
                            @php $opcao = $post->{'opcao'.$i}; if(!$opcao) continue; @endphp
                            @php $qtd = $votos[$i] ?? 0; $pct = $totalVotos > 0 ? round($qtd/$totalVotos*100) : 0; $win = $qtd === $maxVotos && $maxVotos > 0; @endphp
                            <div class="result-row">
                                <div class="result-label">
                                    <span>{{ $opcao }} @if($win) 🏆 @endif</span>
                                    <span>{{ $pct }}% ({{ $qtd }})</span>
                                </div>
                                <div class="result-bar-bg">
                                    <div class="result-bar {{ $win ? 'winner' : '' }}" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="poll-total">Total: {{ $totalVotos }} voto(s)</p>
                </div>
            @endif

        </div>

        <div class="card-actions" onclick="event.stopPropagation()">
            <a href="{{ route('posts.edit', $post->id) }}" class="btn-action btn-edit">✏️ Editar</a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                  onsubmit="return confirm('Excluir este post permanentemente?')" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn-action btn-del">🗑️ Excluir</button>
            </form>
            @if($isAdmin && !$post->aprovado)
                <form action="{{ route('posts.aprovar', $post->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn-action btn-aprovar">✅ Aprovar</button>
                </form>
                <form action="{{ route('posts.rejeitar', $post->id) }}" method="POST"
                      onsubmit="return confirm('Rejeitar e excluir este post?')" style="display:inline">
                    @csrf
                    <button class="btn-action btn-rejeitar">❌ Rejeitar</button>
                </form>
            @endif
        </div>
    </div>
    @endforeach
    </div>

    @if($posts->hasPages())
        <div style="margin-top:1.5rem;">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif

</div>

{{-- Lightbox --}}
<div id="imgModal" onclick="fecharLightbox()">
    <img id="imgModalSrc" src="" alt="imagem ampliada">
</div>

<script>
function abrirImagem(src) {
    document.getElementById('imgModalSrc').src = src;
    document.getElementById('imgModal').classList.add('open');
}
function fecharLightbox() {
    document.getElementById('imgModal').classList.remove('open');
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') fecharLightbox();
});

/* ── Carrossel posts/index ── */
const _idxState = {};

function moverIdx(id, dir) {
    const el    = document.getElementById(id);
    const track = el.querySelector('.idx-track');
    const total = track.children.length;
    _idxState[id] = ((_idxState[id] ?? 0) + dir + total) % total;
    _renderIdx(id, el, track);
}

function irIdxSlide(id, idx) {
    const el    = document.getElementById(id);
    const track = el.querySelector('.idx-track');
    _idxState[id] = idx;
    _renderIdx(id, el, track);
}

function _renderIdx(id, el, track) {
    const idx = _idxState[id] ?? 0;
    track.style.transform = `translateX(-${idx * 100}%)`;

    const cur = el.querySelector('.idx-current');
    if (cur) cur.textContent = idx + 1;

    el.querySelectorAll('.idx-dot').forEach((d, i) => {
        d.style.background = i === idx ? '#fff' : 'rgba(255,255,255,.45)';
    });

    const wrap = el.parentElement;
    wrap.querySelectorAll('.idx-thumb').forEach((t, i) => {
        t.style.borderColor = i === idx ? '#6366f1' : 'transparent';
    });
}
</script>
@endsection

@extends('layouts.site')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<style>
:root {
    --ink:       #0f172a;
    --ink-2:     #334155;
    --muted:     #64748b;
    --border:    #e2e8f0;
    --surface:   #ffffff;
    --bg:        #f1f5f9;
    --accent:    #6366f1;
    --accent-d:  #4f46e5;
    --accent-lt: #eef2ff;
    --success:   #10b981;
    --danger:    #ef4444;
    --warn:      #f59e0b;
    --radius:    16px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--ink);
}

.feed-wrap {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2rem 1rem 4rem;
}

.feed-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.6rem;
}
.feed-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    color: var(--ink);
    letter-spacing: -.02em;
}
.btn-new {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: var(--accent);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    font-size: .875rem;
    padding: .55rem 1.1rem;
    border-radius: 40px;
    text-decoration: none;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 4px 14px rgba(99,102,241,.35);
}
.btn-new:hover {
    background: var(--accent-d);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(99,102,241,.45);
}

.alert-success {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    border-radius: 12px;
    padding: .85rem 1.1rem;
    font-size: .9rem;
    font-weight: 500;
    margin-bottom: 1.4rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

/* ── Grid masonry ── */
.posts-grid {
    columns: 2;
    column-gap: 1.25rem;
}

.post-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
    transition: box-shadow .25s, transform .25s;
    animation: fadeUp .4s ease both;
    break-inside: avoid;
    margin-bottom: 1.25rem;
    display: inline-block;
    width: 100%;
}

.post-card:hover {
    box-shadow: 0 8px 32px rgba(15,23,42,.1);
    transform: translateY(-2px);
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.post-card:nth-child(1) { animation-delay: .05s; }
.post-card:nth-child(2) { animation-delay: .10s; }
.post-card:nth-child(3) { animation-delay: .15s; }
.post-card:nth-child(4) { animation-delay: .20s; }
.post-card:nth-child(5) { animation-delay: .25s; }

.card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1.1rem 1.25rem .6rem;
    gap: .75rem;
}
.card-meta {
    display: flex;
    align-items: center;
    gap: .65rem;
    flex: 1;
    min-width: 0;
}
.tipo-pill {
    flex-shrink: 0;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    padding: .22rem .65rem;
    border-radius: 20px;
}
.tipo-texto  { background: #dbeafe; color: #1d4ed8; }
.tipo-imagem { background: #fce7f3; color: #9d174d; }
.tipo-video  { background: #dcfce7; color: #166534; }
.tipo-enquete{ background: #fef3c7; color: #92400e; }

.card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--ink);
    line-height: 1.3;
    flex: 1;
    min-width: 0;
}

.card-date {
    font-size: .76rem;
    color: var(--muted);
    white-space: nowrap;
    flex-shrink: 0;
    padding-top: .15rem;
}

.card-actions {
    display: flex;
    gap: .4rem;
    padding: 0 1.25rem .7rem;
}
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .78rem;
    font-weight: 600;
    padding: .35rem .75rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all .18s;
}
.btn-edit  { background: #fef9c3; color: #713f12; border: 1.5px solid #fde68a; }
.btn-edit:hover  { background: #fde68a; }
.btn-del   { background: #fef2f2; color: var(--danger); border: 1.5px solid #fecaca; }
.btn-del:hover   { background: var(--danger); color: #fff; border-color: var(--danger); }

.card-divider { height: 1px; background: var(--border); margin: 0 1.25rem; }

.card-body { padding: 1rem 1.25rem 1.25rem; }

.post-text {
    color: var(--ink-2);
    font-size: .95rem;
    line-height: 1.65;
}

.img-grid {
    display: grid;
    gap: 4px;
    border-radius: 12px;
    overflow: hidden;
    margin-top: .2rem;
}
.img-grid.one   { grid-template-columns: 1fr; }
.img-grid.two   { grid-template-columns: 1fr 1fr; }
.img-grid.three { grid-template-columns: 1fr 1fr; }
.img-grid.three .img-cell:first-child { grid-column: 1 / -1; }
.img-grid.many  { grid-template-columns: repeat(3, 1fr); }

.img-cell {
    aspect-ratio: 1;
    overflow: hidden;
    cursor: pointer;
    position: relative;
}
.img-cell img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .3s;
}
.img-cell:hover img { transform: scale(1.04); }
.img-cell .more-overlay {
    position: absolute; inset: 0;
    background: rgba(15,23,42,.6);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.4rem; font-weight: 700;
}

.video-wrap {
    border-radius: 12px;
    overflow: hidden;
    background: #000;
    margin-top: .2rem;
}
.video-wrap iframe,
.video-wrap video {
    width: 100%; display: block;
    aspect-ratio: 16/9;
}

.poll-wrap { margin-top: .2rem; }
.poll-label {
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--muted);
    margin-bottom: .75rem;
}
.poll-options { display: flex; flex-direction: column; gap: .5rem; }
.poll-option { position: relative; cursor: pointer; }
.poll-option input[type=radio] { display: none; }
.poll-option label {
    display: flex;
    align-items: center;
    gap: .7rem;
    padding: .65rem .9rem;
    border: 2px solid var(--border);
    border-radius: 10px;
    cursor: pointer;
    font-size: .93rem;
    font-weight: 500;
    color: var(--ink-2);
    transition: border-color .18s, background .18s;
    position: relative;
    overflow: hidden;
    user-select: none;
}
.poll-option label::before {
    content: '';
    width: 18px; height: 18px;
    border-radius: 50%;
    border: 2px solid var(--border);
    flex-shrink: 0;
    transition: border-color .18s, background .18s;
}
.poll-option input:checked + label { border-color: var(--accent); background: var(--accent-lt); color: var(--accent-d); }
.poll-option input:checked + label::before { border-color: var(--accent); background: var(--accent); }
.poll-option label:hover { border-color: var(--accent); background: var(--accent-lt); }

.btn-vote {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    margin-top: .85rem;
    padding: .55rem 1.3rem;
    border-radius: 40px;
    border: none;
    background: var(--accent);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-weight: 700;
    font-size: .88rem;
    cursor: pointer;
    transition: background .2s, transform .15s;
    box-shadow: 0 4px 12px rgba(99,102,241,.3);
}
.btn-vote:hover { background: var(--accent-d); transform: translateY(-1px); }

.poll-results { display: flex; flex-direction: column; gap: .5rem; }
.result-row { display: flex; flex-direction: column; gap: .3rem; }
.result-label {
    display: flex;
    justify-content: space-between;
    font-size: .88rem;
    font-weight: 500;
    color: var(--ink-2);
}
.result-bar-bg {
    background: var(--bg);
    border-radius: 99px;
    height: 8px;
    overflow: hidden;
}
.result-bar {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, var(--accent), var(--accent-d));
    transition: width 1s cubic-bezier(.4,0,.2,1);
}
.result-bar.winner { background: linear-gradient(90deg, var(--success), #059669); }
.poll-total { font-size: .75rem; color: var(--muted); margin-top: .4rem; }

#imgModal {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.88);
    justify-content: center;
    align-items: center;
    z-index: 9999;
    cursor: zoom-out;
    backdrop-filter: blur(6px);
}
#imgModal img {
    max-width: 92%; max-height: 92%;
    border-radius: 12px;
    box-shadow: 0 24px 80px rgba(0,0,0,.5);
    animation: popIn .2s ease;
}
@keyframes popIn {
    from { transform: scale(.92); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}

.empty-state {
    text-align: center;
    padding: 4rem 1rem;
    color: var(--muted);
}
.empty-state .icon { font-size: 3rem; margin-bottom: 1rem; }
.empty-state p { font-size: .95rem; }

@media(max-width: 700px) {
    .posts-grid { columns: 1; }
    .feed-title { font-size: 1.5rem; }
    .img-grid.many { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush

@section('conteudo')
<div class="feed-wrap">

    <div class="feed-header">
        <h1 class="feed-title">📰 Posts</h1>
        <a href="{{ route('posts.create') }}" class="btn-new">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Novo Post
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    @if($posts->isEmpty())
        <div class="empty-state">
            <div class="icon">📭</div>
            <p>Nenhum post ainda. Que tal criar o primeiro?</p>
        </div>
    @endif

    <div class="posts-grid">
    @foreach($posts as $post)
    @php
        $votos      = [];
        $totalVotos = 0;
        $jaVotou    = false;
        if ($post->tipo === 'enquete') {
            $votos      = $post->votos()->get()->groupBy('opcao')->map->count()->toArray();
            $totalVotos = array_sum($votos);
            $jaVotou    = $post->votos()->where('id_usuario', auth()->id())->exists();
        }
        $maxVotos = $totalVotos > 0 ? max($votos) : 0;
    @endphp
    <div class="post-card">

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

        @if(!$post->aprovado)
            <span class="badge bg-warning text-dark me-1">⏳ Pendente</span>
            <form action="{{ route('posts.aprovar', $post->id) }}" method="POST" style="display:inline">
                @csrf
                <button class="btn btn-success btn-sm">✅ Aprovar</button>
            </form>
            <form action="{{ route('posts.rejeitar', $post->id) }}" method="POST" style="display:inline"
                  onsubmit="return confirm('Rejeitar e excluir este post?')">
                @csrf
                <button class="btn btn-danger btn-sm">❌ Rejeitar</button>
            </form>
        @endif

        <div class="card-divider"></div>

        <div class="card-body">

            @if($post->tipo === 'texto')
                <p class="post-text">{{ $post->texto }}</p>
            @endif

            @if($post->tipo === 'imagem')
                @php
                    $imgs = $post->imagens->count()
                        ? $post->imagens->map(fn($i) => Storage::url($i->caminho))->toArray()
                        : ($post->imagem ? [str_starts_with($post->imagem,'/storage/') ? asset($post->imagem) : Storage::url($post->imagem)] : []);
                    $count = count($imgs);
                    $gridClass = match(true) {
                        $count === 1 => 'one',
                        $count === 2 => 'two',
                        $count === 3 => 'three',
                        default      => 'many',
                    };
                    $show = min($count, 9);
                @endphp
                @if($count)
                <div class="img-grid {{ $gridClass }}">
                    @foreach(array_slice($imgs, 0, $show) as $idx => $src)
                        @php $isLast = ($idx === $show - 1) && $count > $show; @endphp
                        <div class="img-cell" onclick="abrirImagem('{{ $src }}')">
                            <img src="{{ $src }}" alt="imagem {{ $idx + 1 }}">
                            @if($isLast)
                                <div class="more-overlay">+{{ $count - $show }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @else
                    <p style="color:var(--muted);font-size:.88rem;"><em>Sem imagem</em></p>
                @endif
            @endif

            @if($post->tipo === 'video' && $post->video)
                @php
                    $isYt    = str_contains($post->video,'youtube') || str_contains($post->video,'youtu.be');
                    $isVimeo = str_contains($post->video,'vimeo');
                @endphp
                <div class="video-wrap">
                    @if($isYt)
                        @php preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video, $m); @endphp
                        <iframe src="https://www.youtube.com/embed/{{ $m[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                    @elseif($isVimeo)
                        @php preg_match('/vimeo\.com\/(\d+)/', $post->video, $m); @endphp
                        <iframe src="https://player.vimeo.com/video/{{ $m[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        @php $vsrc = str_starts_with($post->video,'/storage/') ? asset($post->video) : Storage::url($post->video); @endphp
                        <video controls><source src="{{ $vsrc }}">Seu navegador não suporta vídeo HTML5.</video>
                    @endif
                </div>
            @endif

            @if($post->tipo === 'enquete')
                <div class="poll-wrap">
                    <p class="poll-label">📊 Enquete · {{ $totalVotos }} {{ $totalVotos === 1 ? 'voto' : 'votos' }}</p>

                    @if($jaVotou || $totalVotos > 0 && !auth()->check())
                        <div class="poll-results">
                            @foreach(range(1,8) as $i)
                                @php $opcao = $post->{'opcao'.$i}; if(!$opcao) continue; @endphp
                                @php
                                    $qtd  = $votos[$i] ?? 0;
                                    $pct  = $totalVotos > 0 ? round($qtd / $totalVotos * 100) : 0;
                                    $win  = $qtd === $maxVotos && $maxVotos > 0;
                                @endphp
                                <div class="result-row">
                                    <div class="result-label">
                                        <span>{{ $opcao }} @if($win) 🏆 @endif</span>
                                        <span>{{ $pct }}% ({{ $qtd }})</span>
                                    </div>
                                    <div class="result-bar-bg">
                                        <div class="result-bar {{ $win ? 'winner' : '' }}"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="poll-total">Você já votou nesta enquete.</p>
                    @else
                        <form method="POST" action="{{ route('posts.votar', $post->id) }}">
                            @csrf
                            <div class="poll-options">
                                @foreach(range(1,8) as $i)
                                    @php $opcao = $post->{'opcao'.$i}; if(!$opcao) continue; @endphp
                                    <div class="poll-option">
                                        <input type="radio" name="opcao" value="{{ $i }}" id="op_{{ $post->id }}_{{ $i }}" required>
                                        <label for="op_{{ $post->id }}_{{ $i }}">{{ $opcao }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn-vote">🗳️ Votar</button>
                        </form>
                    @endif
                </div>
            @endif

        </div>
    </div>
    @endforeach
    </div>

</div>

<div id="imgModal" onclick="this.style.display='none'">
    <img id="imgModalSrc" src="" alt="imagem ampliada">
</div>

<script>
function abrirImagem(src) {
    document.getElementById('imgModalSrc').src = src;
    document.getElementById('imgModal').style.display = 'flex';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.getElementById('imgModal').style.display = 'none';
});
</script>
@endsection

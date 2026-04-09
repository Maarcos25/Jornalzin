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

    body { background: #f1f5f9 !important; font-family: 'Segoe UI', sans-serif; }

    .show-wrap { max-width: 720px; margin: 0 auto; padding: 2rem 1.2rem 4rem; }

    /* ── Card do post ── */
    .post-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.4rem;
    }

    /* ── Mídia ── */
    .post-media img {
        width: 100%; display: block;
        max-height: 520px; object-fit: cover;
        cursor: zoom-in;
    }
    .img-grid { display: grid; gap: 2px; }
    .img-grid.two  { grid-template-columns: 1fr 1fr; }
    .img-grid.many { grid-template-columns: repeat(3, 1fr); }
    .img-cell { position: relative; aspect-ratio: 1; overflow: hidden; cursor: zoom-in; }
    .img-cell img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .3s; }
    .img-cell:hover img { transform: scale(1.04); }
    .img-more { position: absolute; inset: 0; background: rgba(0,0,0,.55); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; }

    .video-wrap { background: #000; }
    .video-wrap iframe, .video-wrap video { width: 100%; display: block; aspect-ratio: 16/9; }

    /* ── Corpo ── */
    .post-body { padding: 1.1rem 1.3rem .5rem; }
    .post-title { font-size: 1.35rem; font-weight: 800; color: var(--text); margin: 0 0 .5rem; line-height: 1.3; }
    .post-tipo {
        display: inline-block; font-size: .72rem; font-weight: 700;
        letter-spacing: .04em; text-transform: uppercase;
        padding: .22rem .7rem; border-radius: 20px;
        margin-bottom: .6rem;
    }
    .tipo-texto  { background: #dbeafe; color: #1d4ed8; }
    .tipo-imagem { background: #fce7f3; color: #9d174d; }
    .tipo-video  { background: #dcfce7; color: #166534; }
    .tipo-enquete{ background: #fef3c7; color: #92400e; }

    .post-text { font-size: .97rem; color: var(--muted); line-height: 1.65; margin: 0; }

    /* ── Enquete ── */
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
    .btn-vote { margin-top: .4rem; padding: .5rem 1.2rem; background: var(--brand); color: #fff; border: none; border-radius: 8px; font-size: .92rem; font-weight: 700; cursor: pointer; transition: background .2s; }
    .btn-vote:hover { background: var(--brand-dark); }
    .poll-res-row { margin-bottom: .5rem; }
    .poll-res-label { display: flex; justify-content: space-between; font-size: .88rem; color: var(--text); margin-bottom: .2rem; }
    .poll-res-bg { height: 9px; background: var(--surface-2); border-radius: 99px; overflow: hidden; border: 1px solid var(--border); }
    .poll-res-bar { height: 100%; background: var(--brand); border-radius: 99px; transition: width .5s; }
    .poll-res-bar.win { background: #10b981; }

    /* ── Footer do card ── */
    .post-footer {
        display: flex; align-items: center; padding: .65rem 1.3rem .75rem;
        border-top: 1px solid var(--border); gap: .6rem; flex-wrap: wrap;
    }
    .post-meta { display: flex; align-items: center; gap: .35rem; font-size: .85rem; color: var(--muted); }
    .avatar-mini {
        width: 28px; height: 28px; border-radius: 50%;
        object-fit: cover; border: 2px solid var(--border);
    }
    .avatar-mini-placeholder {
        width: 28px; height: 28px; border-radius: 50%;
        background: var(--brand); color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .72rem; font-weight: 800; flex-shrink: 0;
    }
    .post-actions { margin-left: auto; display: flex; gap: .5rem; }
    .btn-like, .btn-back {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .42rem 1rem; border-radius: 50px;
        border: 1.5px solid var(--border); background: var(--surface-2);
        color: var(--muted); font-size: .9rem; font-weight: 600;
        cursor: pointer; transition: all .18s; text-decoration: none;
    }
    .btn-like:hover { border-color: #f43f5e; background: #fff1f2; color: #f43f5e; }
    .btn-like.liked { border-color: #f43f5e; background: #fff1f2; color: #f43f5e; }
    .btn-back:hover { border-color: var(--brand); background: #eef2ff; color: var(--brand); }

    /* ── Comentários ── */
    .comments-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.2rem;
    }
    .comments-header { padding: 1rem 1.3rem; border-bottom: 1px solid var(--border); font-weight: 800; font-size: 1rem; color: var(--text); }
    .comment-item { display: flex; gap: .7rem; align-items: flex-start; padding: .85rem 1.3rem; border-bottom: 1px solid var(--border); }
    .comment-item:last-child { border-bottom: none; }
    .comment-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--brand); color: #fff; display: flex; align-items: center; justify-content: center; font-size: .82rem; font-weight: 800; flex-shrink: 0; }
    .comment-author { font-size: .88rem; font-weight: 700; color: var(--text); margin-right: .4rem; }
    .comment-date { font-size: .78rem; color: var(--muted); }
    .comment-text { font-size: .92rem; color: var(--muted); margin-top: .15rem; }
    .empty-comments { padding: 1.5rem 1.3rem; color: var(--muted); font-size: .93rem; }

    /* ── Form comentário ── */
    .comment-form-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
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

    /* ── Lightbox ── */
    #lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.88); z-index: 9999; align-items: center; justify-content: center; cursor: zoom-out; }
    #lightbox.open { display: flex; }
    #lightbox img { max-width: 92vw; max-height: 92vh; border-radius: 10px; object-fit: contain; }
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
        $jaVotou    = $post->votos()->where('id_usuario', auth()->id())->exists();
    }
    $maxVotos = $totalVotos > 0 ? max($votos) : 0;

    $jaLikei = $post->likes()->where('user_id', auth()->id())->exists();
    $totalLikes = $post->likes()->count();

    // Imagens
    $imgs = $post->imagens->count()
        ? $post->imagens->map(fn($i) => Storage::url($i->caminho))->toArray()
        : ($post->imagem ? [str_starts_with($post->imagem,'/storage/') ? asset($post->imagem) : Storage::url($post->imagem)] : []);
    $imgCount = count($imgs);
    $gridClass = match(true) { $imgCount===1=>'one', $imgCount===2=>'two', default=>'many' };
@endphp

<div class="show-wrap">

    <div class="post-card">

        {{-- MÍDIA --}}
        @if($post->tipo === 'imagem' && $imgCount)
            @if($imgCount === 1)
                <div class="post-media">
                    <img src="{{ $imgs[0] }}" alt="{{ $post->titulo }}" onclick="abrirImagem('{{ $imgs[0] }}')">
                </div>
            @else
                <div class="img-grid {{ $gridClass }}">
                    @foreach(array_slice($imgs, 0, 9) as $idx => $src)
                        @php $isLast = ($idx === 8) && $imgCount > 9; @endphp
                        <div class="img-cell" onclick="abrirImagem('{{ $src }}')">
                            <img src="{{ $src }}" alt="imagem {{ $idx+1 }}">
                            @if($isLast) <div class="img-more">+{{ $imgCount - 9 }}</div> @endif
                        </div>
                    @endforeach
                </div>
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
                    <video controls><source src="{{ str_starts_with($post->video,'/storage/') ? asset($post->video) : Storage::url($post->video) }}"></video>
                @endif
            </div>
        @endif

        {{-- CORPO --}}
        <div class="post-body">
            @php
                $pilltypes = ['texto'=>'tipo-texto','imagem'=>'tipo-imagem','video'=>'tipo-video','enquete'=>'tipo-enquete'];
                $pillicons = ['texto'=>'✍️','imagem'=>'🖼️','video'=>'🎬','enquete'=>'📊'];
            @endphp
            <span class="post-tipo {{ $pilltypes[$post->tipo] ?? '' }}">
                {{ $pillicons[$post->tipo] ?? '' }} {{ ucfirst($post->tipo) }}
            </span>
            <h1 class="post-title">{{ $post->titulo }}</h1>

            @if($post->tipo === 'texto' && $post->texto)
                <p class="post-text">{{ $post->texto }}</p>
            @endif

            @if($post->tipo === 'enquete')
                <div class="poll-wrap">
                    <p class="poll-label">📊 {{ $totalVotos }} {{ $totalVotos === 1 ? 'voto' : 'votos' }}</p>
                    @if($jaVotou)
                        <div>
                            @foreach(range(1,8) as $i)
                                @php $opcao = $post->{'opcao'.$i}; if(!$opcao) continue; @endphp
                                @php $qtd=$votos[$i]??0; $pct=$totalVotos>0?round($qtd/$totalVotos*100):0; $win=$qtd===$maxVotos&&$maxVotos>0; @endphp
                                <div class="poll-res-row">
                                    <div class="poll-res-label"><span>{{ $opcao }} @if($win) 🏆 @endif</span><span>{{ $pct }}% ({{ $qtd }})</span></div>
                                    <div class="poll-res-bg"><div class="poll-res-bar {{ $win?'win':'' }}" style="width:{{ $pct }}%"></div></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <form method="POST" action="{{ route('posts.votar', $post->id) }}">
                            @csrf
                            @foreach(range(1,8) as $i)
                                @php $opcao = $post->{'opcao'.$i}; if(!$opcao) continue; @endphp
                                <label class="poll-opt">
                                    <input type="radio" name="opcao" value="{{ $i }}" required> {{ $opcao }}
                                </label>
                            @endforeach
                            <button type="submit" class="btn-vote">🗳️ Votar</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        {{-- FOOTER ── autor, curtidas, voltar --}}
        <div class="post-footer">
            {{-- Autor --}}
            <div class="post-meta">
                @if($post->usuario && $post->usuario->avatar)
                    <img src="{{ asset('storage/'.$post->usuario->avatar) }}" class="avatar-mini" alt="">
                @else
                    <div class="avatar-mini-placeholder">{{ strtoupper(substr($post->usuario->nome ?? 'U', 0, 1)) }}</div>
                @endif
                <span>{{ $post->usuario->nome ?? 'Anônimo' }}</span>
                <span style="color:var(--border)">·</span>
                <span>👁 {{ $post->visualizacoes }}</span>
            </div>

            <div class="post-actions">
                {{-- Curtir --}}
                <button class="btn-like {{ $jaLikei ? 'liked' : '' }}"
                        onclick="toggleLike(this, {{ $post->id }})">
                    <span class="like-icon">{{ $jaLikei ? '❤️' : '🤍' }}</span>
                    <span class="like-count">{{ $totalLikes }}</span>
                </button>

                {{-- Voltar --}}
                <a href="{{ route('home') }}" class="btn-back">← Voltar</a>
            </div>
        </div>
    </div>

    {{-- COMENTÁRIOS --}}
    <div class="comments-card">
        <div class="comments-header">💬 Comentários ({{ $post->comments->count() }})</div>

        @forelse($post->comments as $comentario)
            <div class="comment-item">
                <div class="comment-avatar">
                    {{ strtoupper(substr($comentario->user->nome ?? $comentario->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <span class="comment-author">{{ $comentario->user->nome ?? $comentario->user->name ?? 'Anônimo' }}</span>
                    <span class="comment-date">{{ $comentario->created_at?->format('d/m/Y H:i') }}</span>
                    <p class="comment-text">{{ $comentario->texto }}</p>
                </div>
            </div>
        @empty
            <div class="empty-comments">Nenhum comentário ainda...</div>
        @endforelse
    </div>

    {{-- FORM COMENTÁRIO --}}
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
    @endauth

</div>

{{-- Lightbox --}}
<div id="lightbox" onclick="this.classList.remove('open')">
    <img id="lightbox-img" src="" alt="">
</div>

<script>
function abrirImagem(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.add('open');
}
document.addEventListener('keydown', e => { if(e.key==='Escape') document.getElementById('lightbox').classList.remove('open'); });

function toggleLike(btn, postId) {
    fetch(`/like/${postId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    }).then(r => r.json()).then(data => {
        btn.querySelector('.like-icon').textContent  = data.liked ? '❤️' : '🤍';
        btn.querySelector('.like-count').textContent = data.total;
        data.liked ? btn.classList.add('liked') : btn.classList.remove('liked');
    });
}
</script>
@endsection

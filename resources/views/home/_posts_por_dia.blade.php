@php
    function binPackPosts(iterable $posts): array
    {
        $widths = ['P' => 3, 'M' => 4, 'G' => 6, 'GG' => 12];
        $sorted = collect($posts)
            ->sortByDesc(fn($p) => $widths[$p->tamanho] ?? 4)
            ->values()->all();
        $rows = [];
        while (!empty($sorted)) {
            $row = []; $rowWidth = 0; $notFit = [];
            foreach ($sorted as $post) {
                $w = $widths[$post->tamanho] ?? 4;
                if ($rowWidth + $w <= 12) { $row[] = $post; $rowWidth += $w; }
                else { $notFit[] = $post; }
            }
            if (empty($row)) { $rows[] = [array_shift($sorted)]; $sorted = array_values($sorted); }
            else { $rows[] = $row; $sorted = $notFit; }
        }
        return $rows;
    }
@endphp

<style>
/* ── Mídia nos cards da home ── */
.card-media { margin: 0 -1rem .75rem; overflow: hidden; }

/* Imagem única ou grid */
.home-img-single { width: 100%; max-height: 220px; object-fit: cover; display: block; cursor: pointer; transition: transform .3s; }
.home-img-single:hover { transform: scale(1.02); }

.home-img-grid { display: grid; gap: 2px; }
.home-img-grid.two  { grid-template-columns: 1fr 1fr; }
.home-img-grid.many { grid-template-columns: repeat(3,1fr); }
.home-img-cell { aspect-ratio: 1; overflow: hidden; position: relative; cursor: pointer; }
.home-img-cell img { width:100%; height:100%; object-fit:cover; transition: transform .3s; }
.home-img-cell:hover img { transform: scale(1.05); }
.home-img-more {
    position: absolute; inset: 0;
    background: rgba(0,0,0,.55);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.3rem; font-weight: 700;
}

/* Vídeo */
.home-video-wrap { position: relative; aspect-ratio: 16/9; background: #000; }
.home-video-wrap iframe,
.home-video-wrap video { width: 100%; height: 100%; display: block; }

/* Enquete mini */
.home-poll { padding: .75rem 1rem; }
.home-poll-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6c757d; margin-bottom: .5rem; }
.home-poll-opt {
    display: flex; align-items: center; gap: .5rem;
    padding: .45rem .7rem; margin-bottom: .35rem;
    border: 1.5px solid #dee2e6; border-radius: 8px;
    font-size: .85rem; cursor: pointer;
    transition: border-color .15s, background .15s;
}
.home-poll-opt input[type=radio] { display: none; }
.home-poll-opt:has(input:checked),
.home-poll-opt:hover { border-color: #6366f1; background: #eef2ff; color: #4338ca; }
.home-poll-opt .dot {
    width: 14px; height: 14px; border-radius: 50%;
    border: 2px solid #adb5bd; flex-shrink: 0; transition: all .15s;
}
.home-poll-opt:has(input:checked) .dot { border-color: #6366f1; background: #6366f1; }
.btn-vote-mini {
    margin-top: .5rem; padding: .35rem .9rem;
    border-radius: 20px; border: none;
    background: #6366f1; color: #fff;
    font-size: .8rem; font-weight: 700; cursor: pointer;
    transition: background .2s;
}
.btn-vote-mini:hover { background: #4f46e5; }

/* Resultado de enquete */
.poll-res-row { margin-bottom: .4rem; }
.poll-res-label { display: flex; justify-content: space-between; font-size: .8rem; color: #495057; margin-bottom: .15rem; }
.poll-res-bg { background: #e9ecef; border-radius: 99px; height: 6px; overflow: hidden; }
.poll-res-bar { height: 100%; border-radius: 99px; background: linear-gradient(90deg,#6366f1,#4f46e5); }
.poll-res-bar.win { background: linear-gradient(90deg,#10b981,#059669); }
</style>

@foreach ($postsPorDia as $dia => $postsNoDia)
@php
    $dataFormatada = \Carbon\Carbon::parse($dia)->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY');
    $rows = binPackPosts($postsNoDia);
@endphp

<div class="day-separator mb-3 mt-4" data-dia="{{ $dia }}">
    <div class="d-flex align-items-center gap-3">
        <span class="badge bg-dark fs-6 px-3 py-2 text-capitalize">📅 {{ $dataFormatada }}</span>
        <hr class="flex-grow-1 m-0">
    </div>

    @foreach ($rows as $linha)
    <div class="row g-3 mt-1">
        @foreach ($linha as $post)
        @php
            // Dados de enquete
            $votos = $totalVotos = $jaVotou = 0;
            $votosArr = [];
            if ($post->tipo === 'enquete') {
                $votosArr   = $post->votos()->get()->groupBy('opcao')->map->count()->toArray();
                $totalVotos = array_sum($votosArr);
                $jaVotou    = auth()->check() && $post->votos()->where('id_usuario', auth()->id())->exists();
            }
            $maxVotos = $totalVotos > 0 ? max($votosArr) : 0;

            // Imagens
            $imgs = [];
            if ($post->tipo === 'imagem') {
                $imgs = $post->imagens->count()
                    ? $post->imagens->map(fn($i) => Storage::url($i->caminho))->toArray()
                    : ($post->imagem ? [str_starts_with($post->imagem,'/storage/') ? asset($post->imagem) : Storage::url($post->imagem)] : []);
            }
        @endphp
        <div class="{{ $post->coluna_bootstrap }}">
            <div class="card h-100 shadow-sm" style="border-radius:12px; overflow:hidden;">

                {{-- ── MÍDIA ── --}}

                {{-- IMAGEM --}}
                @if($post->tipo === 'imagem' && count($imgs))
                    @php $c = count($imgs); @endphp
                    @if($c === 1)
                        <img src="{{ $imgs[0] }}" class="home-img-single"
                             onclick="abrirImagem('{{ $imgs[0] }}')" alt="{{ $post->titulo }}">
                    @elseif($c === 2)
                        <div class="home-img-grid two">
                            @foreach($imgs as $src)
                            <div class="home-img-cell" onclick="abrirImagem('{{ $src }}')">
                                <img src="{{ $src }}" alt="">
                            </div>
                            @endforeach
                        </div>
                    @else
                        @php $show = min($c, 6); @endphp
                        <div class="home-img-grid many">
                            @foreach(array_slice($imgs,0,$show) as $idx => $src)
                            <div class="home-img-cell" onclick="abrirImagem('{{ $src }}')">
                                <img src="{{ $src }}" alt="">
                                @if($idx === $show-1 && $c > $show)
                                    <div class="home-img-more">+{{ $c - $show }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                @endif

                {{-- VÍDEO --}}
                @if($post->tipo === 'video' && $post->video)
                    @php
                        $isYt    = str_contains($post->video,'youtube') || str_contains($post->video,'youtu.be');
                        $isVimeo = str_contains($post->video,'vimeo');
                    @endphp
                    <div class="home-video-wrap">
                        @if($isYt)
                            @php preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/',$post->video,$m); @endphp
                            <iframe src="https://www.youtube.com/embed/{{ $m[1]??'' }}" frameborder="0" allowfullscreen></iframe>
                        @elseif($isVimeo)
                            @php preg_match('/vimeo\.com\/(\d+)/',$post->video,$m); @endphp
                            <iframe src="https://player.vimeo.com/video/{{ $m[1]??'' }}" frameborder="0" allowfullscreen></iframe>
                        @else
                            @php $vs = str_starts_with($post->video,'/storage/') ? asset($post->video) : Storage::url($post->video); @endphp
                            <video controls style="width:100%;display:block;"><source src="{{ $vs }}"></video>
                        @endif
                    </div>
                @endif

                <div class="card-body d-flex flex-column">

                    <a class="text-decoration-none text-dark" href="{{ route('posts.show', $post->id) }}">
                        <h5 class="fw-bold">{{ $post->titulo }}</h5>
                        @if($post->tipo === 'texto' && $post->texto)
                            <p class="text-muted small flex-grow-1">
                                {{ Str::limit($post->texto, $post->tamanho === 'GG' ? 400 : ($post->tamanho === 'G' ? 250 : 120)) }}
                            </p>
                        @endif
                    </a>

                    {{-- ENQUETE --}}
                    @if($post->tipo === 'enquete')
                        <div class="home-poll px-0">
                            <p class="home-poll-label">📊 {{ $totalVotos }} {{ $totalVotos === 1 ? 'voto' : 'votos' }}</p>
                            @if($jaVotou)
                                @foreach(range(1,8) as $i)
                                    @php $op = $post->{'opcao'.$i}; if(!$op) continue; @endphp
                                    @php $qtd=$votosArr[$i]??0; $pct=$totalVotos>0?round($qtd/$totalVotos*100):0; $win=$qtd===$maxVotos&&$maxVotos>0; @endphp
                                    <div class="poll-res-row">
                                        <div class="poll-res-label"><span>{{ $op }}@if($win) 🏆@endif</span><span>{{ $pct }}%</span></div>
                                        <div class="poll-res-bg"><div class="poll-res-bar {{ $win?'win':'' }}" style="width:{{ $pct }}%"></div></div>
                                    </div>
                                @endforeach
                            @else
                                <form method="POST" action="{{ route('posts.votar', $post->id) }}">
                                    @csrf
                                    @foreach(range(1,8) as $i)
                                        @php $op = $post->{'opcao'.$i}; if(!$op) continue; @endphp
                                        <label class="home-poll-opt">
                                            <input type="radio" name="opcao" value="{{ $i }}" required>
                                            <span class="dot"></span>{{ $op }}
                                        </label>
                                    @endforeach
                                    <button type="submit" class="btn-vote-mini">🗳️ Votar</button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-auto mb-2">
                        <small class="text-secondary">✍ {{ $post->usuario->nome ?? 'Desconhecido' }}</small>
                        <small class="text-secondary">👁 {{ $post->visualizacoes }}</small>
                    </div>

                    <!-- LIKE -->
                    <form method="POST" action="{{ route('posts.like', $post->id) }}">
                        @csrf
                        <button class="btn btn-outline-primary btn-sm">
                            👍 Curtir ({{ $post->curtidas ?? 0 }})
                        </button>
                    </form>

                    <!-- COMENTÁRIOS -->
                    @if($post->comments && $post->comments->count() > 0)
                        <div class="mt-2">
                            @foreach($post->comments as $comment)
                                <div style="border-top:1px solid #eee;padding:5px 0;font-size:13px;">
                                    <strong>{{ $comment->user->name ?? 'Usuário' }}</strong>: {{ $comment->texto }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <hr class="my-2">

                    <!-- FORM COMENTÁRIO -->
                    <form method="POST" action="{{ route('comments.store') }}">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="texto" class="form-control" placeholder="Comentar...">
                            <button class="btn btn-primary btn-sm">Enviar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
@endforeach

@if($postsPorDia->isEmpty())
    <p class="text-center text-muted mt-5">Nenhuma postagem encontrada.</p>
@endif

{{-- Modal imagem --}}
@once
<div id="imgModalHome" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);justify-content:center;align-items:center;z-index:9999;cursor:zoom-out;backdrop-filter:blur(6px);">
    <img id="imgModalHomeSrc" style="max-width:92%;max-height:92%;border-radius:12px;">
</div>
<script>
function abrirImagem(src) {
    document.getElementById('imgModalHomeSrc').src = src;
    document.getElementById('imgModalHome').style.display = 'flex';
}
document.addEventListener('keydown', e => { if(e.key==='Escape') document.getElementById('imgModalHome').style.display='none'; });
</script>
@endonce

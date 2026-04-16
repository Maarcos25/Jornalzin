@foreach ($postsPorDia as $dia => $postsNoDia)
@php
    $isTodos = $dia === 'todos';
    $filtro = request('filtro');
    $labelFiltro = match($filtro) {
        'views'    => '🔥 Mais Vistos',
        'likes'    => '❤️ Mais Curtidos',
        'recentes' => '🕐 Mais Recentes',
        default    => '📰 Início',
    };
    $dataFormatada = $isTodos
        ? $labelFiltro
        : \Carbon\Carbon::parse($dia)->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY');
@endphp

    <div class="day-block" data-dia="{{ $dia }}">
        <div class="day-sep">
            <span class="day-sep-badge">
                {{ $isTodos ? '' : '📅 ' }}{{ ucfirst($dataFormatada) }}
                @if ($isTodos && $filtro === 'likes') ❤️ @endif
                @if ($isTodos && $filtro === 'views') 🔥 @endif
            </span>
            <div class="day-sep-line"></div>
        </div>

        <div class="posts-col masonry-grid">
            @foreach ($postsNoDia as $post)
                @php
                    $votosArr = [];
                    $totalVotos = 0;
                    $jaVotou = false;
                    if ($post->tipo === 'enquete') {
                        $votosArr = $post->votos()->get()->groupBy('opcao')->map->count()->toArray();
                        $totalVotos = array_sum($votosArr);
                        $jaVotou = auth()->check() && $post->votos()->where('id_usuario', auth()->id())->exists();
                    }
                    $maxVotos = $totalVotos > 0 ? max($votosArr) : 0;

                    $imgs = [];
                    if ($post->tipo === 'imagem') {
                        $imgs = $post->imagens->count()
                            ? $post->imagens->map(fn($i) => Storage::url($i->caminho))->toArray()
                            : ($post->imagem ? [str_starts_with($post->imagem, '/storage/') ? asset($post->imagem) : Storage::url($post->imagem)] : []);
                    }

                    $totalComentarios = $post->comments ? $post->comments->count() : 0;
                    $jaLikei = auth()->check() && $post->likes()->where('user_id', auth()->id())->exists();
                    $totalLikes = $post->likes()->count();

                    $masonryClass = match($post->tamanho) {
                        'GG' => 'masonry-full',
                        'G'  => 'masonry-wide',
                        default => '',
                    };

                    // Destaque: post mais relevante calculado no controller (likes*3 + comments*2 + views)
                    $isDestaque = isset($destaqueId) && $post->id === $destaqueId;
                @endphp

                <div class="masonry-item {{ $masonryClass }}">
                    {{-- Wrapper extra para o efeito de brilho não ser cortado pelo overflow:hidden do card --}}
                    <div class="{{ $isDestaque ? 'destaque-wrap' : '' }}">
                        <div class="post-card">

                            {{-- Badge interno de destaque --}}
                            @if ($isDestaque)
                                <div class="destaque-badge">🏆 Mais Relevante</div>
                            @endif

                            {{-- MÍDIA --}}
                            @if ($post->tipo === 'imagem' && count($imgs))
                                @php $c = count($imgs); @endphp
                                <div class="post-media">
                                    @if ($c === 1)
                                        <img src="{{ $imgs[0] }}" class="home-img-single"
                                            onclick="abrirImagem('{{ $imgs[0] }}')" alt="{{ $post->titulo }}">
                                    @elseif($c === 2)
                                        <div class="home-img-grid two">
                                            @foreach ($imgs as $src)
                                                <div class="home-img-cell" onclick="abrirImagem('{{ $src }}')">
                                                    <img src="{{ $src }}" alt="">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        @php $show = min($c, 6); @endphp
                                        <div class="home-img-grid many">
                                            @foreach (array_slice($imgs, 0, $show) as $idx => $src)
                                                <div class="home-img-cell" onclick="abrirImagem('{{ $src }}')">
                                                    <img src="{{ $src }}" alt="">
                                                    @if ($idx === $show - 1 && $c > $show)
                                                        <div class="home-img-more">+{{ $c - $show }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($post->tipo === 'video' && $post->video)
                                @php
                                    $isYt    = str_contains($post->video, 'youtube') || str_contains($post->video, 'youtu.be');
                                    $isVimeo = str_contains($post->video, 'vimeo');
                                @endphp
                                <div class="home-video-wrap">
                                    @if ($isYt)
                                        @php preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video, $m); @endphp
                                        <iframe src="https://www.youtube.com/embed/{{ $m[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                                    @elseif($isVimeo)
                                        @php preg_match('/vimeo\.com\/(\d+)/', $post->video, $m); @endphp
                                        <iframe src="https://player.vimeo.com/video/{{ $m[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                                    @else
                                        @php $vs = str_starts_with($post->video, '/storage/') ? asset($post->video) : Storage::url($post->video); @endphp
                                        <video controls style="width:100%;display:block;"><source src="{{ $vs }}"></video>
                                    @endif
                                </div>
                            @endif

                            {{-- CORPO --}}
                            <div class="post-body">
                                <a class="post-title" href="{{ route('posts.show', $post->id) }}">{{ $post->titulo }}</a>

                                @if ($post->tipo === 'texto' && $post->texto)
                                    <p class="post-excerpt">
                                        {{ Str::limit($post->texto, $post->tamanho === 'GG' ? 400 : ($post->tamanho === 'G' ? 250 : 120)) }}
                                    </p>
                                @endif

                                @if ($post->tipo === 'enquete')
                                    <div class="home-poll">
                                        <p class="home-poll-label">📊 {{ $totalVotos }} {{ $totalVotos === 1 ? 'voto' : 'votos' }}</p>
                                        @if ($jaVotou)
                                            @foreach (range(1, 8) as $i)
                                                @php $op = $post->{'opcao'.$i}; if (!$op) continue; @endphp
                                                @php
                                                    $qtd = $votosArr[$i] ?? 0;
                                                    $pct = $totalVotos > 0 ? round(($qtd / $totalVotos) * 100) : 0;
                                                    $win = $qtd === $maxVotos && $maxVotos > 0;
                                                @endphp
                                                <div class="poll-res-row">
                                                    <div class="poll-res-label">
                                                        <span>{{ $op }} @if($win) 🏆 @endif</span>
                                                        <span>{{ $pct }}%</span>
                                                    </div>
                                                    <div class="poll-res-bg">
                                                        <div class="poll-res-bar {{ $win ? 'win' : '' }}" style="width:{{ $pct }}%"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <form method="POST" action="{{ route('posts.votar', $post->id) }}">
                                                @csrf
                                                @foreach (range(1, 8) as $i)
                                                    @php $op = $post->{'opcao'.$i}; if (!$op) continue; @endphp
                                                    <label class="home-poll-opt">
                                                        <input type="radio" name="opcao" value="{{ $i }}" required>
                                                        {{ $op }}
                                                    </label>
                                                @endforeach
                                                <button type="submit" class="btn-vote-mini">🗳️ Votar</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- FOOTER --}}
                            <div class="post-footer">
                                <span class="post-meta">
                                    @if ($post->usuario && $post->usuario->avatar)
                                        <img src="{{ asset('storage/' . $post->usuario->avatar) }}" style="width:22px;height:22px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <span>✍️</span>
                                    @endif
                                    {{ $post->usuario->nome ?? 'Desconhecido' }}
                                </span>
                                <span class="post-meta-sep">·</span>
                                <span class="post-meta">👁 {{ $post->visualizacoes }}</span>
                                <div class="post-actions">
                                    <button class="btn-like {{ $jaLikei ? 'liked' : '' }}" onclick="toggleLike(this, {{ $post->id }})" type="button">
                                        <span class="like-icon">{{ $jaLikei ? '❤️' : '🤍' }}</span>
                                        <span class="like-count">{{ $totalLikes }}</span>
                                    </button>
                                    <button class="btn-comment" onclick="toggleComentarios(this, {{ $post->id }})" type="button">
                                        💬 @if ($totalComentarios > 0) <span>{{ $totalComentarios }}</span> @endif
                                    </button>
                                </div>
                            </div>

                            {{-- COMENTÁRIOS --}}
                            <div class="comments-panel" id="comments-{{ $post->id }}">
                                @if ($post->comments && $post->comments->count() > 0)
                                    @foreach ($post->comments as $comment)
                                        <div class="comment-item">
                                            <div class="comment-avatar">
                                                {{ strtoupper(substr($comment->user->nome ?? ($comment->user->name ?? 'U'), 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="comment-author">{{ $comment->user->nome ?? ($comment->user->name ?? 'Usuário') }}</span>
                                                <span class="comment-text">{{ $comment->texto }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p style="color:var(--muted);font-size:.83rem;text-align:center;padding:.4rem 0;">Nenhum comentário ainda. Seja o primeiro! 💬</p>
                                @endif
                                @auth
                                    <form method="POST" action="{{ route('comments.store') }}" class="comment-form">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <input type="text" name="texto" placeholder="Escreva um comentário..." required>
                                        <button type="submit">Enviar</button>
                                    </form>
                                @else
                                    <p style="color:var(--muted);font-size:.82rem;text-align:center;margin-top:.5rem;">
                                        <a href="{{ route('login') }}" style="color:var(--brand);">Entre</a> para comentar.
                                    </p>
                                @endauth
                            </div>

                        </div>{{-- /post-card --}}
                    </div>{{-- /destaque-wrap --}}
                </div>
            @endforeach
        </div>
    </div>
@endforeach

@if ($postsPorDia->isEmpty())
    <div style="text-align:center;padding:4rem 1rem;color:var(--muted);">
        <div style="font-size:3rem;margin-bottom:1rem;">📭</div>
        <p>Nenhuma postagem encontrada.</p>
    </div>
@endif

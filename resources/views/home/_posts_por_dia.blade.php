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
                    $jaFavoritei = auth()->check() && $post->favoritos()->where('user_id', auth()->id())->exists();

                    $masonryClass = match($post->tamanho) {
                        'GG' => 'masonry-full',
                        'G'  => 'masonry-wide',
                        default => '',
                    };

                    $isDestaque = isset($destaqueId) && $post->id === $destaqueId;

                    $isYt = false; $isVimeo = false; $ytId = null;
                    if ($post->tipo === 'video' && $post->video) {
                        $isYt    = str_contains($post->video, 'youtube') || str_contains($post->video, 'youtu.be');
                        $isVimeo = str_contains($post->video, 'vimeo');
                        if ($isYt) { preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video, $ym); $ytId = $ym[1] ?? null; }
                    }
                @endphp

                <div class="masonry-item {{ $masonryClass }}">
                    @if ($isDestaque)
                    <div style="position:relative;border-radius:20px;overflow:hidden;margin-bottom:0;animation:pulse-glow 2.5s infinite;">
                    <style>
                        @keyframes pulse-glow {
                            0%,100% { box-shadow: 0 0 14px 2px rgba(249,115,22,.35); }
                            50%     { box-shadow: 0 0 32px 8px rgba(249,115,22,.65); }
                        }
                    </style>
                    @endif

                    <div class="post-card {{ $isDestaque ? 'destaque-relevante' : '' }}"
                         onclick="window.location='{{ route('posts.show', $post->id) }}'"
                         style="cursor:pointer;">

                        @if ($isDestaque)
                            <div style="padding:.45rem .9rem .0rem;background:transparent;">
                                <span style="color:#f97316;font-size:.75rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;animation:pulse-text 2.5s infinite;">🏆 Mais Relevante</span>
                                <style>@keyframes pulse-text{0%,100%{opacity:1}50%{opacity:.6}}</style>
                            </div>
                        @endif

                        {{-- MÍDIA IMAGEM --}}
                        @if ($post->tipo === 'imagem' && count($imgs))
                            @php $c = count($imgs); @endphp
                            <div class="post-media" onclick="event.stopPropagation()">
                                @if ($c === 1)
                                    <img src="{{ $imgs[0] }}" class="home-img-single"
                                        onclick="abrirImagem('{{ $imgs[0] }}')" alt="{{ $post->titulo }}">
                                @else
                                    @php $cid = 'carousel-'.$post->id; @endphp
                                    <div class="home-carousel" id="{{ $cid }}">
                                        <div class="home-carousel-track">
                                            @foreach ($imgs as $src)
                                                <div class="home-carousel-slide">
                                                    <img src="{{ $src }}" alt="{{ $post->titulo }}" onclick="abrirImagem('{{ $src }}')">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="hc-btn hc-prev" onclick="moverCarousel('{{ $cid }}', -1)">&#8249;</button>
                                        <button class="hc-btn hc-next" onclick="moverCarousel('{{ $cid }}', 1)">&#8250;</button>
                                        <div class="hc-dots">
                                            @foreach ($imgs as $i => $src)
                                                <span class="hc-dot {{ $i === 0 ? 'active' : '' }}" onclick="irParaSlide('{{ $cid }}', {{ $i }})"></span>
                                            @endforeach
                                        </div>
                                        <div class="hc-counter"><span class="hc-current">1</span>/{{ $c }}</div>
                                    </div>
                                @endif
                            </div>

                        {{-- MÍDIA VÍDEO --}}
                        @elseif ($post->tipo === 'video' && $post->video)
                            <div class="home-video-wrap" onclick="event.stopPropagation()">
                                @if ($isYt && $ytId)
                                    <iframe src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0" allowfullscreen></iframe>
                                @elseif ($isVimeo)
                                    @php preg_match('/vimeo\.com\/(\d+)/', $post->video, $vm); @endphp
                                    <iframe src="https://player.vimeo.com/video/{{ $vm[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    @php $vs = str_starts_with($post->video, '/storage/') ? asset($post->video) : Storage::url($post->video); @endphp
                                    <video controls style="width:100%;display:block;"><source src="{{ $vs }}"></video>
                                @endif
                            </div>
                        @endif

                        {{-- CORPO --}}
                        <div class="post-body">
                            <a class="post-title" href="{{ route('posts.show', $post->id) }}" onclick="event.stopPropagation()">{{ $post->titulo }}</a>

                            @if ($post->tipo === 'texto' && $post->texto)
                                <p class="post-excerpt">
                                    {{ Str::limit($post->texto, $post->tamanho === 'GG' ? 400 : ($post->tamanho === 'G' ? 250 : 120)) }}
                                </p>
                            @endif

                            @if ($post->tipo === 'enquete')
                                <div class="home-poll" onclick="event.stopPropagation()">
                                    <p class="home-poll-label">📊 {{ $totalVotos }} {{ $totalVotos === 1 ? 'voto' : 'votos' }}</p>
                                    @if ($jaVotou)
                                        @foreach (range(1, 8) as $i)
                                            @php $op = $post->{'opcao'.$i}; if (!$op) continue; @endphp
                                            @php $qtd = $votosArr[$i] ?? 0; $pct = $totalVotos > 0 ? round(($qtd / $totalVotos) * 100) : 0; $win = $qtd === $maxVotos && $maxVotos > 0; @endphp
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
                        <div class="post-footer" onclick="event.stopPropagation()">
                            <div style="display:flex;align-items:center;gap:.4rem;">
                                @if ($post->usuario && $post->usuario->avatar)
                                    <img src="{{ asset('storage/' . $post->usuario->avatar) }}" style="width:22px;height:22px;border-radius:50%;object-fit:cover;">
                                @else
                                    <span>✍️</span>
                                @endif
                                @if ($post->usuario)
                                    <a href="{{ route('users.perfil', $post->usuario->id) }}" style="color:inherit;text-decoration:none;font-weight:700;font-size:.88rem;" onclick="event.stopPropagation()">
                                        {{ $post->usuario->nome }}
                                    </a>
                                @else
                                    <span style="font-size:.88rem;">Desconhecido</span>
                                @endif
                                <span style="color:var(--border);">·</span>
                                <span style="font-size:.85rem;color:var(--muted);">👁 {{ $post->visualizacoes }}</span>
                            </div>

                            <div style="display:flex;align-items:center;gap:.35rem;margin-left:auto;">
                                @auth
                                <button class="btn-like {{ $jaLikei ? 'liked' : '' }}"
                                    onclick="toggleLike(this, {{ $post->id }})" type="button"
                                    style="padding:.38rem .85rem;font-size:.88rem;">
                                    <span class="like-icon">{{ $jaLikei ? '❤️' : '🤍' }}</span>
                                    <span class="like-count">{{ $totalLikes }}</span>
                                </button>
                                @else
                                <a href="{{ route('login') }}" class="btn-like" style="padding:.38rem .85rem;font-size:.88rem;">🤍 {{ $totalLikes }}</a>
                                @endauth

                                <a class="btn-comment" href="{{ route('posts.show', $post->id) }}#comentarios"
                                    onclick="event.stopPropagation()"
                                    style="padding:.38rem .85rem;font-size:.88rem;">
                                    💬{{ $totalComentarios > 0 ? ' '.$totalComentarios : '' }}
                                </a>

                                <span style="width:1px;height:20px;background:var(--border);margin:0 .1rem;"></span>

                                @auth
                                <div style="position:relative;" onclick="event.stopPropagation()">
                                    <button class="btn-comment"
                                        onclick="toggleMenu({{ $post->id }})"
                                        type="button"
                                        style="padding:.38rem .7rem;font-size:.88rem;font-weight:800;letter-spacing:.1em;">
                                        •••
                                    </button>
                                    <div id="menu-{{ $post->id }}" style="display:none;position:absolute;bottom:110%;right:0;background:var(--surface);border:1px solid var(--border);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.15);min-width:160px;z-index:100;overflow:hidden;">
                                        <button onclick="toggleFavorito(this, {{ $post->id }}); toggleMenu({{ $post->id }})"
                                            type="button"
                                            style="width:100%;padding:.7rem 1rem;border:none;background:transparent;color:var(--text);font-size:.9rem;font-weight:600;cursor:pointer;text-align:left;display:flex;align-items:center;gap:.6rem;"
                                            onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                                            {{ $jaFavoritei ? '🔖 Remover dos salvos' : '🔖 Salvar post' }}
                                        </button>
                                        <button onclick="abrirDenunciaPost({{ $post->id }}); toggleMenu({{ $post->id }})"
                                            type="button"
                                            style="width:100%;padding:.7rem 1rem;border:none;background:transparent;color:#ef4444;font-size:.9rem;font-weight:600;cursor:pointer;text-align:left;display:flex;align-items:center;gap:.6rem;"
                                            onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                                            🚩 Denunciar
                                        </button>
                                    </div>
                                </div>
                                @endauth
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
                                        <div style="flex:1;">
                                            <span class="comment-author">{{ $comment->user->nome ?? ($comment->user->name ?? 'Usuário') }}</span>
                                            <span class="comment-text">{{ $comment->texto }}</span>
                                        </div>
                                        @auth
                                        <button onclick="abrirDenunciaComentario({{ $comment->id }})" type="button"
                                            title="Denunciar comentário"
                                            style="background:none;border:none;cursor:pointer;color:var(--muted);font-size:.85rem;padding:.2rem .4rem;flex-shrink:0;"
                                            onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--muted)'">
                                            🚩
                                        </button>
                                        @endauth
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
                    @if ($isDestaque) </div> @endif
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

{{-- Modal de denúncia --}}
<div id="modalDenuncia" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:var(--surface);border-radius:16px;padding:2rem;max-width:420px;width:90%;box-shadow:0 8px 32px rgba(0,0,0,.2);">
        <h5 style="font-weight:800;color:var(--text);margin:0 0 .5rem;">🚩 Denunciar conteúdo</h5>
        <p style="color:var(--muted);font-size:.88rem;margin-bottom:1rem;">Selecione o motivo da denúncia.</p>
        <form id="formDenuncia" method="POST">
            @csrf
            <input type="hidden" name="tipo" id="denunciaTipo">
            <input type="hidden" name="referencia_id" id="denunciaReferenciaId">
            <div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.2rem;">
                @foreach(['Conteúdo impróprio','Discurso de ódio','Spam','Informação falsa','Outro'] as $motivo)
                <label style="display:flex;align-items:center;gap:.6rem;padding:.55rem .85rem;border:1.5px solid var(--border);border-radius:8px;cursor:pointer;font-size:.93rem;color:var(--text);">
                    <input type="radio" name="motivo" value="{{ $motivo }}" required style="accent-color:#ef4444;"> {{ $motivo }}
                </label>
                @endforeach
            </div>
            <textarea name="descricao" placeholder="Detalhes adicionais (opcional)..." rows="3"
                style="width:100%;padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.9rem;background:var(--surface);color:var(--text);resize:none;margin-bottom:1rem;"></textarea>
            <div style="display:flex;gap:.6rem;justify-content:flex-end;">
                <button type="button" onclick="fecharDenuncia()" style="padding:.5rem 1.2rem;border-radius:8px;border:1.5px solid var(--border);background:transparent;color:var(--muted);font-weight:600;cursor:pointer;">Cancelar</button>
                <button type="submit" style="padding:.5rem 1.4rem;border-radius:8px;border:none;background:#ef4444;color:#fff;font-weight:700;cursor:pointer;">Enviar denúncia</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirDenunciaPost(postId) {
    @auth
        document.getElementById('denunciaTipo').value = 'post';
        document.getElementById('denunciaReferenciaId').value = postId;
        document.getElementById('formDenuncia').action = '{{ route('denuncias.store') }}';
        document.getElementById('modalDenuncia').style.display = 'flex';
    @else
        window.location.href = '{{ route('login') }}';
    @endauth
}

function abrirDenunciaComentario(commentId) {
    @auth
        document.getElementById('denunciaTipo').value = 'comentario';
        document.getElementById('denunciaReferenciaId').value = commentId;
        document.getElementById('formDenuncia').action = '{{ route('denuncias.store') }}';
        document.getElementById('modalDenuncia').style.display = 'flex';
    @else
        window.location.href = '{{ route('login') }}';
    @endauth
}

function fecharDenuncia() {
    document.getElementById('modalDenuncia').style.display = 'none';
    document.getElementById('formDenuncia').reset();
}

document.getElementById('modalDenuncia').addEventListener('click', function(e) {
    if (e.target === this) fecharDenuncia();
});

function toggleMenu(postId) {
    const menu = document.getElementById('menu-' + postId);
    const isOpen = menu.style.display === 'block';
    document.querySelectorAll('[id^="menu-"]').forEach(m => m.style.display = 'none');
    menu.style.display = isOpen ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="menu-"]') && !e.target.closest('button[onclick*="toggleMenu"]')) {
        document.querySelectorAll('[id^="menu-"]').forEach(m => m.style.display = 'none');
    }
});

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
        const menuBtn = btn.closest('[style*="position:relative"]')?.querySelector('button[onclick*="toggleMenu"]');
        if (data.favoritado) {
            btn.innerHTML = '🔖 Remover dos salvos';
        } else {
            btn.innerHTML = '🔖 Salvar post';
        }
    });
}

(function() {
    let lastTap = {};
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.post-card');
        if (!card) return;
        if (e.target.closest('button,a,form,input,textarea,label,select')) return;
        const postId = card.querySelector('.btn-like')?.getAttribute('onclick')?.match(/\d+/)?.[0];
        if (!postId) return;
        const now = Date.now();
        if (lastTap[postId] && (now - lastTap[postId]) < 300) {
            lastTap[postId] = 0;
            const btnLike = card.querySelector('.btn-like');
            if (btnLike && !btnLike.classList.contains('liked')) {
                mostrarCoracao(e.clientX, e.clientY);
                toggleLike(btnLike, parseInt(postId));
            } else if (btnLike) {
                mostrarCoracao(e.clientX, e.clientY);
            }
        } else {
            lastTap[postId] = now;
        }
    });

    function mostrarCoracao(x, y) {
        const heart = document.createElement('div');
        heart.textContent = '❤️';
        heart.style.cssText = `position:fixed;left:${x}px;top:${y}px;font-size:3.5rem;pointer-events:none;z-index:99999;transform:translate(-50%,-50%) scale(0);animation:heartPop .6s ease forwards;`;
        document.body.appendChild(heart);
        if (!document.getElementById('heartStyle')) {
            const s = document.createElement('style');
            s.id = 'heartStyle';
            s.textContent = '@keyframes heartPop{0%{transform:translate(-50%,-50%) scale(0)}50%{transform:translate(-50%,-50%) scale(1.4)}100%{transform:translate(-50%,-50%) scale(1);opacity:0}}';
            document.head.appendChild(s);
        }
        setTimeout(() => heart.remove(), 700);
    }
})();
</script>

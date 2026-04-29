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
                                    <div id="menu-{{ $post->id }}" style="display:none;position:absolute;bottom:110%;right:0;background:var(--surface);border:1px solid var(--border);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.15);min-width:175px;z-index:100;overflow:hidden;">
                                        <button onclick="toggleFavorito(this, {{ $post->id }}); toggleMenu({{ $post->id }})"
                                            type="button"
                                            style="width:100%;padding:.7rem 1rem;border:none;background:transparent;color:var(--text);font-size:.9rem;font-weight:600;cursor:pointer;text-align:left;display:flex;align-items:center;gap:.6rem;"
                                            onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                                            {{ $jaFavoritei ? '🔖 Remover dos salvos' : '🔖 Salvar post' }}
                                        </button>
                                        <button onclick="abrirCompartilhar({{ $post->id }}, '{{ addslashes($post->titulo) }}'); toggleMenu({{ $post->id }})"
                                            type="button"
                                            style="width:100%;padding:.7rem 1rem;border:none;background:transparent;color:var(--text);font-size:.9rem;font-weight:600;cursor:pointer;text-align:left;display:flex;align-items:center;gap:.6rem;"
                                            onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                                            🔗 Compartilhar
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

{{-- Modal Denúncia --}}
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

{{-- Modal Compartilhar --}}
<div id="modalCompartilhar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:var(--surface);border-radius:20px;padding:1.8rem;max-width:400px;width:90%;box-shadow:0 8px 32px rgba(0,0,0,.25);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;">
            <h5 style="font-weight:800;color:var(--text);margin:0;font-size:1.05rem;">🔗 Compartilhar post</h5>
            <button onclick="fecharCompartilhar()" style="background:none;border:none;cursor:pointer;color:var(--muted);font-size:1.2rem;line-height:1;">✕</button>
        </div>

        {{-- Link --}}
        <div style="display:flex;gap:.5rem;margin-bottom:1.2rem;">
            <input id="compartilharLink" readonly
                style="flex:1;padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.88rem;background:var(--surface-2);color:var(--muted);outline:none;">
            <button onclick="copiarLink()"
                style="padding:.6rem 1rem;border-radius:10px;border:none;background:#6366f1;color:#fff;font-weight:700;font-size:.88rem;cursor:pointer;white-space:nowrap;"
                id="btnCopiar">
                📋 Copiar
            </button>
        </div>

        {{-- Redes sociais --}}
        <p style="font-size:.8rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.75rem;">Compartilhar em</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:1.2rem;">
            <button onclick="compartilharWhatsApp()"
                style="padding:.7rem;border-radius:12px;border:1.5px solid #e2e8f0;background:#f0fdf4;color:#15803d;font-weight:700;font-size:.9rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;transition:all .2s;"
                onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.126.556 4.123 1.527 5.857L.057 23.882l6.174-1.618A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.652-.51-5.17-1.395l-.37-.22-3.665.961.977-3.567-.241-.381A9.98 9.98 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                WhatsApp
            </button>
            <button onclick="compartilharTwitter()"
                style="padding:.7rem;border-radius:12px;border:1.5px solid #e2e8f0;background:#f0f9ff;color:#0369a1;font-weight:700;font-size:.9rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;transition:all .2s;"
                onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#000"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.741l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                X (Twitter)
            </button>
            <button onclick="compartilharFacebook()"
                style="padding:.7rem;border-radius:12px;border:1.5px solid #e2e8f0;background:#eff6ff;color:#1d4ed8;font-weight:700;font-size:.9rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;transition:all .2s;"
                onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </button>
            <button onclick="compartilharNativo()"
                style="padding:.7rem;border-radius:12px;border:1.5px solid #e2e8f0;background:#faf5ff;color:#7c3aed;font-weight:700;font-size:.9rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;transition:all .2s;"
                onmouseover="this.style.background='#ede9fe'" onmouseout="this.style.background='#faf5ff'">
                📤 Mais opções
            </button>
        </div>

        {{-- Enviar via DM --}}
        @auth
        <div style="border-top:1px solid var(--border);padding-top:1.1rem;">
            <p style="font-size:.8rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.75rem;">Enviar para usuário</p>
            <div style="display:flex;gap:.5rem;">
                <input id="dmBusca" type="text" placeholder="Buscar usuário..."
                    oninput="buscarUsuarios(this.value)"
                    style="flex:1;padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.88rem;background:var(--surface);color:var(--text);outline:none;">
            </div>
            <div id="dmResultados" style="margin-top:.6rem;max-height:160px;overflow-y:auto;display:flex;flex-direction:column;gap:.4rem;"></div>
        </div>
        @endauth
    </div>
</div>

<script>
// ── Denúncia ──
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

// ── Menu ••• ──
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

// ── Favorito ──
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
        btn.innerHTML = data.favoritado ? '🔖 Remover dos salvos' : '🔖 Salvar post';
    });
}

// ── Compartilhar ──
let _compartilharPostId = null;
let _compartilharTitulo = null;

function abrirCompartilhar(postId, titulo) {
    _compartilharPostId = postId;
    _compartilharTitulo = titulo;
    const url = window.location.origin + '/posts/' + postId;
    document.getElementById('compartilharLink').value = url;
    document.getElementById('dmResultados').innerHTML = '';
    const dmBusca = document.getElementById('dmBusca');
    if (dmBusca) dmBusca.value = '';
    document.getElementById('btnCopiar').textContent = '📋 Copiar';
    document.getElementById('btnCopiar').style.background = '#6366f1';
    document.getElementById('modalCompartilhar').style.display = 'flex';
}

function fecharCompartilhar() {
    document.getElementById('modalCompartilhar').style.display = 'none';
}

document.getElementById('modalCompartilhar').addEventListener('click', function(e) {
    if (e.target === this) fecharCompartilhar();
});

function copiarLink() {
    const input = document.getElementById('compartilharLink');
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = document.getElementById('btnCopiar');
        btn.textContent = '✅ Copiado!';
        btn.style.background = '#22c55e';
        setTimeout(() => { btn.textContent = '📋 Copiar'; btn.style.background = '#6366f1'; }, 2000);
    });
}

function compartilharWhatsApp() {
    const url   = encodeURIComponent(document.getElementById('compartilharLink').value);
    const texto = encodeURIComponent('Olha esse post: ' + _compartilharTitulo + '\n');
    window.open('https://wa.me/?text=' + texto + url, '_blank');
}

function compartilharTwitter() {
    const url   = encodeURIComponent(document.getElementById('compartilharLink').value);
    const texto = encodeURIComponent(_compartilharTitulo);
    window.open('https://twitter.com/intent/tweet?text=' + texto + '&url=' + url, '_blank');
}

function compartilharFacebook() {
    const url = encodeURIComponent(document.getElementById('compartilharLink').value);
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '_blank');
}

function compartilharNativo() {
    if (navigator.share) {
        navigator.share({ title: _compartilharTitulo, url: document.getElementById('compartilharLink').value });
    } else {
        copiarLink();
        showToast('Link copiado! Cole onde quiser 🔗');
    }
}

let _dmTimer = null;
function buscarUsuarios(q) {
    clearTimeout(_dmTimer);
    const res = document.getElementById('dmResultados');
    if (q.length < 2) { res.innerHTML = ''; return; }
    _dmTimer = setTimeout(() => {
        fetch('/usuarios/buscar?q=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(users => {
            res.innerHTML = '';
            if (!users.length) {
                res.innerHTML = '<p style="color:var(--muted);font-size:.85rem;text-align:center;padding:.5rem;">Nenhum usuário encontrado</p>';
                return;
            }
            users.forEach(u => {
                const div = document.createElement('div');
                div.style.cssText = 'display:flex;align-items:center;gap:.6rem;padding:.55rem .8rem;border-radius:10px;border:1.5px solid var(--border);cursor:pointer;transition:background .15s;';
                div.innerHTML = `
                    <div style="width:32px;height:32px;border-radius:50%;background:#6366f1;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0;">
                        ${u.nome.charAt(0).toUpperCase()}
                    </div>
                    <span style="font-size:.9rem;font-weight:600;color:var(--text);flex:1;">${u.nome}</span>
                    <button onclick="enviarViaDM(${u.id}, '${u.nome.replace("'","\\'")}', event)"
                        style="padding:.35rem .85rem;border-radius:8px;border:none;background:#6366f1;color:#fff;font-size:.8rem;font-weight:700;cursor:pointer;">
                        Enviar
                    </button>
                `;
                div.onmouseover = () => div.style.background = 'var(--surface-2)';
                div.onmouseout  = () => div.style.background = 'transparent';
                res.appendChild(div);
            });
        });
    }, 350);
}

function enviarViaDM(userId, nomeUsuario, event) {
    if (event) event.stopPropagation();
    const url   = window.location.origin + '/posts/' + _compartilharPostId;
    const texto = '🔗 ' + _compartilharTitulo + '\n' + url;
    fetch('/dm/enviar-post', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ user_id: userId, texto: texto })
    })
    .then(r => r.json())
    .then(() => {
        fecharCompartilhar();
        showToast('Post enviado para ' + nomeUsuario + '! 💬');
    });
}

// ── Double tap like ──
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

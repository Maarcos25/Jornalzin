@php
    /**
     * Bin-packing: reorganiza os posts de um dia em linhas de 12 colunas
     * sem buracos, usando First Fit Decreasing (FFD).
     */
    function binPackPosts(iterable $posts): array
    {
        $widths = ['P' => 3, 'M' => 4, 'G' => 6, 'GG' => 12];

        // Ordena do maior para o menor (FFD é mais eficiente assim)
        $sorted = collect($posts)
            ->sortByDesc(fn($p) => $widths[$p->tamanho] ?? 4)
            ->values()
            ->all();

        $rows = [];

        while (!empty($sorted)) {
            $row        = [];
            $rowWidth   = 0;
            $notFit     = [];

            foreach ($sorted as $post) {
                $w = $widths[$post->tamanho] ?? 4;

                if ($rowWidth + $w <= 12) {
                    $row[]     = $post;
                    $rowWidth += $w;
                } else {
                    $notFit[] = $post;
                }
            }

            // Segurança: se nada coube (ex: post GG=12 e row já tem algo),
            // força o primeiro item na próxima iteração
            if (empty($row)) {
                $rows[]  = [array_shift($sorted)];
                $sorted  = array_values($sorted);
            } else {
                $rows[] = $row;
                $sorted = $notFit;
            }
        }

        return $rows;
    }
@endphp

@foreach ($postsPorDia as $dia => $postsNoDia)

    @php
        $dataFormatada = \Carbon\Carbon::parse($dia)->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        $rows = binPackPosts($postsNoDia);
    @endphp

    <!-- SEPARADOR DE DIA -->
    <div class="day-separator mb-3 mt-4" data-dia="{{ $dia }}">

        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-dark fs-6 px-3 py-2 text-capitalize">
                📅 {{ $dataFormatada }}
            </span>
            <hr class="flex-grow-1 m-0">
        </div>

        <!-- LINHAS BIN-PACKED -->
        @foreach ($rows as $linha)
            <div class="row g-3 mt-1">
                @foreach ($linha as $post)
                    <div class="{{ $post->coluna_bootstrap }}">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">

                                <a class="text-decoration-none text-dark"
                                   href="{{ route('posts.show', $post->id) }}">
                                    <h5 class="fw-bold">{{ $post->titulo }}</h5>
                                    <p class="text-muted small flex-grow-1">
                                        {{ Str::limit($post->texto, $post->tamanho === 'GG' ? 400 : ($post->tamanho === 'G' ? 250 : 120)) }}
                                    </p>
                                </a>

                                <div class="d-flex justify-content-between mt-auto mb-2">
                                    <small class="text-secondary">✍ {{ $post->usuario->name ?? 'Desconhecido' }}</small>
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
                                @if ($post->comments && $post->comments->count() > 0)
                                    <div class="mt-2">
                                        @foreach ($post->comments as $comment)
                                            <div style="border-top:1px solid #eee; padding:5px 0; font-size:13px;">
                                                <strong>{{ $comment->user->name ?? 'Usuário' }}</strong>:
                                                {{ $comment->texto }}
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
                                        <input type="text" name="texto" class="form-control"
                                            placeholder="Comentar...">
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

@if ($postsPorDia->isEmpty())
    <p class="text-center text-muted mt-5">Nenhuma postagem encontrada.</p>
@endif

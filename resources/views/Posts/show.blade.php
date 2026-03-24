@extends('layouts.site')

@section('conteudo')
<div class="container mt-4">

    <!-- POST -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title mb-3">{{ $post->titulo }}</h2>

            <span class="badge bg-primary mb-3">{{ $post->tipo }}</span>

            <p class="card-text">{{ $post->texto }}</p>

            <hr>

            <div class="d-flex justify-content-between text-muted">
                <small>
                    📅 {{ \Carbon\Carbon::parse($post->data)->format('d/m/Y H:i') }}
                </small>

                <small>
                    👁️ {{ $post->visualizacoes }} visualizações
                </small>
            </div>
        </div>
    </div>

    <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-4">
        ← Voltar
    </a>

    <!-- COMENTÁRIOS -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="mb-3">💬 Comentários</h4>

            @forelse ($post->comments as $comentario)
                <div class="border-bottom pb-2 mb-2">

                    <div class="d-flex justify-content-between">
                        <strong style="font-size: 14px;">
                            {{ $comentario->user->nome }}
                        </strong>

                        <small class="text-muted" style="font-size: 12px;">
                            {{ $comentario->created_at
                                ? $comentario->created_at->format('d/m/Y H:i')
                                : \Carbon\Carbon::parse($comentario->created_at)->format('d/m/Y H:i')
                            }}
                        </small>
                    </div>

                    <p class="mb-1" style="font-size: 14px;">
                        {{ $comentario->texto }}
                    </p>

                </div>
            @empty
                <p class="text-muted">Nenhum comentário ainda...</p>
            @endforelse
        </div>
    </div>

    <!-- FORM COMENTÁRIO -->
    @auth
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Adicionar Comentário</h5>

                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <textarea
                            name="texto"
                            class="form-control"
                            rows="3"
                            placeholder="Digite seu comentário..."
                            required
                        ></textarea>
                    </div>

                    <input type="hidden" name="post_id" value="{{ $post->id }}" />

                    <button class="btn btn-success">
                        💾 Comentar
                    </button>
                </form>
            </div>
        </div>
    @endauth

</div>
@endsection

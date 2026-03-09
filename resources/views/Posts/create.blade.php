@extends('layouts.site')

@section('conteudo')
    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    <h4>Criar Post</h4>
                </div>

                <div class="card-body">

                    <form action="{{ route('posts.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-select">
                                <option value="imagem">Imagem</option>
                                <option value="video">Vídeo</option>
                                <option value="texto">Texto</option>
                                <option value="enquete">Enquete</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Texto</label>
                            <textarea name="texto" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Data</label>
                            <input type="date" name="data" class="form-control">
                        </div>

                        <input type="hidden" name="id_usuario" value="1">

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                                Voltar
                            </a>

                            <button type="submit" class="btn btn-success">
                                Salvar Post
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection

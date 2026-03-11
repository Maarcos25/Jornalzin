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
                            <div id="campoTexto" class="mb-3">
                                <label class="form-label">Texto</label>
                                <textarea name="texto" class="form-control"></textarea>
                            </div>

                            <div id="campoImagem" class="mb-3" style="display:none;">
                                <label class="form-label">Imagem</label>
                                <input type="file" name="imagem" class="form-control">
                            </div>

                            <div id="campoVideo" class="mb-3" style="display:none;">
                                <label class="form-label">Link do Vídeo</label>
                                <input type="text" name="video" class="form-control">
                            </div>

                            <div id="campoEnquete" style="display:none;">

                                <label class="form-label">Opção 1</label>
                                <input type="text" name="opcao1" class="form-control mb-2">

                                <label class="form-label">Opção 2</label>
                                <input type="text" name="opcao2" class="form-control mb-2">

                                <label class="form-label">Opção 3</label>
                                <input type="text" name="opcao3" class="form-control mb-2">

                                <label class="form-label">Opção 4</label>
                                <input type="text" name="opcao4" class="form-control mb-2">

                            </div>
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

                            <script>

                                document.querySelector("select[name='tipo']").addEventListener("change", function(){

                                    let tipo = this.value;

                                    document.getElementById("campoTexto").style.display = "none";
                                    document.getElementById("campoImagem").style.display = "none";
                                    document.getElementById("campoVideo").style.display = "none";
                                    document.getElementById("campoEnquete").style.display = "none";

                                    if(tipo == "texto"){
                                        document.getElementById("campoTexto").style.display = "block";
                                    }

                                    if(tipo == "imagem"){
                                        document.getElementById("campoImagem").style.display = "block";
                                    }

                                    if(tipo == "video"){
                                        document.getElementById("campoVideo").style.display = "block";
                                    }

                                    if(tipo == "enquete"){
                                        document.getElementById("campoEnquete").style.display = "block";
                                    }

                                });

                                </script>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection

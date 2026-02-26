@extends('layouts.app')

@section('content')
<h1>Criar Post</h1>

<form action="{{ route('posts.store') }}" method="POST">
    @csrf

    <label>Tipo:</label>
    <select name="tipo">
        <option value="imagem">Imagem</option>
        <option value="video">Vídeo</option>
        <option value="texto">Texto</option>
        <option value="enquete">Enquete</option>
    </select>

    <label>Título:</label>
    <input type="text" name="titulo">

    <label>Texto:</label>
    <textarea name="texto"></textarea>

    <label>Data:</label>
    <input type="date" name="data">

    <input type="hidden" name="id_usuario" value="1">

    <button type="submit">Salvar</button>
</form>
@endsection

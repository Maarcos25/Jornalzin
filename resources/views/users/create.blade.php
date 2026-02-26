@extends('layouts.app')

@section('conteudo')
<h1>Criar Usuário</h1>

@if ($errors->any())
    <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
        <strong>Ocorreram erros ao enviar o formulário:</strong>
        <ul>
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('users.store') }}">
    @csrf

    <input type="text" name="nome" placeholder="Nome" value="{{ old('nome', $user->nome ?? '') }}">
    <input type="text" name="sobrenome" placeholder="Sobrenome" value="{{ old('sobrenome', $user->sobrenome ?? '') }}">
    <input type="email" name="email" placeholder="Email" value="{{ old('email', $user->email ?? '') }}">
    <input type="text" name="ra" placeholder="RA" value="{{ old('ra', $user->ra ?? '') }}">
    <input type="text" name="telefone" placeholder="Telefone" value="{{ old('telefone', $user->telefone ?? '') }}">
    <input type="date" name="data_nascimento" value="{{ old('data_nascimento', $user->data_nascimento ?? '') }}">

    <select name="tipo">
        <option value="administrador">Administrador</option>
        <option value="editor">Editor</option>
        <option value="leitor">Leitor</option>
    </select>

    <input type="password" name="password" placeholder="Senha">
    <input type="password" name="password_confirmation" placeholder="Confirmar Senha">

    <button type="submit">Salvar</button>
</form>

@endsection

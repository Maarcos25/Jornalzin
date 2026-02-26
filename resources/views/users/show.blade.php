@extends('layouts.app')

@section('conteudo')
<h1>Detalhes do Usuário</h1>

<p>Nome: {{ $user->nome }} {{ $user->sobrenome }}</p>
<p>Email: {{ $user->email }}</p>
<p>RA: {{ $user->ra }}</p>
<p>Telefone: {{ $user->telefone }}</p>
<p>Data Nascimento: {{ $user->data_nascimento }}</p>
<p>Tipo: {{ $user->tipo }}</p>

@endsection

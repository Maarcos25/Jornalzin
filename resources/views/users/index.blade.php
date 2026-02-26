@extends('layouts.app')

@section('conteudo')

<h1>Lista de Usuários</h1>

<a href="{{ route('users.create') }}">
    <button>Criar Novo Usuário</button>
</a>

<br><br>

@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Ações</th>
    </tr>

    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->nome }} {{ $user->sobrenome }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->tipo }}</td>
        <td>
            <a href="{{ route('users.show', $user->id) }}">Ver</a> |
            <a href="{{ route('users.edit', $user->id) }}">Editar</a> |

            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Excluir</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<br>

{{ $users->links() }}

@endsection

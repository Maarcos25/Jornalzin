@extends('layouts.site')

@section('conteudo')

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4>Criar Usuário</h4>
        </div>

        <div class="card-body">

            {{-- ERROS DE VALIDAÇÃO --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ocorreram erros:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome</label>
                        <input
                            type="text"
                            name="nome"
                            class="form-control @error('nome') is-invalid @enderror"
                            value="{{ old('nome') }}"
                        >
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sobrenome</label>
                        <input
                            type="text"
                            name="sobrenome"
                            class="form-control @error('sobrenome') is-invalid @enderror"
                            value="{{ old('sobrenome') }}"
                        >
                        @error('sobrenome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">RA</label>
                        <input
                            type="text"
                            name="ra"
                            class="form-control @error('ra') is-invalid @enderror"
                            value="{{ old('ra') }}"
                        >
                        @error('ra')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telefone</label>
                        <input
                            type="text"
                            name="telefone"
                            class="form-control @error('telefone') is-invalid @enderror"
                            value="{{ old('telefone') }}"
                        >
                        @error('telefone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">Data de Nascimento</label>
                        <input
                            type="date"
                            name="data_nascimento"
                            class="form-control @error('data_nascimento') is-invalid @enderror"
                            value="{{ old('data_nascimento') }}"
                        >
                        @error('data_nascimento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="mb-3">
                    <label class="form-label">Tipo de Usuário</label>
                    <select name="tipo" class="form-select">
                        <option value="administrador">Administrador</option>
                        <option value="editor">Editor</option>
                        <option value="leitor">Leitor</option>
                    </select>
                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Senha</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirmar Senha</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                        >
                    </div>

                </div>


                <div class="mt-3">
                    <button class="btn btn-success">
                        Salvar Usuário
                    </button>

                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection

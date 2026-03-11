@extends('layouts.auth')

@section('content')

<div class="login-container">

    <div class="login-card">

        <h3 class="text-center mb-3">Jornalzin</h3>

        <p class="text-center mb-4">
            Esqueceu sua senha? Digite seu email para receber o link de recuperação.
        </p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Enviar link de recuperação
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}">
                Voltar para login
            </a>
        </div>

    </div>

</div>

@endsection

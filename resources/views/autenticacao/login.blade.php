@extends('layouts.autenticacao')

@section('conteudo')
<style>
    .login-header h2 { font-weight: 800; color: #333; margin-bottom: 4px; }
    .form-label { font-weight: 600; font-size: 0.85rem; color: #6c757d; text-transform: uppercase; margin-bottom: 5px; }
    .form-control { border-radius: 10px; padding: 10px 12px; border: 1px solid #e0e0e0; transition: 0.3s; }
    .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.1); }
    .btn-entrar { border-radius: 10px; padding: 10px; font-weight: 600; transition: 0.3s; }
    .btn-entrar:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13,110,253,0.2); }
    .btn-create { border-radius: 50px; font-weight: 500; font-size: 0.9rem; }
    .password-wrapper { position: relative; }
    .password-toggle {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        cursor: pointer; opacity: 0.6; transition: 0.2s; z-index: 10;
    }
    .password-toggle:hover { opacity: 1; }
    .forgot-link { font-size: 0.85rem; text-decoration: none; color: #6c757d; transition: 0.2s; }
    .forgot-link:hover { color: #0d6efd; text-decoration: underline; }
    .btn-google {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; padding: 10px; border-radius: 10px;
        border: 1px solid #e0e0e0; background: #fff;
        font-weight: 600; color: #444; text-decoration: none; transition: 0.3s;
    }
    .btn-google:hover { background: #f8f9fa; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transform: translateY(-2px); color: #444; }
    .divider { display: flex; align-items: center; gap: 10px; margin: 12px 0; }
    .divider hr { flex: 1; border-color: #e0e0e0; }
    .divider span { font-size: 0.85rem; color: #aaa; }
    .recaptcha-wrap { display: flex; justify-content: center; margin: 0.6rem 0; transform: scale(0.92); transform-origin: center; }
    .recaptcha-error { color: #dc3545; font-size: .82rem; text-align: center; margin-top: .3rem; }
</style>

<div class="login-header text-center mb-2">
    <a href="{{ route('home') }}" class="text-decoration-none d-block">
        <h2><span>📰</span> Jornalzin</h2>
    </a>
    <p class="text-muted small mb-0">Insira seu e-mail e senha para continuar</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-2" style="border-radius: 10px;">
        <small>⚠️ {{ $errors->first() }}</small>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-2">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control"
               placeholder="seu@email.com" value="{{ old('email') }}" required autofocus>
    </div>

    <div class="mb-2">
        <label class="form-label">Senha</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" class="form-control"
                   placeholder="••••••••" required>
            <span class="password-toggle" onclick="toggleSenha()">
                <span id="eyeIcon">👁️</span>
            </span>
        </div>
    </div>

    {{-- reCAPTCHA v2 --}}
    <div class="recaptcha-wrap">
        <div class="g-recaptcha" data-sitekey="6LdEkq8sAAAAAN2mOiAGTzFvRU_KegRSD3CAzkfV"></div>
    </div>
    @error('g-recaptcha-response')
        <p class="recaptcha-error">⚠️ Por favor, confirme que você não é um robô.</p>
    @enderror

    <div class="d-grid mb-2 mt-2">
        <button type="submit" class="btn btn-primary btn-entrar">
            Entrar no Sistema
        </button>
    </div>
</form>

<div class="divider"><hr><span>ou</span><hr></div>

<a href="{{ route('auth.google') }}" class="btn-google mb-2">
    <svg width="20" height="20" viewBox="0 0 48 48">
        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
    </svg>
    Entrar com Google
</a>

<div class="text-center mb-1">
    <p class="text-muted small mb-1">Ainda não tem acesso?</p>
    <a href="{{ url('/users/create') }}" class="btn btn-outline-primary btn-create px-4">
        Criar conta
    </a>
</div>

<div class="text-center mt-2">
    <a href="{{ route('password.request') }}" class="forgot-link">Esqueceu sua senha?</a>
</div>

@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js"></script>
@endpush

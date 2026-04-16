@extends('layouts.autenticacao')

@section('conteudo')
<style>
    :root {
        --brand: #4f46e5; --brand-dark: #3730a3;
        --surface: #fff; --surface-2: #f8fafc;
        --border: #e2e8f0; --text: #1e293b;
        --muted: #64748b; --danger: #ef4444;
        --radius: 14px; --shadow: 0 2px 12px rgba(0,0,0,.07);
    }
    .wrap { max-width: 520px; margin: 0 auto; font-family: 'Segoe UI', sans-serif; }
    .card { background: var(--surface); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden; }
    .card-header { background: linear-gradient(135deg, var(--brand), var(--brand-dark)); padding: 1.4rem 1.8rem; }
    .card-header h4 { color: #fff; margin: 0; font-weight: 700; font-size: 1.1rem; }
    .card-header p  { color: rgba(255,255,255,.8); margin: .3rem 0 0; font-size: .88rem; }
    .card-body { padding: 1.6rem 1.8rem; }
    .card-footer { padding: 1rem 1.8rem; border-top: 1px solid var(--border); background: var(--surface-2); display:flex; justify-content: flex-end; }

    .google-info {
        display: flex; align-items: center; gap: .75rem;
        background: var(--surface-2); border: 1px solid var(--border);
        border-radius: 10px; padding: .75rem 1rem; margin-bottom: 1.4rem;
    }
    .google-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    .google-info .name { font-weight: 700; color: var(--text); font-size: .95rem; }
    .google-info .email { font-size: .82rem; color: var(--muted); }

    .form-group { margin-bottom: 1.1rem; }
    .form-group label { display: block; font-size: .8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .4rem; }
    .form-group input { width: 100%; padding: .65rem .9rem; border: 2px solid var(--border); border-radius: 10px; font-size: .93rem; color: var(--text); background: var(--surface); transition: border .2s; box-sizing: border-box; font-family: inherit; }
    .form-group input:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
    .form-group .err { color: var(--danger); font-size: .8rem; margin-top: .25rem; }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media(max-width: 480px) { .row-2 { grid-template-columns: 1fr; } }

    .err-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: .8rem 1rem; color: #b91c1c; font-size: .88rem; margin-bottom: 1.2rem; }
    .err-box ul { margin: .3rem 0 0 1rem; padding: 0; }

    .btn-salvar { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.6rem; border-radius: 10px; border: none; background: linear-gradient(135deg, var(--brand), var(--brand-dark)); color: #fff; font-weight: 700; font-size: .95rem; cursor: pointer; transition: all .2s; box-shadow: 0 4px 14px rgba(79,70,229,.3); }
    .btn-salvar:hover { transform: translateY(-1px); }
</style>

<div class="wrap">
    <div class="card">
        <div class="card-header">
            <h4>🎉 Quase lá! Complete seu perfil</h4>
            <p>Precisamos de mais alguns dados para finalizar seu cadastro.</p>
        </div>

        <div class="card-body">

            {{-- Info do Google --}}
            <div class="google-info">
                @if($googleUser['avatar'] ?? null)
                    <img src="{{ $googleUser['avatar'] }}" alt="">
                @endif
                <div>
                    <div class="name">{{ $googleUser['nome'] }}</div>
                    <div class="email">{{ $googleUser['email'] }}</div>
                </div>
            </div>

            @if ($errors->any())
                <div class="err-box">
                    <strong>Corrija os erros:</strong>
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('auth.google.completar.store') }}" id="completarForm">
                @csrf

                <div class="form-group">
                    <label>Sobrenome</label>
                    <input type="text" name="sobrenome" placeholder="Silva" value="{{ old('sobrenome') }}">
                </div>

                <div class="row-2">
                    <div class="form-group">
                        <label>RA <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="ra" placeholder="Ex: 12345" value="{{ old('ra') }}" required>
                        @error('ra') <div class="err">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Telefone <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="telefone" placeholder="18912345678"
                               maxlength="11"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                               value="{{ old('telefone') }}" required>
                        @error('telefone') <div class="err">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Data de Nascimento <span style="color:var(--danger)">*</span></label>
                    <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" required>
                    @error('data_nascimento') <div class="err">{{ $message }}</div> @enderror
                </div>

            </form>
        </div>

        <div class="card-footer">
            <button type="submit" form="completarForm" class="btn-salvar">✓ Concluir Cadastro</button>
        </div>
    </div>
</div>
@endsection

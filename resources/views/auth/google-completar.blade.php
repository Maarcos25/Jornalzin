@extends('layouts.autenticacao')

@section('conteudo')
<style>
    :root {
        --brand: #4f46e5; --brand-dark: #3730a3;
        --surface: #fff; --surface-2: #f8fafc;
        --border: #e2e8f0; --text: #1e293b;
        --muted: #64748b; --danger: #ef4444;
        --radius: 16px;
    }

    body {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .wrap {
        width: 100%;
        max-width: 620px;
        font-family: 'Segoe UI', sans-serif;
    }

    .card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: 0 20px 60px rgba(0,0,0,.3);
        border: none;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        padding: 2rem 2.2rem;
    }
    .card-header h4 {
        color: #fff; margin: 0;
        font-weight: 800; font-size: 1.4rem;
    }
    .card-header p {
        color: rgba(255,255,255,.8);
        margin: .5rem 0 0; font-size: .95rem;
    }

    .card-body { padding: 2rem 2.2rem; }

    .card-footer {
        padding: 1.2rem 2.2rem;
        border-top: 1px solid var(--border);
        background: var(--surface-2);
        display: flex;
        justify-content: flex-end;
    }

    .google-info {
        display: flex; align-items: center; gap: 1rem;
        background: var(--surface-2); border: 1px solid var(--border);
        border-radius: 12px; padding: 1rem 1.2rem; margin-bottom: 1.8rem;
    }
    .google-info img {
        width: 52px; height: 52px;
        border-radius: 50%; object-fit: cover;
        border: 2px solid var(--brand);
    }
    .google-info .name { font-weight: 700; color: var(--text); font-size: 1rem; }
    .google-info .email { font-size: .85rem; color: var(--muted); margin-top: .1rem; }

    .form-group { margin-bottom: 1.3rem; }
    .form-group label {
        display: block; font-size: .82rem; font-weight: 700;
        color: var(--muted); text-transform: uppercase;
        letter-spacing: .04em; margin-bottom: .45rem;
    }
    .form-group input {
        width: 100%; padding: .75rem 1rem;
        border: 2px solid var(--border); border-radius: 10px;
        font-size: .95rem; color: var(--text); background: var(--surface);
        transition: border .2s; box-sizing: border-box; font-family: inherit;
    }
    .form-group input:focus {
        outline: none; border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    }
    .form-group input.is-invalid { border-color: var(--danger); }
    .form-group input.is-valid   { border-color: #22c55e; }
    .form-group .err  { color: var(--danger); font-size: .82rem; margin-top: .3rem; display: flex; align-items: center; gap: .3rem; }
    .form-group .hint { color: var(--muted);  font-size: .82rem; margin-top: .3rem; }
    .form-group .ok   { color: #15803d;       font-size: .82rem; margin-top: .3rem; display: flex; align-items: center; gap: .3rem; }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
    @media(max-width: 500px) { .row-2 { grid-template-columns: 1fr; } }

    .err-box {
        background: #fef2f2; border: 1px solid #fecaca;
        border-radius: 10px; padding: .9rem 1.1rem;
        color: #b91c1c; font-size: .88rem; margin-bottom: 1.4rem;
    }
    .err-box ul { margin: .3rem 0 0 1rem; padding: 0; }

    .btn-salvar {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .75rem 2rem; border-radius: 10px; border: none;
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color: #fff; font-weight: 700; font-size: 1rem;
        cursor: pointer; transition: all .2s;
        box-shadow: 0 4px 14px rgba(79,70,229,.35);
    }
    .btn-salvar:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79,70,229,.45); }

    .tel-wrap { position: relative; }
    .tel-wrap input { padding-left: 3rem; }
    .tel-prefix {
        position: absolute; left: .9rem; top: 50%;
        transform: translateY(-50%);
        font-size: .9rem; color: var(--muted); font-weight: 600;
        pointer-events: none;
    }
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

            <form method="POST" action="{{ route('auth.google.completar.store') }}" id="completarForm" novalidate>
                @csrf

                <div class="form-group">
                    <label>Sobrenome</label>
                    <input type="text" name="sobrenome" placeholder="Silva"
                           value="{{ old('sobrenome') }}" id="inputSobrenome">
                </div>

                <div class="row-2">
                    <div class="form-group">
                        <label>RA <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="ra" id="inputRA"
                               placeholder="Ex: 12345"
                               value="{{ old('ra') }}"
                               maxlength="10"
                               oninput="this.value=this.value.replace(/[^0-9]/g,''); validarRA();"
                               required>
                        <div class="hint" id="raHint">Somente números</div>
                        @error('ra') <div class="err">⚠️ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Telefone <span style="color:var(--danger)">*</span></label>
                        <div class="tel-wrap">
                            <span class="tel-prefix">📱</span>
                            <input type="text" name="telefone" id="inputTelefone"
                                   placeholder="18912345678"
                                   maxlength="11"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,''); validarTelefone();"
                                   value="{{ old('telefone') }}" required>
                        </div>
                        <div class="hint" id="telHint">DDD + número (10 ou 11 dígitos)</div>
                        @error('telefone') <div class="err">⚠️ {{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Data de Nascimento <span style="color:var(--danger)">*</span></label>
                    <input type="date" name="data_nascimento" id="inputNascimento"
                           value="{{ old('data_nascimento') }}"
                           onchange="validarNascimento()"
                           max="{{ date('Y-m-d', strtotime('-10 years')) }}"
                           required>
                    <div class="hint" id="nascHint">Você deve ter pelo menos 10 anos</div>
                    @error('data_nascimento') <div class="err">⚠️ {{ $message }}</div> @enderror
                </div>

            </form>
        </div>

        <div class="card-footer">
            <button type="submit" form="completarForm" class="btn-salvar" id="btnSubmit">
                ✓ Concluir Cadastro
            </button>
        </div>
    </div>
</div>

<script>
function validarRA() {
    const input = document.getElementById('inputRA');
    const hint  = document.getElementById('raHint');
    const val   = input.value.trim();

    if (val.length === 0) {
        input.className = ''; hint.className = 'hint';
        hint.textContent = 'Somente números'; return;
    }
    if (val.length < 4) {
        input.classList.add('is-invalid'); input.classList.remove('is-valid');
        hint.className = 'err'; hint.textContent = '⚠️ RA muito curto (mínimo 4 dígitos)';
    } else {
        input.classList.add('is-valid'); input.classList.remove('is-invalid');
        hint.className = 'ok'; hint.textContent = '✓ RA válido';
    }
}

function validarTelefone() {
    const input = document.getElementById('inputTelefone');
    const hint  = document.getElementById('telHint');
    const val   = input.value.trim();

    if (val.length === 0) {
        input.className = ''; hint.className = 'hint';
        hint.textContent = 'DDD + número (10 ou 11 dígitos)'; return;
    }

    const ddd = parseInt(val.substring(0, 2));
    const dddsValidos = [11,12,13,14,15,16,17,18,19,21,22,24,27,28,31,32,33,34,35,37,38,41,42,43,44,45,46,47,48,49,51,53,54,55,61,62,63,64,65,66,67,68,69,71,73,74,75,77,79,81,82,83,84,85,86,87,88,89,91,92,93,94,95,96,97,98,99];

    if (val.length < 10 || val.length > 11) {
        input.classList.add('is-invalid'); input.classList.remove('is-valid');
        hint.className = 'err'; hint.textContent = '⚠️ Deve ter 10 ou 11 dígitos';
    } else if (!dddsValidos.includes(ddd)) {
        input.classList.add('is-invalid'); input.classList.remove('is-valid');
        hint.className = 'err'; hint.textContent = '⚠️ DDD inválido';
    } else {
        input.classList.add('is-valid'); input.classList.remove('is-invalid');
        hint.className = 'ok'; hint.textContent = '✓ Telefone válido';
    }
}

function validarNascimento() {
    const input = document.getElementById('inputNascimento');
    const hint  = document.getElementById('nascHint');
    const val   = input.value;

    if (!val) {
        input.className = ''; hint.className = 'hint';
        hint.textContent = 'Você deve ter pelo menos 10 anos'; return;
    }

    const nasc  = new Date(val);
    const hoje  = new Date();
    let idade   = hoje.getFullYear() - nasc.getFullYear();
    const m     = hoje.getMonth() - nasc.getMonth();
    if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) idade--;

    if (nasc > hoje) {
        input.classList.add('is-invalid'); input.classList.remove('is-valid');
        hint.className = 'err'; hint.textContent = '⚠️ Data não pode ser no futuro';
    } else if (idade < 10) {
        input.classList.add('is-invalid'); input.classList.remove('is-valid');
        hint.className = 'err'; hint.textContent = `⚠️ Você precisa ter pelo menos 10 anos (você tem ${idade})`;
    } else if (idade > 120) {
        input.classList.add('is-invalid'); input.classList.remove('is-valid');
        hint.className = 'err'; hint.textContent = '⚠️ Data inválida';
    } else {
        input.classList.add('is-valid'); input.classList.remove('is-invalid');
        hint.className = 'ok'; hint.textContent = `✓ Idade: ${idade} anos`;
    }
}

// Bloqueia submit se houver campos inválidos
document.getElementById('completarForm').addEventListener('submit', function(e) {
    const tel  = document.getElementById('inputTelefone').value.trim();
    const nasc = document.getElementById('inputNascimento').value;
    const ra   = document.getElementById('inputRA').value.trim();

    let erros = [];

    if (ra.length < 4) erros.push('RA inválido (mínimo 4 dígitos)');
    if (tel.length < 10 || tel.length > 11) erros.push('Telefone inválido (10 ou 11 dígitos)');

    if (nasc) {
        const nascDate = new Date(nasc);
        const hoje = new Date();
        let idade = hoje.getFullYear() - nascDate.getFullYear();
        const m = hoje.getMonth() - nascDate.getMonth();
        if (m < 0 || (m === 0 && hoje.getDate() < nascDate.getDate())) idade--;
        if (idade < 10) erros.push('Você precisa ter pelo menos 10 anos');
    }

    if (erros.length > 0) {
        e.preventDefault();
        alert('⚠️ Corrija os erros:\n\n' + erros.join('\n'));
    }
});
</script>
@endsection
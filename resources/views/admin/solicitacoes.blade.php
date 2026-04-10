@extends('layouts.site')

@section('conteudo')
<div style="max-width:900px;margin:0 auto;padding:2rem 1.5rem;">

    <h1 style="font-size:1.7rem;font-weight:800;margin-bottom:1.5rem;">📋 Solicitações de Editor</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($solicitacoes->isEmpty())
        <div style="text-align:center;padding:3rem;color:#64748b;">
            <div style="font-size:2.5rem;">📭</div>
            <p>Nenhuma solicitação pendente.</p>
        </div>
    @else
        @foreach($solicitacoes as $s)
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:1.5rem;margin-bottom:1rem;box-shadow:0 2px 12px rgba(0,0,0,.07);">
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#3730a3);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;">
                    {{ strtoupper(substr($s->user->nome ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:700;color:#1e293b;">{{ $s->user->nome }} {{ $s->user->sobrenome }}</div>
                    <div style="font-size:.82rem;color:#64748b;">{{ $s->user->email }} · {{ $s->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <span style="margin-left:auto;background:#fef3c7;color:#92400e;padding:.25rem .8rem;border-radius:50px;font-size:.75rem;font-weight:700;">⏳ Pendente</span>
            </div>

            <div style="margin-bottom:.75rem;">
                <div style="font-size:.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.3rem;">Motivo</div>
                <p style="color:#334155;font-size:.93rem;">{{ $s->motivo }}</p>
            </div>

            @if($s->experiencia)
            <div style="margin-bottom:1rem;">
                <div style="font-size:.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.3rem;">Experiência</div>
                <p style="color:#334155;font-size:.93rem;">{{ $s->experiencia }}</p>
            </div>
            @endif

            <div style="display:flex;gap:.6rem;">
                <form method="POST" action="{{ route('editor.aprovar', $s->id) }}">
                    @csrf
                    <button type="submit" style="background:#10b981;color:#fff;border:none;border-radius:50px;padding:.5rem 1.2rem;font-weight:700;cursor:pointer;">
                        ✅ Aprovar
                    </button>
                </form>
                <form method="POST" action="{{ route('editor.rejeitar', $s->id) }}"
                      onsubmit="return confirm('Rejeitar esta solicitação?')">
                    @csrf
                    <button type="submit" style="background:#fef2f2;color:#ef4444;border:1.5px solid #fecaca;border-radius:50px;padding:.5rem 1.2rem;font-weight:700;cursor:pointer;">
                        ❌ Rejeitar
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    @endif

</div>
@endsection

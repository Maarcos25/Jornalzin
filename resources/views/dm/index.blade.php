@extends('layouts.site')

@push('styles')
<style>
    .dm-wrap { max-width: 700px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }
    .dm-title { font-size: 1.5rem; font-weight: 800; color: var(--text); margin-bottom: 1.5rem; }
    .dm-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; padding: 1rem 1.2rem;
        display: flex; align-items: center; gap: 1rem;
        margin-bottom: .75rem; text-decoration: none; color: inherit;
        transition: box-shadow .2s, transform .2s;
    }
    .dm-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.1); transform: translateY(-2px); }
    .dm-avatar {
        width: 50px; height: 50px; border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #3730a3);
        color: #fff; font-size: 1.2rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; overflow: hidden;
    }
    .dm-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .dm-info { flex: 1; min-width: 0; }
    .dm-nome { font-weight: 700; color: var(--text); font-size: .95rem; }
    .dm-preview { font-size: .83rem; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: .1rem; }
    .dm-meta { text-align: right; flex-shrink: 0; }
    .dm-tempo { font-size: .75rem; color: var(--muted); }
    .dm-badge { display: inline-block; background: #4f46e5; color: #fff; border-radius: 50px; font-size: .72rem; font-weight: 700; padding: .15rem .55rem; margin-top: .25rem; }
    .dm-vazio { text-align: center; padding: 4rem 1rem; color: var(--muted); }
    .dm-vazio div { font-size: 3rem; margin-bottom: 1rem; }
</style>
@endpush

@section('conteudo')
<div class="dm-wrap">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <h1 class="dm-title" style="margin:0;">Ì≤¨ Mensagens</h1>
    </div>
    @if($conversas->isEmpty())
        <div class="dm-vazio">
            <div>Ì≤¨</div>
            <p>Nenhuma conversa ainda.</p>
            <p style="font-size:.88rem;">V√° ao perfil de um usu√°rio e clique em "Enviar mensagem".</p>
        </div>
    @else
        @foreach($conversas as $conversa)
            @php
                $outro    = $conversa->outroUsuario();
                $ultima   = $conversa->mensagens->last();
                $naoLidas = $conversa->naoLidas();
            @endphp
            <a href="{{ route('dm.conversa', $conversa->id) }}" class="dm-card">
                <div class="dm-avatar">
                    @if($outro && $outro->avatar)
                        <img src="{{ asset('storage/' . $outro->avatar) }}" alt="">
                    @else
                        {{ strtoupper(substr($outro->nome ?? 'U', 0, 1)) }}
                    @endif
                </div>
                <div class="dm-info">
                    <div class="dm-nome">{{ $outro->nome ?? '' }} {{ $outro->sobrenome ?? '' }}</div>
                    <div class="dm-preview">
                        @if($ultima)
                            {{ $ultima->remetente_id === auth()->id() ? 'Voc√™: ' : '' }}{{ Str::limit($ultima->texto, 50) }}
                        @else
                            Nenhuma mensagem ainda
                        @endif
                    </div>
                </div>
                <div class="dm-meta">
                    <div class="dm-tempo">{{ $conversa->ultima_mensagem_at ? $conversa->ultima_mensagem_at->diffForHumans() : '' }}</div>
                    @if($naoLidas > 0)
                        <span class="dm-badge">{{ $naoLidas }}</span>
                    @endif
                </div>
            </a>
        @endforeach
    @endif
</div>
@endsection

@extends('layouts.site')

@push('styles')
<style>
    .chat-wrap { max-width: 700px; margin: 0 auto; padding: 1.5rem 1.5rem 2rem; }

    .chat-header {
        display: flex; align-items: center; gap: 1rem;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; padding: 1rem 1.2rem; margin-bottom: 1rem;
    }
    .chat-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #3730a3);
        color: #fff; font-size: 1.1rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; overflow: hidden;
    }
    .chat-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .chat-nome { font-weight: 700; color: var(--text); font-size: 1rem; }
    .chat-subtitulo { font-size: .8rem; color: var(--muted); }

    .chat-messages {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; padding: 1.2rem;
        min-height: 400px; max-height: 500px;
        overflow-y: auto; margin-bottom: 1rem;
        display: flex; flex-direction: column; gap: .8rem;
    }

    .msg-row { display: flex; }
    .msg-row.mine  { justify-content: flex-end; }
    .msg-row.other { justify-content: flex-start; }

    .msg-content {
        display: flex; flex-direction: column;
        max-width: 65%;
    }
    .msg-row.mine  .msg-content { align-items: flex-end; }
    .msg-row.other .msg-content { align-items: flex-start; }

    .msg-bubble {
        padding: .55rem 1rem;
        border-radius: 18px;
        font-size: .95rem;
        line-height: 1.5;
        word-break: break-word;
        white-space: pre-wrap;
        /* FIX: não deixa a bolha encolher demais */
        width: fit-content;
        min-width: 2.5rem;
    }
    .msg-row.mine .msg-bubble {
        background: #4f46e5; color: #fff;
        border-bottom-right-radius: 5px;
    }
    .msg-row.other .msg-bubble {
        background: var(--surface-2); color: var(--text);
        border: 1px solid var(--border);
        border-bottom-left-radius: 5px;
    }

    .msg-author { font-size: .75rem; color: var(--muted); margin-bottom: .2rem; }
    .msg-time   { font-size: .7rem;  color: var(--muted); margin-top: .25rem; }

    .chat-form {
        display: flex; gap: .5rem;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 50px; padding: .5rem .5rem .5rem 1rem;
    }
    .chat-form input {
        flex: 1; border: none; outline: none;
        background: transparent; font-size: .95rem; color: var(--text);
    }
    .chat-form input::placeholder { color: var(--muted); }
    .chat-form button {
        background: #4f46e5; color: #fff; border: none;
        border-radius: 50px; padding: .5rem 1.2rem;
        font-size: .92rem; font-weight: 700;
        cursor: pointer; transition: background .2s;
    }
    .chat-form button:hover { background: #3730a3; }

    .dm-vazio-msg {
        text-align: center; color: var(--muted);
        padding: 2rem; font-size: .9rem;
        margin: auto;
    }
</style>
@endpush

@section('conteudo')
<div class="chat-wrap">

    <div style="margin-bottom:.75rem;">
        <a href="{{ route('dm.index') }}" style="color:var(--muted);text-decoration:none;font-size:.9rem;font-weight:600;">← Voltar</a>
    </div>

    <div class="chat-header">
        <div class="chat-avatar">
            @if($outro->avatar)
                <img src="{{ asset('storage/' . $outro->avatar) }}" alt="">
            @else
                {{ strtoupper(substr($outro->nome, 0, 1)) }}
            @endif
        </div>
        <div>
            <div class="chat-nome">
                <a href="{{ route('users.perfil', $outro->id) }}" style="text-decoration:none;color:inherit;">
                    {{ $outro->nome }} {{ $outro->sobrenome ?? '' }}
                </a>
            </div>
            <div class="chat-subtitulo">{{ ucfirst($outro->tipo) }}</div>
        </div>
    </div>

    <div class="chat-messages" id="chatMessages">
        @if($mensagens->isEmpty())
            <div class="dm-vazio-msg">Nenhuma mensagem ainda. Diga olá! 👋</div>
        @else
            @foreach($mensagens as $msg)
                @php $isMine = $msg->remetente_id === auth()->id(); @endphp
                <div class="msg-row {{ $isMine ? 'mine' : 'other' }}">
                    <div class="msg-content">
                        @if(!$isMine)
                            <div class="msg-author">{{ $msg->remetente->nome }}</div>
                        @endif
                        <div class="msg-bubble">{{ $msg->texto }}</div>
                        <div class="msg-time">{{ $msg->created_at->format('d/m H:i') }}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <form method="POST" action="{{ route('dm.enviar', $conversa->id) }}" class="chat-form">
        @csrf
        <input type="text" name="texto" placeholder="Digite uma mensagem..."
               required autocomplete="off" id="msgInput">
        <button type="submit">Enviar ➤</button>
    </form>

</div>
@endsection

@push('scripts')
<script>
    const chat = document.getElementById('chatMessages');
    chat.scrollTop = chat.scrollHeight;
    document.getElementById('msgInput').focus();
</script>
@endpush

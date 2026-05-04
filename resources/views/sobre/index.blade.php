@extends('layouts.site')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=UnifrakturMaguntia&display=swap" rel="stylesheet">
<style>
    .sobre-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem 5rem;
    }

    /* Hero */
    .sobre-hero {
        text-align: center;
        padding: 3.5rem 2rem 2.5rem;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 24px;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .sobre-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .sobre-hero-logo {
        font-family: 'UnifrakturMaguntia', cursive;
        font-size: 4rem;
        color: #fff;
        margin-bottom: .5rem;
        position: relative;
    }
    .sobre-hero-sub {
        color: rgba(255,255,255,.85);
        font-size: 1.1rem;
        font-weight: 500;
        margin: 0;
        position: relative;
    }
    .sobre-hero-badge {
        display: inline-block;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        font-size: .78rem;
        font-weight: 700;
        padding: .3rem .9rem;
        border-radius: 50px;
        margin-bottom: 1rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        position: relative;
    }

    /* Cards de seção */
    .sobre-section {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2rem 2.2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
    }
    .sobre-section-title {
        display: flex;
        align-items: center;
        gap: .7rem;
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 1.1rem;
        padding-bottom: .8rem;
        border-bottom: 2px solid var(--border);
    }
    .sobre-section-title span.icon {
        width: 38px; height: 38px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .sobre-section p {
        color: var(--muted);
        line-height: 1.8;
        font-size: .97rem;
        margin: 0;
    }
    .sobre-section p + p {
        margin-top: .9rem;
    }

    /* Valores grid */
    .valores-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: .5rem;
    }
    .valor-item {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.2rem 1rem;
        text-align: center;
        transition: all .2s;
    }
    .valor-item:hover {
        border-color: var(--brand);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(99,102,241,.15);
    }
    .valor-item .valor-icon { font-size: 1.8rem; margin-bottom: .5rem; }
    .valor-item .valor-titulo { font-size: .88rem; font-weight: 800; color: var(--text); margin-bottom: .3rem; }
    .valor-item .valor-desc { font-size: .8rem; color: var(--muted); line-height: 1.5; }

    /* Time / equipe */
    .equipe-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        margin-top: .5rem;
    }
    .equipe-card {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.4rem 1rem;
        text-align: center;
        transition: all .2s;
    }
    .equipe-card:hover {
        border-color: var(--brand-light);
        transform: translateY(-3px);
    }
    .equipe-avatar {
        width: 56px; height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        font-size: 1.4rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto .8rem;
    }
    .equipe-nome { font-size: .9rem; font-weight: 800; color: var(--text); }
    .equipe-cargo { font-size: .78rem; color: var(--muted); margin-top: .2rem; }

    /* Stats */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: .5rem;
    }
    .stat-item {
        text-align: center;
        padding: 1.2rem;
        background: var(--surface-2);
        border-radius: 14px;
        border: 1px solid var(--border);
    }
    .stat-num { font-size: 2rem; font-weight: 900; color: var(--brand); line-height: 1; }
    .stat-label { font-size: .82rem; color: var(--muted); margin-top: .3rem; font-weight: 600; }

    /* CTA final */
    .sobre-cta {
        text-align: center;
        background: linear-gradient(135deg, #0f172a 0%, #1e1f3a 100%);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        margin-top: 2rem;
    }
    html:not(.dark) .sobre-cta { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
    .sobre-cta h3 { color: #fff; font-weight: 800; font-size: 1.3rem; margin-bottom: .5rem; }
    .sobre-cta p { color: rgba(255,255,255,.75); font-size: .93rem; margin-bottom: 1.2rem; }
    .btn-cta {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .7rem 1.8rem; border-radius: 50px;
        background: #fff; color: #4f46e5;
        font-weight: 800; font-size: .95rem;
        text-decoration: none; transition: all .2s;
        box-shadow: 0 4px 14px rgba(0,0,0,.2);
    }
    .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.3); color: #4f46e5; }

    @media(max-width: 640px) {
        .valores-grid { grid-template-columns: repeat(2, 1fr); }
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .sobre-hero-logo { font-size: 2.8rem; }
        .sobre-section { padding: 1.4rem; }
    }
</style>
@endpush

@section('conteudo')
<div class="sobre-wrap">

    {{-- Hero --}}
    <div class="sobre-hero">
        <span class="sobre-hero-badge">📰 Projeto TCC · 2026</span>
        <div class="sobre-hero-logo">Jornalzin</div>
        <p class="sobre-hero-sub">O jornal escolar feito por alunos, para alunos.</p>
    </div>

    {{-- O que é --}}
    <div class="sobre-section">
        <div class="sobre-section-title">
            <span class="icon">📖</span>
            O que é o Jornalzin?
        </div>
        <p>
            O <strong style="color:var(--text)">Jornalzin</strong> é uma plataforma de jornalismo escolar desenvolvida como projeto de TCC,
            criada para dar voz aos alunos e aproximar a comunidade escolar através da informação.
            Aqui, estudantes podem publicar notícias, opiniões, reportagens e enquetes sobre temas
            relevantes para o cotidiano escolar e para o mundo.
        </p>
        <p>
            Acreditamos que o jornalismo começa dentro da sala de aula — e que cada aluno tem uma
            história importante para contar. O Jornalzin nasce dessa ideia: transformar estudantes
            em protagonistas da informação.
        </p>
    </div>

    {{-- Missão, visão, valores --}}
    <div class="sobre-section">
        <div class="sobre-section-title">
            <span class="icon">🎯</span>
            Missão, Visão e Valores
        </div>
        <div class="valores-grid">
            <div class="valor-item">
                <div class="valor-icon">🚀</div>
                <div class="valor-titulo">Missão</div>
                <div class="valor-desc">Democratizar a produção de conteúdo jornalístico dentro do ambiente escolar.</div>
            </div>
            <div class="valor-item">
                <div class="valor-icon">🌟</div>
                <div class="valor-titulo">Visão</div>
                <div class="valor-desc">Ser referência em jornalismo jovem, inspirando escolas de todo o país.</div>
            </div>
            <div class="valor-item">
                <div class="valor-icon">❤️</div>
                <div class="valor-titulo">Valores</div>
                <div class="valor-desc">Ética, criatividade, respeito, inclusão e responsabilidade com a informação.</div>
            </div>
            <div class="valor-item">
                <div class="valor-icon">🔍</div>
                <div class="valor-titulo">Transparência</div>
                <div class="valor-desc">Todo conteúdo é revisado e moderado para garantir qualidade e veracidade.</div>
            </div>
            <div class="valor-item">
                <div class="valor-icon">🤝</div>
                <div class="valor-titulo">Comunidade</div>
                <div class="valor-desc">Valorizamos a participação de todos — leitores, editores e colaboradores.</div>
            </div>
            <div class="valor-item">
                <div class="valor-icon">💡</div>
                <div class="valor-titulo">Inovação</div>
                <div class="valor-desc">Usamos tecnologia para tornar o jornalismo escolar mais dinâmico e acessível.</div>
            </div>
        </div>
    </div>

    {{-- Como funciona --}}
    <div class="sobre-section">
        <div class="sobre-section-title">
            <span class="icon">⚙️</span>
            Como funciona?
        </div>
        <p>
            Qualquer aluno pode se cadastrar como <strong style="color:var(--text)">leitor</strong> e interagir com as publicações — curtindo,
            comentando e compartilhando. Para publicar conteúdo, o aluno pode solicitar acesso como
            <strong style="color:var(--text)">editor</strong>, que será avaliado pela equipe de administração.
        </p>
        <p>
            Os editores podem criar posts em diferentes formatos: <strong style="color:var(--text)">texto</strong>,
            <strong style="color:var(--text)">imagens</strong>, <strong style="color:var(--text)">vídeos</strong> e
            <strong style="color:var(--text)">enquetes</strong>. A plataforma conta ainda com um sistema de denúncias,
            mensagens diretas entre usuários e notificações em tempo real — tudo pensado para criar
            uma experiência completa de comunicação escolar.
        </p>
    </div>

    {{-- Números --}}
    <div class="sobre-section">
        <div class="sobre-section-title">
            <span class="icon">📊</span>
            O Jornalzin em números
        </div>
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-num">{{ \App\Models\User::count() }}</div>
                <div class="stat-label">Usuários cadastrados</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ \App\Models\Post::where('aprovado', true)->count() }}</div>
                <div class="stat-label">Posts publicados</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ \App\Models\Post::sum('visualizacoes') }}</div>
                <div class="stat-label">Visualizações totais</div>
            </div>
        </div>
    </div>

    {{-- Equipe --}}
    <div class="sobre-section">
        <div class="sobre-section-title">
            <span class="icon">👥</span>
            Nossa Equipe
        </div>
        <div class="equipe-grid">
            <div class="equipe-card">
                <div class="equipe-avatar">D</div>
                <div class="equipe-nome">Dev Principal</div>
                <div class="equipe-cargo">🛠️ Desenvolvimento Full Stack</div>
            </div>
            <div class="equipe-card">
                <div class="equipe-avatar">O</div>
                <div class="equipe-nome">Orientador</div>
                <div class="equipe-cargo">🎓 Orientação Pedagógica</div>
            </div>
            <div class="equipe-card">
                <div class="equipe-avatar">E</div>
                <div class="equipe-nome">Equipe Editorial</div>
                <div class="equipe-cargo">✍️ Produção de Conteúdo</div>
            </div>
            <div class="equipe-card">
                <div class="equipe-avatar">A</div>
                <div class="equipe-nome">Administração</div>
                <div class="equipe-cargo">⚙️ Gestão da Plataforma</div>
            </div>
        </div>
    </div>

    {{-- Tecnologias --}}
    <div class="sobre-section">
        <div class="sobre-section-title">
            <span class="icon">💻</span>
            Tecnologias utilizadas
        </div>
        <p>
            O Jornalzin foi desenvolvido com as melhores tecnologias do mercado:
            <strong style="color:var(--text)">Laravel</strong> no backend,
            <strong style="color:var(--text)">Bootstrap 5</strong> e CSS customizado no frontend,
            <strong style="color:var(--text)">MySQL</strong> como banco de dados e
            <strong style="color:var(--text)">Groq AI (LLaMA)</strong> para sugestões inteligentes de conteúdo.
            O projeto foi construído com foco em usabilidade, acessibilidade e performance.
        </p>
    </div>

    {{-- CTA --}}
    <div class="sobre-cta">
        <h3>Faça parte do Jornalzin! 🎉</h3>
        <p>Crie sua conta, leia as últimas notícias e, se quiser, torne-se um editor e publique seu conteúdo.</p>
        <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap;">
            @guest
                <a href="{{ url('/users/create') }}" class="btn-cta">✍️ Criar conta grátis</a>
                <a href="{{ route('home') }}" class="btn-cta" style="background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.3);">📰 Ver publicações</a>
            @else
                <a href="{{ route('home') }}" class="btn-cta">📰 Ver publicações</a>
            @endguest
        </div>
    </div>

</div>
@endsection

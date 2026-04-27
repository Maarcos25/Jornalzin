@extends('layouts.site')

@section('conteudo')
<div style="max-width:480px;margin:5rem auto;padding:2rem;text-align:center;font-family:'Segoe UI',sans-serif;">
    <div style="font-size:4rem;margin-bottom:1rem;">✅</div>
    <h2 style="font-weight:800;color:var(--text,#1e293b);margin-bottom:.5rem;">Denúncia enviada!</h2>
    <p style="color:var(--muted,#64748b);font-size:.97rem;margin-bottom:2rem;">
        Obrigado por nos avisar. Nossa equipe irá analisar o conteúdo e tomar as medidas necessárias.
    </p>
    <a href="{{ route('home') }}"
       style="display:inline-block;padding:.7rem 2rem;background:#4f46e5;color:#fff;border-radius:10px;font-weight:700;text-decoration:none;font-size:.97rem;">
        ← Voltar para o início
    </a>
</div>
@endsection
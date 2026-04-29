@extends('layouts.site')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    .post-creator-wrap { max-width: 760px; margin: 2rem auto; font-family: 'Nunito', sans-serif; padding: 0 1rem; }
    .creator-card { background: var(--surface); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border); }
    .creator-header { background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%); padding: 1.4rem 1.8rem; display: flex; align-items: center; gap: .75rem; }
    .creator-header h4 { color: #fff; margin: 0; font-weight: 700; font-size: 1.2rem; }
    .creator-body { padding: 1.8rem; }
    .tipo-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: .6rem; margin-bottom: 1.6rem; }
    .tipo-btn { border: 2px solid var(--border); border-radius: 10px; padding: .65rem .4rem; background: var(--surface-2); cursor: pointer; text-align: center; transition: all .2s; user-select: none; }
    .tipo-btn:hover { border-color: var(--brand-light); background: #eef2ff; }
    .tipo-btn.active { border-color: var(--brand); background: #eef2ff; color: var(--brand-dark); }
    .tipo-btn .icon { font-size: 1.5rem; display: block; margin-bottom: .3rem; }
    .tipo-btn .label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
    .tipo-btn input[type=radio] { display: none; }
    .tipo-field { display: none; animation: fadeIn .25s ease; }
    .tipo-field.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    .drop-zone { border: 2.5px dashed var(--border); border-radius: var(--radius); padding: 2.5rem 1rem; text-align: center; cursor: pointer; transition: all .2s; background: var(--surface-2); position: relative; }
    .drop-zone:hover, .drop-zone.dragover { border-color: var(--brand); background: #eef2ff; }
    .drop-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .drop-zone-icon { font-size: 2.4rem; display: block; margin-bottom: .5rem; }
    .drop-zone p { margin: 0; color: var(--muted); font-size: .88rem; }
    .drop-zone strong { color: var(--brand); }
    .img-preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: .6rem; margin-top: .9rem; }
    .img-preview-item { position: relative; border-radius: 8px; overflow: hidden; aspect-ratio: 1; background: var(--surface-2); border: 1px solid var(--border); }
    .img-preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .img-preview-item .remove-img { position: absolute; top: 4px; right: 4px; background: rgba(0,0,0,.55); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: .75rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .img-preview-item .remove-img:hover { background: var(--danger); }
    .img-count-badge { display: inline-block; margin-top: .5rem; font-size: .78rem; color: var(--muted); background: var(--surface-2); border: 1px solid var(--border); border-radius: 20px; padding: .2rem .7rem; }
    .video-tabs { display: flex; gap: .4rem; margin-bottom: 1rem; }
    .video-tab { padding: .45rem 1rem; border-radius: 8px; border: 2px solid var(--border); background: var(--surface-2); font-size: .82rem; font-weight: 700; cursor: pointer; transition: all .2s; }
    .video-tab.active { border-color: var(--brand); background: #eef2ff; color: var(--brand-dark); }
    .video-preview { border-radius: var(--radius); overflow: hidden; margin-top: .75rem; background: #000; display: none; }
    .video-preview iframe, .video-preview video { width: 100%; display: block; border-radius: var(--radius); aspect-ratio: 16/9; }
    .enquete-opcoes { display: flex; flex-direction: column; gap: .65rem; }
    .opcao-row { display: flex; align-items: center; gap: .5rem; }
    .opcao-num { width: 28px; height: 28px; background: var(--brand); color: #fff; border-radius: 50%; font-size: .75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .opcao-row input { flex: 1; }
    .btn-remove-opcao { background: none; border: none; color: var(--muted); cursor: pointer; padding: 0 .25rem; font-size: 1.1rem; line-height: 1; transition: color .15s; }
    .btn-remove-opcao:hover { color: var(--danger); }
    .btn-add-opcao { margin-top: .6rem; border: 2px dashed var(--border); background: none; border-radius: 8px; width: 100%; padding: .55rem; font-size: .85rem; font-weight: 600; color: var(--muted); cursor: pointer; transition: all .2s; }
    .btn-add-opcao:hover { border-color: var(--brand); color: var(--brand); }
    .enquete-config { display: flex; gap: .8rem; flex-wrap: wrap; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); }
    .enquete-config label { font-size: .83rem; color: var(--muted); font-weight: 600; }
    .form-section { margin-bottom: 1.3rem; }
    .form-section > label { display: block; font-size: .83rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .45rem; }
    .form-control-styled { width: 100%; padding: .65rem .9rem; border: 2px solid var(--border); border-radius: 10px; font-size: .93rem; color: var(--text); background: var(--surface); transition: border-color .2s, box-shadow .2s; font-family: inherit; box-sizing: border-box; }
    .form-control-styled:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
    textarea.form-control-styled { resize: none; min-height: 100px; }
    .titulo-wrap { position: relative; }
    .titulo-counter { position: absolute; right: .8rem; bottom: .6rem; font-size: .75rem; color: var(--muted); font-weight: 600; }
    .titulo-counter.ok { color: #22c55e; }
    .titulo-counter.err { color: var(--danger); }
    .ia-wrap { margin-top: .5rem; }
    .btn-ia { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem 1rem; border-radius: 8px; border: none; background: linear-gradient(135deg, #7c3aed, #4f46e5); color: #fff; font-size: .82rem; font-weight: 700; cursor: pointer; transition: all .2s; box-shadow: 0 2px 8px rgba(99,102,241,.3); }
    .btn-ia:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(99,102,241,.4); }
    .btn-ia:disabled { opacity: .6; cursor: not-allowed; transform: none; }
    .ia-sugestoes { margin-top: .75rem; display: flex; flex-direction: column; gap: .5rem; }
    .ia-sugestao { padding: .65rem .9rem; border: 1.5px solid var(--border); border-radius: 12px; font-size: .88rem; color: var(--text); background: var(--surface-2); cursor: pointer; transition: all .15s; text-align: left; width: 100%; }
    .ia-sugestao:hover { border-color: var(--brand); background: #eef2ff; color: var(--brand-dark); }
    .ia-loading { display: flex; align-items: center; gap: .5rem; font-size: .85rem; color: var(--muted); padding: .5rem 0; }
    .ia-spinner { width: 16px; height: 16px; border: 2px solid var(--border); border-top-color: var(--brand); border-radius: 50%; animation: spin .7s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .creator-footer { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.8rem; border-top: 1px solid var(--border); background: var(--surface-2); }
    .btn-voltar { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.2rem; border-radius: 10px; border: 2px solid var(--border); background: var(--surface); color: var(--muted); font-weight: 700; font-size: .9rem; text-decoration: none; transition: all .2s; }
    .btn-voltar:hover { border-color: var(--muted); color: var(--text); }
    .btn-salvar { display: inline-flex; align-items: center; gap: .5rem; padding: .65rem 1.6rem; border-radius: 10px; border: none; background: linear-gradient(135deg, var(--brand), var(--brand-dark)); color: #fff; font-weight: 700; font-size: .95rem; cursor: pointer; transition: all .2s; box-shadow: 0 4px 14px rgba(99,102,241,.35); }
    .btn-salvar:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.45); }
    .validation-alert { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: .85rem 1.1rem; color: #b91c1c; font-size: .88rem; margin-bottom: 1.4rem; }
    .validation-alert ul { margin: .3rem 0 0 1rem; padding: 0; }
    .tamanho-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: .5rem; }
    .tamanho-opt { display: none; }
    .tamanho-opt + label { display: block; border: 2px solid var(--border); border-radius: 8px; padding: .5rem; text-align: center; cursor: pointer; font-size: .78rem; font-weight: 700; color: var(--muted); transition: all .15s; }
    .tamanho-opt:checked + label { border-color: var(--brand); background: #eef2ff; color: var(--brand-dark); }
    .preview-section { margin: 0 1.8rem 1.4rem; border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; }
    .preview-section-header { padding: .75rem 1.1rem; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: .8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; display: flex; align-items: center; gap: .4rem; }
    .preview-post { background: var(--surface); overflow: hidden; }
    .preview-img-wrap { width: 100%; aspect-ratio: 16/9; background: var(--surface-2); display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
    .preview-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .preview-img-placeholder { font-size: 2.5rem; color: var(--border); }
    .preview-img-count { position: absolute; top: .5rem; right: .5rem; background: rgba(0,0,0,.6); color: #fff; font-size: .72rem; font-weight: 700; padding: .2rem .6rem; border-radius: 20px; }
    .preview-body { padding: .85rem 1rem .5rem; }
    .preview-titulo { font-size: .95rem; font-weight: 800; color: var(--text); margin-bottom: .3rem; line-height: 1.4; min-height: 1.2rem; }
    .preview-legenda { font-size: .82rem; color: var(--muted); line-height: 1.5; margin: 0; }
    .preview-titulo-placeholder { color: var(--border); font-style: italic; }
    .preview-footer { display: flex; align-items: center; padding: .6rem 1rem .7rem; border-top: 1px solid var(--border); gap: .4rem; }
    .preview-autor { display: flex; align-items: center; gap: .35rem; font-size: .8rem; color: var(--muted); flex: 1; }
    .preview-avatar { width: 22px; height: 22px; border-radius: 50%; background: var(--brand); color: #fff; display: flex; align-items: center; justify-content: center; font-size: .65rem; font-weight: 800; }
    .preview-actions { display: flex; gap: .35rem; }
    .preview-btn { display: inline-flex; align-items: center; gap: .25rem; padding: .32rem .7rem; border-radius: 50px; border: 1.5px solid var(--border); background: var(--surface-2); color: var(--muted); font-size: .78rem; font-weight: 600; cursor: default; }
    .preview-btn.like { border-color: #f43f5e; background: #fff1f2; color: #f43f5e; }
    .preview-btn.more { padding: .32rem .55rem; }
    @media(max-width: 560px) { .tipo-grid { grid-template-columns: repeat(2, 1fr); } .tamanho-grid { grid-template-columns: repeat(2, 1fr); } }
</style>
@endpush

@section('conteudo')
<div class="post-creator-wrap">
    <div class="creator-card">

        <div class="creator-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <h4>Criar Post</h4>
        </div>

        <div class="creator-body">

            @if ($errors->any())
                <div class="validation-alert">
                    <strong>Por favor, corrija os erros:</strong>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                @csrf

                {{-- Tipo --}}
                <div class="form-section">
                    <label>Tipo do Post</label>
                    <div class="tipo-grid">
                        <div class="tipo-btn {{ old('tipo', 'texto') == 'texto' ? 'active' : '' }}" onclick="setTipo('texto', this)">
                            <span class="icon">✍️</span><span class="label">Texto</span>
                            <input type="radio" name="tipo" value="texto" {{ old('tipo', 'texto') == 'texto' ? 'checked' : '' }}>
                        </div>
                        <div class="tipo-btn {{ old('tipo') == 'imagem' ? 'active' : '' }}" onclick="setTipo('imagem', this)">
                            <span class="icon">🖼️</span><span class="label">Imagens</span>
                            <input type="radio" name="tipo" value="imagem" {{ old('tipo') == 'imagem' ? 'checked' : '' }}>
                        </div>
                        <div class="tipo-btn {{ old('tipo') == 'video' ? 'active' : '' }}" onclick="setTipo('video', this)">
                            <span class="icon">🎬</span><span class="label">Vídeo</span>
                            <input type="radio" name="tipo" value="video" {{ old('tipo') == 'video' ? 'checked' : '' }}>
                        </div>
                        <div class="tipo-btn {{ old('tipo') == 'enquete' ? 'active' : '' }}" onclick="setTipo('enquete', this)">
                            <span class="icon">📊</span><span class="label">Enquete</span>
                            <input type="radio" name="tipo" value="enquete" {{ old('tipo') == 'enquete' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                {{-- Imagens --}}
                <div class="tipo-field {{ old('tipo') == 'imagem' ? 'active' : '' }}" id="field-imagem">
                    <div class="form-section">
                        <label>Imagens <span style="font-weight:400;text-transform:none;font-size:.8rem;">(até 10 · JPG, PNG, WEBP)</span></label>
                        <div class="drop-zone" id="dropZone" onclick="document.getElementById('inputImagens').click()">
                            <span class="drop-zone-icon">📂</span>
                            <p><strong>Clique para selecionar</strong> ou arraste aqui</p>
                            <p style="font-size:.78rem;margin-top:.3rem;">JPG · PNG · WEBP — máx. 5 MB cada</p>
                        </div>
                        <input type="file" name="imagens[]" id="inputImagens" accept="image/jpeg,image/png,image/webp" multiple style="display:none;">
                        <div class="img-preview-grid" id="imgPreviewGrid"></div>
                        <span class="img-count-badge" id="imgCountBadge" style="display:none"></span>
                    </div>
                </div>

                {{-- Vídeo --}}
                <div class="tipo-field {{ old('tipo') == 'video' ? 'active' : '' }}" id="field-video">
                    <div class="form-section">
                        <label>Vídeo</label>
                        <div class="video-tabs">
                            <button type="button" class="video-tab active" onclick="setVideoTab('url', this)">🔗 Link (YouTube / Vimeo)</button>
                            <button type="button" class="video-tab" onclick="setVideoTab('file', this)">📁 Arquivo</button>
                        </div>
                        <div id="videoTabUrl">
                            <input type="url" name="video_url" id="videoUrlInput" class="form-control-styled" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url') }}">
                            <div class="video-preview" id="videoUrlPreview">
                                <iframe id="videoIframe" src="" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div id="videoTabFile" style="display:none">
                            <input type="file" name="video_file" id="videoFileInput" class="form-control-styled" accept="video/mp4,video/webm,video/ogg" style="padding:.5rem;">
                            <div class="video-preview" id="videoFilePreview">
                                <video id="videoPlayer" controls></video>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Enquete --}}
                <div class="tipo-field {{ old('tipo') == 'enquete' ? 'active' : '' }}" id="field-enquete">
                    <div class="form-section">
                        <label>Opções da Enquete</label>
                        <div class="enquete-opcoes" id="enqueteOpcoes">
                            @php $opcoes = old('opcoes', ['', '']); @endphp
                            @foreach($opcoes as $i => $val)
                            <div class="opcao-row" data-idx="{{ $i + 1 }}">
                                <span class="opcao-num">{{ $i + 1 }}</span>
                                <input type="text" name="opcoes[]" class="form-control-styled" placeholder="Opção {{ $i + 1 }}" value="{{ $val }}">
                                @if($i >= 2)<button type="button" class="btn-remove-opcao" onclick="removeOpcao(this)">✕</button>@endif
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add-opcao" id="btnAddOpcao" onclick="addOpcao()">+ Adicionar opção</button>
                        <div class="enquete-config">
                            <div>
                                <label>Encerra em</label>
                                <input type="date" name="enquete_fim" class="form-control-styled" style="margin-top:.25rem;" value="{{ old('enquete_fim') }}">
                            </div>
                            <div>
                                <label>Múltipla escolha?</label>
                                <div style="margin-top:.4rem;display:flex;align-items:center;gap:.5rem;">
                                    <input type="checkbox" name="multipla_escolha" value="1" id="multiplaEscolha" {{ old('multipla_escolha') ? 'checked' : '' }} style="width:18px;height:18px;cursor:pointer;">
                                    <label for="multiplaEscolha" style="text-transform:none;letter-spacing:0;font-size:.88rem;color:var(--text);margin:0;cursor:pointer;">Permitir mais de uma resposta</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Título + Texto --}}
                <div class="tipo-field active" id="field-texto-desc">

                    {{-- TÍTULO --}}
                    <div class="form-section">
                        <label>Título <span style="color:var(--danger)">*</span></label>
                        <div class="titulo-wrap">
                            <input type="text" name="titulo" id="inputTitulo"
                                   class="form-control-styled"
                                   placeholder="Digite o título do post... (mínimo 10 caracteres)"
                                   value="{{ old('titulo') }}"
                                   minlength="10"
                                   oninput="atualizarTitulo(this)">
                            <span class="titulo-counter" id="tituloCounter">0/10</span>
                        </div>
                        <div id="tituloMsg" style="font-size:.78rem;margin-top:.3rem;color:var(--danger);display:none;">
                            ⚠️ Título muito curto — mínimo 10 caracteres
                        </div>
                        {{--
                            Botão: SUGERIR TÍTULO com IA
                            → Usa o conteúdo do campo TEXTO como base para gerar sugestões de título
                        --}}
                        <div class="ia-wrap" style="margin-top:.5rem;">
                            <button type="button" class="btn-ia" id="btnIATitulo" onclick="gerarTituloIA()">
                                ✨ Sugerir título com IA
                            </button>
                            <div id="iaLoadingTitulo" class="ia-loading" style="display:none;">
                                <div class="ia-spinner"></div>
                                <span>Gerando títulos...</span>
                            </div>
                            <div id="iaSugestoesTitulo" class="ia-sugestoes"></div>
                        </div>
                    </div>

                    {{-- TEXTO / LEGENDA --}}
                    <div class="form-section" id="wrapTexto">
                        <label id="labelTexto">Texto / Legenda</label>
                        <textarea name="texto" id="textoArea"
                                  class="form-control-styled" rows="4"
                                  placeholder="Escreva o conteúdo do post..."
                                  oninput="atualizarLegenda(this)">{{ old('texto') }}</textarea>
                        {{--
                            Botão: GERAR IDEIA com IA
                            → Usa o conteúdo do campo TÍTULO como base para gerar ideias de texto/legenda
                        --}}
                        <div class="ia-wrap">
                            <button type="button" class="btn-ia" id="btnIA" onclick="gerarLegendaIA()">
                                ✨ Gerar ideia com IA
                            </button>
                            <div id="iaLoading" class="ia-loading" style="display:none;">
                                <div class="ia-spinner"></div>
                                <span>Gerando ideias...</span>
                            </div>
                            <div id="iaSugestoes" class="ia-sugestoes"></div>
                        </div>
                    </div>
                </div>

                {{-- Data --}}
                <div class="form-section">
                    <label>Data de Publicação</label>
                    <input type="date" name="data" class="form-control-styled" value="{{ old('data', date('Y-m-d')) }}">
                </div>

                {{-- Tamanho --}}
                <div class="form-section">
                    <label>Tamanho do Card</label>
                    <div class="tamanho-grid">
                        <div><input type="radio" name="tamanho" value="P" id="tamP" class="tamanho-opt" {{ old('tamanho') == 'P' ? 'checked' : '' }}><label for="tamP">Pequeno</label></div>
                        <div><input type="radio" name="tamanho" value="M" id="tamM" class="tamanho-opt" {{ old('tamanho', 'M') == 'M' ? 'checked' : '' }}><label for="tamM">Médio</label></div>
                        <div><input type="radio" name="tamanho" value="G" id="tamG" class="tamanho-opt" {{ old('tamanho') == 'G' ? 'checked' : '' }}><label for="tamG">Grande</label></div>
                        <div><input type="radio" name="tamanho" value="GG" id="tamGG" class="tamanho-opt" {{ old('tamanho') == 'GG' ? 'checked' : '' }}><label for="tamGG">Extra Grande</label></div>
                    </div>
                </div>

                <input type="hidden" name="id_usuario" value="{{ auth()->id() }}">

            </form>
        </div>

        {{-- Preview --}}
        <div class="preview-section">
            <div class="preview-section-header">👁 Preview do post</div>
            <div class="preview-post" id="previewPost">
                <div class="preview-img-wrap" id="previewMedia">
                    <span class="preview-img-placeholder">📝</span>
                </div>
                <div class="preview-body">
                    <div class="preview-titulo" id="previewTitulo">
                        <span class="preview-titulo-placeholder">Título do post...</span>
                    </div>
                    <p class="preview-legenda" id="previewLegenda"></p>
                </div>
                <div class="preview-footer">
                    <div class="preview-autor">
                        <div class="preview-avatar">{{ strtoupper(substr(auth()->user()->nome ?? 'U', 0, 1)) }}</div>
                        <span>{{ auth()->user()->nome ?? 'Você' }}</span>
                        <span style="color:var(--border)">·</span>
                        <span>👁 0</span>
                    </div>
                    <div class="preview-actions">
                        <span class="preview-btn like">❤️ 0</span>
                        <span class="preview-btn">💬</span>
                        <span class="preview-btn more">•••</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="creator-footer">
            <a href="{{ route('posts.index') }}" class="btn-voltar">← Voltar</a>
            <button type="submit" form="postForm" class="btn-salvar" id="btnPublicar">✓ Publicar Post</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CAMPOS_TIPO = ['imagem', 'video', 'enquete'];

function setTipo(tipo, btn) {
    btn.querySelector('input[type=radio]').checked = true;
    document.querySelectorAll('.tipo-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    CAMPOS_TIPO.forEach(t => document.getElementById('field-' + t)?.classList.remove('active'));
    if (tipo !== 'texto') document.getElementById('field-' + tipo)?.classList.add('active');
    const labelTexto = document.getElementById('labelTexto');
    const wrapTexto  = document.getElementById('wrapTexto');
    if (tipo === 'enquete') {
        wrapTexto.style.display = 'none';
    } else {
        wrapTexto.style.display = 'block';
        labelTexto.textContent = tipo === 'texto' ? 'Texto' : 'Legenda (opcional)';
    }
    atualizarPreviewMedia();
}

function atualizarTitulo(input) {
    const len = input.value.length;
    const counter = document.getElementById('tituloCounter');
    const msg     = document.getElementById('tituloMsg');
    counter.textContent = `${len}/10`;
    if (len === 0) { counter.className = 'titulo-counter'; msg.style.display = 'none'; }
    else if (len < 10) { counter.className = 'titulo-counter err'; msg.style.display = 'block'; }
    else { counter.className = 'titulo-counter ok'; msg.style.display = 'none'; }
    const prev = document.getElementById('previewTitulo');
    prev.innerHTML = len > 0 ? input.value : '<span class="preview-titulo-placeholder">Título do post...</span>';
}

function atualizarLegenda(textarea) {
    const prev = document.getElementById('previewLegenda');
    prev.textContent = textarea.value.length > 80 ? textarea.value.substring(0, 80) + '...' : textarea.value;
}

async function chamarIA(payload) {
    const response = await fetch('/ia/sugestoes', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(payload)
    });
    const data = await response.json();
    const texto = data.content?.[0]?.text || '';
    return texto.split('\n')
        .map(l => l.trim())
        .filter(l => l.length > 5)
        .map(l => l.replace(/^[\d]+[\.:\)]\s*/, '').replace(/^\-\s*/, '').replace(/^["']|["']$/g, '').trim())
        .filter(l => l.length > 5)
        .slice(0, 3);
}

function renderSugestoes(linhas, texto, divId, onClickFn) {
    const div = document.getElementById(divId);
    div.innerHTML = '';
    if (linhas.length === 0) {
        div.innerHTML = '<p style="color:var(--muted);font-size:.82rem;">⚠️ ' + (texto || 'Sem resposta da IA.') + '</p>';
        return;
    }
    linhas.forEach(linha => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'ia-sugestao';
        btn.textContent = linha;
        btn.onclick = () => onClickFn(linha, div);
        div.appendChild(btn);
    });
}

// GERAR IDEIA COM IA → lê o TEXTO e gera mais ideias de texto/legenda
async function gerarLegendaIA() {
    const textoDigitado = document.getElementById('textoArea').value.trim();
    const tipo          = document.querySelector('input[name="tipo"]:checked')?.value || 'texto';
    const btn           = document.getElementById('btnIA');
    const loading       = document.getElementById('iaLoading');

    btn.disabled = true;
    loading.style.display = 'flex';
    document.getElementById('iaSugestoes').innerHTML = '';

    try {
        const linhas = await chamarIA({ titulo: textoDigitado, tipo: tipo, campo: 'legenda' });
        renderSugestoes(linhas, '', 'iaSugestoes', (linha, div) => {
            document.getElementById('textoArea').value = linha;
            atualizarLegenda(document.getElementById('textoArea'));
            div.innerHTML = '';
        });
    } catch(e) {
        document.getElementById('iaSugestoes').innerHTML = '<p style="color:var(--danger);font-size:.82rem;">⚠️ Erro: ' + e.message + '</p>';
    } finally {
        btn.disabled = false;
        loading.style.display = 'none';
    }
}

// SUGERIR TÍTULO COM IA → lê o TÍTULO e sugere variações de título
async function gerarTituloIA() {
    const tituloDigitado = document.getElementById('inputTitulo').value.trim();
    const tipo           = document.querySelector('input[name="tipo"]:checked')?.value || 'texto';
    const btn            = document.getElementById('btnIATitulo');
    const loading        = document.getElementById('iaLoadingTitulo');

    btn.disabled = true;
    loading.style.display = 'flex';
    document.getElementById('iaSugestoesTitulo').innerHTML = '';

    try {
        const linhas = await chamarIA({ titulo: tituloDigitado, tipo: tipo, campo: 'titulo' });
        renderSugestoes(linhas, '', 'iaSugestoesTitulo', (linha, div) => {
            const input = document.getElementById('inputTitulo');
            input.value = linha;
            atualizarTitulo(input);
            div.innerHTML = '';
        });
    } catch(e) {
        document.getElementById('iaSugestoesTitulo').innerHTML = '<p style="color:var(--danger);font-size:.82rem;">⚠️ Erro: ' + e.message + '</p>';
    } finally {
        btn.disabled = false;
        loading.style.display = 'none';
    }
}

let imagensFiles = [];

function atualizarPreviewMedia() {
    const tipo = document.querySelector('input[name="tipo"]:checked')?.value || 'texto';
    const media = document.getElementById('previewMedia');
    const icons = { texto: '📝', imagem: '🖼️', video: '🎬', enquete: '📊' };
    if (tipo === 'imagem' && imagensFiles.length > 0) {
        const reader = new FileReader();
        reader.onload = e => {
            media.innerHTML = `<img src="${e.target.result}" alt="">`;
            if (imagensFiles.length > 1) media.innerHTML += `<div class="preview-img-count">1/${imagensFiles.length}</div>`;
        };
        reader.readAsDataURL(imagensFiles[0]);
    } else if (tipo === 'video') {
        const url = document.getElementById('videoUrlInput')?.value;
        const m = url?.match(/(?:watch\?v=|youtu\.be\/)([^&\s]+)/);
        media.innerHTML = m
            ? `<img src="https://img.youtube.com/vi/${m[1]}/hqdefault.jpg" style="width:100%;height:100%;object-fit:cover;">`
            : `<span class="preview-img-placeholder">${icons[tipo]}</span>`;
    } else {
        media.innerHTML = `<span class="preview-img-placeholder">${icons[tipo]}</span>`;
    }
}

const dropZone    = document.getElementById('dropZone');
const inputImgs   = document.getElementById('inputImagens');
const previewGrid = document.getElementById('imgPreviewGrid');
const countBadge  = document.getElementById('imgCountBadge');

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => { e.preventDefault(); dropZone.classList.remove('dragover'); addImages([...e.dataTransfer.files]); });
inputImgs.addEventListener('change', () => { addImages([...inputImgs.files]); inputImgs.value = ''; });

function addImages(files) {
    files.forEach(file => { if (!file.type.startsWith('image/')) return; if (imagensFiles.length >= 10) return; imagensFiles.push(file); });
    renderPreviews(); atualizarPreviewMedia();
}

function renderPreviews() {
    previewGrid.innerHTML = '';
    imagensFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = e => {
            const item = document.createElement('div');
            item.className = 'img-preview-item';
            item.innerHTML = `<img src="${e.target.result}" alt="preview ${idx+1}"><button type="button" class="remove-img" onclick="removeImage(${idx})">✕</button>`;
            previewGrid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
    const n = imagensFiles.length;
    countBadge.style.display = n > 0 ? 'inline-block' : 'none';
    countBadge.textContent = `${n} imagem${n !== 1 ? 's' : ''} selecionada${n !== 1 ? 's' : ''}`;
    syncInputFiles();
}

function removeImage(idx) { imagensFiles.splice(idx, 1); renderPreviews(); atualizarPreviewMedia(); }

function syncInputFiles() {
    const dt = new DataTransfer();
    imagensFiles.forEach(f => dt.items.add(f));
    inputImgs.files = dt.files;
}

function setVideoTab(tab, btn) {
    document.querySelectorAll('.video-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('videoTabUrl').style.display  = tab === 'url'  ? 'block' : 'none';
    document.getElementById('videoTabFile').style.display = tab === 'file' ? 'block' : 'none';
}

document.getElementById('videoUrlInput').addEventListener('input', function () {
    const embed = getEmbedUrl(this.value.trim());
    const preview = document.getElementById('videoUrlPreview');
    const iframe  = document.getElementById('videoIframe');
    if (embed) { iframe.src = embed; preview.style.display = 'block'; }
    else { preview.style.display = 'none'; iframe.src = ''; }
    atualizarPreviewMedia();
});

function getEmbedUrl(url) {
    let m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/);
    if (m) return `https://www.youtube.com/embed/${m[1]}`;
    m = url.match(/vimeo\.com\/(\d+)/);
    if (m) return `https://player.vimeo.com/video/${m[1]}`;
    return null;
}

document.getElementById('videoFileInput').addEventListener('change', function () {
    const file = this.files[0];
    const preview = document.getElementById('videoFilePreview');
    const player  = document.getElementById('videoPlayer');
    if (file) { player.src = URL.createObjectURL(file); preview.style.display = 'block'; }
    else { preview.style.display = 'none'; player.src = ''; }
});

const MAX_OPCOES = 8;
let opcaoCount = document.querySelectorAll('#enqueteOpcoes .opcao-row').length;

function addOpcao() {
    if (opcaoCount >= MAX_OPCOES) return;
    opcaoCount++;
    const wrap = document.getElementById('enqueteOpcoes');
    const row  = document.createElement('div');
    row.className = 'opcao-row'; row.dataset.idx = opcaoCount;
    row.innerHTML = `<span class="opcao-num">${opcaoCount}</span><input type="text" name="opcoes[]" class="form-control-styled" placeholder="Opção ${opcaoCount}"><button type="button" class="btn-remove-opcao" onclick="removeOpcao(this)">✕</button>`;
    wrap.appendChild(row);
    if (opcaoCount >= MAX_OPCOES) document.getElementById('btnAddOpcao').style.display = 'none';
}

function removeOpcao(btn) {
    if (document.querySelectorAll('#enqueteOpcoes .opcao-row').length <= 2) return;
    btn.closest('.opcao-row').remove();
    renumerarOpcoes();
    document.getElementById('btnAddOpcao').style.display = 'block';
}

function renumerarOpcoes() {
    const rows = document.querySelectorAll('#enqueteOpcoes .opcao-row');
    opcaoCount = rows.length;
    rows.forEach((row, i) => {
        row.querySelector('.opcao-num').textContent = i + 1;
        row.querySelector('input').placeholder = `Opção ${i + 1}`;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const tipoInicial = document.querySelector('input[name="tipo"]:checked')?.value || 'texto';
    const btnAtivo = [...document.querySelectorAll('.tipo-btn')].find(b => b.querySelector('input')?.value === tipoInicial);
    if (btnAtivo) setTipo(tipoInicial, btnAtivo);
    const titulo = document.getElementById('inputTitulo');
    if (titulo.value) atualizarTitulo(titulo);
});

document.getElementById('postForm').addEventListener('submit', function(e) {
    const titulo = document.getElementById('inputTitulo').value.trim();
    if (titulo.length < 10) {
        e.preventDefault();
        document.getElementById('inputTitulo').focus();
        document.getElementById('tituloMsg').style.display = 'block';
        alert('⚠️ O título precisa ter pelo menos 10 caracteres!');
        return;
    }
    if (document.querySelector('input[name="tipo"]:checked')?.value === 'imagem') syncInputFiles();
});
</script>
@endpush

@extends('layouts.site')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --brand:       #6366f1;
        --brand-light: #818cf8;
        --brand-dark:  #4f46e5;
        --surface:     #ffffff;
        --surface-2:   #f8fafc;
        --border:      #e2e8f0;
        --text:        #1e293b;
        --muted:       #64748b;
        --danger:      #ef4444;
        --radius:      14px;
        --shadow:      0 4px 24px rgba(99,102,241,.10);
    }

    .post-editor-wrap {
        max-width: 760px;
        margin: 2rem auto;
        font-family: 'Nunito', sans-serif;
    }

    .editor-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .editor-header {
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
        padding: 1.4rem 1.8rem;
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .editor-header h4 { color: #fff; margin: 0; font-weight: 700; font-size: 1.2rem; }

    .tipo-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: rgba(255,255,255,.2);
        color: #fff;
        border-radius: 20px;
        padding: .25rem .9rem;
        font-size: .82rem;
        font-weight: 700;
        margin-left: auto;
    }

    .editor-body { padding: 1.8rem; }

    /* ── Alertas ── */
    .validation-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: .85rem 1.1rem;
        color: #b91c1c;
        font-size: .88rem;
        margin-bottom: 1.4rem;
    }
    .validation-alert ul { margin: .3rem 0 0 1rem; padding: 0; }

    .success-alert {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: .85rem 1.1rem;
        color: #166534;
        font-size: .88rem;
        margin-bottom: 1.4rem;
    }

    /* ── Campos ── */
    .form-section { margin-bottom: 1.3rem; }
    .form-section > label {
        display: block;
        font-size: .83rem;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: .45rem;
    }
    .form-control-styled {
        width: 100%;
        padding: .65rem .9rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: .93rem;
        color: var(--text);
        background: var(--surface);
        transition: border-color .2s, box-shadow .2s;
        font-family: inherit;
        box-sizing: border-box;
    }
    .form-control-styled:focus {
        outline: none;
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    textarea.form-control-styled { resize: vertical; min-height: 110px; }

    /* ── Preview de imagens atuais ── */
    .img-current-grid {
        display: flex;
        flex-wrap: wrap;
        gap: .6rem;
        margin-bottom: .8rem;
    }
    .img-current-item {
        position: relative;
        width: 110px;
        height: 110px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid var(--border);
    }
    .img-current-item img {
        width: 100%; height: 100%; object-fit: cover; display: block;
    }

    /* ── Drop zone (novas imagens) ── */
    .drop-zone {
        border: 2.5px dashed var(--border);
        border-radius: var(--radius);
        padding: 1.8rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        background: var(--surface-2);
        position: relative;
    }
    .drop-zone:hover, .drop-zone.dragover { border-color: var(--brand); background: #eef2ff; }
    .drop-zone input[type=file] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .drop-zone p { margin: 0; color: var(--muted); font-size: .88rem; }
    .drop-zone strong { color: var(--brand); }

    .img-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: .6rem;
        margin-top: .8rem;
    }
    .img-preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
        background: var(--surface-2);
        border: 1px solid var(--border);
    }
    .img-preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .img-preview-item .remove-img {
        position: absolute; top: 4px; right: 4px;
        background: rgba(0,0,0,.55); color: #fff;
        border: none; border-radius: 50%;
        width: 22px; height: 22px; font-size: .75rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: background .15s;
    }
    .img-preview-item .remove-img:hover { background: var(--danger); }
    .img-count-badge {
        display: inline-block; margin-top: .5rem; font-size: .78rem;
        color: var(--muted); background: var(--surface-2);
        border: 1px solid var(--border); border-radius: 20px; padding: .2rem .7rem;
    }

    /* ── Vídeo ── */
    .video-preview-wrap {
        border-radius: var(--radius); overflow: hidden;
        background: #000; margin-bottom: .8rem;
    }
    .video-preview-wrap iframe,
    .video-preview-wrap video {
        width: 100%; display: block;
        aspect-ratio: 16/9; border-radius: var(--radius);
    }

    /* ── Enquete ── */
    .enquete-opcoes { display: flex; flex-direction: column; gap: .6rem; }
    .opcao-row { display: flex; align-items: center; gap: .5rem; }
    .opcao-num {
        width: 28px; height: 28px;
        background: var(--brand); color: #fff;
        border-radius: 50%; font-size: .75rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .opcao-row input { flex: 1; }

    /* ── Tamanho ── */
    .tamanho-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: .5rem;
    }
    .tamanho-opt { display: none; }
    .tamanho-opt + label {
        display: block; border: 2px solid var(--border);
        border-radius: 8px; padding: .5rem; text-align: center;
        cursor: pointer; font-size: .78rem; font-weight: 700;
        color: var(--muted); transition: all .15s;
    }
    .tamanho-opt:checked + label { border-color: var(--brand); background: #eef2ff; color: var(--brand-dark); }

    /* ── Footer ── */
    .editor-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.2rem 1.8rem;
        border-top: 1px solid var(--border);
        background: var(--surface-2);
        gap: .6rem;
        flex-wrap: wrap;
    }

    .btn-voltar {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.2rem; border-radius: 10px;
        border: 2px solid var(--border); background: #fff;
        color: var(--muted); font-weight: 700; font-size: .9rem;
        text-decoration: none; transition: all .2s;
    }
    .btn-voltar:hover { border-color: var(--muted); color: var(--text); }

    .btn-salvar {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .65rem 1.6rem; border-radius: 10px; border: none;
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color: #fff; font-weight: 700; font-size: .95rem;
        cursor: pointer; transition: all .2s;
        box-shadow: 0 4px 14px rgba(99,102,241,.35);
    }
    .btn-salvar:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.45); }
    .btn-salvar:active { transform: translateY(0); }

    .btn-remover {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .6rem 1.2rem; border-radius: 10px; border: none;
        background: #fef2f2; color: var(--danger);
        font-weight: 700; font-size: .88rem;
        cursor: pointer; transition: all .2s;
        border: 2px solid #fecaca;
    }
    .btn-remover:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

    @media(max-width: 560px) {
        .tamanho-grid { grid-template-columns: repeat(2, 1fr); }
        .editor-footer { flex-direction: column; align-items: stretch; }
        .btn-voltar, .btn-salvar, .btn-remover { text-align: center; justify-content: center; }
    }
</style>
@endpush

@section('conteudo')
<div class="post-editor-wrap">
    <div class="editor-card">

        {{-- Header --}}
        <div class="editor-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.85)" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <h4>Editar Post</h4>
            <span class="tipo-badge">
                @php
                    $icons = ['texto' => '✍️', 'imagem' => '🖼️', 'video' => '🎬', 'enquete' => '📊'];
                @endphp
                {{ $icons[$post->tipo] ?? '📄' }} {{ ucfirst($post->tipo) }}
            </span>
        </div>

        <div class="editor-body">

            @if(session('success'))
                <div class="success-alert">✅ {{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="validation-alert">
                    <strong>Por favor, corrija os erros:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form principal --}}
            <form action="{{ route('posts.update', $post->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="editForm">
                @csrf
                @method('PUT')

                {{-- Título --}}
                <div class="form-section">
                    <label>Título</label>
                    <input type="text" name="titulo" class="form-control-styled"
                           placeholder="Digite o título..."
                           value="{{ old('titulo', $post->titulo) }}" required>
                </div>

                {{-- TEXTO --}}
                @if($post->tipo === 'texto')
                    <div class="form-section">
                        <label>Texto</label>
                        <textarea name="texto" class="form-control-styled"
                                  rows="5"
                                  placeholder="Conteúdo do post...">{{ old('texto', $post->texto) }}</textarea>
                    </div>
                @endif

                {{-- IMAGENS --}}
                @if($post->tipo === 'imagem')
                    {{-- Imagens atuais --}}
                    @if($post->imagens && $post->imagens->count())
                        <div class="form-section">
                            <label>Imagens atuais</label>
                            <div class="img-current-grid">
                                @foreach($post->imagens->sortBy('ordem') as $img)
                                    <div class="img-current-item">
                                        <img src="{{ Storage::url($img->caminho) }}" alt="imagem">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($post->imagem)
                        <div class="form-section">
                            <label>Imagem atual</label>
                            <div class="img-current-grid">
                                <div class="img-current-item">
                                    <img src="{{ str_starts_with($post->imagem, '/storage/') ? asset($post->imagem) : Storage::url($post->imagem) }}" alt="imagem">
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Novas imagens --}}
                    <div class="form-section">
                        <label>Substituir imagens <span style="font-weight:400;text-transform:none;font-size:.8rem;">(opcional · até 10 arquivos)</span></label>
                        <div class="drop-zone" id="dropZone">
                            <input type="file" name="imagens[]" id="inputImagens"
                                   accept="image/jpeg,image/png,image/webp" multiple>
                            <p><strong>Clique para selecionar</strong> ou arraste aqui</p>
                            <p style="font-size:.76rem;margin-top:.3rem;color:#94a3b8;">JPG · PNG · WEBP — máx. 5 MB cada</p>
                        </div>
                        <div class="img-preview-grid" id="imgPreviewGrid"></div>
                        <span class="img-count-badge" id="imgCountBadge" style="display:none"></span>
                    </div>
                @endif

                {{-- VÍDEO --}}
                @if($post->tipo === 'video')
                    @if($post->video)
                        <div class="form-section">
                            <label>Vídeo atual</label>
                            <div class="video-preview-wrap">
                                @php
                                    $isYt = str_contains($post->video, 'youtube') || str_contains($post->video, 'youtu.be');
                                    $isVimeo = str_contains($post->video, 'vimeo');
                                @endphp
                                @if($isYt)
                                    @php preg_match('/(?:watch\?v=|youtu\.be\/)([^&\s]+)/', $post->video, $m); @endphp
                                    <iframe src="https://www.youtube.com/embed/{{ $m[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                                @elseif($isVimeo)
                                    @php preg_match('/vimeo\.com\/(\d+)/', $post->video, $m); @endphp
                                    <iframe src="https://player.vimeo.com/video/{{ $m[1] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    <video controls>
                                        <source src="{{ str_starts_with($post->video, '/storage/') ? asset($post->video) : Storage::url($post->video) }}">
                                    </video>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="form-section">
                        <label>Novo link (YouTube / Vimeo)</label>
                        <input type="url" name="video_url" class="form-control-styled"
                               placeholder="https://www.youtube.com/watch?v=..."
                               value="{{ old('video_url') }}">
                    </div>

                    <div class="form-section">
                        <label>Ou novo arquivo de vídeo</label>
                        <input type="file" name="video_file" class="form-control-styled"
                               accept="video/mp4,video/webm,video/ogg"
                               style="padding:.5rem;">
                    </div>
                @endif

                {{-- ENQUETE --}}
                @if($post->tipo === 'enquete')
                    <div class="form-section">
                        <label>Opções da Enquete</label>
                        <div class="enquete-opcoes">
                            @php
                                // Mostra as opções preenchidas + 2 vazias extras (máx 8)
                                $ultimaPreenchida = 0;
                                foreach(range(1, 8) as $i) {
                                    if(!empty($post->{'opcao'.$i})) $ultimaPreenchida = $i;
                                }
                                $mostrarAte = min(8, max(2, $ultimaPreenchida + 2));
                            @endphp

                            @foreach(range(1, $mostrarAte) as $i)
                                @php $campo = 'opcao'.$i; @endphp
                                <div class="opcao-row">
                                    <span class="opcao-num">{{ $i }}</span>
                                    <input type="text" name="{{ $campo }}"
                                           class="form-control-styled"
                                           placeholder="Opção {{ $i }}"
                                           value="{{ old($campo, $post->$campo) }}">
                                </div>
                            @endforeach
                        </div>

                        @if($mostrarAte < 8)
                            <p style="font-size:.78rem;color:var(--muted);margin-top:.5rem;">
                                💡 Preencha as opções acima para liberar mais campos (máx. 8)
                            </p>
                        @endif
                    </div>
                @endif

                {{-- Data --}}
                <div class="form-section">
                    <label>Data de Publicação</label>
                    <input type="date" name="data" class="form-control-styled"
                           value="{{ old('data', $post->data) }}">
                </div>

                {{-- Tamanho --}}
                <div class="form-section">
                    <label>Tamanho do Card</label>
                    <div class="tamanho-grid">
                        @foreach(['P' => 'Pequeno', 'M' => 'Médio', 'G' => 'Grande', 'GG' => 'Extra Grande'] as $val => $label)
                            <div>
                                <input type="radio" name="tamanho" value="{{ $val }}"
                                       id="tam{{ $val }}" class="tamanho-opt"
                                       {{ old('tamanho', $post->tamanho) === $val ? 'checked' : '' }}>
                                <label for="tam{{ $val }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="editor-footer">
            <a href="{{ route('posts.index') }}" class="btn-voltar">← Voltar</a>

            {{-- Remover mídia (form separado com DELETE) --}}
            @if($post->video || $post->imagem || ($post->imagens && $post->imagens->count()))
                <form method="POST"
                      action="{{ route('posts.removerMidia', $post->id) }}"
                      onsubmit="return confirm('Deseja remover a mídia deste post?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-remover">🗑️ Remover mídia</button>
                </form>
            @endif

            <button type="submit" form="editForm" class="btn-salvar">💾 Salvar alterações</button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
/* ── Preview de novas imagens ── */
let imagensFiles = [];
const dropZone    = document.getElementById('dropZone');
const inputImgs   = document.getElementById('inputImagens');
const previewGrid = document.getElementById('imgPreviewGrid');
const countBadge  = document.getElementById('imgCountBadge');

if (dropZone) {
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        addImages([...e.dataTransfer.files]);
    });
    inputImgs.addEventListener('change', () => {
        addImages([...inputImgs.files]);
        inputImgs.value = '';
    });
}

function addImages(files) {
    files.forEach(file => {
        if (!file.type.startsWith('image/')) return;
        if (imagensFiles.length >= 10) return;
        imagensFiles.push(file);
    });
    renderPreviews();
}

function renderPreviews() {
    if (!previewGrid) return;
    previewGrid.innerHTML = '';
    imagensFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = e => {
            const item = document.createElement('div');
            item.className = 'img-preview-item';
            item.innerHTML = `
                <img src="${e.target.result}" alt="preview">
                <button type="button" class="remove-img" onclick="removeImage(${idx})">✕</button>
            `;
            previewGrid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
    const n = imagensFiles.length;
    countBadge.style.display = n > 0 ? 'inline-block' : 'none';
    countBadge.textContent = `${n} imagem${n !== 1 ? 's' : ''} selecionada${n !== 1 ? 's' : ''}`;
    syncInputFiles();
}

function removeImage(idx) {
    imagensFiles.splice(idx, 1);
    renderPreviews();
}

function syncInputFiles() {
    const dt = new DataTransfer();
    imagensFiles.forEach(f => dt.items.add(f));
    if (inputImgs) inputImgs.files = dt.files;
}
</script>
@endpush

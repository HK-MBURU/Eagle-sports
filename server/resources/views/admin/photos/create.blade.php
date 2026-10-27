@extends('layouts.admin')

@section('title', 'Add Photos')
@section('page-title', 'Add Photos')

@section('content')
<div class="pg-create-wrap">

    <div class="pg-create-header">
        <a href="{{ route('admin.photos.index') }}" class="pg-back-btn">
            <i class="bi bi-arrow-left"></i> Back to Gallery
        </a>
        <h1 class="pg-create-title">Add Photos to Gallery</h1>
    </div>

    @if(session('error'))
        <div class="pg-alert pg-alert-error"><i class="bi bi-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="pg-alert pg-alert-error"><i class="bi bi-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif

    {{-- Mode Tabs --}}
    <div class="pg-mode-tabs">
        <button class="pg-mode-tab active" id="tab-upload" onclick="switchTab('upload')">
            <i class="bi bi-cloud-upload"></i> Upload New Photo
        </button>
        <button class="pg-mode-tab" id="tab-import" onclick="switchTab('import')">
            <i class="bi bi-collection"></i> Import from Existing Content
        </button>
    </div>

    {{-- ────────────────────────── UPLOAD TAB ────────────────────────── --}}
    <div id="pane-upload">
        <form action="{{ route('admin.photos.store') }}" method="POST" enctype="multipart/form-data" class="pg-form">
            @csrf
            <div class="pg-form-grid">

                {{-- Left: image upload --}}
                <div class="pg-form-col">
                    <div class="pg-card-form">
                        <div class="pg-card-form-header"><i class="bi bi-image"></i> Photo</div>
                        <div class="pg-card-form-body">
                            <div class="pg-upload-zone" id="uploadZone" onclick="document.getElementById('imageInput').click()">
                                <div class="pg-upload-placeholder" id="uploadPlaceholder">
                                    <i class="bi bi-cloud-upload" style="font-size:36px;color:#9aa5b4;"></i>
                                    <p>Click to upload or drag & drop</p>
                                    <span>JPEG, PNG, WebP, GIF — max 10 MB</span>
                                </div>
                                <img id="imagePreview" src="" alt="" style="display:none;max-width:100%;max-height:300px;border-radius:6px;object-fit:contain;">
                            </div>
                            <input type="file" id="imageInput" name="image" accept="image/*" style="display:none" required>
                            @error('image')<div class="pg-field-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Right: metadata --}}
                <div class="pg-form-col">
                    <div class="pg-card-form">
                        <div class="pg-card-form-header"><i class="bi bi-info-circle"></i> Details</div>
                        <div class="pg-card-form-body">

                            <div class="pg-field">
                                <label class="pg-label">Title <span class="req">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}" class="pg-input @error('title') err @enderror" placeholder="Photo title" required>
                                @error('title')<div class="pg-field-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="pg-field">
                                <label class="pg-label">Caption</label>
                                <textarea name="caption" rows="3" class="pg-input" placeholder="Optional caption shown in the gallery…">{{ old('caption') }}</textarea>
                            </div>

                            <div class="pg-field">
                                <label class="pg-label">Alt Text</label>
                                <input type="text" name="alt_text" value="{{ old('alt_text') }}" class="pg-input" placeholder="Describe the image for accessibility">
                            </div>

                            <div class="pg-field-row">
                                <div class="pg-field">
                                    <label class="pg-label">Category <span class="req">*</span></label>
                                    <select name="category" class="pg-input" required>
                                        @foreach($categories as $key => $label)
                                            <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pg-field">
                                    <label class="pg-label">Status</label>
                                    <select name="status" class="pg-input">
                                        <option value="published" {{ old('status','published') === 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="draft"     {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>
                            </div>

                            <label class="pg-checkbox-label">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <span>Mark as featured</span>
                            </label>

                        </div>
                    </div>
                    <div style="margin-top:16px;text-align:right;">
                        <button type="submit" class="pg-submit-btn"><i class="bi bi-plus-lg"></i> Add to Gallery</button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- ────────────────────────── IMPORT TAB ────────────────────────── --}}
    <div id="pane-import" style="display:none">
        <div class="pg-import-header">
            <p class="pg-import-intro">Browse images from existing content and add them to the gallery. Select multiple and click <strong>Import Selected</strong>.</p>

            <div class="pg-import-controls">
                {{-- Source type switcher --}}
                <div class="pg-source-tabs">
                    <button class="pg-source-tab active" data-source="story" onclick="loadSource('story', this)">
                        <i class="bi bi-newspaper"></i> Stories
                    </button>
                    <button class="pg-source-tab" data-source="women_running" onclick="loadSource('women_running', this)">
                        <i class="bi bi-person-standing"></i> Women Athletes
                    </button>
                    <button class="pg-source-tab" data-source="race_event" onclick="loadSource('race_event', this)">
                        <i class="bi bi-calendar-event"></i> Race Events
                    </button>
                </div>

                <div style="display:flex;align-items:center;gap:10px;">
                    <select id="importCategory" class="pg-filter-select">
                        <option value="">Auto-detect category</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <button onclick="importSelected()" class="pg-submit-btn" id="importBtn" disabled>
                        <i class="bi bi-download"></i> Import Selected (<span id="selCount">0</span>)
                    </button>
                </div>
            </div>
        </div>

        <div id="importAlertWrap"></div>

        {{-- Source image grid --}}
        <div id="sourceGrid" class="pg-source-grid">
            <div class="pg-source-loading"><i class="bi bi-arrow-clockwise pg-spin"></i> Loading…</div>
        </div>

        <div id="sourceLoadMore" style="display:none;text-align:center;margin-top:20px;">
            <button onclick="loadMore()" class="pg-load-more-btn">Load More</button>
        </div>
    </div>

</div>

<style>
.pg-create-wrap { padding:24px; max-width:1100px; }
.pg-create-header { display:flex; align-items:center; gap:14px; margin-bottom:24px; flex-wrap:wrap; }
.pg-back-btn { display:inline-flex; align-items:center; gap:6px; color:#5a6a7e; text-decoration:none; font-size:13px; font-weight:600; padding:8px 14px; background:#f4f6f9; border-radius:8px; }
.pg-back-btn:hover { background:#e8edf5; }
.pg-create-title { margin:0; font-size:20px; font-weight:800; color:#10284a; }

.pg-alert { padding:10px 16px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px; }
.pg-alert-success { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
.pg-alert-error { background:#ffebee; color:#c62828; border:1px solid #ef9a9a; }

.pg-mode-tabs { display:flex; gap:4px; margin-bottom:24px; background:#f4f6f9; border-radius:10px; padding:4px; width:fit-content; }
.pg-mode-tab { padding:10px 20px; border:none; background:transparent; border-radius:8px; font-size:13px; font-weight:700; color:#5a6a7e; cursor:pointer; display:inline-flex; align-items:center; gap:7px; transition:all .15s; }
.pg-mode-tab.active { background:#10284a; color:#fff; }

.pg-form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
@media(max-width:720px){ .pg-form-grid { grid-template-columns:1fr; } }

.pg-card-form { background:#fff; border-radius:12px; border:1.5px solid #e8edf5; overflow:hidden; }
.pg-card-form-header { padding:14px 20px; font-size:14px; font-weight:800; color:#10284a; background:#f8fafc; border-bottom:1.5px solid #e8edf5; display:flex; align-items:center; gap:8px; }
.pg-card-form-body { padding:20px; }

.pg-upload-zone { border:2px dashed #c8d0db; border-radius:10px; padding:28px 20px; text-align:center; cursor:pointer; transition:border-color .15s, background .15s; min-height:200px; display:flex; align-items:center; justify-content:center; flex-direction:column; }
.pg-upload-zone:hover { border-color:#10284a; background:#f8fafc; }
.pg-upload-zone p { margin:8px 0 4px; font-size:13px; font-weight:600; color:#5a6a7e; }
.pg-upload-zone span { font-size:11px; color:#9aa5b4; }

.pg-field { margin-bottom:16px; }
.pg-field-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.pg-label { display:block; font-size:12px; font-weight:700; color:#5a6a7e; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px; }
.req { color:#d62835; }
.pg-input { width:100%; padding:9px 13px; border:1.5px solid #dde3eb; border-radius:8px; font-size:13px; color:#10284a; background:#fff; box-sizing:border-box; }
.pg-input:focus { outline:none; border-color:#10284a; }
.pg-input.err { border-color:#d62835; }
.pg-field-error { color:#d62835; font-size:11px; margin-top:4px; }
.pg-checkbox-label { display:inline-flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#5a6a7e; cursor:pointer; }
.pg-submit-btn { display:inline-flex; align-items:center; gap:7px; background:#10284a; color:#fff; padding:11px 24px; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; transition:background .15s; }
.pg-submit-btn:hover:not(:disabled) { background:#d62835; }
.pg-submit-btn:disabled { background:#9aa5b4; cursor:not-allowed; }
.pg-filter-select { padding:9px 13px; border:1.5px solid #dde3eb; border-radius:8px; font-size:13px; background:#fff; cursor:pointer; }

/* Import tab */
.pg-import-header { margin-bottom:20px; }
.pg-import-intro { font-size:13px; color:#5a6a7e; margin:0 0 14px; }
.pg-import-controls { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; }
.pg-source-tabs { display:flex; gap:4px; background:#f4f6f9; border-radius:8px; padding:3px; }
.pg-source-tab { padding:8px 16px; border:none; background:transparent; border-radius:6px; font-size:13px; font-weight:700; color:#5a6a7e; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.pg-source-tab.active { background:#10284a; color:#fff; }
.pg-source-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:14px; margin-top:4px; }
.pg-source-card { border-radius:10px; overflow:hidden; background:#fff; border:2.5px solid #e8edf5; cursor:pointer; transition:border-color .12s, box-shadow .12s; position:relative; }
.pg-source-card.selected { border-color:#d62835; box-shadow:0 0 0 2px #d6283540; }
.pg-source-card img { width:100%; aspect-ratio:4/3; object-fit:cover; display:block; }
.pg-source-card-label { padding:7px 10px; font-size:11px; font-weight:700; color:#10284a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pg-source-card-check { position:absolute; top:7px; right:7px; width:22px; height:22px; background:#d62835; border-radius:50%; display:none; align-items:center; justify-content:center; color:#fff; font-size:12px; }
.pg-source-card.selected .pg-source-card-check { display:flex; }
.pg-source-loading { grid-column:1/-1; text-align:center; padding:48px; color:#9aa5b4; font-size:14px; }
.pg-source-error { grid-column:1/-1; text-align:center; padding:32px; color:#c62828; font-size:13px; }
.pg-source-empty { grid-column:1/-1; text-align:center; padding:32px; color:#9aa5b4; font-size:13px; }
.pg-already { position:absolute; inset:0; background:rgba(255,255,255,.55); display:flex; align-items:center; justify-content:center; }
.pg-already span { background:#10284a; color:#fff; font-size:10px; font-weight:700; padding:3px 8px; border-radius:4px; }
.pg-load-more-btn { padding:9px 24px; background:#f4f6f9; border:1.5px solid #dde3eb; border-radius:8px; font-size:13px; font-weight:700; color:#5a6a7e; cursor:pointer; }
.pg-load-more-btn:hover { background:#e8edf5; }
@keyframes spin { to { transform:rotate(360deg); } }
.pg-spin { display:inline-block; animation:spin .9s linear infinite; }
</style>

<script>
// ── Tab switching ──────────────────────────────────────────────
function switchTab(tab) {
    document.getElementById('pane-upload').style.display = tab === 'upload' ? '' : 'none';
    document.getElementById('pane-import').style.display = tab === 'import' ? '' : 'none';
    document.getElementById('tab-upload').classList.toggle('active', tab === 'upload');
    document.getElementById('tab-import').classList.toggle('active', tab === 'import');
    if (tab === 'import' && !sourceLoaded) loadSource('story', document.querySelector('.pg-source-tab'));
}

// ── Image upload preview ───────────────────────────────────────
document.getElementById('imageInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPlaceholder').style.display = 'none';
        const img = document.getElementById('imagePreview');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// Drag & drop
const zone = document.getElementById('uploadZone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor='#10284a'; });
zone.addEventListener('dragleave', () => { zone.style.borderColor=''; });
zone.addEventListener('drop', e => {
    e.preventDefault(); zone.style.borderColor='';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file);
        document.getElementById('imageInput').files = dt.files;
        document.getElementById('imageInput').dispatchEvent(new Event('change'));
    }
});

// ── Import from existing content ───────────────────────────────
let currentSource = 'story';
let currentPage   = 1;
let sourceLoaded  = false;
let hasMore       = false;
const selected    = new Map(); // key: `source_type:source_id:image_path`

function loadSource(source, btn) {
    currentSource = source;
    currentPage   = 1;
    selected.clear();
    updateImportBtn();
    document.querySelectorAll('.pg-source-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    fetchSourceImages(true);
    sourceLoaded = true;
}

function loadMore() {
    currentPage++;
    fetchSourceImages(false);
}

function fetchSourceImages(reset) {
    const grid = document.getElementById('sourceGrid');
    if (reset) grid.innerHTML = '<div class="pg-source-loading"><i class="bi bi-arrow-clockwise pg-spin"></i> Loading…</div>';

    fetch(`{{ route('admin.photos.sourceImages') }}?source=${currentSource}&page=${currentPage}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (reset) grid.innerHTML = '';
        hasMore = data.has_more;
        document.getElementById('sourceLoadMore').style.display = hasMore ? 'block' : 'none';

        if (!data.items || data.items.length === 0) {
            if (reset) grid.innerHTML = '<div class="pg-source-empty">No images found for this source.</div>';
            return;
        }

        data.items.forEach(item => {
            const key  = `${item.source_type}:${item.source_id}:${item.image_path}`;
            const card = document.createElement('div');
            card.className = 'pg-source-card' + (selected.has(key) ? ' selected' : '');
            card.dataset.key = key;
            card.innerHTML = `
                <img src="${item.thumb_url}" alt="${item.title}" loading="lazy" onerror="this.style.background='#dde3eb'">
                <div class="pg-source-card-check">✓</div>
                <div class="pg-source-card-label" title="${item.title}">${item.title}</div>
            `;
            card.addEventListener('click', () => toggleSelect(card, key, item));
            grid.appendChild(card);
        });
    })
    .catch(() => {
        if (reset) grid.innerHTML = '<div class="pg-source-error">Failed to load images. Please try again.</div>';
    });
}

function toggleSelect(card, key, item) {
    if (selected.has(key)) {
        selected.delete(key);
        card.classList.remove('selected');
    } else {
        selected.set(key, item);
        card.classList.add('selected');
    }
    updateImportBtn();
}

function resetImportBtn(count) {
    const btn = document.getElementById('importBtn');
    btn.innerHTML = '<i class="bi bi-download"></i> Import Selected (<span id="selCount">' + count + '</span>)';
    btn.disabled = count === 0;
}

function updateImportBtn() {
    const count = selected.size;
    const el = document.getElementById('selCount');
    if (el) el.textContent = count;
    const btn = document.getElementById('importBtn');
    if (btn) btn.disabled = count === 0;
}

function importSelected() {
    if (selected.size === 0) return;
    const btn = document.getElementById('importBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise pg-spin"></i> Importing…';

    const items   = Array.from(selected.values());
    const catOver = document.getElementById('importCategory').value;

    fetch('{{ route('admin.photos.batchImport') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ items, category_override: catOver }),
    })
    .then(r => {
        if (!r.ok) throw new Error('Server error ' + r.status);
        return r.json();
    })
    .then(data => {
        document.getElementById('importAlertWrap').innerHTML =
            `<div class="pg-alert pg-alert-success"><i class="bi bi-check-circle"></i> ${data.message || 'Imported successfully.'}</div>`;
        selected.clear();
        resetImportBtn(0);          // restore button first
        fetchSourceImages(true);    // refresh grid
    })
    .catch(err => {
        console.error('Import error:', err);
        document.getElementById('importAlertWrap').innerHTML =
            '<div class="pg-alert pg-alert-error"><i class="bi bi-exclamation-circle"></i> Import failed. Please try again.</div>';
        resetImportBtn(selected.size); // restore button with current count
    });
}
</script>
@endsection

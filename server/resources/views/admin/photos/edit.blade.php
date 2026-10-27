@extends('layouts.admin')

@section('title', 'Edit Photo')
@section('page-title', 'Edit Photo')

@section('content')
<div class="pg-create-wrap">

    <div class="pg-create-header">
        <a href="{{ route('admin.photos.index') }}" class="pg-back-btn">
            <i class="bi bi-arrow-left"></i> Back to Gallery
        </a>
        <h1 class="pg-create-title">Edit Photo</h1>
    </div>

    @if(session('error'))
        <div class="pg-alert pg-alert-error"><i class="bi bi-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="pg-alert pg-alert-error"><i class="bi bi-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif

    <form action="{{ route('admin.photos.update', $photo) }}" method="POST" enctype="multipart/form-data" class="pg-form">
        @csrf @method('PUT')

        <div class="pg-form-grid">
            {{-- Left: current image --}}
            <div class="pg-form-col">
                <div class="pg-card-form">
                    <div class="pg-card-form-header"><i class="bi bi-image"></i> Photo</div>
                    <div class="pg-card-form-body">
                        <div style="border-radius:8px;overflow:hidden;background:#f4f6f9;margin-bottom:14px;">
                            <img src="{{ $photo->display_url }}" alt="{{ $photo->title }}"
                                 style="width:100%;max-height:280px;object-fit:contain;display:block;"
                                 id="currentPreview" onerror="this.style.display='none'">
                        </div>
                        <label class="pg-label">Replace Photo (optional)</label>
                        <div class="pg-upload-zone" id="uploadZone" onclick="document.getElementById('imageInput').click()" style="min-height:80px;padding:16px;">
                            <i class="bi bi-cloud-upload" style="font-size:24px;color:#9aa5b4;"></i>
                            <p style="margin:4px 0 0;font-size:12px;color:#9aa5b4;">Click to choose a new image</p>
                        </div>
                        <input type="file" id="imageInput" name="image" accept="image/*" style="display:none">
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
                            <input type="text" name="title" value="{{ old('title', $photo->title) }}" class="pg-input @error('title') err @enderror" required>
                        </div>

                        <div class="pg-field">
                            <label class="pg-label">Caption</label>
                            <textarea name="caption" rows="3" class="pg-input">{{ old('caption', $photo->caption) }}</textarea>
                        </div>

                        <div class="pg-field">
                            <label class="pg-label">Alt Text</label>
                            <input type="text" name="alt_text" value="{{ old('alt_text', $photo->alt_text) }}" class="pg-input">
                        </div>

                        <div class="pg-field-row">
                            <div class="pg-field">
                                <label class="pg-label">Category <span class="req">*</span></label>
                                <select name="category" class="pg-input" required>
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}" {{ old('category', $photo->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pg-field">
                                <label class="pg-label">Status</label>
                                <select name="status" class="pg-input">
                                    <option value="published" {{ old('status', $photo->status) === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="draft"     {{ old('status', $photo->status) === 'draft'     ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>

                        <div class="pg-field">
                            <label class="pg-label">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $photo->sort_order) }}" class="pg-input" min="0" placeholder="0 = default">
                        </div>

                        <label class="pg-checkbox-label">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $photo->is_featured) ? 'checked' : '' }}>
                            <span>Mark as featured</span>
                        </label>

                    </div>
                </div>
                <div style="margin-top:16px;display:flex;gap:10px;justify-content:flex-end;align-items:center;">
                    <a href="{{ route('admin.photos.index') }}" class="pg-back-btn">Cancel</a>
                    <button type="submit" class="pg-submit-btn"><i class="bi bi-check-lg"></i> Save Changes</button>
                </div>
            </div>

        </div>
    </form>
</div>

<style>
.pg-create-wrap{padding:24px;max-width:1100px}
.pg-create-header{display:flex;align-items:center;gap:14px;margin-bottom:24px;flex-wrap:wrap}
.pg-back-btn{display:inline-flex;align-items:center;gap:6px;color:#5a6a7e;text-decoration:none;font-size:13px;font-weight:600;padding:8px 14px;background:#f4f6f9;border-radius:8px}
.pg-back-btn:hover{background:#e8edf5}
.pg-create-title{margin:0;font-size:20px;font-weight:800;color:#10284a}
.pg-alert{padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px}
.pg-alert-error{background:#ffebee;color:#c62828;border:1px solid #ef9a9a}
.pg-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
@media(max-width:720px){.pg-form-grid{grid-template-columns:1fr}}
.pg-card-form{background:#fff;border-radius:12px;border:1.5px solid #e8edf5;overflow:hidden}
.pg-card-form-header{padding:14px 20px;font-size:14px;font-weight:800;color:#10284a;background:#f8fafc;border-bottom:1.5px solid #e8edf5;display:flex;align-items:center;gap:8px}
.pg-card-form-body{padding:20px}
.pg-upload-zone{border:2px dashed #c8d0db;border-radius:10px;padding:16px 20px;text-align:center;cursor:pointer;transition:border-color .15s;display:flex;align-items:center;justify-content:center;flex-direction:column}
.pg-upload-zone:hover{border-color:#10284a;background:#f8fafc}
.pg-field{margin-bottom:16px}
.pg-field-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.pg-label{display:block;font-size:12px;font-weight:700;color:#5a6a7e;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px}
.req{color:#d62835}
.pg-input{width:100%;padding:9px 13px;border:1.5px solid #dde3eb;border-radius:8px;font-size:13px;color:#10284a;background:#fff;box-sizing:border-box}
.pg-input:focus{outline:none;border-color:#10284a}
.pg-input.err{border-color:#d62835}
.pg-field-error{color:#d62835;font-size:11px;margin-top:4px}
.pg-checkbox-label{display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#5a6a7e;cursor:pointer}
.pg-submit-btn{display:inline-flex;align-items:center;gap:7px;background:#10284a;color:#fff;padding:11px 24px;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;transition:background .15s}
.pg-submit-btn:hover{background:#d62835}
</style>

<script>
document.getElementById('imageInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('currentPreview').src = e.target.result;
        document.getElementById('currentPreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection

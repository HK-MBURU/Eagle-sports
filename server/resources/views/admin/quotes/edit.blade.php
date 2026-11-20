@extends('layouts.admin')

@section('title', 'Edit Quote')
@section('page-title', 'Edit Quote')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.quotes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Quotes
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Quote</h5>
                    <a href="{{ route('admin.quotes.show', $quote) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quotes.update', $quote) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="content" class="form-label">
                                Quote Content <span class="text-danger">*</span>
                            </label>
                            <textarea
                                name="content"
                                id="content"
                                class="form-control @error('content') is-invalid @enderror"
                                rows="5"
                                required>{{ old('content', $quote->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="owner" class="form-label">
                                Quote Owner <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="owner"
                                id="owner"
                                class="form-control @error('owner') is-invalid @enderror"
                                value="{{ old('owner', $quote->owner) }}"
                                required>
                            @error('owner')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="owner_title" class="form-label">Owner Title</label>
                            <input
                                type="text"
                                name="owner_title"
                                id="owner_title"
                                class="form-control @error('owner_title') is-invalid @enderror"
                                value="{{ old('owner_title', $quote->owner_title) }}"
                                placeholder="e.g., Olympic Marathon Champion">
                            @error('owner_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Owner Photo -->
                        <div class="mb-4">
                            <label class="form-label">Owner Photo</label>

                            @if($quote->owner_image)
                                <!-- Show current image -->
                                <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded">
                                    <img id="currentImg"
                                         src="{{ url('images/' . $quote->owner_image) }}"
                                         alt="{{ $quote->owner }}"
                                         style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #dee2e6;">
                                    <div>
                                        <div class="text-muted small mb-1">Current photo</div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="remove_image" id="remove_image" value="1"
                                                   {{ old('remove_image') ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger small" for="remove_image">
                                                Remove this photo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <!-- New upload preview -->
                                <div id="previewWrap" style="display:none;">
                                    <img id="previewImg" src="" alt="New photo"
                                         style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #0d6efd;">
                                </div>
                                @unless($quote->owner_image)
                                    <div id="placeholderCircle"
                                         style="width:60px;height:60px;border-radius:50%;background:#e9ecef;
                                                display:flex;align-items:center;justify-content:center;
                                                font-size:22px;color:#adb5bd;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endunless
                                <div class="flex-grow-1">
                                    <label for="owner_image" class="form-label small mb-1">
                                        {{ $quote->owner_image ? 'Upload replacement photo' : 'Upload photo' }}
                                    </label>
                                    <input type="file" name="owner_image" id="owner_image"
                                           class="form-control @error('owner_image') is-invalid @enderror"
                                           accept="image/jpeg,image/jpg,image/png,image/webp,image/gif">
                                    @error('owner_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        JPG, PNG, WebP — max 2 MB. Displayed as a circular avatar.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Added by:</strong> {{ $quote->addedBy->name }} on {{ $quote->created_at->format('M d, Y') }}
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.quotes.show', $quote) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Quote
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">Once you delete this quote, there is no going back. Please be certain.</p>
                    <form action="{{ route('admin.quotes.destroy', $quote) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this quote permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Quote Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('owner_image').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewWrap').style.display = 'block';
        const ph = document.getElementById('placeholderCircle');
        if (ph) ph.style.display = 'none';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection

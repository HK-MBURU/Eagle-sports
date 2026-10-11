@extends('layouts.admin')

@section('title', 'Edit Race Result')
@section('page-title', 'Edit Race Result')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Race Result</h2>
            <p class="text-muted mb-0">Update race result information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.results.show', $result->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-eye me-2"></i>View
            </a>
            <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.results.update', $result->id) }}" method="POST" enctype="multipart/form-data" id="edit-form">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $result->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="form-label fw-semibold">
                                Race Category <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('category') is-invalid @enderror"
                                    id="category"
                                    name="category"
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $result->category) == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current File Info -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Current File</label>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($result->isImage())
                                                    <i class="fas fa-image text-primary fa-2x"></i>
                                                @elseif($result->isPdf())
                                                    <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                                @else
                                                    <i class="fas fa-file-alt text-secondary fa-2x"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $result->file_name }}</div>
                                                <div class="small text-muted">
                                                    {{ $result->formatted_file_size }} • {{ strtoupper($result->file_type) }}
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.results.download', $result->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Replace File (Optional) -->
                        <div class="mb-4">
                            <label for="file" class="form-label fw-semibold">
                                Replace File <span class="text-muted small">(Optional)</span>
                            </label>
                            <div class="upload-area border-2 border-dashed rounded p-4 text-center @error('file') border-danger @enderror"
                                 id="upload-area">
                                <input type="file"
                                       class="form-control d-none @error('file') is-invalid @enderror"
                                       id="file"
                                       name="file"
                                       accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.txt">
                                <div id="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt text-muted mb-3" style="font-size: 2.5rem;"></i>
                                    <p class="mb-2">
                                        <strong>Click to upload</strong> or drag and drop
                                    </p>
                                    <p class="text-muted small mb-0">
                                        PDF, Images, or Documents (Max 10MB)
                                    </p>
                                </div>
                                <div id="file-preview" class="d-none">
                                    <i class="fas fa-file-alt text-success mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0 fw-semibold" id="file-name"></p>
                                    <p class="text-muted small mb-2" id="file-size"></p>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="remove-file">
                                        <i class="fas fa-times me-1"></i>Remove
                                    </button>
                                </div>
                            </div>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Add optional description...">{{ old('description', $result->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Race Date & Location -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="race_date" class="form-label fw-semibold">Race Date</label>
                                <input type="date"
                                       class="form-control @error('race_date') is-invalid @enderror"
                                       id="race_date"
                                       name="race_date"
                                       value="{{ old('race_date', $result->race_date ? $result->race_date->format('Y-m-d') : '') }}">
                                @error('race_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="location" class="form-label fw-semibold">Location</label>
                                <input type="text"
                                       class="form-control @error('location') is-invalid @enderror"
                                       id="location"
                                       name="location"
                                       value="{{ old('location', $result->location) }}"
                                       placeholder="e.g., Nairobi, Kenya">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('admin.results.index') }}" class="btn btn-lg btn-light">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-lg btn-primary" id="submit-btn">
                                <i class="fas fa-save me-2"></i>Update Result
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Publish Settings -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Publish Settings</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_published"
                               name="is_published"
                               {{ old('is_published', $result->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            <strong>Published</strong>
                            <div class="small text-muted">Make this result visible to public</div>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_featured"
                               name="is_featured"
                               {{ old('is_featured', $result->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <strong>Featured</strong>
                            <div class="small text-muted">Display on homepage</div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Views</span>
                        <strong>{{ $result->views_count }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Downloads</span>
                        <strong>{{ $result->downloads_count }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Uploaded By</span>
                        <strong>{{ $result->uploader->name }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Uploaded At</span>
                        <strong>{{ $result->created_at->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger border-0 shadow-sm">
                <div class="card-header bg-danger bg-opacity-10 border-danger">
                    <h5 class="mb-0 text-danger">Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Once you delete this result, there is no going back. Please be certain.
                    </p>
                    <form action="{{ route('admin.results.destroy', $result->id) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this result? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Delete Result
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .upload-area {
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    .upload-area:hover {
        background: #e9ecef;
        border-color: #007bff !important;
    }
    .form-control, .form-select {
        border-radius: 8px;
    }
    .card {
        border-radius: 12px;
    }
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
</style>
@endpush

@push('scripts')
<script>
    // File upload handling (same as create page)
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('file');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');

    uploadArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            displayFile(this.files[0]);
        }
    });

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            displayFile(e.dataTransfer.files[0]);
        }
    });

    function displayFile(file) {
        const size = (file.size / (1024 * 1024)).toFixed(2);
        fileName.textContent = file.name;
        fileSize.textContent = `${size} MB`;
        uploadPlaceholder.classList.add('d-none');
        filePreview.classList.remove('d-none');
    }

    removeFileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.value = '';
        uploadPlaceholder.classList.remove('d-none');
        filePreview.classList.add('d-none');
    });

    document.getElementById('edit-form').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    });
</script>
@endpush

@endsection

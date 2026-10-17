@extends('layouts.admin')

@section('title', 'Upload Result Document')
@section('page-title', 'Upload Result Document')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Upload Result Document</h2>
            <p class="text-muted mb-0">Upload a race result file (PDF, image, or document) for public download</p>
        </div>
        <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Result Documents
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.results.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="e.g., Nairobi Marathon 2024 - Final Results"
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
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="file" class="form-label fw-semibold">
                                Upload File <span class="text-danger">*</span>
                            </label>
                            <div class="upload-area border-2 border-dashed rounded p-4 text-center @error('file') border-danger @enderror"
                                 id="upload-area">
                                <input type="file"
                                       class="form-control d-none @error('file') is-invalid @enderror"
                                       id="file"
                                       name="file"
                                       accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.txt"
                                       required>
                                <div id="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt text-primary mb-3" style="font-size: 3rem;"></i>
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
                                      placeholder="Add optional description about the race results...">{{ old('description') }}</textarea>
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
                                       value="{{ old('race_date') }}">
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
                                       value="{{ old('location') }}"
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
                                <i class="fas fa-check me-2"></i>Upload Result
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
                               {{ old('is_published', true) ? 'checked' : '' }}>
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
                               {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <strong>Featured</strong>
                            <div class="small text-muted">Display on homepage</div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>Upload Guidelines
                    </h6>
                    <ul class="small mb-0 ps-3">
                        <li class="mb-2">Maximum file size: <strong>10MB</strong></li>
                        <li class="mb-2">Accepted formats: PDF, JPG, PNG, DOC, XLS</li>
                        <li class="mb-2">Use descriptive titles for better searchability</li>
                        <li class="mb-2">Add race date for proper categorization</li>
                    </ul>
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
    .upload-area.dragover {
        background: #e7f3ff;
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
    // File upload handling
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('file');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');

    // Click to upload
    uploadArea.addEventListener('click', () => fileInput.click());

    // File selected
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            displayFile(this.files[0]);
        }
    });

    // Drag and drop
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

    // Display file info
    function displayFile(file) {
        const size = (file.size / (1024 * 1024)).toFixed(2);
        fileName.textContent = file.name;
        fileSize.textContent = `${size} MB`;
        uploadPlaceholder.classList.add('d-none');
        filePreview.classList.remove('d-none');
    }

    // Remove file
    removeFileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.value = '';
        uploadPlaceholder.classList.remove('d-none');
        filePreview.classList.add('d-none');
    });

    // Form submission
    document.getElementById('upload-form').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    });
</script>
@endpush

@endsection

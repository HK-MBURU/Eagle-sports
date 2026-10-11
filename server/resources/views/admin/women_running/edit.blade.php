@extends('layouts.admin')

@section('title', 'Edit Entry - ' . $womenRunning->name)
@section('page-title', 'Edit Entry')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.women-running.index') }}">Women Running</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.women-running.show', $womenRunning) }}">{{ $womenRunning->name }}</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Edit Entry</h5>
                            <a href="{{ route('admin.women-running.show', $womenRunning) }}"
                                class="btn btn-secondary btn-sm">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.women-running.update', $womenRunning) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">
                                        Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $womenRunning->name) }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Title -->
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label fw-bold">
                                        Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $womenRunning->title) }}"
                                        required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label fw-bold">
                                        Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" required>{{ old('description', $womenRunning->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label fw-bold">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        @foreach ($categories as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('category', $womenRunning->category) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Icon -->
                                <div class="col-md-4 mb-3">
                                    <label for="icon" class="form-label fw-bold">Icon</label>
                                    <select class="form-select @error('icon') is-invalid @enderror" id="icon"
                                        name="icon">
                                        <option value="">Select Icon</option>
                                        @foreach ($icons as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('icon', $womenRunning->icon) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Order -->
                                <div class="col-md-4 mb-3">
                                    <label for="order" class="form-label fw-bold">Display Order</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                        id="order" name="order" value="{{ old('order', $womenRunning->order) }}"
                                        min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current Image Preview -->
                                @if ($womenRunning->image)
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">Current Image</label>
                                        <div>
                                            <img src="{{ asset('storage/' . $womenRunning->image) }}"
                                                alt="{{ $womenRunning->name }}" class="img-thumbnail"
                                                style="max-width: 300px;">
                                        </div>
                                    </div>
                                @endif

                                <!-- New Image -->
                                <div class="col-md-12 mb-3">
                                    <label for="image" class="form-label fw-bold">
                                        {{ $womenRunning->image ? 'Change Image' : 'Upload Image' }}
                                    </label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>

                                <!-- Story Content -->
                                <div class="col-md-12 mb-3">
                                    <label for="story_content" class="form-label fw-bold">Full Story Content</label>
                                    <textarea class="form-control @error('story_content') is-invalid @enderror" id="story_content" name="story_content"
                                        rows="8">{{ old('story_content', $womenRunning->story_content) }}</textarea>
                                    @error('story_content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Video URL -->
                                <div class="col-md-12 mb-3">
                                    <label for="video_url" class="form-label fw-bold">Video URL</label>
                                    <input type="url" class="form-control @error('video_url') is-invalid @enderror"
                                        id="video_url" name="video_url"
                                        value="{{ old('video_url', $womenRunning->video_url) }}"
                                        placeholder="https://youtube.com/watch?v=...">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Checkboxes -->
                                <div class="col-md-12 mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="featured"
                                                            name="featured" value="1"
                                                            {{ old('featured', $womenRunning->featured) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="featured">
                                                            <i class="fas fa-star text-warning"></i> Featured Entry
                                                        </label>
                                                        <small class="d-block text-muted">Display this entry
                                                            prominently</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="status"
                                                            name="status" value="1"
                                                            {{ old('status', $womenRunning->status) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="status">
                                                            <i class="fas fa-check-circle text-success"></i> Active Status
                                                        </label>
                                                        <small class="d-block text-muted">Make this entry visible</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-save me-2"></i> Update Entry
                                </button>
                                <a href="{{ route('admin.women-running.show', $womenRunning) }}"
                                    class="btn btn-secondary btn-lg px-4">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Tips Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Tips</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Name:</strong> The person's full name
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Title:</strong> A compelling headline about their achievement
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Description:</strong> Brief summary (2-3 sentences)
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Category:</strong> Choose the appropriate program category
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Image:</strong> Use high-quality images (recommended: 800x600px)
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Featured:</strong> Only mark as featured for highlighted stories
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Entry Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted d-block">Created</small>
                            <strong>{{ $womenRunning->created_at->format('M d, Y h:i A') }}</strong>
                        </div>
                        <div>
                            <small class="text-muted d-block">Last Modified</small>
                            <strong>{{ $womenRunning->updated_at->format('M d, Y h:i A') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .card {
                border-radius: 15px;
            }

            .form-label {
                font-size: 0.95rem;
            }

            .form-control,
            .form-select {
                border-radius: 8px;
                padding: 0.6rem 0.75rem;
            }

            .form-check-input {
                width: 2.5rem;
                height: 1.25rem;
                cursor: pointer;
            }

            .breadcrumb-item+.breadcrumb-item::before {
                content: "›";
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function previewImage(event) {
                const preview = document.getElementById('imagePreview');
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `
                <div class="mt-2">
                    <p class="small text-muted mb-2">New Image Preview:</p>
                    <img src="${e.target.result}" class="img-thumbnail" style="max-width: 300px;">
                </div>
            `;
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = '';
                }
            }
        </script>
    @endpush
@endsection

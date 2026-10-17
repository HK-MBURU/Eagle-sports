@extends('layouts.admin')

@section('title', $result->title)
@section('page-title', 'Result Document Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.results.index') }}" class="text-muted text-decoration-none mb-2 d-inline-block">
                <i class="fas fa-arrow-left me-2"></i>Back to Result Documents
            </a>
            <h2 class="mb-1">{{ $result->title }}</h2>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-primary">{{ $result->category }}</span>
                @if($result->is_featured)
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-star"></i> Featured
                    </span>
                @endif
                @if($result->is_published)
                    <span class="badge bg-success">Published</span>
                @else
                    <span class="badge bg-secondary">Draft</span>
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.results.download', $result->id) }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Download
            </a>
            <a href="{{ route('admin.results.edit', $result->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <form action="{{ route('admin.results.destroy', $result->id) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this result?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- File Preview Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    @if($result->isImage())
                        <img src="{{ $result->file_url }}"
                             alt="{{ $result->title }}"
                             class="w-100 rounded-top"
                             style="max-height: 500px; object-fit: contain; background: #f8f9fa;">
                    @elseif($result->isPdf())
                        <div class="p-5 text-center bg-light">
                            <i class="fas fa-file-pdf text-danger mb-3" style="font-size: 5rem;"></i>
                            <h4>PDF Document</h4>
                            <p class="text-muted mb-4">{{ $result->file_name }}</p>
                            <a href="{{ $result->file_url }}"
                               target="_blank"
                               class="btn btn-outline-danger me-2">
                                <i class="fas fa-eye me-2"></i>View PDF
                            </a>
                            <a href="{{ route('admin.results.download', $result->id) }}"
                               class="btn btn-danger">
                                <i class="fas fa-download me-2"></i>Download
                            </a>
                        </div>
                    @else
                        <div class="p-5 text-center bg-light">
                            <i class="fas fa-file-alt text-secondary mb-3" style="font-size: 5rem;"></i>
                            <h4>{{ strtoupper($result->file_type) }} Document</h4>
                            <p class="text-muted mb-4">{{ $result->file_name }}</p>
                            <a href="{{ route('admin.results.download', $result->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Download File
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description Card -->
            @if($result->description)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $result->description }}</p>
                </div>
            </div>
            @endif

            <!-- Details Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Race Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-calendar-alt text-primary mt-1 me-3"></i>
                                <div>
                                    <div class="text-muted small">Race Date</div>
                                    <div class="fw-semibold">{{ $result->formatted_date ?? 'Not specified' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-danger mt-1 me-3"></i>
                                <div>
                                    <div class="text-muted small">Location</div>
                                    <div class="fw-semibold">{{ $result->location ?? 'Not specified' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-tag text-success mt-1 me-3"></i>
                                <div>
                                    <div class="text-muted small">Category</div>
                                    <div class="fw-semibold">{{ $result->category }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-file text-info mt-1 me-3"></i>
                                <div>
                                    <div class="text-muted small">File Type</div>
                                    <div class="fw-semibold">{{ strtoupper($result->file_type) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-hdd text-warning mt-1 me-3"></i>
                                <div>
                                    <div class="text-muted small">File Size</div>
                                    <div class="fw-semibold">{{ $result->formatted_file_size }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-user text-secondary mt-1 me-3"></i>
                                <div>
                                    <div class="text-muted small">Uploaded By</div>
                                    <div class="fw-semibold">{{ $result->uploader->name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.results.toggle-published', $result->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $result->is_published ? 'btn-outline-secondary' : 'btn-success' }} w-100">
                                <i class="fas {{ $result->is_published ? 'fa-eye-slash' : 'fa-eye' }} me-2"></i>
                                {{ $result->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.results.toggle-featured', $result->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $result->is_featured ? 'btn-outline-warning' : 'btn-warning' }} w-100">
                                <i class="fas fa-star me-2"></i>
                                {{ $result->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.results.download', $result->id) }}" class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-eye text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Views</div>
                                <div class="h5 mb-0">{{ $result->views_count }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-download text-success"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Downloads</div>
                                <div class="h5 mb-0">{{ $result->downloads_count }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Information -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">File Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-muted">Filename</div>
                        <div class="text-break">{{ $result->file_name }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Size</div>
                        <div>{{ $result->formatted_file_size }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Type</div>
                        <div>{{ strtoupper($result->file_type) }}</div>
                    </div>
                    <div>
                        <div class="small text-muted">Uploaded</div>
                        <div>{{ $result->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Meta Information -->
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>Meta Information
                    </h6>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">ID:</span>
                            <span class="fw-semibold">{{ $result->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Created:</span>
                            <span class="fw-semibold">{{ $result->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Updated:</span>
                            <span class="fw-semibold">{{ $result->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">By:</span>
                            <span class="fw-semibold">{{ $result->uploader->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 12px;
    }
    .badge {
        padding: 0.5em 0.8em;
        border-radius: 6px;
    }
    .btn {
        border-radius: 8px;
    }
</style>
@endpush

@endsection

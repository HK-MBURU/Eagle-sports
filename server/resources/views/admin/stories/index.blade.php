@extends('layouts.admin')

@section('title', 'Stories')
@section('page-title', 'All Stories')

@section('content')
<!-- Header with Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Stories Management</h2>
        <p class="text-muted mb-0">{{ $stories->total() }} total stories</p>
    </div>
    <a href="{{ route('admin.stories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Create New Story
    </a>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Stories</h6>
                        <h3 class="mb-0">{{ $stories->total() }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-file-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Published</h6>
                        <h3 class="mb-0 text-success">{{ \App\Models\Story::where('status', 'published')->count() }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Drafts</h6>
                        <h3 class="mb-0 text-warning">{{ \App\Models\Story::where('status', 'draft')->count() }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-pencil-square fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Featured</h6>
                        <h3 class="mb-0 text-warning">{{ \App\Models\Story::where('is_featured', true)->count() }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-star-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.stories.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="filter_status" class="form-label fw-semibold">Status</label>
                <select class="form-select" id="filter_status" name="status">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_category" class="form-label fw-semibold">Category</label>
                <select class="form-select" id="filter_category" name="category">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Story::CATEGORIES as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label fw-semibold">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                       value="{{ request('search') }}" placeholder="Search by title or author...">
            </div>
            <div class="col-md-2">
                <label class="form-label d-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </div>
        </form>

        @if(request()->hasAny(['status', 'category', 'search']))
        <div class="mt-3">
            <a href="{{ route('admin.stories.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Clear Filters
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Stories Grid -->
@if($stories->count() > 0)
<div class="row g-4 mb-4">
    @foreach($stories as $story)
    <div class="col-md-6 col-lg-4">
        <div class="card border h-100">
            <!-- Story Image -->
            <div style="height: 200px; overflow: hidden; background: #f8f9fa;">
                <img src="{{ asset('storage/' . $story->image) }}"
                     alt="{{ $story->title }}"
                     class="w-100"
                     style="height: 200px; object-fit: cover;">
            </div>

            <div class="card-body d-flex flex-column">
                <!-- Status & Featured Badges -->
                <div class="mb-2">
                    @if($story->status === 'published')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle"></i> Published
                        </span>
                    @elseif($story->status === 'draft')
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-pencil-square"></i> Draft
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="bi bi-archive"></i> Archived
                        </span>
                    @endif

                    @if($story->is_featured)
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-star-fill"></i> Featured
                        </span>
                    @endif
                </div>

                <!-- Category -->
                <div class="mb-2">
                    <span class="badge bg-primary">{{ $story->category }}</span>
                </div>

                <!-- Title -->
                <h5 class="card-title mb-2">
                    <a href="{{ route('admin.stories.show', $story) }}" class="text-dark text-decoration-none">
                        {{ Str::limit($story->title, 60) }}
                    </a>
                </h5>

                <!-- Excerpt -->
                <p class="card-text text-muted small mb-3">
                    {{ Str::limit($story->excerpt, 100) }}
                </p>

                <!-- Meta Info -->
                <div class="text-muted small mb-3">
                    <div class="mb-1">
                        <i class="bi bi-person-circle me-1"></i>{{ $story->author_name }}
                    </div>
                    <div class="mb-1">
                        <i class="bi bi-calendar3 me-1"></i>{{ $story->published_date->format('M d, Y') }}
                    </div>
                    <div>
                        <i class="bi bi-clock me-1"></i>{{ $story->read_time }} min
                        <i class="bi bi-heart ms-2 me-1"></i>{{ $story->likes_count ?? 0 }} likes
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-auto pt-2 border-top">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.stories.show', $story) }}"
                           class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <a href="{{ route('admin.stories.edit', $story) }}"
                           class="btn btn-sm btn-outline-warning flex-fill">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $story->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal for each story -->
    <div class="modal fade" id="deleteModal{{ $story->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Are you sure you want to delete <strong>"{{ $story->title }}"</strong>?</p>
                    <p class="text-danger mb-0">
                        <small><i class="bi bi-info-circle me-1"></i>This action cannot be undone.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.stories.destroy', $story) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $stories->links() }}
</div>

@else
<!-- Empty State -->
<div class="card border">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
        <h4 class="text-muted mb-3">No stories found</h4>

        @if(request()->hasAny(['status', 'category', 'search']))
            <p class="text-muted mb-3">Try adjusting your filters</p>
            <a href="{{ route('admin.stories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i>Clear Filters
            </a>
        @else
            <p class="text-muted mb-3">Get started by creating your first story</p>
            <a href="{{ route('admin.stories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create Your First Story
            </a>
        @endif
    </div>
</div>
@endif

@push('styles')
<style>
    /* Minimal, flat design with no animations */
    .card {
        border-radius: 4px;
    }

    .card-title a:hover {
        color: #FF6600 !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #FF6600, #FFBF00);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #E55A00, #E6AB00);
    }

    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }

    /* Simple focus states */
    .form-control:focus,
    .form-select:focus {
        border-color: #FF6600;
        box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.15);
    }

    /* Clean button styles */
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>
@endpush
@endsection

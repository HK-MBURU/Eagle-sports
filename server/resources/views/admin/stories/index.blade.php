@extends('layouts.admin')

@section('title', 'Stories')
@section('page-title', 'All Stories')

@section('content')
<!-- Header with Actions -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h2 class="mb-0 fs-4 fw-bold">Stories Management</h2>
        <p class="text-muted mb-0 small">{{ $stories->total() }} total stories</p>
    </div>
    <a href="{{ route('admin.stories.create') }}" class="btn btn-primary flex-shrink-0">
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
    <div class="col-6 col-md-3">
        <div class="card border h-100">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Stories</p>
                        <h3 class="mb-0 fw-bold">{{ $stories->total() }}</h3>
                    </div>
                    <div class="text-primary d-none d-sm-block">
                        <i class="bi bi-file-text fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border h-100">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Published</p>
                        <h3 class="mb-0 fw-bold text-success">{{ \App\Models\Story::where('status', 'published')->count() }}</h3>
                    </div>
                    <div class="text-success d-none d-sm-block">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border h-100">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Drafts</p>
                        <h3 class="mb-0 fw-bold text-warning">{{ \App\Models\Story::where('status', 'draft')->count() }}</h3>
                    </div>
                    <div class="text-warning d-none d-sm-block">
                        <i class="bi bi-pencil-square fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border h-100">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Featured</p>
                        <h3 class="mb-0 fw-bold text-warning">{{ \App\Models\Story::where('is_featured', true)->count() }}</h3>
                    </div>
                    <div class="text-warning d-none d-sm-block">
                        <i class="bi bi-star-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.stories.index') }}" class="row g-2">
            <div class="col-6 col-md-3">
                <label for="filter_status" class="form-label fw-semibold small mb-1">Status</label>
                <select class="form-select form-select-sm" id="filter_status" name="status">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label for="filter_category" class="form-label fw-semibold small mb-1">Category</label>
                <select class="form-select form-select-sm" id="filter_category" name="category">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Story::CATEGORIES as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label for="search" class="form-label fw-semibold small mb-1">Search</label>
                <input type="text" class="form-control form-control-sm" id="search" name="search"
                       value="{{ request('search') }}" placeholder="Search by title or author...">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label d-block small mb-1">&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-sm w-100">
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
                           class="btn btn-sm fw-semibold text-white flex-fill"
                           style="background:#0891b2;border:none;">
                            <i class="bi bi-eye-fill me-1"></i>View
                        </a>
                        <a href="{{ route('admin.stories.edit', $story) }}"
                           class="btn btn-sm fw-semibold text-white flex-fill"
                           style="background:#d97706;border:none;">
                            <i class="bi bi-pencil-fill me-1"></i>Edit
                        </a>
                        <button type="button"
                                class="btn btn-sm fw-semibold text-white"
                                style="background:#dc2626;border:none;min-width:38px;"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $story->id }}">
                            <i class="bi bi-trash-fill"></i>
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
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-2 pt-3 border-top">
    <p class="text-muted small mb-0">
        Showing {{ $stories->firstItem() }}–{{ $stories->lastItem() }} of {{ $stories->total() }} stories
    </p>
    <div class="es-pagination">
        {{ $stories->links() }}
    </div>
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
    .card { border-radius: 6px; }

    .card-title a:hover { color: var(--es-red) !important; }

    .badge { font-weight: 600; font-size: 0.72rem; }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--es-red);
        box-shadow: 0 0 0 3px rgba(214,40,53,.08);
    }

    /* ── Pagination ── */
    .es-pagination .pagination {
        margin: 0;
        gap: 3px;
    }
    .es-pagination .page-item .page-link {
        border-radius: 5px !important;
        border: 1px solid var(--es-line);
        color: var(--es-navy);
        font-size: 0.8rem;
        font-weight: 600;
        padding: 5px 11px;
        line-height: 1.5;
        min-width: 34px;
        text-align: center;
        transition: background .12s, border-color .12s, color .12s;
    }
    .es-pagination .page-item.active .page-link {
        background: var(--es-red);
        border-color: var(--es-red);
        color: #fff;
    }
    .es-pagination .page-item .page-link:hover:not(.disabled) {
        background: var(--es-paper);
        border-color: var(--es-red);
        color: var(--es-red);
    }
    .es-pagination .page-item.disabled .page-link {
        color: #b0b8c4;
        border-color: var(--es-line);
        background: transparent;
    }
</style>
@endpush
@endsection

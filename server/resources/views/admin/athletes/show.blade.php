@extends('layouts.admin')

@section('title', $athlete->name)
@section('page-title', 'Athlete Details')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('admin.athletes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Athletes
            </a>
            <div class="btn-group">
                <a href="{{ route('admin.athletes.edit', $athlete) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <button type="button" 
                        class="btn btn-danger"
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-2"></i>Delete
                </button>
            </div>
        </div>

        <!-- Athlete Card -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <!-- Hero Section with Image -->
            <div class="row g-0">
                <div class="col-md-5">
                    <div class="position-relative" style="height: 500px;">
                        <img src="{{ asset('storage/' . $athlete->image) }}" 
                             alt="{{ $athlete->name }}"
                             class="w-100 h-100 object-fit-cover">
                        
                        <!-- Badges Overlay -->
                        <div class="position-absolute top-0 start-0 p-3">
                            @if($athlete->badge)
                                <span class="badge mb-2" 
                                      style="background: {{ $athlete->badge_color }}; color: #000; font-size: 1rem; padding: 8px 16px;">
                                    {{ $athlete->badge }}
                                </span>
                            @endif
                            
                            <br>
                            
                            @if($athlete->is_featured)
                                <span class="badge bg-primary" style="font-size: 0.9rem; padding: 6px 12px;">
                                    <i class="bi bi-star-fill me-1"></i>Featured
                                </span>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge {{ $athlete->status === 'active' ? 'bg-success' : 'bg-secondary' }}" 
                                  style="font-size: 0.9rem; padding: 6px 12px;">
                                {{ ucfirst($athlete->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card-body p-5">
                        <!-- Name -->
                        <h1 class="display-5 fw-bold mb-3" style="color: #333A12;">
                            {{ $athlete->name }}
                        </h1>

                        <!-- Title -->
                        <h4 class="mb-3" style="color: #FF6600;">
                            {{ $athlete->title }}
                        </h4>

                        <!-- Description -->
                        <p class="lead text-muted mb-4">
                            {{ $athlete->description }}
                        </p>

                        <hr class="my-4">

                        <!-- Biography -->
                        <h5 class="fw-bold mb-3">Biography</h5>
                        <div class="text-muted" style="line-height: 1.8;">
                            {!! nl2br(e($athlete->bio)) !!}
                        </div>

                        <hr class="my-4">

                        <!-- Metadata -->
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">Display Order</small>
                                <strong>{{ $athlete->display_order }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">Badge Color</small>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 30px; height: 30px; background: {{ $athlete->badge_color }}; border-radius: 4px; border: 1px solid #ddd;"></div>
                                    <strong>{{ $athlete->badge_color }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">Created</small>
                                <strong>{{ $athlete->created_at->format('M d, Y') }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">Last Updated</small>
                                <strong>{{ $athlete->updated_at->format('M d, Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Athlete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $athlete->name }}</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.athletes.destroy', $athlete) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endpush
@endsection
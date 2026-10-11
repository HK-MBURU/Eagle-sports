@extends('layouts.admin')

@section('title', 'Featured Athletes')
@section('page-title', 'Featured Athletes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">All Athletes ({{ $athletes->total() }})</h5>
        <p class="text-muted mb-0">Manage your featured athletes</p>
    </div>
    <a href="{{ route('admin.athletes.create') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-2"></i>Add New Athlete
    </a>
</div>

@if($athletes->count() > 0)
    <div class="row g-4">
        @foreach($athletes as $athlete)
        <div class="col-lg-4 col-md-6">
            <div class="card athlete-card border-0 shadow-sm h-100">
                <!-- Image -->
                <div class="athlete-image-wrapper">
                    <img src="{{ asset('storage/' . $athlete->image) }}" 
                         class="card-img-top" 
                         alt="{{ $athlete->name }}"
                         style="height: 300px; object-fit: cover;">
                    
                    <!-- Badge -->
                    @if($athlete->badge)
                        <span class="badge position-absolute top-0 start-0 m-3" 
                              style="background: {{ $athlete->badge_color }}; color: #000; font-weight: bold;">
                            {{ $athlete->badge }}
                        </span>
                    @endif

                    <!-- Status Badge -->
                    <span class="badge position-absolute top-0 end-0 m-3 {{ $athlete->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($athlete->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <!-- Name -->
                    <h5 class="card-title mb-2">{{ $athlete->name }}</h5>
                    
                    <!-- Title -->
                    <p class="text-warning fw-semibold mb-2">{{ $athlete->title }}</p>
                    
                    <!-- Description -->
                    <p class="text-muted small mb-3">{{ Str::limit($athlete->description, 60) }}</p>

                    <!-- Featured Badge -->
                    @if($athlete->is_featured)
                        <span class="badge bg-primary mb-3">
                            <i class="bi bi-star-fill me-1"></i>Featured
                        </span>
                    @endif

                    <!-- Actions -->
                    <div class="btn-group w-100">
                        <a href="{{ route('admin.athletes.show', $athlete) }}" 
                           class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.athletes.edit', $athlete) }}" 
                           class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal{{ $athlete->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal{{ $athlete->id }}" tabindex="-1">
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
                            <form action="{{ route('admin.athletes.destroy', $athlete) }}" method="POST">
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
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $athletes->links() }}
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-people display-1 text-muted"></i>
            <h3 class="mt-3">No Athletes Yet</h3>
            <p class="text-muted mb-4">Start by adding your first featured athlete.</p>
            <a href="{{ route('admin.athletes.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle me-2"></i>Add First Athlete
            </a>
        </div>
    </div>
@endif

@push('styles')
<style>
    .athlete-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .athlete-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .athlete-image-wrapper {
        position: relative;
        overflow: hidden;
    }
</style>
@endpush
@endsection
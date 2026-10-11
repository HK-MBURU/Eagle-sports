@extends('layouts.admin')

@section('title', 'View Entry - ' . $womenRunning->name)
@section('page-title', 'Entry Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.women-running.index') }}">Women Running</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $womenRunning->name }}</li>
                    </ol>
                </nav>
                <div>
                    <a href="{{ route('admin.women-running.edit', $womenRunning) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Entry
                    </a>
                    <a href="{{ route('admin.women-running.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Image Card -->
            <div class="card shadow-sm border-0 mb-4">
                @if($womenRunning->image)
                <img src="{{ asset('storage/' . $womenRunning->image) }}" 
                     class="card-img-top" 
                     alt="{{ $womenRunning->name }}"
                     style="max-height: 500px; object-fit: cover;">
                @else
                <div class="bg-gradient-secondary d-flex align-items-center justify-content-center text-white" 
                     style="height: 400px;">
                    <div class="text-center">
                        <i class="fas fa-image fa-5x opacity-50 mb-3"></i>
                        <p class="mb-0">No image available</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Details Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-2 fw-bold">{{ $womenRunning->name }}</h3>
                            <h5 class="text-muted mb-3">{{ $womenRunning->title }}</h5>
                        </div>
                        <div>
                            @if($womenRunning->featured)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> Featured
                            </span>
                            @endif
                            <span class="badge bg-{{ $womenRunning->status ? 'success' : 'secondary' }}">
                                {{ $womenRunning->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted small mb-2">Description</h6>
                        <p class="lead">{{ $womenRunning->description }}</p>
                    </div>

                    @if($womenRunning->story_content)
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted small mb-2">Full Story</h6>
                        <div class="story-content">
                            {!! nl2br(e($womenRunning->story_content)) !!}
                        </div>
                    </div>
                    @endif

                    @if($womenRunning->video_url)
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted small mb-2">Video Interview</h6>
                        <div class="ratio ratio-16x9">
                            @php
                                $videoId = null;
                                if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $womenRunning->video_url, $matches)) {
                                    $videoId = $matches[1];
                                } elseif (preg_match('/youtu\.be\/([^?]+)/', $womenRunning->video_url, $matches)) {
                                    $videoId = $matches[1];
                                }
                            @endphp
                            
                            @if($videoId)
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen></iframe>
                            @else
                            <div class="d-flex align-items-center justify-content-center bg-light">
                                <a href="{{ $womenRunning->video_url }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt me-2"></i> Watch Video
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Info Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold">Entry Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Category</small>
                        <span class="badge bg-primary">
                            @if($womenRunning->icon)
                            <i class="fas fa-{{ $womenRunning->icon }} me-1"></i>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $womenRunning->category)) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Display Order</small>
                        <strong>{{ $womenRunning->order }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        <span class="badge bg-{{ $womenRunning->status ? 'success' : 'secondary' }}">
                            {{ $womenRunning->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Featured</small>
                        <span class="badge bg-{{ $womenRunning->featured ? 'warning' : 'secondary' }}">
                            {{ $womenRunning->featured ? 'Yes' : 'No' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Created</small>
                        <strong>{{ $womenRunning->created_at->format('M d, Y') }}</strong>
                        <br>
                        <small class="text-muted">{{ $womenRunning->created_at->diffForHumans() }}</small>
                    </div>

                    <div class="mb-0">
                        <small class="text-muted d-block mb-1">Last Updated</small>
                        <strong>{{ $womenRunning->updated_at->format('M d, Y') }}</strong>
                        <br>
                        <small class="text-muted">{{ $womenRunning->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.women-running.toggle-status', $womenRunning) }}" 
                          method="POST" 
                          class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $womenRunning->status ? 'success' : 'secondary' }} w-100">
                            <i class="fas fa-power-off me-2"></i>
                            {{ $womenRunning->status ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.women-running.toggle-featured', $womenRunning) }}" 
                          method="POST" 
                          class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $womenRunning->featured ? 'warning' : 'outline-warning' }} w-100">
                            <i class="fas fa-star me-2"></i>
                            {{ $womenRunning->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                        </button>
                    </form>

                    <hr>

                    <form action="{{ route('admin.women-running.destroy', $womenRunning) }}" 
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this entry? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i> Delete Entry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .story-content {
        line-height: 1.8;
        font-size: 1.05rem;
    }
    
    .card {
        border-radius: 15px;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
    }
</style>
@endpush
@endsection
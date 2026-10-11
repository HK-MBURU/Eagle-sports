@extends('layouts.admin')

@section('title', 'Women Running')
@section('page-title', 'Women Running Management')

@section('content')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 header-gradient">
                <div class="card-body text-white py-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h3 class="mb-2 fw-bold text-white">Women Running Entries</h3>
                            <p class="mb-0 text-white-75">Manage mentorship, coaching, and advocacy content</p>
                        </div>
                        <a href="{{ route('admin.women-running.create') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-plus me-2"></i> Add New Entry
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <ul class="nav nav-pills justify-content-start" id="categoryFilter">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-filter="all">
                                <i class="fas fa-th me-1"></i> All 
                                <span class="badge bg-light text-dark ms-1">{{ $womenRunning->total() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="mentorship">
                                <i class="fas fa-handshake me-1"></i> Mentorship
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="coaching">
                                <i class="fas fa-users me-1"></i> Coaching
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="advocacy">
                                <i class="fas fa-bullhorn me-1"></i> Advocacy
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="featured_story">
                                <i class="fas fa-star me-1"></i> Featured Stories
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Grid -->
    @if($womenRunning->count() > 0)
    <div class="row g-4" id="entriesGrid">
        @foreach($womenRunning as $item)
        <div class="col-xl-3 col-lg-4 col-md-6 entry-card" data-category="{{ $item->category }}">
            <div class="card h-100 shadow-sm border-0 hover-lift">
                <!-- Image -->
                <div class="position-relative overflow-hidden">
                    @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" 
                         class="card-img-top img-zoom" 
                         alt="{{ $item->name }}"
                         style="height: 220px; object-fit: cover;">
                    @else
                    <div class="card-placeholder d-flex align-items-center justify-content-center text-white" 
                         style="height: 220px;">
                        <i class="fas fa-image fa-3x opacity-50"></i>
                    </div>
                    @endif
                    
                    <!-- Status Badges -->
                    <div class="position-absolute top-0 start-0 m-2">
                        @if($item->featured)
                        <span class="badge badge-featured shadow-sm">
                            <i class="fas fa-star"></i> Featured
                        </span>
                        @endif
                    </div>
                    
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge badge-status-{{ $item->status ? 'active' : 'inactive' }} shadow-sm">
                            <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>
                            {{ $item->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Category Badge -->
                    <div class="position-absolute bottom-0 start-0 m-2 end-0">
                        <span class="badge badge-category shadow-sm">
                            @if($item->icon)
                            <i class="fas fa-{{ $item->icon }} me-1"></i>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body d-flex flex-column p-3">
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-sort-numeric-down me-1"></i> Order: <strong>{{ $item->order }}</strong>
                        </small>
                    </div>
                    <h5 class="card-title fw-bold mb-2 text-dark">{{ $item->name }}</h5>
                    <h6 class="card-subtitle text-primary mb-3" style="font-size: 0.9rem;">
                        {{ Str::limit($item->title, 50) }}
                    </h6>
                    <p class="card-text text-muted small flex-grow-1" style="line-height: 1.6;">
                        {{ Str::limit($item->description, 100) }}
                    </p>

                    <!-- Actions -->
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.women-running.show', $item) }}" 
                           class="btn btn-sm btn-info text-white flex-fill" 
                           title="View">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.women-running.edit', $item) }}" 
                           class="btn btn-sm btn-primary flex-fill" 
                           title="Edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" 
                                class="btn btn-sm btn-danger" 
                                onclick="deleteEntry({{ $item->id }})"
                                title="Delete">
                                Delete
                           <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>

                    <!-- Quick Actions -->
                    <div class="d-flex gap-2 mt-2">
                        <form action="{{ route('admin.women-running.toggle-status', $item) }}" 
                              method="POST" 
                              class="flex-fill">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-sm w-100 {{ $item->status ? 'btn-success' : 'btn-outline-secondary' }}">
                                <i class="fas fa-power-off me-1"></i> 
                                {{ $item->status ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.women-running.toggle-featured', $item) }}" 
                              method="POST" 
                              class="flex-fill">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-sm w-100 {{ $item->featured ? 'btn-warning' : 'btn-outline-warning' }}">
                                <i class="fas fa-star me-1"></i> 
                                {{ $item->featured ? 'Featured' : 'Feature' }}
                            </button>
                        </form>
                    </div>

                    <!-- Hidden Delete Form -->
                    <form id="delete-form-{{ $item->id }}" 
                          action="{{ route('admin.women-running.destroy', $item) }}" 
                          method="POST" 
                          class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="row mt-5">
        <div class="col-md-12 d-flex justify-content-center">
            {{ $womenRunning->links() }}
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-inbox fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-dark mb-3">No entries found</h4>
                    <p class="text-muted mb-4">Get started by adding your first women running entry.</p>
                    <a href="{{ route('admin.women-running.create') }}" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-plus me-2"></i> Add Your First Entry
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Header Gradient - Fixed visibility */
    .header-gradient {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
        border-radius: 15px;
    }
    
    .text-white-75 {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    /* Card Hover Effect */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,.15) !important;
        border-color: rgba(99, 102, 241, 0.2);
    }
    
    /* Image Zoom Effect */
    .img-zoom {
        transition: transform 0.5s ease;
    }
    
    .hover-lift:hover .img-zoom {
        transform: scale(1.1);
    }
    
    /* Card Placeholder */
    .card-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Navigation Pills */
    .nav-pills .nav-link {
        color: #6c757d;
        border-radius: 10px;
        padding: 0.6rem 1.2rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
        font-weight: 500;
        border: 2px solid transparent;
    }
    
    .nav-pills .nav-link:hover {
        background-color: #f8f9fa;
        color: #495057;
        border-color: #e9ecef;
    }
    
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white !important;
        border-color: transparent;
    }
    
    .nav-pills .nav-link.active .badge {
        background-color: rgba(255, 255, 255, 0.3) !important;
        color: white !important;
    }
    
    /* Card Styling */
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-img-top {
        border-radius: 0;
    }
    
    /* Custom Badges */
    .badge {
        border-radius: 20px;
        padding: 0.5rem 0.9rem;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.3px;
    }
    
    .badge-featured {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #78350f;
    }
    
    .badge-status-active {
        background-color: #10b981;
        color: white;
    }
    
    .badge-status-inactive {
        background-color: #6b7280;
        color: white;
    }
    
    .badge-category {
        background-color: rgba(17, 24, 39, 0.8);
        backdrop-filter: blur(10px);
        color: white;
    }
    
    /* Button Improvements */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .btn-info {
        background-color: #06b6d4;
        border-color: #06b6d4;
    }
    
    .btn-info:hover {
        background-color: #0891b2;
        border-color: #0891b2;
    }
    
    /* Empty State */
    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 50%;
    }
    
    /* Alert Animation */
    @keyframes slideInDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .alert {
        animation: slideInDown 0.4s ease-out;
        border: none;
        border-radius: 10px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .header-gradient .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .nav-pills {
            flex-wrap: wrap;
        }
        
        .nav-pills .nav-link {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
    }
    
    /* Pagination Styling */
    .pagination {
        gap: 0.5rem;
    }
    
    .pagination .page-link {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        color: #6366f1;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }
    
    .pagination .page-link:hover {
        background-color: #f3f4f6;
        border-color: #6366f1;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-color: #6366f1;
    }
</style>
@endpush

@push('scripts')
<script>
    // Category Filter with Animation
    document.querySelectorAll('#categoryFilter .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            document.querySelectorAll('#categoryFilter .nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            const cards = document.querySelectorAll('.entry-card');
            
            // Fade out all cards first
            cards.forEach(card => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
            });
            
            // Wait for fade out, then filter and fade in
            setTimeout(() => {
                cards.forEach(card => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        card.style.display = 'none';
                    }
                });
            }, 300);
        });
    });

    // Delete with confirmation (improved)
    function deleteEntry(id) {
        if (confirm('⚠️ Are you sure you want to delete this entry?\n\nThis action cannot be undone.')) {
            const form = document.getElementById('delete-form-' + id);
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Show loading state
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                submitBtn.disabled = true;
            }
            
            form.submit();
        }
    }

    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 5000);
        });
    });

    // Add loading state to toggle buttons
    document.querySelectorAll('form[action*="toggle"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        });
    });
</script>
@endpush
@endsection
@extends('layouts.admin')

@section('title', 'Videos Management')
@section('page-title', 'All Videos')

@section('content')
<div class="videos-container">
    <!-- Header with Actions -->
    <div class="videos-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">Videos Management</h1>
                @if(isset($categoryName))
                    <div class="category-badge">
                        Category: {{ $categoryName }}
                    </div>
                @endif
                @if(isset($featuredView) && $featuredView)
                    <div class="featured-badge">
                        <i class="fas fa-star"></i>
                        Featured Videos
                    </div>
                @endif
                @if(isset($searchTerm))
                    <div class="search-badge">
                        Search: "{{ $searchTerm }}"
                    </div>
                @endif
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.videos.create') }}" class="add-video-btn">
                    <i class="fas fa-plus"></i>
                    Add New Video
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="filters-bar">
        <div class="filters-content">
            <div class="filter-group">
                <label>Filter by:</label>
                <div class="filter-buttons">
                    <a href="{{ route('admin.videos.index') }}" class="filter-btn {{ !isset($categoryName) && !isset($featuredView) ? 'active' : '' }}">
                        All Videos
                    </a>
                    <a href="{{ route('admin.videos.featured') }}" class="filter-btn {{ isset($featuredView) && $featuredView ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        Featured
                    </a>
                </div>
            </div>

            <div class="filter-group">
                <label>Categories:</label>
                <div class="category-buttons">
                    @foreach(App\Models\Video::getCategories() as $key => $category)
                        <a href="{{ route('admin.videos.byCategory', $key) }}" class="category-btn {{ isset($categoryName) && $categoryName === $category ? 'active' : '' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search Form -->
            <div class="search-group">
                <form action="{{ route('admin.videos.search') }}" method="POST" class="search-form">
                    @csrf
                    <div class="search-input-group">
                        <input type="text" name="search" placeholder="Search videos..." class="search-input" value="{{ $searchTerm ?? '' }}">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Videos Grid -->
    <div class="videos-grid">
        @forelse($videos as $video)
            <div class="video-card">
                <!-- Video Thumbnail -->
                <div class="video-thumbnail">
                    <img src="{{ $video->getThumbnailUrl() }}" alt="{{ $video->title }}" class="thumbnail-img">
                    <div class="video-overlay">
                        <a href="{{ $video->youtube_url }}" target="_blank" class="play-btn" title="Watch on YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                    @if($video->is_featured)
                        <div class="featured-indicator">
                            <i class="fas fa-star"></i>
                            Featured
                        </div>
                    @endif
                </div>

                <!-- Video Details -->
                <div class="video-details">
                    <h3 class="video-title">{{ Str::limit($video->title, 60) }}</h3>

                    <div class="video-category">
                        <span class="category-tag {{ $video->category }}">
                            {{ $video->getCategoryDisplayName() }}
                        </span>
                    </div>

                    @if($video->description)
                        <p class="video-description">
                            {{ Str::limit($video->description, 100) }}
                        </p>
                    @endif

                    <div class="video-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Added by: {{ $video->addedBy->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $video->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($video->isRecent())
                            <div class="meta-item new-indicator">
                                <i class="fas fa-certificate"></i>
                                <span>New</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="video-actions">
                    <a href="{{ route('admin.videos.show', $video) }}" class="btn-action view" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.videos.edit', $video) }}" class="btn-action edit" title="Edit Video">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.videos.toggleFeatured', $video) }}" method="POST" class="action-form">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-action {{ $video->is_featured ? 'unfeature' : 'feature' }}" title="{{ $video->is_featured ? 'Remove Featured' : 'Make Featured' }}">
                            <i class="fas fa-star"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="action-form delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action delete" title="Delete Video" onclick="return confirm('Are you sure you want to delete this video?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="no-videos">
                <i class="fas fa-video"></i>
                <h3>No Videos Found</h3>
                <p>
                    @if(isset($categoryName))
                        No videos found in the {{ $categoryName }} category.
                    @elseif(isset($featuredView) && $featuredView)
                        No featured videos found.
                    @elseif(isset($searchTerm))
                        No videos found matching "{{ $searchTerm }}".
                    @else
                        Get started by adding your first video.
                    @endif
                </p>
                <a href="{{ route('admin.videos.create') }}" class="add-video-btn">
                    <i class="fas fa-plus"></i>
                    Add New Video
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($videos->hasPages())
        <div class="pagination-container">
            {{ $videos->links() }}
        </div>
    @endif
</div>

<style>
/* Base Styles */
.videos-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.videos-header {
    margin-bottom: 20px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 15px;
}

.header-left {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.category-badge, .featured-badge, .search-badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
}

.category-badge {
    background-color: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}

.featured-badge {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
    display: flex;
    align-items: center;
    gap: 6px;
}

.search-badge {
    background-color: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.add-video-btn {
    background-color: #28a745;
    color: white;
    padding: 10px 16px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: background-color 0.2s;
}

.add-video-btn:hover {
    background-color: #218838;
}

/* Filters Bar */
.filters-bar {
    background-color: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 20px;
    margin-bottom: 30px;
}

.filters-content {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-group label {
    font-weight: 500;
    color: #333;
    min-width: 80px;
}

.filter-buttons, .category-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-btn, .category-btn {
    padding: 8px 16px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    text-decoration: none;
    color: #6c757d;
    background-color: white;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.filter-btn:hover, .category-btn:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.filter-btn.active, .category-btn.active {
    background-color: #3498db;
    color: white;
    border-color: #3498db;
}

/* Search Form */
.search-group {
    margin-left: auto;
}

.search-form {
    display: flex;
}

.search-input-group {
    display: flex;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}

.search-input {
    padding: 8px 12px;
    border: none;
    outline: none;
    width: 250px;
    font-size: 14px;
}

.search-btn {
    background-color: #f8f9fa;
    border: none;
    padding: 8px 12px;
    color: #6c757d;
    cursor: pointer;
    transition: background-color 0.2s;
}

.search-btn:hover {
    background-color: #e9ecef;
}

/* Alert Styles */
.alert {
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Videos Grid */
.videos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* Video Card Styles */
.video-card {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.video-thumbnail {
    position: relative;
    height: 180px;
    overflow: hidden;
    background-color: #f8f9fa;
}

.thumbnail-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
}

.video-card:hover .video-overlay {
    opacity: 1;
}

.play-btn {
    width: 50px;
    height: 50px;
    background-color: #ff0000;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 20px;
}

.featured-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #ffc107;
    color: #212529;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Video Details */
.video-details {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.video-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
    line-height: 1.4;
}

.video-category {
    margin: 5px 0;
}

.category-tag {
    display: inline-block;
    padding: 4px 8px;
    background-color: #e3f2fd;
    color: #1976d2;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.video-description {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
}

.video-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-top: auto;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 12px;
}

.meta-item i {
    width: 12px;
    text-align: center;
}

.new-indicator {
    color: #28a745;
    font-weight: 500;
}

/* Action Buttons */
.video-actions {
    display: flex;
    border-top: 1px solid #e0e0e0;
    background-color: #f8f9fa;
}

.btn-action {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    color: #6c757d;
    text-decoration: none;
    transition: background-color 0.2s;
    border: none;
    background: none;
    cursor: pointer;
}

.btn-action:hover {
    background-color: #e9ecef;
}

.btn-action.view:hover {
    color: #17a2b8;
}

.btn-action.edit:hover {
    color: #ffc107;
}

.btn-action.feature:hover {
    color: #ffc107;
}

.btn-action.unfeature:hover {
    color: #6c757d;
}

.btn-action.delete:hover {
    color: #dc3545;
}

.action-form {
    flex: 1;
    margin: 0;
}

/* No Videos State */
.no-videos {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.no-videos i {
    font-size: 64px;
    margin-bottom: 20px;
    color: #dee2e6;
}

.no-videos h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #495057;
}

.no-videos p {
    margin-bottom: 20px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Pagination Styles */
.pagination-container {
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 5px;
}

.pagination li {
    display: inline-block;
}

.pagination li a,
.pagination li span {
    display: inline-block;
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
}

.pagination li.active span {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination li.disabled span {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .videos-container {
        padding: 15px;
    }

    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .filters-content {
        gap: 20px;
    }

    .filter-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .filter-group label {
        min-width: auto;
    }

    .search-group {
        margin-left: 0;
        width: 100%;
    }

    .search-input {
        width: 100%;
    }

    .videos-grid {
        grid-template-columns: 1fr;
    }

    .video-actions {
        flex-wrap: wrap;
    }

    .btn-action {
        flex: 0 0 25%;
    }
}
</style>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@extends('layouts.admin')

@section('title', $video->title . ' - Video Details')
@section('page-title', 'Video Details')

@section('content')
<div class="videos-show-container">
    <!-- Header with Actions -->
    <div class="show-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('admin.videos.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Videos
                </a>
                <h1 class="page-title">{{ $video->title }}</h1>
                @if($video->is_featured)
                    <div class="featured-badge">
                        <i class="fas fa-star"></i>
                        Featured Video
                    </div>
                @endif
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.videos.edit', $video) }}" class="action-btn edit">
                    <i class="fas fa-edit"></i>
                    Edit Video
                </a>
                <form action="{{ route('admin.videos.toggleFeatured', $video) }}" method="POST" class="action-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="action-btn {{ $video->is_featured ? 'unfeature' : 'feature' }}">
                        <i class="fas fa-star"></i>
                        {{ $video->is_featured ? 'Unfeature' : 'Feature' }}
                    </button>
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

    <!-- Video Details -->
    <div class="video-details-grid">
        <!-- Video Player Card -->
        <div class="detail-card video-player-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-play-circle"></i>
                    Video Player
                </h3>
            </div>
            <div class="card-body">
                <div class="video-embed-container">
                    @if($video->getEmbedUrl())
                        <iframe
                            src="{{ $video->getEmbedUrl() }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            class="video-iframe"
                        ></iframe>
                    @else
                        <div class="video-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h4>Unable to load video</h4>
                            <p>The YouTube URL appears to be invalid or unavailable.</p>
                            <a href="{{ $video->youtube_url }}" target="_blank" class="external-link">
                                <i class="fas fa-external-link-alt"></i>
                                Open on YouTube
                            </a>
                        </div>
                    @endif
                </div>

                <div class="video-actions-row">
                    <a href="{{ $video->youtube_url }}" target="_blank" class="youtube-link">
                        <i class="fab fa-youtube"></i>
                        Watch on YouTube
                    </a>
                    <div class="video-stats">
                        <span class="stat-item">
                            <i class="fas fa-eye"></i>
                            Embedded View
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Information Card -->
        <div class="detail-card info-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Video Information
                </h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">Title</label>
                        <div class="info-value">{{ $video->title }}</div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Category</label>
                        <div class="info-value">
                            <span class="category-tag {{ $video->category }}">
                                {{ $video->getCategoryDisplayName() }}
                            </span>
                        </div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Featured Status</label>
                        <div class="info-value">
                            @if($video->is_featured)
                                <span class="status-badge featured">
                                    <i class="fas fa-star"></i>
                                    Featured
                                </span>
                            @else
                                <span class="status-badge regular">
                                    <i class="fas fa-circle"></i>
                                    Regular
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($video->description)
                    <div class="info-item full-width">
                        <label class="info-label">Description</label>
                        <div class="info-value description-text">
                            {{ $video->description }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- YouTube Details Card -->
        <div class="detail-card youtube-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fab fa-youtube"></i>
                    YouTube Details
                </h3>
            </div>
            <div class="card-body">
                <div class="youtube-info">
                    <div class="youtube-thumbnail">
                        <img src="{{ $video->getThumbnailUrl() }}" alt="Video thumbnail" class="thumbnail">
                        <div class="thumbnail-overlay">
                            <i class="fab fa-youtube"></i>
                        </div>
                    </div>
                    <div class="youtube-details">
                        <div class="detail-item">
                            <label class="detail-label">YouTube URL</label>
                            <div class="detail-value">
                                <a href="{{ $video->youtube_url }}" target="_blank" class="url-link">
                                    {{ $video->youtube_url }}
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Video ID</label>
                            <div class="detail-value code">{{ $video->getYoutubeId() }}</div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Embed URL</label>
                            <div class="detail-value">
                                <a href="{{ $video->getEmbedUrl() }}" target="_blank" class="url-link">
                                    {{ $video->getEmbedUrl() }}
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metadata Card -->
        <div class="detail-card meta-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database"></i>
                    Metadata
                </h3>
            </div>
            <div class="card-body">
                <div class="meta-grid">
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="meta-content">
                            <label class="meta-label">Added By</label>
                            <div class="meta-value">{{ $video->addedBy->name ?? 'Unknown' }}</div>
                            <div class="meta-email">{{ $video->addedBy->email ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="meta-content">
                            <label class="meta-label">Created</label>
                            <div class="meta-value">{{ $video->created_at->format('F j, Y') }}</div>
                            <div class="meta-time">{{ $video->created_at->format('g:i A') }}</div>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="meta-content">
                            <label class="meta-label">Last Updated</label>
                            <div class="meta-value">{{ $video->updated_at->format('F j, Y') }}</div>
                            <div class="meta-time">{{ $video->updated_at->format('g:i A') }}</div>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="meta-content">
                            <label class="meta-label">Age</label>
                            <div class="meta-value">{{ $video->created_at->diffForHumans() }}</div>
                            <div class="meta-time">{{ $video->created_at->diffInDays(now()) }} days ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="detail-card actions-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="actions-grid">
                    <a href="{{ route('admin.videos.edit', $video) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Edit Video</div>
                            <div class="action-desc">Update title, description, or category</div>
                        </div>
                    </a>

                    @if($video->is_featured)
                        <form action="{{ route('admin.videos.toggleFeatured', $video) }}" method="POST" class="quick-action-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="quick-action">
                                <div class="action-icon remove-featured">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Remove Featured</div>
                                    <div class="action-desc">Make this a regular video</div>
                                </div>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.videos.toggleFeatured', $video) }}" method="POST" class="quick-action-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="quick-action">
                                <div class="action-icon add-featured">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Make Featured</div>
                                    <div class="action-desc">Highlight this video</div>
                                </div>
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.videos.byCategory', $video->category) }}" class="quick-action">
                        <div class="action-icon category">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">View Category</div>
                            <div class="action-desc">See all {{ $video->getCategoryDisplayName() }} videos</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.videos.create') }}" class="quick-action">
                        <div class="action-icon create">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Add New Video</div>
                            <div class="action-desc">Create another video</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="danger-zone">
        <div class="danger-header">
            <h3 class="danger-title">
                <i class="fas fa-exclamation-triangle"></i>
                Danger Zone
            </h3>
        </div>
        <div class="danger-content">
            <div class="danger-warning">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="warning-content">
                    <h4>Delete this video</h4>
                    <p>Once you delete a video, there is no going back. Please be certain.</p>
                </div>
            </div>
            <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="danger-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="danger-btn" onclick="return confirm('Are you absolutely sure you want to delete this video? This action cannot be undone.')">
                    <i class="fas fa-trash"></i>
                    Delete this Video
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Base Styles */
.videos-show-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.show-header {
    margin-bottom: 30px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
}

.header-left {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    text-decoration: none;
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    transition: all 0.2s;
}

.back-btn:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin: 0;
    max-width: 500px;
    line-height: 1.3;
}

.featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background-color: #fff3cd;
    color: #856404;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
}

.header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.action-btn.edit {
    background-color: #ffc107;
    color: #212529;
    border: 1px solid #ffc107;
}

.action-btn.edit:hover {
    background-color: #e0a800;
    border-color: #e0a800;
}

.action-btn.feature {
    background-color: #17a2b8;
    color: white;
    border: 1px solid #17a2b8;
}

.action-btn.feature:hover {
    background-color: #138496;
    border-color: #138496;
}

.action-btn.unfeature {
    background-color: #6c757d;
    color: white;
    border: 1px solid #6c757d;
}

.action-btn.unfeature:hover {
    background-color: #5a6268;
    border-color: #5a6268;
}

.action-form {
    margin: 0;
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

/* Details Grid */
.video-details-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    grid-template-rows: auto auto;
    gap: 20px;
    margin-bottom: 30px;
}

@media (max-width: 1024px) {
    .video-details-grid {
        grid-template-columns: 1fr;
    }
}

/* Video Player Card */
.video-player-card {
    grid-column: 1;
    grid-row: 1;
}

.video-embed-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    height: 0;
    margin-bottom: 15px;
}

.video-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 4px;
}

.video-error {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.video-error i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #dc3545;
}

.video-error h4 {
    margin: 0 0 8px 0;
    color: #495057;
}

.video-error p {
    margin: 0 0 15px 0;
}

.external-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.external-link:hover {
    text-decoration: underline;
}

.video-actions-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.youtube-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #ff0000;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 16px;
    border: 1px solid #ff0000;
    border-radius: 4px;
    transition: all 0.2s;
}

.youtube-link:hover {
    background-color: #ff0000;
    color: white;
}

.video-stats {
    display: flex;
    gap: 15px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #6c757d;
    font-size: 14px;
}

/* Detail Card Styles */
.detail-card {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

.card-header {
    background-color: #f8f9fa;
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-body {
    padding: 20px;
}

/* Info Card Styles */
.info-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-weight: 500;
    color: #6c757d;
    font-size: 14px;
}

.info-value {
    color: #333;
    font-size: 16px;
    line-height: 1.4;
}

.description-text {
    white-space: pre-line;
    line-height: 1.6;
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

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.featured {
    background-color: #fff3cd;
    color: #856404;
}

.status-badge.regular {
    background-color: #e9ecef;
    color: #495057;
}

/* YouTube Card Styles */
.youtube-info {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.youtube-thumbnail {
    position: relative;
    width: 120px;
    height: 80px;
    background-color: #f8f9fa;
    border-radius: 4px;
    overflow: hidden;
    flex-shrink: 0;
}

.youtube-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumbnail-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.youtube-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.detail-label {
    font-weight: 500;
    color: #6c757d;
    font-size: 12px;
}

.detail-value {
    color: #333;
    font-size: 14px;
    word-break: break-all;
}

.detail-value.code {
    font-family: 'Courier New', monospace;
    background-color: #f8f9fa;
    padding: 4px 8px;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
}

.url-link {
    color: #3498db;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.url-link:hover {
    text-decoration: underline;
}

/* Meta Card Styles */
.meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 768px) {
    .meta-grid {
        grid-template-columns: 1fr;
    }
}

.meta-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.meta-icon {
    width: 32px;
    height: 32px;
    background-color: #f8f9fa;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    flex-shrink: 0;
}

.meta-content {
    flex: 1;
}

.meta-label {
    display: block;
    font-weight: 500;
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 2px;
}

.meta-value {
    color: #333;
    font-size: 14px;
    font-weight: 500;
}

.meta-email, .meta-time {
    color: #6c757d;
    font-size: 12px;
}

/* Actions Card Styles */
.actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

@media (max-width: 768px) {
    .actions-grid {
        grid-template-columns: 1fr;
    }
}

.quick-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}

.quick-action:hover {
    background-color: #f8f9fa;
    border-color: #3498db;
}

.quick-action-form {
    margin: 0;
}

.quick-action-form button {
    width: 100%;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
}

.action-icon {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.action-icon.edit {
    background-color: #fff3cd;
    color: #856404;
}

.action-icon.add-featured {
    background-color: #d4edda;
    color: #155724;
}

.action-icon.remove-featured {
    background-color: #f8d7da;
    color: #721c24;
}

.action-icon.category {
    background-color: #e3f2fd;
    color: #1976d2;
}

.action-icon.create {
    background-color: #d1ecf1;
    color: #0c5460;
}

.action-content {
    flex: 1;
}

.action-title {
    font-weight: 500;
    color: #333;
    margin-bottom: 2px;
}

.action-desc {
    font-size: 12px;
    color: #6c757d;
}

/* Danger Zone */
.danger-zone {
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    overflow: hidden;
}

.danger-header {
    background-color: #f8d7da;
    padding: 16px 20px;
    border-bottom: 1px solid #f5c6cb;
}

.danger-title {
    font-size: 16px;
    font-weight: 600;
    color: #721c24;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.danger-content {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.danger-warning {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    flex: 1;
}

.warning-icon {
    color: #dc3545;
    font-size: 20px;
    margin-top: 2px;
}

.warning-content h4 {
    margin: 0 0 4px 0;
    color: #721c24;
}

.warning-content p {
    margin: 0;
    color: #856404;
    font-size: 14px;
}

.danger-form {
    margin: 0;
}

.danger-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
}

.danger-btn:hover {
    background-color: #c82333;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .videos-show-container {
        padding: 15px;
    }

    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-left {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
    }

    .action-btn {
        flex: 1;
        justify-content: center;
    }

    .youtube-info {
        flex-direction: column;
        text-align: center;
    }

    .youtube-thumbnail {
        width: 100%;
        height: 120px;
    }

    .video-actions-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .danger-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .danger-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

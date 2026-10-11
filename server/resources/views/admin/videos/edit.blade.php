@extends('layouts.admin')

@section('title', 'Edit ' . $video->title)
@section('page-title', 'Edit Video')

@section('content')
<div class="videos-edit-container">
    <!-- Header with Navigation -->
    <div class="edit-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('admin.videos.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Videos
                </a>
                <h1 class="page-title">Edit Video</h1>
                <div class="video-status">
                    @if($video->is_featured)
                        <span class="status-badge featured">
                            <i class="fas fa-star"></i>
                            Featured
                        </span>
                    @endif
                    <span class="status-badge category">
                        {{ $video->getCategoryDisplayName() }}
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.videos.show', $video) }}" class="action-btn view">
                    <i class="fas fa-eye"></i>
                    View
                </a>
                <a href="{{ $video->youtube_url }}" target="_blank" class="action-btn youtube">
                    <i class="fab fa-youtube"></i>
                    YouTube
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            Please fix the errors below and try again.
        </div>
    @endif

    <!-- Edit Form -->
    <div class="edit-form-wrapper">
        <form action="{{ route('admin.videos.update', $video) }}" method="POST" class="video-edit-form">
            @csrf
            @method('PUT')

            <div class="form-layout">
                <!-- Main Form Content -->
                <div class="form-main">
                    <!-- YouTube URL Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fab fa-youtube"></i>
                                YouTube Video
                            </h3>
                        </div>
                        <div class="section-body">
                            <div class="form-group">
                                <label for="youtube_url" class="form-label required">YouTube URL</label>
                                <input
                                    type="url"
                                    id="youtube_url"
                                    name="youtube_url"
                                    value="{{ old('youtube_url', $video->youtube_url) }}"
                                    class="form-control large @error('youtube_url') error @enderror"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    required
                                >
                                @error('youtube_url')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-hint">
                                    Paste the full YouTube URL. Supported formats: youtube.com/watch?v=..., youtu.be/..., youtube.com/embed/...
                                </div>
                            </div>

                            <!-- URL Preview -->
                            <div class="url-preview">
                                <label class="preview-label">URL Preview:</label>
                                <div class="preview-value">
                                    <code id="urlPreview">{{ $video->youtube_url }}</code>
                                    <span class="url-status" id="urlStatus">
                                        <i class="fas fa-check-circle"></i>
                                        Valid YouTube URL
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Details Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Video Details
                            </h3>
                        </div>
                        <div class="section-body">
                            <div class="form-group">
                                <label for="title" class="form-label required">Video Title</label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title', $video->title) }}"
                                    class="form-control large @error('title') error @enderror"
                                    placeholder="Enter a descriptive title for the video"
                                    required
                                >
                                @error('title')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="char-count">
                                    <span id="titleCount">{{ strlen($video->title) }}</span>/255 characters
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    class="form-control @error('description') error @enderror"
                                    placeholder="Describe the video content, athletes featured, or key moments..."
                                >{{ old('description', $video->description) }}</textarea>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="char-count">
                                    <span id="descCount">{{ strlen($video->description ?? '') }}</span>/1000 characters
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div class="form-sidebar">
                    <!-- Category & Status -->
                    <div class="sidebar-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-tag"></i>
                                Category & Status
                            </h3>
                        </div>
                        <div class="section-body">
                            <div class="form-group">
                                <label for="category" class="form-label required">Sport Category</label>
                                <select
                                    id="category"
                                    name="category"
                                    class="form-control @error('category') error @enderror"
                                    required
                                >
                                    <option value="">Select a category</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}"
                                            {{ old('category', $video->category) == $key ? 'selected' : '' }}
                                            data-icon="{{ $key }}">
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="checkbox-group large">
                                    <input
                                        type="checkbox"
                                        id="is_featured"
                                        name="is_featured"
                                        value="1"
                                        {{ old('is_featured', $video->is_featured) ? 'checked' : '' }}
                                        class="checkbox-input"
                                    >
                                    <label for="is_featured" class="checkbox-label">
                                        <div class="checkbox-custom">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="checkbox-content">
                                            <div class="checkbox-title">
                                                <i class="fas fa-star"></i>
                                                Feature this video
                                            </div>
                                            <div class="checkbox-description">
                                                Featured videos appear prominently on the homepage
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Card -->
                    <div class="sidebar-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-eye"></i>
                                Preview
                            </h3>
                        </div>
                        <div class="section-body">
                            <div class="preview-card">
                                <div class="preview-thumbnail">
                                    <img src="{{ $video->getThumbnailUrl() }}" alt="Preview" id="previewThumbnail">
                                    <div class="preview-overlay">
                                        <i class="fab fa-youtube"></i>
                                    </div>
                                    <div class="preview-badges">
                                        <span class="preview-badge category" id="previewCategory">
                                            {{ $video->getCategoryDisplayName() }}
                                        </span>
                                        <span class="preview-badge featured" id="previewFeatured"
                                              style="display: {{ $video->is_featured ? 'flex' : 'none' }};">
                                            <i class="fas fa-star"></i>
                                            Featured
                                        </span>
                                    </div>
                                </div>
                                <div class="preview-content">
                                    <h4 class="preview-title" id="previewTitle">{{ $video->title }}</h4>
                                    <p class="preview-description" id="previewDescription">
                                        {{ $video->description ? Str::limit($video->description, 120) : 'No description' }}
                                    </p>
                                    <div class="preview-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            Updated: {{ $video->updated_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="sidebar-section">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-large">
                                <i class="fas fa-save"></i>
                                Update Video
                            </button>
                            <a href="{{ route('admin.videos.show', $video) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                            <button type="button" class="btn btn-outline" onclick="resetForm()">
                                <i class="fas fa-undo"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Video Information -->
    <div class="video-info-footer">
        <div class="info-grid">
            <div class="info-item">
                <label>Created</label>
                <div class="info-value">
                    {{ $video->created_at->format('F j, Y \\a\\t g:i A') }}
                    <small>by {{ $video->addedBy->name ?? 'System' }}</small>
                </div>
            </div>
            <div class="info-item">
                <label>Last Updated</label>
                <div class="info-value">
                    {{ $video->updated_at->format('F j, Y \\a\\t g:i A') }}
                    <small>{{ $video->updated_at->diffForHumans() }}</small>
                </div>
            </div>
            <div class="info-item">
                <label>Video ID</label>
                <div class="info-value code">{{ $video->getYoutubeId() }}</div>
            </div>
            <div class="info-item">
                <label>Duration</label>
                <div class="info-value">
                    <i class="fas fa-clock"></i>
                    Unknown duration
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Base Styles */
.videos-edit-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.edit-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.header-left {
    flex: 1;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    text-decoration: none;
    padding: 8px 16px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    margin-bottom: 15px;
    transition: all 0.2s;
}

.back-btn:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin: 0 0 15px 0;
}

.video-status {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-badge.featured {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-badge.category {
    background-color: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}

.header-actions {
    display: flex;
    gap: 10px;
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
}

.action-btn.view {
    background-color: #17a2b8;
    color: white;
    border: 1px solid #17a2b8;
}

.action-btn.view:hover {
    background-color: #138496;
}

.action-btn.youtube {
    background-color: #ff0000;
    color: white;
    border: 1px solid #ff0000;
}

.action-btn.youtube:hover {
    background-color: #cc0000;
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

/* Form Layout */
.edit-form-wrapper {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 30px;
}

.form-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 0;
}

@media (max-width: 1024px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
}

.form-main {
    padding: 30px;
    border-right: 1px solid #e0e0e0;
}

.form-sidebar {
    padding: 30px;
    background-color: #f8f9fa;
}

@media (max-width: 1024px) {
    .form-main {
        border-right: none;
        border-bottom: 1px solid #e0e0e0;
    }
}

/* Form Sections */
.form-section, .sidebar-section {
    margin-bottom: 30px;
}

.form-section:last-child, .sidebar-section:last-child {
    margin-bottom: 0;
}

.section-header {
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e0e0e0;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-body {
    /* Content styles */
}

/* Form Controls */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.2s;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #3498db;
}

.form-control.error {
    border-color: #dc3545;
}

.form-control.large {
    padding: 14px;
    font-size: 16px;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 16px;
    padding-right: 40px;
}

.form-hint {
    font-size: 12px;
    color: #6c757d;
    margin-top: 6px;
    line-height: 1.4;
}

.char-count {
    text-align: right;
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
}

/* URL Preview */
.url-preview {
    background-color: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 15px;
    margin-top: 15px;
}

.preview-label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
}

.preview-value {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.preview-value code {
    background-color: white;
    padding: 4px 8px;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    color: #333;
    flex: 1;
    word-break: break-all;
}

.url-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #28a745;
    font-weight: 500;
}

/* Checkbox Styles */
.checkbox-group.large {
    padding: 15px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
}

.checkbox-input {
    display: none;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    margin: 0;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    background: white;
    margin-top: 2px;
    flex-shrink: 0;
}

.checkbox-custom i {
    color: white;
    font-size: 12px;
    opacity: 0;
    transition: opacity 0.2s;
}

.checkbox-input:checked + .checkbox-label .checkbox-custom {
    background-color: #3498db;
    border-color: #3498db;
}

.checkbox-input:checked + .checkbox-label .checkbox-custom i {
    opacity: 1;
}

.checkbox-content {
    flex: 1;
}

.checkbox-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.checkbox-description {
    font-size: 12px;
    color: #6c757d;
    line-height: 1.4;
}

/* Preview Card */
.preview-card {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

.preview-thumbnail {
    position: relative;
    height: 120px;
    background-color: #f8f9fa;
    overflow: hidden;
}

.preview-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-overlay {
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
    font-size: 24px;
}

.preview-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.preview-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

.preview-badge.category {
    background-color: rgba(255, 255, 255, 0.9);
    color: #1976d2;
}

.preview-badge.featured {
    background-color: rgba(255, 193, 7, 0.9);
    color: #856404;
}

.preview-content {
    padding: 15px;
}

.preview-title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.preview-description {
    font-size: 12px;
    color: #6c757d;
    line-height: 1.4;
    margin: 0 0 10px 0;
}

.preview-meta {
    border-top: 1px solid #e0e0e0;
    padding-top: 10px;
}

.meta-item {
    font-size: 11px;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Form Actions */
.form-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
    font-size: 14px;
    text-align: center;
}

.btn-primary {
    background-color: #28a745;
    color: white;
    border: 1px solid #28a745;
}

.btn-primary:hover {
    background-color: #218838;
}

.btn-secondary {
    background-color: white;
    color: #6c757d;
    border: 1px solid #e0e0e0;
}

.btn-secondary:hover {
    background-color: #f8f9fa;
}

.btn-outline {
    background-color: transparent;
    color: #6c757d;
    border: 1px solid #e0e0e0;
}

.btn-outline:hover {
    background-color: #f8f9fa;
}

.btn-large {
    padding: 14px 20px;
    font-size: 16px;
}

/* Error Message */
.error-message {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

/* Video Info Footer */
.video-info-footer {
    background-color: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item label {
    display: block;
    font-weight: 600;
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    color: #333;
    font-size: 14px;
    font-weight: 500;
}

.info-value small {
    display: block;
    color: #6c757d;
    font-weight: normal;
    font-size: 12px;
    margin-top: 2px;
}

.info-value.code {
    font-family: 'Courier New', monospace;
    background-color: white;
    padding: 4px 8px;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
    font-size: 12px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .videos-edit-container {
        padding: 15px;
    }

    .header-content {
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

    .form-main, .form-sidebar {
        padding: 20px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.video-edit-form');
    const youtubeUrlInput = document.getElementById('youtube_url');
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const categorySelect = document.getElementById('category');
    const featuredCheckbox = document.getElementById('is_featured');

    const urlPreview = document.getElementById('urlPreview');
    const urlStatus = document.getElementById('urlStatus');
    const titleCount = document.getElementById('titleCount');
    const descCount = document.getElementById('descCount');

    const previewThumbnail = document.getElementById('previewThumbnail');
    const previewTitle = document.getElementById('previewTitle');
    const previewDescription = document.getElementById('previewDescription');
    const previewCategory = document.getElementById('previewCategory');
    const previewFeatured = document.getElementById('previewFeatured');

    // Store original values
    const originalValues = {
        youtube_url: "{{ old('youtube_url', $video->youtube_url) }}",
        title: "{{ old('title', $video->title) }}",
        description: "{{ old('description', $video->description) }}",
        category: "{{ old('category', $video->category) }}",
        is_featured: {{ old('is_featured', $video->is_featured) ? 'true' : 'false' }}
    };

    // Character counters
    function updateCounters() {
        titleCount.textContent = titleInput.value.length;
        descCount.textContent = descriptionInput.value.length;
    }

    // YouTube URL validation
    function validateYouTubeUrl(url) {
        const patterns = [
            /^https?:\/\/(?:www\.)?youtube\.com\/watch\?v=([^&]+)/,
            /^https?:\/\/(?:www\.)?youtu\.be\/([^?]+)/,
            /^https?:\/\/(?:www\.)?youtube\.com\/embed\/([^\/]+)/
        ];

        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) {
                return { valid: true, videoId: match[1] };
            }
        }
        return { valid: false, videoId: null };
    }

    // Update preview
    function updatePreview() {
        // Update URL preview
        urlPreview.textContent = youtubeUrlInput.value;
        const urlValidation = validateYouTubeUrl(youtubeUrlInput.value);

        if (youtubeUrlInput.value && urlValidation.valid) {
            urlStatus.innerHTML = '<i class="fas fa-check-circle"></i> Valid YouTube URL';
            urlStatus.style.color = '#28a745';

            // Update thumbnail if URL changed
            if (youtubeUrlInput.value !== originalValues.youtube_url) {
                previewThumbnail.src = `https://img.youtube.com/vi/${urlValidation.videoId}/hqdefault.jpg`;
            }
        } else if (youtubeUrlInput.value) {
            urlStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> Invalid YouTube URL';
            urlStatus.style.color = '#dc3545';
        } else {
            urlStatus.innerHTML = '<i class="fas fa-info-circle"></i> Enter YouTube URL';
            urlStatus.style.color = '#6c757d';
        }

        // Update other preview elements
        previewTitle.textContent = titleInput.value || 'Untitled Video';
        previewDescription.textContent = descriptionInput.value ?
            (descriptionInput.value.length > 120 ? descriptionInput.value.substring(0, 120) + '...' : descriptionInput.value) :
            'No description';

        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        previewCategory.textContent = selectedOption ? selectedOption.text : 'Uncategorized';

        // Update featured status
        previewFeatured.style.display = featuredCheckbox.checked ? 'flex' : 'none';
    }

    // Event listeners
    youtubeUrlInput.addEventListener('input', updatePreview);
    titleInput.addEventListener('input', function() {
        updateCounters();
        updatePreview();
    });
    descriptionInput.addEventListener('input', function() {
        updateCounters();
        updatePreview();
    });
    categorySelect.addEventListener('change', updatePreview);
    featuredCheckbox.addEventListener('change', updatePreview);

    // Form validation
    youtubeUrlInput.addEventListener('blur', function() {
        const validation = validateYouTubeUrl(this.value);
        if (this.value && !validation.valid) {
            this.classList.add('error');
        } else {
            this.classList.remove('error');
        }
    });

    // Reset form
    window.resetForm = function() {
        if (confirm('Are you sure you want to reset all changes? Any unsaved changes will be lost.')) {
            youtubeUrlInput.value = originalValues.youtube_url;
            titleInput.value = originalValues.title;
            descriptionInput.value = originalValues.description;
            categorySelect.value = originalValues.category;
            featuredCheckbox.checked = originalValues.is_featured === 'true';

            // Clear errors
            document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
            document.querySelectorAll('.error-message').forEach(el => el.remove());

            updateCounters();
            updatePreview();
        }
    };

    // Initialize
    updateCounters();
    updatePreview();
});
</script>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@extends('layouts.admin')

@section('title', 'Add New Video')
@section('page-title', 'Add New Video')

@section('content')
<div class="videos-create-container">
    <!-- Header with Back Button -->
    <div class="create-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('admin.videos.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Videos
                </a>
                <h1 class="page-title">Add New Video</h1>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="create-form-container">
        <form action="{{ route('admin.videos.store') }}" method="POST" class="video-form">
            @csrf

            <div class="form-grid">
                <!-- Basic Information Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            Video Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- YouTube URL -->
                        <div class="form-group">
                            <label for="youtube_url" class="form-label">
                                YouTube URL <span class="required">*</span>
                            </label>
                            <input
                                type="url"
                                id="youtube_url"
                                name="youtube_url"
                                value="{{ old('youtube_url') }}"
                                class="form-control @error('youtube_url') error @enderror"
                                placeholder="https://www.youtube.com/watch?v=..."
                                required
                            >
                            @error('youtube_url')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="help-text">
                                Enter the full YouTube URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID)
                            </div>
                        </div>

                        <!-- Video Title -->
                        <div class="form-group">
                            <label for="title" class="form-label">
                                Video Title <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                class="form-control @error('title') error @enderror"
                                placeholder="Enter video title"
                                required
                            >
                            @error('title')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Video Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                Description
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="form-control @error('description') error @enderror"
                                placeholder="Enter video description (optional)"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="help-text">
                                Brief description of the video content (max 500 characters)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category & Settings Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog"></i>
                            Category & Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Category Selection -->
                        <div class="form-group">
                            <label for="category" class="form-label">
                                Category <span class="required">*</span>
                            </label>
                            <select
                                id="category"
                                name="category"
                                class="form-control @error('category') error @enderror"
                                required
                            >
                                <option value="">Select a category</option>
                                @foreach($categories as $key => $category)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
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

                        <!-- Featured Video Toggle -->
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input
                                    type="checkbox"
                                    id="is_featured"
                                    name="is_featured"
                                    value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}
                                    class="checkbox-input"
                                >
                                <label for="is_featured" class="checkbox-label">
                                    <div class="checkbox-custom">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="checkbox-text">
                                        <i class="fas fa-star"></i>
                                        Feature this video
                                    </span>
                                </label>
                            </div>
                            <div class="help-text">
                                Featured videos will be highlighted on the main page
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="preview-section">
                            <h4 class="section-title">
                                <i class="fas fa-eye"></i>
                                Live Preview
                            </h4>
                            <div class="preview-content">
                                <div class="preview-placeholder" id="previewPlaceholder">
                                    <i class="fab fa-youtube"></i>
                                    <p>Preview will appear here after entering a valid YouTube URL</p>
                                </div>
                                <div class="preview-video" id="previewVideo" style="display: none;">
                                    <div class="preview-thumbnail">
                                        <img id="previewThumbnail" src="" alt="Video thumbnail">
                                        <div class="preview-overlay">
                                            <i class="fab fa-youtube"></i>
                                        </div>
                                    </div>
                                    <div class="preview-details">
                                        <h5 id="previewTitle">Video Title</h5>
                                        <div class="preview-meta">
                                            <span id="previewCategory">Category</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.videos.index') }}" class="cancel-btn">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-plus"></i>
                    Create Video
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Base Styles */
.videos-create-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.create-header {
    margin-bottom: 30px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
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
}

/* Form Container */
.create-form-container {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    padding: 20px;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Form Card Styles */
.form-card {
    background-color: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

.card-header {
    background-color: #fff;
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

/* Form Group Styles */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 500;
    color: #333;
    margin-bottom: 6px;
}

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
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

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 16px;
    padding-right: 32px;
}

.help-text {
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
}

/* Checkbox Styles */
.checkbox-group {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.checkbox-input {
    display: none;
}

.checkbox-label {
    display: flex;
    align-items: center;
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

.checkbox-text {
    font-weight: 500;
    color: #333;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Preview Section */
.preview-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.section-title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.preview-content {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

.preview-placeholder {
    padding: 40px 20px;
    text-align: center;
    color: #6c757d;
}

.preview-placeholder i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #dee2e6;
}

.preview-placeholder p {
    margin: 0;
    font-size: 14px;
}

.preview-video {
    display: flex;
    gap: 15px;
    padding: 15px;
}

.preview-thumbnail {
    position: relative;
    width: 120px;
    height: 80px;
    background-color: #f8f9fa;
    border-radius: 4px;
    overflow: hidden;
    flex-shrink: 0;
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

.preview-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.preview-details h5 {
    margin: 0 0 8px 0;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    line-height: 1.4;
}

.preview-meta {
    display: flex;
    gap: 8px;
}

.preview-meta span {
    padding: 2px 8px;
    background-color: #e3f2fd;
    color: #1976d2;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
}

/* Error Message Styles */
.error-message {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

/* Form Actions */
.form-actions {
    background-color: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    padding: 20px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.cancel-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    color: #6c757d;
    text-decoration: none;
    background-color: white;
    transition: all 0.2s;
}

.cancel-btn:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.submit-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    background-color: #28a745;
    color: white;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.2s;
}

.submit-btn:hover {
    background-color: #218838;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .videos-create-container {
        padding: 15px;
    }

    .header-left {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .form-grid {
        padding: 15px;
    }

    .preview-video {
        flex-direction: column;
        text-align: center;
    }

    .preview-thumbnail {
        width: 100%;
        height: 120px;
    }

    .form-actions {
        flex-direction: column;
    }

    .cancel-btn, .submit-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const youtubeUrlInput = document.getElementById('youtube_url');
    const titleInput = document.getElementById('title');
    const categorySelect = document.getElementById('category');
    const previewPlaceholder = document.getElementById('previewPlaceholder');
    const previewVideo = document.getElementById('previewVideo');
    const previewThumbnail = document.getElementById('previewThumbnail');
    const previewTitle = document.getElementById('previewTitle');
    const previewCategory = document.getElementById('previewCategory');

    // Function to extract YouTube video ID
    function getYouTubeId(url) {
        const patterns = [
            /^https?:\/\/(?:www\.)?youtube\.com\/watch\?v=([^&]+)/,
            /^https?:\/\/(?:www\.)?youtu\.be\/([^?]+)/,
            /^https?:\/\/(?:www\.)?youtube\.com\/embed\/([^\/]+)/
        ];

        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) {
                return match[1];
            }
        }
        return null;
    }

    // Function to update preview
    function updatePreview() {
        const youtubeUrl = youtubeUrlInput.value;
        const videoId = getYouTubeId(youtubeUrl);

        if (videoId) {
            const thumbnailUrl = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;
            const title = titleInput.value || 'Untitled Video';
            const category = categorySelect.options[categorySelect.selectedIndex]?.text || 'Uncategorized';

            previewThumbnail.src = thumbnailUrl;
            previewTitle.textContent = title;
            previewCategory.textContent = category;

            previewPlaceholder.style.display = 'none';
            previewVideo.style.display = 'flex';
        } else {
            previewPlaceholder.style.display = 'block';
            previewVideo.style.display = 'none';
        }
    }

    // Event listeners for real-time preview
    youtubeUrlInput.addEventListener('input', updatePreview);
    titleInput.addEventListener('input', updatePreview);
    categorySelect.addEventListener('change', updatePreview);

    // Initial preview update
    updatePreview();

    // YouTube URL validation
    youtubeUrlInput.addEventListener('blur', function() {
        const url = this.value;
        if (url && !getYouTubeId(url)) {
            this.classList.add('error');
            this.setCustomValidity('Please enter a valid YouTube URL');
        } else {
            this.classList.remove('error');
            this.setCustomValidity('');
        }
    });
});
</script>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

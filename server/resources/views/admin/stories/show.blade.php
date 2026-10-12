@extends('layouts.admin')

@section('title', 'View Story')
@section('page-title', $story->title)

@section('content')
    <!-- Action Buttons Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.stories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Stories
            </a>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stories.edit', $story) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit Story
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash me-2"></i>Delete
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Story Content -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <!-- Status & Featured Badges -->
                    <div class="mb-3">
                        @if ($story->status === 'published')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Published
                            </span>
                        @elseif($story->status === 'draft')
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-pencil-square me-1"></i>Draft
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6">
                                <i class="bi bi-archive me-1"></i>Archived
                            </span>
                        @endif

                        @if ($story->is_featured)
                            <span class="badge bg-warning text-dark fs-6 ms-2">
                                <i class="bi bi-star-fill me-1"></i>Featured
                            </span>
                        @endif

                        <span class="badge bg-primary fs-6 ms-2">{{ $story->category }}</span>
                    </div>

                    <!-- Title -->
                    <h1 class="display-5 fw-bold mb-4">{{ $story->title }}</h1>

                    <!-- Meta Info -->
                    <div class="d-flex align-items-center text-muted mb-4 flex-wrap gap-3">
                        <span>
                            <i class="bi bi-person-circle me-1"></i>{{ $story->author_name }}
                        </span>
                        <span>
                            <i class="bi bi-calendar3 me-1"></i>{{ $story->published_date->format('F d, Y') }}
                        </span>
                        <span>
                            <i class="bi bi-clock me-1"></i>{{ $story->read_time }} min read
                        </span>
                        <span>
                            <i class="bi bi-heart me-1"></i>{{ $story->likes_count ?? 0 }} likes
                        </span>
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}"
                            class="img-fluid rounded shadow-sm w-100" style="max-height: 500px; object-fit: cover;">
                        @if ($story->image_attribution)
                            <div class="text-muted small mt-2">
                                <i class="bi bi-camera me-1"></i>{{ $story->image_attribution }}
                            </div>
                        @endif
                    </div>

                    <hr class="my-4">

                    <!-- Excerpt -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="bi bi-card-text me-2 text-primary"></i>Excerpt
                        </h5>
                        <p class="lead text-muted">{{ $story->excerpt }}</p>
                    </div>

                    <hr class="my-4">

                    <!-- YouTube Video -->
                    @if ($story->youtube_url)
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="bi bi-youtube text-danger me-2"></i>Embedded Video
                            </h5>
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $story->youtube_embed_url }}" title="YouTube video" allowfullscreen
                                    class="rounded shadow-sm"></iframe>
                            </div>
                        </div>
                        <hr class="my-4">
                    @endif

                    <!-- Full Content -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="bi bi-file-text me-2 text-primary"></i>Full Story Content
                        </h5>
                        <div class="story-content">
                            {!! $story->content !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-search me-2"></i>SEO Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Meta Title</h6>
                        <p class="mb-0">{{ $story->meta_title ?? $story->title }}</p>
                        <small class="text-muted">{{ strlen($story->meta_title ?? $story->title) }}/60 characters</small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Meta Description</h6>
                        <p class="mb-0">{{ $story->meta_description ?? Str::limit(strip_tags($story->excerpt), 160) }}
                        </p>
                        <small class="text-muted">{{ strlen($story->meta_description ?? '') }}/160 characters</small>
                    </div>

                    @if ($story->meta_keywords)
                        <hr>

                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Meta Keywords</h6>
                            <p class="mb-0">
                                @foreach (explode(',', $story->meta_keywords) as $keyword)
                                    <span class="badge bg-light text-dark me-1">{{ trim($keyword) }}</span>
                                @endforeach
                            </p>
                        </div>
                    @endif

                    @if ($story->hashtags)
                        <hr>

                        <div>
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-hash me-1"></i>Social Media Hashtags
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach (explode(',', $story->hashtags) as $hashtag)
                                    <span class="hashtag-badge">#{{ trim($hashtag) }}</span>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>Used when sharing to social media
                            </small>
                        </div>
                    @endif

                    @if ($story->og_image)
                        <hr>

                        <div>
                            <h6 class="text-muted mb-2">Social Media Image (OG Image)</h6>
                            <img src="{{ asset('storage/' . $story->og_image) }}" alt="OG Image" class="img-thumbnail"
                                style="max-width: 400px;">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Social Media Sharing Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-share me-2"></i>Social Media Sharing
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Share URL -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Share URL</h6>
                        <div class="input-group">
                            @php
                                $frontendDomain = env('FRONTEND_DOMAIN', 'https://eaglesports.co.ke');
                                $shareUrl = "{$frontendDomain}/stories/{$story->slug}";
                            @endphp
                            <input type="text" class="form-control" id="shareUrl"
                                value="{{ $shareUrl }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyShareUrl()">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                        <small class="text-muted">This URL contains all meta tags for social sharing</small>
                    </div>

                    <!-- Social Media Preview -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Facebook/LinkedIn Preview</h6>
                        <div class="social-preview facebook-preview">
                            <div class="preview-image">
                                <img src="{{ $story->og_image_url }}" alt="Preview">
                            </div>
                            <div class="preview-content">
                                <div class="preview-url">{{ parse_url($frontendDomain, PHP_URL_HOST) }}</div>
                                <div class="preview-title">{{ $story->meta_title ?? $story->title }}</div>
                                <div class="preview-description">
                                    {{ Str::limit($story->meta_description ?? $story->excerpt, 100) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Twitter Preview -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Twitter Preview</h6>
                        <div class="social-preview twitter-preview">
                            <div class="preview-image">
                                <img src="{{ $story->og_image_url }}" alt="Preview">
                            </div>
                            <div class="preview-content">
                                <div class="preview-title">{{ $story->meta_title ?? $story->title }}</div>
                                <div class="preview-description">
                                    {{ Str::limit($story->meta_description ?? $story->excerpt, 100) }}
                                </div>
                                <div class="preview-url">
                                    <i class="bi bi-link-45deg"></i>
                                    {{ parse_url($frontendDomain, PHP_URL_HOST) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Testing Tools -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-3">Test Your Share Links</h6>
                        <div class="d-grid gap-2">
                            @php
                                // Use the public meta-tag route so crawlers pull proper OG tags
                                $metaTagUrl = route('story.meta', ['slug' => $story->slug]);
                            @endphp
                            <a href="https://developers.facebook.com/tools/debug/?q={{ urlencode($metaTagUrl) }}"
                                target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-facebook me-2"></i>Test on Facebook Debugger
                            </a>
                            <a href="https://cards-dev.twitter.com/validator?url={{ urlencode($metaTagUrl) }}"
                                target="_blank" class="btn btn-outline-info">
                                <i class="bi bi-twitter me-2"></i>Test on Twitter Validator
                            </a>
                            <a href="https://www.linkedin.com/post-inspector/inspect/{{ urlencode($metaTagUrl) }}"
                                target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-linkedin me-2"></i>Test on LinkedIn Inspector
                            </a>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div>
                        <h6 class="text-muted mb-3">Quick Share</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-primary" onclick="shareToFacebook()">
                                <i class="bi bi-facebook me-2"></i>Share on Facebook
                            </button>
                            <button class="btn btn-info text-white" onclick="shareToTwitter()">
                                <i class="bi bi-twitter me-2"></i>Share on Twitter
                            </button>
                            <button class="btn btn-primary" onclick="shareToLinkedIn()">
                                <i class="bi bi-linkedin me-2"></i>Share on LinkedIn
                            </button>
                            <button class="btn btn-success" onclick="shareToWhatsApp()">
                                <i class="bi bi-whatsapp me-2"></i>Share on WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @push('styles')
                <style>
                    .social-preview {
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        overflow: hidden;
                        background: #fff;
                        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    }

                    .facebook-preview {
                        max-width: 500px;
                    }

                    .twitter-preview {
                        max-width: 506px;
                    }

                    .preview-image {
                        width: 100%;
                        aspect-ratio: 1.91/1;
                        overflow: hidden;
                        background: #f0f0f0;
                    }

                    .preview-image img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }

                    .preview-content {
                        padding: 12px 16px;
                    }

                    .preview-url {
                        font-size: 12px;
                        color: #606770;
                        text-transform: uppercase;
                        margin-bottom: 4px;
                    }

                    .preview-title {
                        font-size: 16px;
                        font-weight: 600;
                        color: #1c1e21;
                        margin-bottom: 4px;
                        line-height: 1.3;
                    }

                    .preview-description {
                        font-size: 14px;
                        color: #606770;
                        line-height: 1.4;
                    }

                    .twitter-preview .preview-content {
                        padding: 12px;
                    }

                    .twitter-preview .preview-title {
                        font-size: 15px;
                    }

                    .twitter-preview .preview-description {
                        font-size: 13px;
                    }
                </style>
            @endpush

            @push('scripts')
                <script>
                    function copyShareUrl() {
                        const input = document.getElementById('shareUrl');
                        input.select();
                        document.execCommand('copy');

                        const btn = event.target.closest('button');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
                        btn.classList.add('btn-success');
                        btn.classList.remove('btn-outline-secondary');

                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-secondary');
                        }, 2000);
                    }

                    function shareToFacebook() {
                        const url = encodeURIComponent('{{ $shareUrl }}');
                        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
                    }

                    function shareToTwitter() {
                        const url = encodeURIComponent('{{ $shareUrl }}');
                        const text = encodeURIComponent('{{ $story->title }}');
                        const hashtags = '{{ $story->hashtags }}';
                        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}&hashtags=${hashtags}`, '_blank',
                            'width=600,height=400');
                    }

                    function shareToLinkedIn() {
                        const url = encodeURIComponent('{{ $shareUrl }}');
                        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400');
                    }

                    function shareToWhatsApp() {
                        const url = encodeURIComponent('{{ $shareUrl }}');
                        const text = encodeURIComponent('{{ $story->title }}');
                        window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
                    }
                </script>
            @endpush
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Story Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Slug -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">URL Slug</h6>
                        <code class="d-block p-2 bg-light rounded">{{ $story->slug }}</code>
                    </div>

                    <hr>

                    <!-- Category -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Category</h6>
                        <span class="badge bg-primary fs-6">{{ $story->category }}</span>
                    </div>

                    <hr>

                    <!-- Author -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Author</h6>
                        <p class="mb-0">
                            <i class="bi bi-person-circle me-1"></i>{{ $story->author_name }}
                        </p>
                    </div>

                    <hr>

                    <!-- Read Time -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Estimated Read Time</h6>
                        <p class="mb-0">
                            <i class="bi bi-clock me-1"></i>{{ $story->read_time }} minutes
                        </p>
                    </div>

                    <hr>

                    <!-- Published Date -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Published Date</h6>
                        <p class="mb-0">
                            <i class="bi bi-calendar-check me-1"></i>{{ $story->published_date->format('F d, Y') }}
                        </p>
                    </div>

                    <hr>

                    <!-- Status -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Status</h6>
                        @if ($story->status === 'published')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Published
                            </span>
                        @elseif($story->status === 'draft')
                            <span class="badge bg-warning">
                                <i class="bi bi-pencil-square me-1"></i>Draft
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-archive me-1"></i>Archived
                            </span>
                        @endif
                    </div>

                    <hr>

                    <!-- Featured -->
                    <div>
                        <h6 class="text-muted mb-2">Featured Story</h6>
                        @if ($story->is_featured)
                            <span class="text-success">
                                <i class="bi bi-star-fill me-1"></i>Yes
                            </span>
                        @else
                            <span class="text-muted">
                                <i class="bi bi-star me-1"></i>No
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Statistics
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Total Likes</h6>
                        <p class="mb-0 fs-4 fw-bold text-danger">
                            <i class="bi bi-heart-fill me-1"></i>{{ $story->likes_count ?? 0 }}
                        </p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Word Count</h6>
                        <p class="mb-0">
                            <i class="bi bi-file-text me-1"></i>
                            {{ str_word_count(strip_tags($story->content)) }} words
                        </p>
                    </div>

                    <hr>

                    <div>
                        <h6 class="text-muted mb-2">Character Count</h6>
                        <p class="mb-0">
                            <i class="bi bi-textarea-t me-1"></i>
                            {{ strlen(strip_tags($story->content)) }} characters
                        </p>
                    </div>
                </div>
            </div>

            <!-- Timestamps Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Timestamps
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Created</h6>
                        <p class="mb-0">
                            <i class="bi bi-plus-circle me-1"></i>
                            {{ $story->created_at->format('F d, Y') }}
                            <br>
                            <small class="text-muted">{{ $story->created_at->format('g:i A') }}</small>
                        </p>
                        <small class="text-muted">{{ $story->created_at->diffForHumans() }}</small>
                    </div>

                    <hr>

                    <div>
                        <h6 class="text-muted mb-2">Last Updated</h6>
                        <p class="mb-0">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            {{ $story->updated_at->format('F d, Y') }}
                            <br>
                            <small class="text-muted">{{ $story->updated_at->format('g:i A') }}</small>
                        </p>
                        <small class="text-muted">{{ $story->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.stories.edit', $story) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Story
                        </a>

                        @if ($story->status === 'published')
                            @php
                                $frontendUrl = "{$frontendDomain}/stories/{$story->slug}";
                            @endphp
                            <a href="{{ $frontendUrl }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-eye me-2"></i>View Public Page
                            </a>
                        @endif

                        <button type="button" class="btn btn-outline-secondary"
                            onclick="copyToClipboard('{{ $frontendUrl }}')">
                            <i class="bi bi-link-45deg me-2"></i>Copy Link
                        </button>

                        @if ($story->status === 'draft')
                            <form action="{{ route('admin.stories.update', $story) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="published">
                                <input type="hidden" name="title" value="{{ $story->title }}">
                                <input type="hidden" name="excerpt" value="{{ $story->excerpt }}">
                                <input type="hidden" name="content" value="{{ $story->content }}">
                                <input type="hidden" name="category" value="{{ $story->category }}">
                                <input type="hidden" name="author_name" value="{{ $story->author_name }}">
                                <input type="hidden" name="read_time" value="{{ $story->read_time }}">
                                <input type="hidden" name="published_date"
                                    value="{{ $story->published_date->format('Y-m-d') }}">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-upload me-2"></i>Publish Story
                                </button>
                            </form>
                        @endif

                        @if ($story->status === 'published')
                            <form action="{{ route('admin.stories.update', $story) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="draft">
                                <input type="hidden" name="title" value="{{ $story->title }}">
                                <input type="hidden" name="excerpt" value="{{ $story->excerpt }}">
                                <input type="hidden" name="content" value="{{ $story->content }}">
                                <input type="hidden" name="category" value="{{ $story->category }}">
                                <input type="hidden" name="author_name" value="{{ $story->author_name }}">
                                <input type="hidden" name="read_time" value="{{ $story->read_time }}">
                                <input type="hidden" name="published_date"
                                    value="{{ $story->published_date->format('Y-m-d') }}">
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-pencil-square me-2"></i>Unpublish
                                </button>
                            </form>
                        @endif

                        <hr class="my-2">

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>Delete Story
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Are you sure you want to delete <strong>"{{ $story->title }}"</strong>?</p>
                    <p class="text-danger mb-0">
                        <small>
                            <i class="bi bi-info-circle me-1"></i>
                            This action cannot be undone. All associated images and data will be permanently deleted.
                        </small>
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.stories.destroy', $story) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .story-content {
                font-size: 1.125rem;
                line-height: 1.8;
                color: #333;
            }

            .story-content img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                margin: 2rem 0;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .story-content h2 {
                font-size: 2rem;
                font-weight: 700;
                margin-top: 2.5rem;
                margin-bottom: 1rem;
                color: #1a1a1a;
            }

            .story-content h3 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-top: 2rem;
                margin-bottom: 1rem;
                color: #1a1a1a;
            }

            .story-content p {
                margin-bottom: 1.5rem;
            }

            .story-content ul,
            .story-content ol {
                margin-bottom: 1.5rem;
                padding-left: 2rem;
            }

            .story-content blockquote {
                border-left: 4px solid #FF6600;
                padding-left: 1.5rem;
                margin: 2rem 0;
                font-style: italic;
                color: #555;
            }

            .story-content a {
                color: #FF6600;
                text-decoration: none;
                border-bottom: 1px solid #FF6600;
            }

            .story-content a:hover {
                color: #E55A00;
                border-bottom-color: #E55A00;
            }

            .story-content code {
                background-color: #f4f4f4;
                padding: 2px 6px;
                border-radius: 4px;
                font-family: 'Courier New', monospace;
            }

            .story-content pre {
                background-color: #f4f4f4;
                padding: 1rem;
                border-radius: 8px;
                overflow-x: auto;
                margin: 1.5rem 0;
            }

            .card {
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
            }

            .img-thumbnail {
                border: 2px solid #e9ecef;
                padding: 5px;
            }

            code {
                font-size: 0.875rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Copy to clipboard function
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    // Show success message
                    const btn = event.target.closest('button');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check me-2"></i>Copied!';
                    btn.classList.remove('btn-outline-secondary');
                    btn.classList.add('btn-success');

                    setTimeout(function() {
                        btn.innerHTML = originalHTML;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-outline-secondary');
                    }, 2000);
                }, function(err) {
                    alert('Failed to copy link');
                });
            }
        </script>
    @endpush
@endsection

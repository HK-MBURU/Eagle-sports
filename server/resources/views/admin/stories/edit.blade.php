@extends('layouts.admin')

@section('title', 'Edit Story')
@section('page-title', 'Edit Story')

@section('content')
    <div class="row">
        <div class="col-lg-11 mx-auto">
            <form action="{{ route('admin.stories.update', $story) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Main Content Area (Left) -->
                    <div class="col-lg-8">
                        <!-- Title -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <label for="title" class="form-label">Story Title *</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title', $story->title) }}"
                                    placeholder="Enter an engaging story title..." required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <label for="excerpt" class="form-label">Excerpt *</label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3"
                                    placeholder="Write a compelling summary that will appear on story cards..." required>{{ old('excerpt', $story->excerpt) }}</textarea>
                                <small class="text-muted">This appears on story cards and search results</small>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Rich Text Editor -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <label for="content" class="form-label">Story Content *</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" required>{{ old('content', $story->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SEO Section -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0">
                                    <i class="bi bi-search me-2"></i>SEO Optimization
                                </h5>
                                <small class="text-muted">Help your story rank better in search engines</small>
                            </div>
                            <div class="card-body p-4">
                                <!-- Meta Title -->
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                        id="meta_title" name="meta_title"
                                        value="{{ old('meta_title', $story->meta_title) }}" maxlength="60"
                                        placeholder="Leave blank to use story title">
                                    <small class="text-muted">
                                        <span
                                            id="meta-title-count">{{ strlen(old('meta_title', $story->meta_title ?? '')) }}</span>/60
                                        characters • Shown in search results
                                    </small>
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                        name="meta_description" rows="3" maxlength="160" placeholder="Brief description for search engines...">{{ old('meta_description', $story->meta_description) }}</textarea>
                                    <small class="text-muted">
                                        <span
                                            id="meta-desc-count">{{ strlen(old('meta_description', $story->meta_description ?? '')) }}</span>/160
                                        characters • Appears below title in search results
                                    </small>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Keywords -->
                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                        id="meta_keywords" name="meta_keywords"
                                        value="{{ old('meta_keywords', $story->meta_keywords) }}"
                                        placeholder="running, athletics, youth empowerment">
                                    <small class="text-muted">Separate keywords with commas</small>
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Hashtags for Social Media -->
                                <div class="mb-3">
                                    <label for="hashtags" class="form-label">Social Media Hashtags</label>
                                    <div class="hashtag-input-wrapper">
                                        <input type="text" class="form-control @error('hashtags') is-invalid @enderror"
                                            id="hashtags-input" placeholder="Type hashtag and press Enter..."
                                            autocomplete="off">
                                        <input type="hidden" name="hashtags" id="hashtags"
                                            value="{{ old('hashtags', $story->hashtags) }}">
                                        <div class="hashtags-container mt-2" id="hashtags-container"></div>
                                    </div>
                                    <small class="text-muted">Press Enter or comma to add hashtags • Used when sharing to
                                        social media</small>
                                    @error('hashtags')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current OG Image -->
                                @if ($story->og_image)
                                    <div class="mb-3">
                                        <label class="form-label d-block">Current Social Media Image</label>
                                        <img src="{{ asset('storage/' . $story->og_image) }}" alt="OG Image"
                                            class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                @endif

                                <!-- OG Image -->
                                <div>
                                    <label for="og_image" class="form-label">Social Media Image
                                        {{ $story->og_image ? '(Change)' : '(Optional)' }}</label>
                                    <input type="file" class="form-control @error('og_image') is-invalid @enderror"
                                        id="og_image" name="og_image" accept="image/*"
                                        onchange="previewImage(event, 'ogImagePreview')">
                                    <small class="text-muted">1200x630px recommended • Shows when shared on social
                                        media</small>
                                    @error('og_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="ogImagePreview" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar (Right) - REMOVED STICKY -->
                    <div class="col-lg-4">
                        <!-- Publish Settings -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0">
                                    <i class="bi bi-gear me-2"></i>Publish Settings
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="draft"
                                            {{ old('status', $story->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published"
                                            {{ old('status', $story->status) == 'published' ? 'selected' : '' }}>Published
                                        </option>
                                        <option value="archived"
                                            {{ old('status', $story->status) == 'archived' ? 'selected' : '' }}>Archived
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Published Date -->
                                <div class="mb-3">
                                    <label for="published_date" class="form-label">Published Date *</label>
                                    <input type="date"
                                        class="form-control @error('published_date') is-invalid @enderror"
                                        id="published_date" name="published_date"
                                        value="{{ old('published_date', $story->published_date->format('Y-m-d')) }}"
                                        required>
                                    @error('published_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Is Featured -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_featured"
                                            name="is_featured" value="1"
                                            {{ old('is_featured', $story->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            Featured Story
                                        </label>
                                    </div>
                                    <small class="text-muted">Appears prominently on homepage</small>
                                </div>

                                <hr>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
                                        <option value="">— Select Category —</option>
                                        @foreach ($categoryTree as $parent)
                                            @if ($parent->children->isNotEmpty())
                                                <optgroup label="{{ $parent->name }}">
                                                    <option value="{{ $parent->name }}"
                                                        {{ old('category', $story->category) == $parent->name ? 'selected' : '' }}>
                                                        {{ $parent->name }} — General
                                                    </option>
                                                    @foreach ($parent->children as $child)
                                                        <option value="{{ $child->name }}"
                                                            {{ old('category', $story->category) == $child->name ? 'selected' : '' }}>
                                                            ↳ {{ $child->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $parent->name }}"
                                                    {{ old('category', $story->category) == $parent->name ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Author -->
                                <div class="mb-3">
                                    <label for="author_name" class="form-label">Author Name *</label>
                                    <input type="text" class="form-control @error('author_name') is-invalid @enderror"
                                        id="author_name" name="author_name"
                                        value="{{ old('author_name', $story->author_name) }}" placeholder="Author name"
                                        required>
                                    @error('author_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Read Time -->
                                <div class="mb-3">
                                    <label for="read_time" class="form-label">Read Time (min) *</label>
                                    <input type="number" class="form-control @error('read_time') is-invalid @enderror"
                                        id="read_time" name="read_time"
                                        value="{{ old('read_time', $story->read_time) }}" min="1" required>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2 w-100"
                                        onclick="calculateReadTime()">
                                        <i class="bi bi-calculator"></i> Auto-calculate Read Time
                                    </button>
                                    @error('read_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0">
                                    <i class="bi bi-image me-2"></i>Featured Image
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <!-- Current Image -->
                                <div class="mb-3">
                                    <label class="form-label d-block">Current Image</label>
                                    <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}"
                                        class="img-fluid rounded" style="max-width: 100%;">
                                </div>

                                <label for="image" class="form-label">Change Image (Optional)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*"
                                    onchange="previewImage(event, 'imagePreview')">
                                <small class="text-muted d-block mt-2">1200x600px recommended • Leave empty to keep
                                    current</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="imagePreview" class="mt-3"></div>

                                <div class="mt-3">
                                    <label for="image_attribution" class="form-label">Image Attribution (optional)</label>
                                    <input type="text"
                                        class="form-control @error('image_attribution') is-invalid @enderror"
                                        id="image_attribution" name="image_attribution"
                                        value="{{ old('image_attribution', $story->image_attribution) }}"
                                        placeholder="e.g., Photo by Jane Doe / Unsplash">
                                    <small class="text-muted">Shown as a small credit near the story image.</small>
                                    @error('image_attribution')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- YouTube Video -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0">
                                    <i class="bi bi-youtube text-danger me-2"></i>YouTube Video
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <label for="youtube_url" class="form-label">YouTube URL (Optional)</label>
                                <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                                    id="youtube_url" name="youtube_url"
                                    value="{{ old('youtube_url', $story->youtube_url) }}"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted">Paste YouTube video link to embed it in your story</small>
                                @error('youtube_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($story->youtube_url)
                                    <div class="mt-3">
                                        <small class="text-muted d-block mb-2">Current Video Preview:</small>
                                        <div class="ratio ratio-16x9">
                                            <iframe src="{{ $story->youtube_embed_url }}" title="YouTube video"
                                                allowfullscreen class="rounded"></iframe>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Update Story
                            </button>
                            <a href="{{ route('admin.stories.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Back to List
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-lg" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-2"></i>Delete Story
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
                    <p class="text-danger mb-0"><small><i class="bi bi-info-circle"></i> This action cannot be undone. All
                            associated images will also be deleted.</small></p>
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
            .form-label {
                font-weight: 600;
                color: #333A12;
                margin-bottom: 0.5rem;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #FF6600;
                box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.15);
            }

            .btn-primary {
                background: linear-gradient(135deg, #FF6600, #FFBF00);
                border: none;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #E55A00, #E6AB00);
            }

            .card {
                border-radius: 8px;
            }

            #imagePreview img,
            #ogImagePreview img {
                max-width: 100%;
                border-radius: 8px;
                margin-top: 10px;
            }

            .img-thumbnail {
                border: 2px solid #e9ecef;
                padding: 5px;
            }

            .tox-tinymce {
                border-radius: 8px !important;
            }

            .hashtag-input-wrapper {
                position: relative;
            }

            .hashtags-container {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                min-height: 40px;
                padding: 8px;
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                background-color: #f8f9fa;
            }

            .hashtag-tag {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 12px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 500;
                animation: slideIn 0.2s ease-out;
            }

            .hashtag-tag .remove-tag {
                cursor: pointer;
                font-size: 18px;
                line-height: 1;
                opacity: 0.8;
                transition: opacity 0.2s;
            }

            .hashtag-tag .remove-tag:hover {
                opacity: 1;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <!-- TinyMCE CDN -->
        <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>

        <script>
            // Initialize TinyMCE
            tinymce.init({
                selector: '#content',
                height: 600,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount', 'codesample'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | \
                                          alignleft aligncenter alignright alignjustify | \
                                          bullist numlist outdent indent | link image media | \
                                          removeformat code help',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; line-height: 1.6; }',

                // Image upload handler
                images_upload_url: '{{ route('admin.stories.upload-image') }}',
                automatic_uploads: true,
                paste_data_images: true,
                images_upload_handler: function(blobInfo, success, failure) {
                    let xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route('admin.stories.upload-image') }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.onload = function() {
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }

                        let json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        success(json.location);
                    };

                    xhr.onerror = function() {
                        failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                    };

                    let formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    xhr.send(formData);
                },

                file_picker_types: 'image',
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype === 'image') {
                        let input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');

                        input.onchange = function() {
                            let file = this.files[0];
                            let reader = new FileReader();

                            reader.onload = function() {
                                let id = 'blobid' + (new Date()).getTime();
                                let blobCache = tinymce.activeEditor.editorUpload.blobCache;
                                let base64 = reader.result.split(',')[1];
                                let blobInfo = blobCache.create(id, file, base64);
                                blobCache.add(blobInfo);

                                callback(blobInfo.blobUri(), {
                                    title: file.name
                                });
                            };
                            reader.readAsDataURL(file);
                        };

                        input.click();
                    }
                }
            });

            // Character counters for SEO fields
            document.getElementById('meta_title').addEventListener('input', function() {
                document.getElementById('meta-title-count').textContent = this.value.length;
            });

            document.getElementById('meta_description').addEventListener('input', function() {
                document.getElementById('meta-desc-count').textContent = this.value.length;
            });

            // Image preview function
            function previewImage(event, previewId) {
                const file = event.target.files[0];
                const preview = document.getElementById(previewId);

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" alt="Preview">`;
                    }
                    reader.readAsDataURL(file);
                }
            }

            // Auto-calculate read time based on content
            function calculateReadTime() {
                const content = tinymce.get('content').getContent({
                    format: 'text'
                });
                const wordCount = content.trim().split(/\s+/).length;
                const minutes = Math.max(1, Math.ceil(wordCount / 200)); // 200 words per minute
                document.getElementById('read_time').value = minutes;

                // Show feedback
                alert(`Calculated read time: ${minutes} minute(s) based on ${wordCount} words`);
            }

            // Hashtag management
            const hashtagsArray = [];
            const hashtagInput = document.getElementById('hashtags-input');
            const hashtagsContainer = document.getElementById('hashtags-container');
            const hashtagsHidden = document.getElementById('hashtags');

            // Load existing hashtags from database
            if (hashtagsHidden.value) {
                const existingTags = hashtagsHidden.value.split(',').filter(tag => tag.trim());
                existingTags.forEach(tag => addHashtag(tag.trim()));
            }

            hashtagInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const hashtag = this.value.trim();
                    if (hashtag) {
                        addHashtag(hashtag);
                        this.value = '';
                    }
                }
            });

            hashtagInput.addEventListener('blur', function() {
                const hashtag = this.value.trim();
                if (hashtag) {
                    addHashtag(hashtag);
                    this.value = '';
                }
            });

            function addHashtag(text) {
                // Clean the hashtag
                let hashtag = text.replace(/[^a-zA-Z0-9]/g, '');

                if (!hashtag) return;

                // Prevent duplicates
                if (hashtagsArray.includes(hashtag.toLowerCase())) {
                    return;
                }

                hashtagsArray.push(hashtag.toLowerCase());

                // Create tag element
                const tagElement = document.createElement('span');
                tagElement.className = 'hashtag-tag';
                tagElement.innerHTML = `
        #${hashtag}
        <span class="remove-tag" onclick="removeHashtag('${hashtag.toLowerCase()}')">&times;</span>
    `;

                hashtagsContainer.appendChild(tagElement);
                updateHiddenInput();
            }

            function removeHashtag(hashtag) {
                const index = hashtagsArray.indexOf(hashtag);
                if (index > -1) {
                    hashtagsArray.splice(index, 1);
                    renderHashtags();
                    updateHiddenInput();
                }
            }

            function renderHashtags() {
                hashtagsContainer.innerHTML = '';
                hashtagsArray.forEach(hashtag => {
                    const tagElement = document.createElement('span');
                    tagElement.className = 'hashtag-tag';
                    tagElement.innerHTML = `
            #${hashtag}
            <span class="remove-tag" onclick="removeHashtag('${hashtag}')">&times;</span>
        `;
                    hashtagsContainer.appendChild(tagElement);
                });
            }

            function updateHiddenInput() {
                hashtagsHidden.value = hashtagsArray.join(',');
            }
        </script>
    @endpush
@endsection

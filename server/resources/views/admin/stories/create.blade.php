@extends('layouts.admin')

@section('title', 'Add Story')
@section('page-title', 'Create New Story')

@section('content')
    <div class="row">
        <div class="col-lg-11 mx-auto">
            <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data" id="storyForm">
                @csrf

                <div class="row">
                    <!-- Main Content Area (Left) -->
                    <div class="col-lg-8">
                        <!-- Title -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <label for="title" class="form-label">Story Title *</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}"
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
                                    placeholder="Write a compelling summary that will appear on story cards..." required>{{ old('excerpt') }}</textarea>
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
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" required>{{ old('content') }}</textarea>
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
                                        id="meta_title" name="meta_title" value="{{ old('meta_title') }}" maxlength="60"
                                        placeholder="Leave blank to use story title">
                                    <small class="text-muted">
                                        <span id="meta-title-count">0</span>/60 characters • Shown in search results
                                    </small>
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                        name="meta_description" rows="3" maxlength="160" placeholder="Brief description for search engines...">{{ old('meta_description') }}</textarea>
                                    <small class="text-muted">
                                        <span id="meta-desc-count">0</span>/160 characters • Appears below title in search
                                        results
                                    </small>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Keywords -->
                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                        id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                        placeholder="running, athletics, youth empowerment">
                                    <small class="text-muted">Separate keywords with commas</small>
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- OG Image (Hidden - we'll handle this with cropper) -->
                                <div>
                                    <label for="og_image" class="form-label">Social Media Image (Optional)</label>
                                    <input type="file" class="form-control @error('og_image') is-invalid @enderror"
                                        id="og_image" name="og_image_original" accept="image/*">
                                    <small class="text-muted">1200x630px recommended • Shows when shared on Facebook,
                                        Twitter, LinkedIn</small>
                                    @error('og_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <input type="hidden" name="og_image_cropped" id="og_image_cropped">
                                </div>

                                <!-- Hashtags for Social Media -->
                                <div class="mt-3">
                                    <label for="hashtags" class="form-label">Social Media Hashtags</label>
                                    <div class="hashtag-input-wrapper">
                                        <input type="text" class="form-control @error('hashtags') is-invalid @enderror"
                                            id="hashtags-input" placeholder="Type hashtag and press Enter..."
                                            autocomplete="off">
                                        <input type="hidden" name="hashtags" id="hashtags"
                                            value="{{ old('hashtags') }}">
                                        <div class="hashtags-container mt-2" id="hashtags-container"></div>
                                    </div>
                                    <small class="text-muted">Press Enter or comma to add hashtags • Used when sharing to
                                        social media</small>
                                    @error('hashtags')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar (Right) -->
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
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                            Published</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                                            Archived</option>
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
                                        value="{{ old('published_date', date('Y-m-d')) }}" required>
                                    @error('published_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Is Featured -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_featured"
                                            name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
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
                                                        {{ old('category') == $parent->name ? 'selected' : '' }}>
                                                        {{ $parent->name }} — General
                                                    </option>
                                                    @foreach ($parent->children as $child)
                                                        <option value="{{ $child->name }}"
                                                            {{ old('category') == $child->name ? 'selected' : '' }}>
                                                            ↳ {{ $child->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $parent->name }}"
                                                    {{ old('category') == $parent->name ? 'selected' : '' }}>
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
                                        id="author_name" name="author_name" value="{{ old('author_name') }}"
                                        placeholder="Author name" required>
                                    @error('author_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Read Time -->
                                <div class="mb-3">
                                    <label for="read_time" class="form-label">Read Time (min) *</label>
                                    <input type="number" class="form-control @error('read_time') is-invalid @enderror"
                                        id="read_time" name="read_time" value="{{ old('read_time', 5) }}"
                                        min="1" required>
                                    @error('read_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0"><i class="bi bi-image me-2"></i>Featured Image</h5>
                            </div>
                            <div class="card-body p-4">

                                {{-- Hidden field: path of the AJAX pre-uploaded image --}}
                                <input type="hidden" name="image_temp" id="imageTempPath" value="{{ old('image_temp') }}">

                                {{-- File input: only used to trigger the AJAX upload --}}
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*"
                                    {{ !old('image_temp') ? 'required' : '' }}>
                                <small class="text-muted d-block mt-1">JPEG / PNG / WebP · max 10 MB · 1200×600 px recommended</small>

                                {{-- Upload status indicator --}}
                                <div id="imageUploadStatus" class="mt-2" style="font-size:.82rem;display:none">
                                    <span id="imageStatusUploading" style="color:#2563eb;display:none">
                                        <span class="spinner-border spinner-border-sm me-1"></span>Uploading & compressing…
                                    </span>
                                    <span id="imageStatusReady" style="color:#16a34a;display:none">
                                        <i class="bi bi-check-circle-fill me-1"></i>Image ready — saved, will not be lost on error.
                                    </span>
                                    <span id="imageStatusError" style="color:#d62835;display:none"></span>
                                </div>

                                @error('image')
                                    <div class="text-danger mt-1" style="font-size:.82rem">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror

                                {{-- Preview: restored from server after validation error, or shown after AJAX upload --}}
                                <div id="imagePreview" class="mt-3">
                                    @if(old('image_temp') && Storage::disk('public')->exists(old('image_temp')))
                                        <div class="position-relative d-inline-block w-100">
                                            <img src="{{ asset('storage/' . old('image_temp')) }}"
                                                 class="img-fluid rounded shadow-sm w-100"
                                                 style="max-height:260px;object-fit:cover"
                                                 alt="Featured image preview">
                                            <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                                <i class="bi bi-check-circle me-1"></i>Saved
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <label for="image_attribution" class="form-label">Image Attribution (optional)</label>
                                    <input type="text"
                                        class="form-control @error('image_attribution') is-invalid @enderror"
                                        id="image_attribution" name="image_attribution"
                                        value="{{ old('image_attribution') }}"
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
                                    id="youtube_url" name="youtube_url" value="{{ old('youtube_url') }}"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted">Paste YouTube video link to embed it in your story</small>
                                @error('youtube_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons-sticky">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-check-circle me-2"></i>Create Story
                                        </button>
                                        <a href="{{ route('admin.stories.index') }}"
                                            class="btn btn-outline-secondary btn-lg">
                                            <i class="bi bi-x-circle me-2"></i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modern Image Cropper Modal -->
    <div class="modal fade" id="imageCropperModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-gradient-primary text-white">
                    <div>
                        <h4 class="modal-title mb-1">
                            <i class="bi bi-crop me-2"></i>Optimize for Social Media
                        </h4>
                        <p class="mb-0 small opacity-90">Position your image perfectly for Facebook sharing</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <!-- Cropper Area -->
                        <div class="col-lg-8 bg-dark d-flex align-items-center justify-content-center p-4">
                            <div class="cropper-container-wrapper">
                                <canvas id="cropperCanvas" class="cropper-canvas"></canvas>
                                <div class="cropper-overlay">
                                    <div class="crop-frame" id="cropFrame">
                                        <div class="frame-corner tl"></div>
                                        <div class="frame-corner tr"></div>
                                        <div class="frame-corner bl"></div>
                                        <div class="frame-corner br"></div>
                                        <div class="frame-grid">
                                            <div class="grid-line v1"></div>
                                            <div class="grid-line v2"></div>
                                            <div class="grid-line h1"></div>
                                            <div class="grid-line h2"></div>
                                        </div>
                                        <div class="frame-label">
                                            <i class="bi bi-facebook me-2"></i>1200 × 630 px
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Controls Panel -->
                        <div class="col-lg-4 bg-light p-4">
                            <div class="controls-panel">
                                <div class="mb-4">
                                    <h6 class="fw-bold text-uppercase text-muted small mb-3">
                                        <i class="bi bi-sliders me-2"></i>Adjust Image
                                    </h6>

                                    <!-- Zoom Control -->
                                    <div class="mb-4">
                                        <label class="form-label small fw-semibold">
                                            <i class="bi bi-zoom-in me-1"></i>Zoom
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="zoomOut">
                                                <i class="bi bi-dash-lg"></i>
                                            </button>
                                            <input type="range" class="form-range flex-grow-1" id="zoomSlider"
                                                min="1" max="3" step="0.1" value="1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="zoomIn">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Position Info -->
                                    <div class="alert alert-info border-0 mb-4">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                            <div class="small">
                                                <strong>How to use:</strong>
                                                <ul class="mb-0 ps-3 mt-1">
                                                    <li>Drag to reposition image</li>
                                                    <li>Use slider to zoom in/out</li>
                                                    <li>Click reset to start over</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-secondary" id="resetCrop">
                                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Position
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" id="fitImage">
                                            <i class="bi bi-arrows-angle-contract me-2"></i>Fit to Frame
                                        </button>
                                    </div>
                                </div>

                                <hr>

                                <!-- Preview -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-uppercase text-muted small mb-3">
                                        <i class="bi bi-eye me-2"></i>Facebook Preview
                                    </h6>
                                    <div class="preview-box">
                                        <canvas id="previewCanvas" class="preview-canvas"></canvas>
                                        <div class="preview-overlay">
                                            <div class="preview-meta">
                                                <div class="preview-title">Your Story Title</div>
                                                <div class="preview-domain">yoursite.com</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary px-4" id="applyCrop">
                        <i class="bi bi-check-circle me-2"></i>Apply & Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Unsaved Changes Modal -->
    <div class="modal fade" id="unsavedChangesModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden">
                <div class="modal-body text-center px-5 py-5">
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center"
                         style="width:72px;height:72px;background:#fff3cd;border-radius:50%">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:2rem;color:#d97706"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="font-size:1.15rem">Leave without saving?</h5>
                    <p class="text-muted mb-4" style="font-size:.88rem;line-height:1.65">
                        You have unsaved content. If you leave now, everything you've typed or uploaded will be permanently lost.
                    </p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary py-2" data-bs-dismiss="modal">
                            <i class="bi bi-pencil-square me-2"></i>Keep Editing
                        </button>
                        <button type="button" class="btn btn-outline-danger py-2" id="confirmLeave">
                            <i class="bi bi-box-arrow-right me-2"></i>Discard &amp; Leave
                        </button>
                    </div>
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
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #E55A00, #E6AB00);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(255, 102, 0, 0.3);
            }

            .bg-gradient-primary {
                background: linear-gradient(135deg, #FF6600, #FFBF00);
            }

            .card {
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
            }

            #imagePreview img {
                max-width: 100%;
                border-radius: 8px;
                margin-top: 10px;
            }

            .action-buttons-sticky {
                position: sticky;
                bottom: 20px;
                z-index: 1000;
                margin-top: 20px;
            }

            html {
                scroll-behavior: smooth;
            }

            @media (max-width: 991px) {
                .action-buttons-sticky {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    margin: 0;
                    border-radius: 0;
                    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
                }

                .action-buttons-sticky .card {
                    border-radius: 0;
                    margin-bottom: 0;
                }

                .col-lg-4 {
                    padding-bottom: 100px;
                }
            }

            /* Hashtag Styles */
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

            /* Image Cropper Styles */
            .cropper-container-wrapper {
                position: relative;
                max-width: 100%;
                max-height: 70vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cropper-canvas {
                max-width: 100%;
                max-height: 70vh;
                display: block;
                cursor: move;
                border-radius: 8px;
            }

            .cropper-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
            }

            .crop-frame {
                position: absolute;
                border: 3px solid #FF6600;
                box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
                pointer-events: none;
                transition: all 0.1s ease;
            }

            .frame-corner {
                position: absolute;
                width: 20px;
                height: 20px;
                border: 3px solid #FF6600;
                background: white;
                border-radius: 50%;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            }

            .frame-corner.tl {
                top: -10px;
                left: -10px;
            }

            .frame-corner.tr {
                top: -10px;
                right: -10px;
            }

            .frame-corner.bl {
                bottom: -10px;
                left: -10px;
            }

            .frame-corner.br {
                bottom: -10px;
                right: -10px;
            }

            .frame-grid {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0.5;
            }

            .grid-line {
                position: absolute;
                background: white;
            }

            .grid-line.v1,
            .grid-line.v2 {
                width: 1px;
                height: 100%;
                top: 0;
            }

            .grid-line.v1 {
                left: 33.33%;
            }

            .grid-line.v2 {
                left: 66.66%;
            }

            .grid-line.h1,
            .grid-line.h2 {
                height: 1px;
                width: 100%;
                left: 0;
            }

            .grid-line.h1 {
                top: 33.33%;
            }

            .grid-line.h2 {
                top: 66.66%;
            }

            .frame-label {
                position: absolute;
                top: -35px;
                left: 50%;
                transform: translateX(-50%);
                background: linear-gradient(135deg, #FF6600, #FFBF00);
                color: white;
                padding: 6px 16px;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 600;
                white-space: nowrap;
                box-shadow: 0 4px 12px rgba(255, 102, 0, 0.3);
            }

            .controls-panel {
                height: 100%;
                overflow-y: auto;
            }

            .preview-box {
                position: relative;
                background: #f8f9fa;
                border-radius: 8px;
                overflow: hidden;
                border: 2px solid #dee2e6;
            }

            .preview-canvas {
                width: 100%;
                display: block;
            }

            .preview-overlay {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
                padding: 12px;
            }

            .preview-meta {
                color: white;
            }

            .preview-title {
                font-weight: 600;
                font-size: 14px;
                margin-bottom: 2px;
            }

            .preview-domain {
                font-size: 11px;
                opacity: 0.8;
                text-transform: uppercase;
            }

            .modal-xl {
                max-width: 1400px;
            }

            @media (max-width: 991px) {
                .cropper-container-wrapper {
                    max-height: 50vh;
                }

                .cropper-canvas {
                    max-height: 50vh;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <!-- TinyMCE CDN -->
        <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>

        <script>
            // Global variables
            let cropperImage = null;
            let cropperCanvas = null;
            let cropperCtx = null;
            let previewCanvas = null;
            let previewCtx = null;
            let imageObj = new Image();
            let isDragging = false;
            let dragStartX = 0;
            let dragStartY = 0;
            let imageX = 0;
            let imageY = 0;
            let imageScale = 1;
            let cropperModal = null;
            let formSubmitted = false;
            let isDirty       = false;
            let isSubmitting  = false;
            let pendingNavUrl = null;
            function markDirty() { isDirty = true; }

            // OG Image dimensions (Facebook recommended)
            const OG_WIDTH = 1200;
            const OG_HEIGHT = 630;
            const ASPECT_RATIO = OG_WIDTH / OG_HEIGHT;

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
                setup: function(editor) {
                    editor.on('init', function() {
                        document.getElementById('content').removeAttribute('required');
                    });
                    editor.on('input', markDirty);
                },
                automatic_uploads: true,
                paste_data_images: true,
                images_upload_handler: function(blobInfo, progress) {
                    return new Promise(function(resolve, reject) {
                        const xhr = new XMLHttpRequest();
                        xhr.withCredentials = true;
                        xhr.open('POST', '{{ route('admin.stories.upload-image') }}');
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                        xhr.upload.onprogress = function(e) {
                            if (e.lengthComputable) progress(e.loaded / e.total * 100);
                        };

                        xhr.onload = function() {
                            if (xhr.status < 200 || xhr.status >= 300) {
                                reject({ message: 'HTTP error ' + xhr.status, remove: true });
                                return;
                            }
                            let json;
                            try { json = JSON.parse(xhr.responseText); }
                            catch(e) { reject({ message: 'Server returned an error — check file size and format', remove: true }); return; }
                            if (!json || typeof json.location !== 'string') {
                                reject({ message: json.error || 'Upload failed', remove: true });
                                return;
                            }
                            resolve(json.location);
                        };

                        xhr.onerror = function() {
                            reject({ message: 'Upload failed — network error', remove: true });
                        };

                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        xhr.send(formData);
                    });
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

            // Initialize cropper modal
            document.addEventListener('DOMContentLoaded', function() {
                cropperModal = new bootstrap.Modal(document.getElementById('imageCropperModal'));
                cropperCanvas = document.getElementById('cropperCanvas');
                cropperCtx = cropperCanvas.getContext('2d');
                previewCanvas = document.getElementById('previewCanvas');
                previewCtx = previewCanvas.getContext('2d');


                setupCropperControls();
            });

            // Form submission handler
            document.getElementById('storyForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate TinyMCE content
                tinymce.triggerSave();
                const content = tinymce.get('content').getContent();
                if (!content || content.trim() === '') {
                    alert('Please enter story content before submitting.');
                    tinymce.get('content').focus();
                    return false;
                }

                // Check if we already processed the cropper
                if (formSubmitted) {
                    isSubmitting = true;
                    this.submit();
                    return;
                }

                // Get the OG image or featured image
                const ogImageInput = document.getElementById('og_image');
                const featuredImageInput = document.getElementById('image');

                let imageToProcess = null;
                let imageFile = null;

                if (ogImageInput.files && ogImageInput.files[0]) {
                    imageFile = ogImageInput.files[0];
                    imageToProcess = 'og';
                } else if (featuredImageInput.files && featuredImageInput.files[0]) {
                    imageFile = featuredImageInput.files[0];
                    imageToProcess = 'featured';
                } else if (document.getElementById('imageTempPath').value) {
                    // Image was pre-uploaded via AJAX — submit without cropper
                    isSubmitting = true;
                    this.submit();
                    return;
                } else {
                    alert('Please upload a featured image.');
                    return false;
                }

                // Load image into cropper
                loadImageToCropper(imageFile, imageToProcess);
            });



            // Load image into cropper
            function loadImageToCropper(file, source) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageObj = new Image();
                    imageObj.onload = function() {
                        initializeCropper();
                        cropperModal.show();
                    };
                    imageObj.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            // Initialize cropper canvas
            function initializeCropper() {
                // Calculate canvas size to maintain aspect ratio
                const maxWidth = 800;
                const maxHeight = 600;

                let canvasWidth = imageObj.width;
                let canvasHeight = imageObj.height;

                if (canvasWidth > maxWidth) {
                    canvasHeight = (maxWidth / canvasWidth) * canvasHeight;
                    canvasWidth = maxWidth;
                }

                if (canvasHeight > maxHeight) {
                    canvasWidth = (maxHeight / canvasHeight) * canvasWidth;
                    canvasHeight = maxHeight;
                }

                cropperCanvas.width = canvasWidth;
                cropperCanvas.height = canvasHeight;

                // Calculate crop frame size
                const frameWidth = canvasWidth * 0.9;
                const frameHeight = frameWidth / ASPECT_RATIO;

                const cropFrame = document.getElementById('cropFrame');
                cropFrame.style.width = frameWidth + 'px';
                cropFrame.style.height = frameHeight + 'px';
                cropFrame.style.left = ((canvasWidth - frameWidth) / 2) + 'px';
                cropFrame.style.top = ((canvasHeight - frameHeight) / 2) + 'px';

                // Reset position and scale
                imageScale = 1;
                fitImageToFrame();

                drawImage();
                updatePreview();
            }

            // Fit image to frame
            function fitImageToFrame() {
                const cropFrame = document.getElementById('cropFrame');
                const frameWidth = parseFloat(cropFrame.style.width);
                const frameHeight = parseFloat(cropFrame.style.height);

                // Calculate scale to cover the frame
                const scaleX = frameWidth / imageObj.width;
                const scaleY = frameHeight / imageObj.height;
                imageScale = Math.max(scaleX, scaleY);

                // Center the image
                const scaledWidth = imageObj.width * imageScale;
                const scaledHeight = imageObj.height * imageScale;
                imageX = (cropperCanvas.width - scaledWidth) / 2;
                imageY = (cropperCanvas.height - scaledHeight) / 2;

                document.getElementById('zoomSlider').value = imageScale;
                drawImage();
                updatePreview();
            }

            // Draw image on canvas
            function drawImage() {
                cropperCtx.clearRect(0, 0, cropperCanvas.width, cropperCanvas.height);
                const scaledWidth = imageObj.width * imageScale;
                const scaledHeight = imageObj.height * imageScale;
                cropperCtx.drawImage(imageObj, imageX, imageY, scaledWidth, scaledHeight);
            }

            // Update preview
            function updatePreview() {
                const cropFrame = document.getElementById('cropFrame');
                const frameLeft = parseFloat(cropFrame.style.left);
                const frameTop = parseFloat(cropFrame.style.top);
                const frameWidth = parseFloat(cropFrame.style.width);
                const frameHeight = parseFloat(cropFrame.style.height);

                // Set preview canvas size
                previewCanvas.width = OG_WIDTH;
                previewCanvas.height = OG_HEIGHT;

                // Calculate source rectangle
                const sourceX = (frameLeft - imageX) / imageScale;
                const sourceY = (frameTop - imageY) / imageScale;
                const sourceWidth = frameWidth / imageScale;
                const sourceHeight = frameHeight / imageScale;

                // Draw cropped area
                previewCtx.drawImage(
                    imageObj,
                    sourceX, sourceY, sourceWidth, sourceHeight,
                    0, 0, OG_WIDTH, OG_HEIGHT
                );
            }

            // Setup cropper controls
            function setupCropperControls() {
                // Zoom slider
                document.getElementById('zoomSlider').addEventListener('input', function(e) {
                    imageScale = parseFloat(e.target.value);
                    drawImage();
                    updatePreview();
                });

                // Zoom buttons
                document.getElementById('zoomIn').addEventListener('click', function() {
                    const slider = document.getElementById('zoomSlider');
                    slider.value = Math.min(3, parseFloat(slider.value) + 0.1);
                    slider.dispatchEvent(new Event('input'));
                });

                document.getElementById('zoomOut').addEventListener('click', function() {
                    const slider = document.getElementById('zoomSlider');
                    slider.value = Math.max(1, parseFloat(slider.value) - 0.1);
                    slider.dispatchEvent(new Event('input'));
                });

                // Reset button
                document.getElementById('resetCrop').addEventListener('click', function() {
                    imageScale = 1;
                    fitImageToFrame();
                });

                // Fit button
                document.getElementById('fitImage').addEventListener('click', function() {
                    fitImageToFrame();
                });

                // Dragging
                cropperCanvas.addEventListener('mousedown', startDrag);
                cropperCanvas.addEventListener('mousemove', drag);
                cropperCanvas.addEventListener('mouseup', endDrag);
                cropperCanvas.addEventListener('mouseleave', endDrag);

                // Touch events
                cropperCanvas.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    const touch = e.touches[0];
                    const mouseEvent = new MouseEvent('mousedown', {
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    cropperCanvas.dispatchEvent(mouseEvent);
                });

                cropperCanvas.addEventListener('touchmove', function(e) {
                    e.preventDefault();
                    const touch = e.touches[0];
                    const mouseEvent = new MouseEvent('mousemove', {
                        clientX: touch.clientX,
                        clientY: touch.clientY
                    });
                    cropperCanvas.dispatchEvent(mouseEvent);
                });

                cropperCanvas.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    const mouseEvent = new MouseEvent('mouseup', {});
                    cropperCanvas.dispatchEvent(mouseEvent);
                });

                // Apply crop button
                document.getElementById('applyCrop').addEventListener('click', applyCrop);
            }

            // Drag handlers
            function startDrag(e) {
                isDragging = true;
                const rect = cropperCanvas.getBoundingClientRect();
                dragStartX = e.clientX - rect.left - imageX;
                dragStartY = e.clientY - rect.top - imageY;
                cropperCanvas.style.cursor = 'grabbing';
            }

            function drag(e) {
                if (!isDragging) return;
                const rect = cropperCanvas.getBoundingClientRect();
                imageX = e.clientX - rect.left - dragStartX;
                imageY = e.clientY - rect.top - dragStartY;
                drawImage();
                updatePreview();
            }

            function endDrag() {
                isDragging = false;
                cropperCanvas.style.cursor = 'move';
            }

            // Apply crop and submit form
            function applyCrop() {
                const croppedImageData = previewCanvas.toDataURL('image/jpeg', 0.9);
                document.getElementById('og_image_cropped').value = croppedImageData;
                formSubmitted = true;
                isSubmitting  = true;
                isDirty       = false;
                cropperModal.hide();
                document.getElementById('storyForm').submit();
            }

            // Character counters
            document.getElementById('meta_title').addEventListener('input', function() {
                document.getElementById('meta-title-count').textContent = this.value.length;
            });

            document.getElementById('meta_description').addEventListener('input', function() {
                document.getElementById('meta-desc-count').textContent = this.value.length;
            });

            // ── Featured image: instant preview + AJAX upload ──────────────────
            document.getElementById('image').addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                const preview  = document.getElementById('imagePreview');
                const status   = document.getElementById('imageUploadStatus');
                const uploading = document.getElementById('imageStatusUploading');
                const ready    = document.getElementById('imageStatusReady');
                const errorEl  = document.getElementById('imageStatusError');
                const tempInput = document.getElementById('imageTempPath');

                // 1. Show local preview immediately
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded shadow-sm w-100"
                        style="max-height:260px;object-fit:cover" alt="Preview">`;
                };
                reader.readAsDataURL(file);

                // 2. Upload to server
                status.style.display   = '';
                uploading.style.display = '';
                ready.style.display    = 'none';
                errorEl.style.display  = 'none';

                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                fetch('{{ route('admin.stories.upload-temp-image') }}', {
                    method: 'POST',
                    body: formData,
                })
                .then(r => r.json())
                .then(data => {
                    uploading.style.display = 'none';
                    if (data.success) {
                        tempInput.value = data.path;
                        // Swap to server-side URL and add "Saved" badge
                        preview.innerHTML = `
                            <div class="position-relative d-inline-block w-100">
                                <img src="${data.url}" class="img-fluid rounded shadow-sm w-100"
                                     style="max-height:260px;object-fit:cover" alt="Preview">
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                    <i class="bi bi-check-circle me-1"></i>Saved
                                </span>
                            </div>`;
                        ready.style.display = '';
                        // File input no longer required — temp image covers it
                        document.getElementById('image').removeAttribute('required');
                    } else {
                        tempInput.value = '';
                        errorEl.textContent = '⚠ ' + (data.message || 'Upload failed. Try a different image.');
                        errorEl.style.display = '';
                        document.getElementById('image').setAttribute('required', '');
                    }
                })
                .catch(() => {
                    uploading.style.display = 'none';
                    tempInput.value = '';
                    errorEl.textContent = '⚠ Network error — check your connection and try again.';
                    errorEl.style.display = '';
                });
            });

            // Auto-fill meta fields
            document.getElementById('title').addEventListener('blur', function() {
                const metaTitle = document.getElementById('meta_title');
                if (!metaTitle.value) {
                    metaTitle.value = this.value.substring(0, 60);
                    document.getElementById('meta-title-count').textContent = metaTitle.value.length;
                }
            });

            document.getElementById('excerpt').addEventListener('blur', function() {
                const metaDesc = document.getElementById('meta_description');
                if (!metaDesc.value) {
                    metaDesc.value = this.value.substring(0, 160);
                    document.getElementById('meta-desc-count').textContent = metaDesc.value.length;
                }
            });

            // Hashtag management
            const hashtagsArray = [];
            const hashtagInput = document.getElementById('hashtags-input');
            const hashtagsContainer = document.getElementById('hashtags-container');
            const hashtagsHidden = document.getElementById('hashtags');

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
                let hashtag = text.replace(/[^a-zA-Z0-9]/g, '');
                if (!hashtag) return;
                if (hashtagsArray.includes(hashtag.toLowerCase())) return;

                hashtagsArray.push(hashtag.toLowerCase());

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

            // ── Unsaved-changes guard ──────────────────────────────────────────────────
            (function () {
                // Track dirty state on all visible form fields
                document.querySelectorAll(
                    '#storyForm input:not([type=hidden]), #storyForm textarea, #storyForm select'
                ).forEach(function (el) {
                    el.addEventListener('input',  markDirty);
                    el.addEventListener('change', markDirty);
                });

                // Native browser dialog (handles tab close, refresh, address-bar nav)
                window.addEventListener('beforeunload', function (e) {
                    if (isDirty && !isSubmitting) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });

                document.addEventListener('DOMContentLoaded', function () {
                    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));

                    document.getElementById('confirmLeave').addEventListener('click', function () {
                        isSubmitting = true; // prevent beforeunload firing
                        modal.hide();
                        window.location.href = pendingNavUrl;
                    });

                    // Intercept every <a> link when form is dirty
                    document.addEventListener('click', function (e) {
                        if (!isDirty || isSubmitting) return;
                        const link = e.target.closest('a[href]');
                        if (!link) return;
                        const href = link.getAttribute('href');
                        if (!href || href === '#' || href.startsWith('javascript:') || href.startsWith('mailto:')) return;
                        if (link.dataset.bsToggle || link.dataset.bsDismiss) return;
                        e.preventDefault();
                        e.stopPropagation();
                        pendingNavUrl = href;
                        modal.show();
                    }, true); // capture phase — intercepts before Bootstrap
                });
            }());

            document.addEventListener('DOMContentLoaded', function() {
                const imageInput = document.getElementById('image');
                const ogImageInput = document.getElementById('og_image');
                const maxSize = 3 * 1024 * 1024; // 3MB in bytes

                function validateFileSize(input) {
                    if (input.files && input.files[0]) {
                        const file = input.files[0];
                        if (file.size > maxSize) {
                            alert('File size must be less than 3MB. Your file is ' +
                                (file.size / (1024 * 1024)).toFixed(2) + 'MB');
                            input.value = '';
                            return false;
                        }
                    }
                    return true;
                }

                if (imageInput) {
                    imageInput.addEventListener('change', function() {
                        validateFileSize(this);
                    });
                }

                if (ogImageInput) {
                    ogImageInput.addEventListener('change', function() {
                        validateFileSize(this);
                    });
                }

                // Also validate before form submission
                document.getElementById('storyForm').addEventListener('submit', function(e) {
                    let valid = true;

                    if (imageInput && imageInput.files[0] && imageInput.files[0].size > maxSize) {
                        alert('Featured image must be less than 3MB');
                        valid = false;
                    }

                    if (ogImageInput && ogImageInput.files[0] && ogImageInput.files[0].size > maxSize) {
                        alert('OG image must be less than 3MB');
                        valid = false;
                    }

                    if (!valid) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection

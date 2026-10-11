@extends('layouts.admin')

@section('title', 'Edit ' . $partner->name)
@section('page-title', 'Edit Partner')

@section('content')
<div class="partners-edit-container">
    <!-- Header with Back Button -->
    <div class="edit-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('admin.partners.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Partners
                </a>
                <h1 class="page-title">Edit Partner</h1>
                @if($partner->is_featured)
                    <div class="featured-badge">
                        <i class="fas fa-star"></i>
                        Featured Partner
                    </div>
                @endif
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.partners.show', $partner) }}" class="action-btn view">
                    <i class="fas fa-eye"></i>
                    View Partner
                </a>
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

    <!-- Edit Form -->
    <div class="edit-form-container">
        <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data" class="partner-form">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Basic Information Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            Basic Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Partner Name -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                Partner Name <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $partner->name) }}"
                                class="form-control @error('name') error @enderror"
                                placeholder="Enter partner name"
                                required
                            >
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Featured Partner Toggle -->
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input
                                    type="checkbox"
                                    id="is_featured"
                                    name="is_featured"
                                    value="1"
                                    {{ old('is_featured', $partner->is_featured) ? 'checked' : '' }}
                                    class="checkbox-input"
                                >
                                <label for="is_featured" class="checkbox-label">
                                    <div class="checkbox-custom">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="checkbox-text">
                                        <i class="fas fa-star"></i>
                                        Feature this partner
                                    </span>
                                </label>
                            </div>
                            <div class="help-text">
                                Featured partners will be highlighted with a special badge
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="timestamps">
                            <div class="timestamp-item">
                                <label class="timestamp-label">Created</label>
                                <div class="timestamp-value">
                                    {{ $partner->created_at->format('F j, Y \\a\\t g:i A') }}
                                </div>
                            </div>
                            <div class="timestamp-item">
                                <label class="timestamp-label">Last Updated</label>
                                <div class="timestamp-value">
                                    {{ $partner->updated_at->format('F j, Y \\a\\t g:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logo Management Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image"></i>
                            Partner Logo
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Current Logo -->
                        <div class="current-logo-section">
                            <h4 class="section-title">Current Logo</h4>
                            @if($partner->logo)
                                <div class="current-logo">
                                    <img src="{{ Storage::disk('public')->url($partner->logo) }}" alt="{{ $partner->name }}" class="logo-preview">
                                    <div class="logo-info">
                                        <div class="logo-path">
                                            <i class="fas fa-folder"></i>
                                            {{ $partner->logo }}
                                        </div>
                                        <div class="logo-actions">
                                            <a href="{{ Storage::disk('public')->url($partner->logo) }}" target="_blank" class="logo-action view">
                                                <i class="fas fa-external-link-alt"></i>
                                                View Full Size
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="no-logo">
                                    <i class="fas fa-building"></i>
                                    <p>No logo currently set</p>
                                </div>
                            @endif
                        </div>

                        <!-- Logo Update -->
                        <div class="logo-update-section">
                            <h4 class="section-title">Update Logo</h4>

                            <!-- Upload Area -->
                            <div class="upload-area @error('logo') error @enderror" id="uploadArea">
                                <div class="upload-content" id="uploadContent">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <h4>Upload New Logo</h4>
                                    <p>PNG, JPG, GIF, SVG - Max 2MB</p>
                                    <button type="button" class="upload-btn" onclick="document.getElementById('logo').click()">
                                        Choose File
                                    </button>
                                </div>
                                <div class="upload-preview" id="uploadPreview" style="display: none;">
                                    <img id="logoPreview" src="" alt="Logo preview">
                                    <button type="button" class="remove-logo-btn" onclick="removeLogo()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <input
                                    type="file"
                                    id="logo"
                                    name="logo"
                                    accept="image/*"
                                    class="file-input"
                                    onchange="previewLogo(this)"
                                >
                            </div>
                            @error('logo')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            <!-- Remove Logo Option -->
                            @if($partner->logo)
                                <div class="remove-logo-section">
                                    <div class="checkbox-group">
                                        <input
                                            type="checkbox"
                                            id="remove_logo"
                                            name="remove_logo"
                                            value="1"
                                            class="checkbox-input"
                                        >
                                        <label for="remove_logo" class="checkbox-label">
                                            <div class="checkbox-custom">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <span class="checkbox-text">
                                                <i class="fas fa-trash"></i>
                                                Remove current logo
                                            </span>
                                        </label>
                                    </div>
                                    <div class="help-text">
                                        Check this to remove the current logo without uploading a new one
                                    </div>
                                </div>
                            @endif

                            <!-- Logo Requirements -->
                            <div class="requirements">
                                <h5>Logo Requirements:</h5>
                                <ul>
                                    <li>Recommended size: 300x200 pixels</li>
                                    <li>Format: PNG, JPG, GIF, or SVG</li>
                                    <li>Maximum file size: 2MB</li>
                                    <li>Transparent background preferred</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="action-left">
                    <a href="{{ route('admin.partners.show', $partner) }}" class="cancel-btn">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="button" class="reset-btn" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        Reset Changes
                    </button>
                </div>
                <div class="action-right">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i>
                        Update Partner
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Base Styles */
.partners-edit-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.edit-header {
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
    border-color: #138496;
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

/* Form Container */
.edit-form-container {
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
}

.form-control:focus {
    outline: none;
    border-color: #3498db;
}

.form-control.error {
    border-color: #dc3545;
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

.help-text {
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
    margin-left: 32px;
}

/* Timestamp Styles */
.timestamps {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 15px;
}

.timestamp-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.timestamp-item:last-child {
    margin-bottom: 0;
}

.timestamp-label {
    font-weight: 500;
    color: #6c757d;
    font-size: 14px;
}

.timestamp-value {
    color: #333;
    font-size: 14px;
    font-weight: 500;
}

/* Current Logo Styles */
.section-title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0 0 12px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #e0e0e0;
}

.current-logo {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    margin-bottom: 20px;
}

.logo-preview {
    width: 80px;
    height: 60px;
    object-fit: contain;
    flex-shrink: 0;
}

.logo-info {
    flex: 1;
}

.logo-path {
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.logo-actions {
    display: flex;
    gap: 10px;
}

.logo-action {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #3498db;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
}

.logo-action:hover {
    text-decoration: underline;
}

.no-logo {
    text-align: center;
    padding: 30px;
    color: #6c757d;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    margin-bottom: 20px;
}

.no-logo i {
    font-size: 32px;
    margin-bottom: 8px;
    color: #dee2e6;
}

.no-logo p {
    margin: 0;
}

/* Upload Area Styles */
.upload-area {
    border: 2px dashed #e0e0e0;
    border-radius: 6px;
    padding: 25px;
    text-align: center;
    transition: all 0.2s;
    background: white;
    margin-bottom: 15px;
}

.upload-area:hover {
    border-color: #3498db;
}

.upload-area.error {
    border-color: #dc3545;
}

.upload-area.dragover {
    border-color: #3498db;
    background-color: #f8f9fa;
}

.upload-content i {
    font-size: 40px;
    color: #6c757d;
    margin-bottom: 10px;
}

.upload-content h4 {
    margin: 0 0 8px 0;
    color: #333;
    font-size: 16px;
}

.upload-content p {
    margin: 0 0 15px 0;
    color: #6c757d;
    font-size: 14px;
}

.upload-btn {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.2s;
}

.upload-btn:hover {
    background-color: #2980b9;
}

.file-input {
    display: none;
}

.upload-preview {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.upload-preview img {
    max-width: 200px;
    max-height: 120px;
    object-fit: contain;
}

.remove-logo-btn {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 24px;
    height: 24px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
}

/* Remove Logo Section */
.remove-logo-section {
    background-color: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 15px;
}

/* Requirements Styles */
.requirements {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 15px;
}

.requirements h5 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 14px;
    font-weight: 600;
}

.requirements ul {
    margin: 0;
    padding-left: 20px;
    color: #6c757d;
    font-size: 12px;
    line-height: 1.5;
}

.requirements li {
    margin-bottom: 4px;
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
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.action-left {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.action-right {
    display: flex;
    gap: 12px;
}

.cancel-btn, .reset-btn, .submit-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.cancel-btn {
    background-color: white;
    color: #6c757d;
    border: 1px solid #e0e0e0;
}

.cancel-btn:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.reset-btn {
    background-color: #6c757d;
    color: white;
    border: 1px solid #6c757d;
}

.reset-btn:hover {
    background-color: #5a6268;
    border-color: #5a6268;
}

.submit-btn {
    background-color: #28a745;
    color: white;
    border: 1px solid #28a745;
}

.submit-btn:hover {
    background-color: #218838;
    border-color: #218838;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .partners-edit-container {
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

    .form-grid {
        padding: 15px;
    }

    .current-logo {
        flex-direction: column;
        text-align: center;
    }

    .upload-area {
        padding: 20px;
    }

    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .action-left, .action-right {
        width: 100%;
    }

    .cancel-btn, .reset-btn, .submit-btn {
        flex: 1;
        justify-content: center;
    }
}
</style>

<script>
// Store original form values
const originalFormData = {
    name: "{{ old('name', $partner->name) }}",
    is_featured: {{ old('is_featured', $partner->is_featured) ? 'true' : 'false' }},
    remove_logo: false
};

function previewLogo(input) {
    const uploadArea = document.getElementById('uploadArea');
    const uploadContent = document.getElementById('uploadContent');
    const uploadPreview = document.getElementById('uploadPreview');
    const logoPreview = document.getElementById('logoPreview');
    const removeLogoCheckbox = document.getElementById('remove_logo');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            logoPreview.src = e.target.result;
            uploadContent.style.display = 'none';
            uploadPreview.style.display = 'flex';

            // Uncheck remove logo if uploading new logo
            if (removeLogoCheckbox) {
                removeLogoCheckbox.checked = false;
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo() {
    const input = document.getElementById('logo');
    const uploadContent = document.getElementById('uploadContent');
    const uploadPreview = document.getElementById('uploadPreview');
    const removeLogoCheckbox = document.getElementById('remove_logo');

    input.value = '';
    uploadContent.style.display = 'block';
    uploadPreview.style.display = 'none';

    // Uncheck remove logo if clearing new upload
    if (removeLogoCheckbox) {
        removeLogoCheckbox.checked = false;
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        // Reset form fields
        document.getElementById('name').value = originalFormData.name;
        document.getElementById('is_featured').checked = originalFormData.is_featured;

        // Reset remove logo checkbox
        const removeLogoCheckbox = document.getElementById('remove_logo');
        if (removeLogoCheckbox) {
            removeLogoCheckbox.checked = originalFormData.remove_logo;
        }

        // Reset logo upload
        removeLogo();

        // Clear any validation errors
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(error => error.remove());

        const errorInputs = document.querySelectorAll('.error');
        errorInputs.forEach(input => input.classList.remove('error'));
    }
}

// Drag and drop functionality
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('logo');

if (uploadArea && fileInput) {
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            previewLogo(fileInput);
        }
    });
}

// Handle remove logo checkbox change
const removeLogoCheckbox = document.getElementById('remove_logo');
if (removeLogoCheckbox) {
    removeLogoCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Clear any new logo upload if removing current logo
            removeLogo();
        }
    });
}
</script>


@endsection

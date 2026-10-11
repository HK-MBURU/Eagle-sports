@extends('layouts.admin')

@section('title', 'Add New Partner')
@section('page-title', 'Add New Partner')

@section('content')
<div class="partners-create-container">
    <!-- Header with Back Button -->
    <div class="create-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('admin.partners.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Partners
                </a>
                <h1 class="page-title">Add New Partner</h1>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="create-form-container">
        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="partner-form">
            @csrf

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
                                value="{{ old('name') }}"
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
                                    {{ old('is_featured') ? 'checked' : '' }}
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
                    </div>
                </div>

                <!-- Logo Upload Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image"></i>
                            Partner Logo
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Logo Upload Area -->
                        <div class="upload-area @error('logo') error @enderror" id="uploadArea">
                            <div class="upload-content" id="uploadContent">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <h4>Upload Logo</h4>
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

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.partners.index') }}" class="cancel-btn">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-plus"></i>
                    Create Partner
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Base Styles */
.partners-create-container {
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

/* Upload Area Styles */
.upload-area {
    border: 2px dashed #e0e0e0;
    border-radius: 6px;
    padding: 30px;
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
    font-size: 48px;
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
    .partners-create-container {
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

    .upload-area {
        padding: 20px;
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
function previewLogo(input) {
    const uploadArea = document.getElementById('uploadArea');
    const uploadContent = document.getElementById('uploadContent');
    const uploadPreview = document.getElementById('uploadPreview');
    const logoPreview = document.getElementById('logoPreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            logoPreview.src = e.target.result;
            uploadContent.style.display = 'none';
            uploadPreview.style.display = 'flex';
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo() {
    const input = document.getElementById('logo');
    const uploadContent = document.getElementById('uploadContent');
    const uploadPreview = document.getElementById('uploadPreview');

    input.value = '';
    uploadContent.style.display = 'block';
    uploadPreview.style.display = 'none';
}

// Drag and drop functionality
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('logo');

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
</script>


@endsection

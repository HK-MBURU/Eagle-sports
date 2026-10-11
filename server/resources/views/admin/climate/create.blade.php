@extends('layouts.admin')

@section('title', 'Create Climate Content')
@section('page-title', 'Create New Climate Content')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('admin.climate.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <!-- Form Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Climate Content Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.climate.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file"
                                       class="form-control @error('image') is-invalid @enderror"
                                       id="image"
                                       name="image"
                                       accept="image/*">
                                <small class="text-muted">Accepted formats: JPG, PNG, WEBP. Max size: 5MB</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="imagePreview" class="mt-2"></div>
                            </div>

                            <!-- Statistics Section -->
                            <div class="col-md-12 mb-3">
                                <h6 class="border-bottom pb-2">Statistics</h6>
                            </div>

                            <!-- Statistic 1 -->
                            <div class="col-md-6 mb-3">
                                <label for="stat_1_value" class="form-label">Statistic 1 Value <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('stat_1_value') is-invalid @enderror"
                                       id="stat_1_value"
                                       name="stat_1_value"
                                       value="{{ old('stat_1_value') }}"
                                       placeholder="e.g., +2.5°C"
                                       required>
                                @error('stat_1_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stat_1_label" class="form-label">Statistic 1 Label <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('stat_1_label') is-invalid @enderror"
                                       id="stat_1_label"
                                       name="stat_1_label"
                                       value="{{ old('stat_1_label') }}"
                                       placeholder="e.g., Temp increase in Rift Valley"
                                       required>
                                @error('stat_1_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statistic 2 -->
                            <div class="col-md-6 mb-3">
                                <label for="stat_2_value" class="form-label">Statistic 2 Value <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('stat_2_value') is-invalid @enderror"
                                       id="stat_2_value"
                                       name="stat_2_value"
                                       value="{{ old('stat_2_value') }}"
                                       placeholder="e.g., -30%"
                                       required>
                                @error('stat_2_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stat_2_label" class="form-label">Statistic 2 Label <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('stat_2_label') is-invalid @enderror"
                                       id="stat_2_label"
                                       name="stat_2_label"
                                       value="{{ old('stat_2_label') }}"
                                       placeholder="e.g., Rainfall variability"
                                       required>
                                @error('stat_2_label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Call to Action Section -->
                            <div class="col-md-12 mb-3">
                                <h6 class="border-bottom pb-2">Call to Action</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cta_text" class="form-label">CTA Button Text</label>
                                <input type="text"
                                       class="form-control @error('cta_text') is-invalid @enderror"
                                       id="cta_text"
                                       name="cta_text"
                                       value="{{ old('cta_text', 'Learn More About Our Climate Work') }}">
                                @error('cta_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cta_url" class="form-label">CTA Button URL</label>
                                <input type="text"
                                       class="form-control @error('cta_url') is-invalid @enderror"
                                       id="cta_url"
                                       name="cta_url"
                                       value="{{ old('cta_url', '/impact/climate') }}">
                                @error('cta_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Display Settings -->
                            <div class="col-md-12 mb-3">
                                <h6 class="border-bottom pb-2">Display Settings</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="display_order" class="form-label">Display Order</label>
                                <input type="number"
                                       class="form-control @error('display_order') is-invalid @enderror"
                                       id="display_order"
                                       name="display_order"
                                       value="{{ old('display_order', 0) }}"
                                       min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Climate Content
                                </button>
                                <a href="{{ route('admin.climate.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image Preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').innerHTML =
                    `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 300px;">`;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection

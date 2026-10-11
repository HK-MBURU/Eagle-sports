@extends('layouts.admin')

@section('title', 'Add New Race Event')
@section('page-title', 'Add New Race Event')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Events
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-md-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create New Race Event</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">
                                    Event Title <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}"
                                    placeholder="e.g., Nairobi Marathon 2025"
                                    required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="expected_runners" class="form-label">
                                    Expected Runners
                                </label>
                                <input
                                    type="text"
                                    name="expected_runners"
                                    id="expected_runners"
                                    class="form-control @error('expected_runners') is-invalid @enderror"
                                    value="{{ old('expected_runners') }}"
                                    placeholder="e.g., 15,000+ runners">
                                @error('expected_runners')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="event_date" class="form-label">
                                    Event Date <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    name="event_date"
                                    id="event_date"
                                    class="form-control @error('event_date') is-invalid @enderror"
                                    value="{{ old('event_date') }}"
                                    required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="start_time" class="form-label">
                                    Start Time <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="time"
                                    name="start_time"
                                    id="start_time"
                                    class="form-control @error('start_time') is-invalid @enderror"
                                    value="{{ old('start_time', '06:00') }}"
                                    required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">24-hour format</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="location" class="form-label">
                                    Location <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="location"
                                    id="location"
                                    class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}"
                                    placeholder="e.g., Uhuru Gardens, Nairobi"
                                    required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="distance_options" class="form-label">
                                Distance Options <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="distance_options"
                                id="distance_options"
                                class="form-control @error('distance_options') is-invalid @enderror"
                                value="{{ old('distance_options') }}"
                                placeholder="e.g., 42.2km, 21.1km, 10km, 5km"
                                required>
                            @error('distance_options')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Separate multiple distances with commas
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">
                                Event Image/Banner
                            </label>
                            <input
                                type="file"
                                name="image"
                                id="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg,image/gif">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB
                            </small>
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Event Description
                            </label>
                            <textarea
                                name="description"
                                id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="5"
                                placeholder="Provide details about the race event, route, amenities, etc.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="registration_link" class="form-label">
                                Registration Link
                            </label>
                            <input
                                type="url"
                                name="registration_link"
                                id="registration_link"
                                class="form-control @error('registration_link') is-invalid @enderror"
                                value="{{ old('registration_link') }}"
                                placeholder="https://example.com/register">
                            @error('registration_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Full URL to the registration page
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input
                                        type="checkbox"
                                        name="is_featured"
                                        id="is_featured"
                                        class="form-check-input"
                                        value="1"
                                        {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning"></i> Featured Event
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Featured events appear prominently on the website
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        id="is_active"
                                        class="form-check-input"
                                        value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle text-success"></i> Active
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Only active events are visible to the public
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Race Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection

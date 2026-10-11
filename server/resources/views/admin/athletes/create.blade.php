@extends('layouts.admin')

@section('title', 'Add Athlete')
@section('page-title', 'Add New Athlete')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.athletes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Athlete Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="e.g., Eliud Kipchoge"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title & Badge (2 columns) -->
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Title/Achievement *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="e.g., Marathon World Record Holder"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="badge" class="form-label">Badge (Optional)</label>
                            <input type="text" 
                                   class="form-control @error('badge') is-invalid @enderror" 
                                   id="badge" 
                                   name="badge" 
                                   value="{{ old('badge') }}" 
                                   placeholder="e.g., Legend">
                            @error('badge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Short Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="2" 
                                  placeholder="e.g., Climate Advocate & Foundation Founder"
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label for="bio" class="form-label">Full Biography *</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" 
                                  name="bio" 
                                  rows="6" 
                                  placeholder="Write the full story and achievements..."
                                  required>{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Profile Image *</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*" 
                               required>
                        <small class="text-muted">Recommended: 800x1000px (JPG, PNG)</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Badge Color, Display Order, Status (3 columns) -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="badge_color" class="form-label">Badge Color</label>
                            <input type="color" 
                                   class="form-control form-control-color" 
                                   id="badge_color" 
                                   name="badge_color" 
                                   value="{{ old('badge_color', '#FFBF00') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="display_order" 
                                   name="display_order" 
                                   value="{{ old('display_order', 0) }}" 
                                   min="0">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Featured Checkbox -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_featured" 
                                   name="is_featured" 
                                   value="1" 
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <strong>Mark as Featured</strong>
                                <small class="text-muted d-block">Featured athletes appear on the homepage</small>
                            </label>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Create Athlete
                        </button>
                        <a href="{{ route('admin.athletes.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
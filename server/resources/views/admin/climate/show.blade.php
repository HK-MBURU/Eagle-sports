@extends('layouts.admin')

@section('title', 'View Climate Content')
@section('page-title', 'Climate Content Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Action Buttons -->
            <div class="mb-3">
                <a href="{{ route('admin.climate.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('admin.climate.edit', $climate) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.climate.duplicate', $climate) }}"
                      method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-copy"></i> Duplicate
                    </button>
                </form>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete
                </button>

                <!-- Hidden delete form -->
                <form id="delete-form"
                      action="{{ route('admin.climate.destroy', $climate) }}"
                      method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            <!-- Content Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Climate Content Information</h5>
                    <div>
                        @if($climate->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Image -->
                        @if($climate->image_url)
                            <div class="col-md-12 mb-4">
                                <h6 class="text-muted mb-2">Featured Image</h6>
                                <img src="{{ $climate->image_url }}"
                                     alt="{{ $climate->title }}"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-width: 600px;">
                            </div>
                        @endif

                        <!-- Title -->
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted mb-2">Title</h6>
                            <h4>{{ $climate->title }}</h4>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p class="lead">{{ $climate->description }}</p>
                        </div>

                        <!-- Statistics Section -->
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted mb-3">Statistics</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Statistic 1</h6>
                                    <h3 class="text-warning mb-1">{{ $climate->stat_1_value }}</h3>
                                    <p class="mb-0">{{ $climate->stat_1_label }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Statistic 2</h6>
                                    <h3 class="text-info mb-1">{{ $climate->stat_2_value }}</h3>
                                    <p class="mb-0">{{ $climate->stat_2_label }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Call to Action Section -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="text-muted mb-3">Call to Action</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Button Text</h6>
                            <p>{{ $climate->cta_text }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Button URL</h6>
                            <p>
                                <a href="{{ $climate->cta_url }}" target="_blank">
                                    {{ $climate->cta_url }}
                                    <i class="fas fa-external-link-alt fa-xs"></i>
                                </a>
                            </p>
                        </div>

                        <!-- Display Settings -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="text-muted mb-3">Display Settings</h6>
                        </div>

                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-2">Display Order</h6>
                            <p><span class="badge bg-secondary">{{ $climate->display_order }}</span></p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-2">Status</h6>
                            <p>
                                @if($climate->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-2">Created</h6>
                            <p>{{ $climate->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        <!-- Metadata -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h6 class="text-muted mb-3">Metadata</h6>
                        </div>

                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-2">ID</h6>
                            <p>{{ $climate->id }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-2">Created At</h6>
                            <p>{{ $climate->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-2">Last Updated</h6>
                            <p>{{ $climate->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Frontend Preview</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row align-items-center">
                        @if($climate->image_url)
                            <div class="col-md-6 mb-3 mb-md-0">
                                <img src="{{ $climate->image_url }}"
                                     alt="{{ $climate->title }}"
                                     class="img-fluid rounded">
                            </div>
                        @endif
                        <div class="col-md-6">
                            <h3 class="mb-3">{{ $climate->title }}</h3>
                            <p class="mb-4">{{ $climate->description }}</p>

                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="bg-white p-3 rounded">
                                        <h4 class="text-warning mb-0">{{ $climate->stat_1_value }}</h4>
                                        <small class="text-muted">{{ $climate->stat_1_label }}</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white p-3 rounded">
                                        <h4 class="text-info mb-0">{{ $climate->stat_2_value }}</h4>
                                        <small class="text-muted">{{ $climate->stat_2_label }}</small>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ $climate->cta_url }}" class="btn btn-success">
                                {{ $climate->cta_text }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this climate content?')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush
@endsection

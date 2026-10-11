@extends('layouts.admin')

@section('title', 'Climate Content')
@section('page-title', 'Climate Content Management')

@section('content')
    <div class="container-fluid">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0">Climate Impact Content</h4>
                <p class="text-muted mb-0">Manage climate change and environmental impact content</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.climate.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Content
                </a>
                <a href="{{ route('admin.climate.export') }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Export
                </a>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4 border">
            <div class="card-body">
                <form action="{{ route('admin.climate.search') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by title or description..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('admin.climate.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if ($contents->count() > 0)
            <!-- Bulk Actions Bar -->
            <form id="bulkDeleteForm" action="{{ route('admin.climate.bulkDelete') }}" method="POST">
                @csrf
                <div class="card mb-4 border">
                    <div class="card-body py-2">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" id="selectAll" class="form-check-input me-2">
                            <label for="selectAll" class="form-check-label me-3">Select All</label>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmBulkDelete()" disabled
                                id="bulkDeleteBtn">
                                <i class="fas fa-trash"></i> Delete Selected
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Content Cards Grid -->
                <div class="row g-4">
                    @foreach ($contents as $content)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border h-100">
                                <!-- Checkbox -->
                                <div class="position-absolute top-0 start-0 m-3">
                                    <input type="checkbox" name="ids[]" value="{{ $content->id }}"
                                        class="form-check-input content-checkbox" style="width: 20px; height: 20px;">
                                </div>

                                <!-- Status Badge -->
                                <div class="position-absolute top-0 end-0 m-3">
                                    @if ($content->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>

                                <!-- Image -->
                                <div style="height: 200px; overflow: hidden; background-color: #f8f9fa;">
                                    @if ($content->image_url)
                                        <img src="{{ $content->image_url }}" alt="{{ $content->title }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">
                                    <!-- Display Order Badge -->
                                    <div class="mb-2">
                                        <span class="badge bg-secondary">Order: {{ $content->display_order }}</span>
                                    </div>

                                    <!-- Title -->
                                    <h5 class="card-title mb-2">{{ $content->title }}</h5>

                                    <!-- Description -->
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($content->description, 100) }}
                                    </p>

                                    <!-- Statistics -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="border p-2 text-center">
                                                <div class="fw-bold text-warning">{{ $content->stat_1_value }}</div>
                                                <small class="text-muted">{{ Str::limit($content->stat_1_label, 25) }}</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border p-2 text-center">
                                                <div class="fw-bold text-info">{{ $content->stat_2_value }}</div>
                                                <small class="text-muted">{{ Str::limit($content->stat_2_label, 25) }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CTA Info -->
                                    <div class="border-top pt-3 mb-3">
                                        <small class="text-muted d-block">CTA: {{ $content->cta_text }}</small>
                                        <small class="text-muted d-block">URL: {{ Str::limit($content->cta_url, 30) }}</small>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.climate.show', $content) }}"
                                                class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.climate.edit', $content) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="confirmDelete({{ $content->id }})">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Secondary Actions -->
                                    <div class="d-flex gap-2 mt-2">
                                        <form action="{{ route('admin.climate.duplicate', $content) }}" method="POST"
                                            class="flex-fill">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                                <i class="fas fa-copy"></i> Duplicate
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.climate.toggleActive', $content) }}" method="POST"
                                            class="flex-fill">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-dark btn-sm w-100">
                                                <i class="fas fa-toggle-on"></i> Toggle
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Hidden delete form -->
                                <form id="delete-form-{{ $content->id }}"
                                    action="{{ route('admin.climate.destroy', $content) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $contents->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="card border">
                <div class="card-body text-center py-5">
                    <i class="fas fa-leaf fa-4x text-muted mb-4"></i>
                    <h5 class="mb-3">No Climate Content Found</h5>
                    <p class="text-muted mb-4">Start by creating your first climate impact content.</p>
                    <a href="{{ route('admin.climate.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Content
                    </a>
                </div>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            /* Remove all transitions and animations */
            * {
                transition: none !important;
                animation: none !important;
            }

            /* Card styling - flat design */
            .card {
                border: 1px solid #dee2e6;
                border-radius: 0;
                box-shadow: none;
                background-color: #ffffff;
            }

            .card:hover {
                box-shadow: none;
                transform: none;
            }

            /* Button styling - flat */
            .btn {
                border-radius: 0;
                transition: none;
            }

            .btn:hover {
                transform: none;
            }

            /* Form controls - flat */
            .form-control,
            .form-select {
                border-radius: 0;
                transition: none;
            }

            /* Badge styling - flat */
            .badge {
                border-radius: 0;
                font-weight: 600;
                padding: 0.35em 0.65em;
            }

            /* Image container */
            .card img {
                transition: none;
            }

            /* Remove hover effects */
            a:hover,
            button:hover {
                text-decoration: none;
            }

            /* Alert styling */
            .alert {
                border-radius: 0;
                border-left: 4px solid;
            }

            .alert-success {
                border-left-color: #28a745;
            }

            .alert-danger {
                border-left-color: #dc3545;
            }

            /* Pagination */
            .pagination {
                border-radius: 0;
            }

            .page-link {
                border-radius: 0;
                transition: none;
            }

            /* Card body spacing */
            .card-body {
                padding: 1.25rem;
            }

            /* Grid gap */
            .g-4 {
                --bs-gutter-x: 1.5rem;
                --bs-gutter-y: 1.5rem;
            }

            /* Ensure equal height cards */
            .h-100 {
                height: 100%;
            }

            /* Statistics box */
            .border {
                border: 1px solid #dee2e6 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Select All Checkbox
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.content-checkbox');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                toggleBulkDeleteBtn();
            });

            // Individual checkbox change
            document.querySelectorAll('.content-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkDeleteBtn);
            });

            function toggleBulkDeleteBtn() {
                const checkedBoxes = document.querySelectorAll('.content-checkbox:checked');
                document.getElementById('bulkDeleteBtn').disabled = checkedBoxes.length === 0;
            }

            // Confirm single delete
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this climate content?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }

            // Confirm bulk delete
            function confirmBulkDelete() {
                const checkedBoxes = document.querySelectorAll('.content-checkbox:checked');
                if (confirm(`Are you sure you want to delete ${checkedBoxes.length} item(s)?`)) {
                    document.getElementById('bulkDeleteForm').submit();
                }
            }
        </script>
    @endpush
@endsection

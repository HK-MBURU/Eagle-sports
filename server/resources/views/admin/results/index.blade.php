@extends('layouts.admin')

@section('title', 'Race Results')
@section('page-title', 'Race Results')

@section('content')
<div class="results-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Race Results</h1>
                <p class="page-subtitle">Manage all race results and documents</p>
            </div>
            <a href="{{ route('admin.results.create') }}" class="primary-btn">
                <i class="fas fa-plus"></i>
                Upload New Result
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-number">{{ $results->total() }}</div>
                <div class="stat-label">Total Results</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-number">{{ $results->where('is_published', true)->count() }}</div>
                <div class="stat-label">Published</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-number">{{ $results->where('is_featured', true)->count() }}</div>
                <div class="stat-label">Featured</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-number">{{ $results->sum('downloads_count') }}</div>
                <div class="stat-label">Total Downloads</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-download"></i>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="filters-card">
        <div class="filters-header">
            <h3>Filters & Search</h3>
        </div>
        <div class="filters-body">
            <form action="{{ route('admin.results.search') }}" method="GET" class="filters-form">
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by title, location..." value="{{ request('search') }}">
                    </div>
                    <div class="form-group">
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            <option value="Marathon (42.195 km)" {{ request('category') == 'Marathon (42.195 km)' ? 'selected' : '' }}>Marathon</option>
                            <option value="Half Marathon (21.097 km)" {{ request('category') == 'Half Marathon (21.097 km)' ? 'selected' : '' }}>Half Marathon</option>
                            <option value="10K Run" {{ request('category') == '10K Run' ? 'selected' : '' }}>10K Run</option>
                            <option value="5K Run" {{ request('category') == '5K Run' ? 'selected' : '' }}>5K Run</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                            Search
                        </button>
                        <a href="{{ route('admin.results.index') }}" class="reset-btn">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Grid -->
    <div class="results-section">
        <div class="section-header">
            <h2>Race Results ({{ $results->total() }})</h2>
        </div>

        @if($results->count() > 0)
            <div class="results-grid">
                @foreach($results as $result)
                <div class="result-card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="file-type">
                            @if($result->isImage())
                                <i class="fas fa-image"></i>
                            @elseif($result->isPdf())
                                <i class="fas fa-file-pdf"></i>
                            @else
                                <i class="fas fa-file-alt"></i>
                            @endif
                        </div>
                        <div class="card-actions">
                            <input type="checkbox" class="result-checkbox" value="{{ $result->id }}">
                            <div class="status-badge {{ $result->is_published ? 'published' : 'draft' }}">
                                {{ $result->is_published ? 'Published' : 'Draft' }}
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <h3 class="result-title">
                            <a href="{{ route('admin.results.show', $result->id) }}">{{ $result->title }}</a>
                        </h3>

                        <div class="result-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $result->location ?? 'No location' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $result->formatted_date ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="category-tag">
                            {{ $result->category }}
                        </div>

                        @if($result->is_featured)
                        <div class="featured-badge">
                            <i class="fas fa-star"></i>
                            Featured
                        </div>
                        @endif

                        <div class="file-info">
                            <span class="file-type-text">{{ $result->file_type }}</span>
                            <span class="file-size">{{ $result->formatted_file_size }}</span>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer">
                        <div class="result-stats">
                            <div class="stat">
                                <i class="fas fa-eye"></i>
                                <span>{{ $result->views_count }}</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-download"></i>
                                <span>{{ $result->downloads_count }}</span>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('admin.results.show', $result->id) }}" class="action-btn view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.results.download', $result->id) }}" class="action-btn download" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="{{ route('admin.results.edit', $result->id) }}" class="action-btn edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.results.destroy', $result->id) }}" method="POST" class="action-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this result?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} results
                </div>
                <div class="pagination-links">
                    {{ $results->links() }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3>No Race Results Found</h3>
                <p>Start by uploading your first race result</p>
                <a href="{{ route('admin.results.create') }}" class="primary-btn">
                    <i class="fas fa-plus"></i>
                    Upload Result
                </a>
            </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions-bar" id="bulkActionsBar">
        <div class="bulk-content">
            <div class="bulk-info">
                <span id="selectedCount">0</span> items selected
            </div>
            <div class="bulk-actions">
                <button type="button" class="danger-btn" id="bulkDeleteBtn">
                    <i class="fas fa-trash"></i>
                    Delete Selected
                </button>
                <button type="button" class="secondary-btn" id="clearSelection">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Base Styles */
.results-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.page-header {
    margin-bottom: 30px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.header-text {
    flex: 1;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
}

.page-subtitle {
    font-size: 16px;
    color: #6c757d;
    margin: 0;
}

.primary-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: background-color 0.2s;
    border: 1px solid #3498db;
}

.primary-btn:hover {
    background-color: #2980b9;
    color: white;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    display: flex;
    align-items: center;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-card:nth-child(1) .stat-icon { background-color: #3498db; }
.stat-card:nth-child(2) .stat-icon { background-color: #28a745; }
.stat-card:nth-child(3) .stat-icon { background-color: #ffc107; }
.stat-card:nth-child(4) .stat-icon { background-color: #17a2b8; }

/* Filters Card */
.filters-card {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 30px;
    overflow: hidden;
}

.filters-header {
    background-color: #f8f9fa;
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
}

.filters-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.filters-body {
    padding: 20px;
}

.filters-form {
    width: 100%;
}

.form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 15px;
    align-items: end;
}

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-control {
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #3498db;
}

.form-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
}

.search-btn:hover {
    background-color: #2980b9;
}

.reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
}

.reset-btn:hover {
    background-color: #e9ecef;
    color: #495057;
}

/* Results Section */
.results-section {
    margin-bottom: 30px;
}

.section-header {
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

/* Results Grid */
.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.result-card {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: border-color 0.2s;
}

.result-card:hover {
    border-color: #3498db;
}

/* Card Header */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.file-type {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    background-color: #3498db;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.file-type i.fa-file-pdf { background-color: #dc3545; }
.file-type i.fa-image { background-color: #28a745; }
.file-type i.fa-file-alt { background-color: #6c757d; }

.card-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
}

.status-badge.published {
    background-color: #d4edda;
    color: #155724;
}

.status-badge.draft {
    background-color: #e2e3e5;
    color: #383d41;
}

/* Card Body */
.card-body {
    padding: 20px;
}

.result-title {
    margin: 0 0 12px 0;
}

.result-title a {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    text-decoration: none;
    line-height: 1.4;
}

.result-title a:hover {
    color: #3498db;
}

.result-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 12px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 14px;
}

.meta-item i {
    width: 14px;
    text-align: center;
}

.category-tag {
    display: inline-block;
    padding: 4px 8px;
    background-color: #e3f2fd;
    color: #1976d2;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 12px;
}

.featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background-color: #fff3cd;
    color: #856404;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
    margin-bottom: 12px;
}

.file-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-top: 1px solid #f8f9fa;
}

.file-type-text {
    font-size: 12px;
    color: #6c757d;
}

.file-size {
    font-size: 12px;
    color: #495057;
    font-weight: 500;
}

/* Card Footer */
.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background-color: #f8f9fa;
    border-top: 1px solid #e0e0e0;
}

.result-stats {
    display: flex;
    gap: 15px;
}

.stat {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #6c757d;
    font-size: 13px;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    background-color: white;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
}

.action-btn:hover {
    background-color: #f8f9fa;
}

.action-btn.view:hover {
    border-color: #3498db;
    color: #3498db;
}

.action-btn.download:hover {
    border-color: #28a745;
    color: #28a745;
}

.action-btn.edit:hover {
    border-color: #ffc107;
    color: #ffc107;
}

.action-btn.delete:hover {
    border-color: #dc3545;
    color: #dc3545;
}

.action-form {
    margin: 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.empty-icon {
    font-size: 64px;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 20px;
    color: #495057;
    margin: 0 0 8px 0;
}

.empty-state p {
    color: #6c757d;
    margin: 0 0 20px 0;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    border-top: 1px solid #e0e0e0;
}

.pagination-info {
    color: #6c757d;
    font-size: 14px;
}

.pagination-links {
    display: flex;
    gap: 5px;
}

/* Bulk Actions */
.bulk-actions-bar {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: white;
    border-radius: 8px;
    padding: 16px 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: none;
    z-index: 1000;
    min-width: 400px;
}

.bulk-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.bulk-info {
    font-weight: 500;
}

.bulk-actions {
    display: flex;
    gap: 10px;
}

.danger-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
}

.danger-btn:hover {
    background-color: #c82333;
}

.secondary-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
}

.secondary-btn:hover {
    background-color: #5a6268;
}

/* Responsive Design */
@media (max-width: 768px) {
    .results-container {
        padding: 15px;
    }

    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .results-grid {
        grid-template-columns: 1fr;
    }

    .card-footer {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .action-buttons {
        justify-content: center;
    }

    .bulk-actions-bar {
        min-width: 90%;
        left: 5%;
        transform: none;
    }

    .bulk-content {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .search-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkboxes
    const selectAllCheckbox = document.getElementById('select-all');
    const resultCheckboxes = document.querySelectorAll('.result-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const clearSelection = document.getElementById('clearSelection');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    // Update bulk actions bar
    function updateBulkActionsBar() {
        const checkedBoxes = document.querySelectorAll('.result-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkActionsBar.style.display = 'block';
            selectedCount.textContent = checkedBoxes.length;
        } else {
            bulkActionsBar.style.display = 'none';
        }
    }

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            resultCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionsBar();
        });
    }

    // Individual checkbox changes
    resultCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });

    // Clear selection
    if (clearSelection) {
        clearSelection.addEventListener('click', function() {
            resultCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
            updateBulkActionsBar();
        });
    }

    // Bulk delete
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.result-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (confirm(`Are you sure you want to delete ${ids.length} result(s)?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.results.bulk-delete") }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
</script>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

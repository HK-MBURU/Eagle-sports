@extends('layouts.admin')

@section('title', 'Partners Management')
@section('page-title', 'All Partners')

@section('content')
<div class="partners-container">
    <!-- Header with Add Button -->
    <div class="partners-header">
        <div class="header-content">
            <h1 class="page-title">Partners Management</h1>
            <a href="{{ route('admin.partners.create') }}" class="add-partner-btn">
                <i class="fas fa-plus"></i>
                Add New Partner
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Partners Grid -->
    <div class="partners-grid">
        @forelse($partners as $partner)
            <div class="partner-card">
                <!-- Partner Logo -->
                <div class="partner-logo">
                    @if($partner->logo)
                        <img src="{{ Storage::disk('public')->url($partner->logo) }}" alt="{{ $partner->name }}">
                    @else
                        <div class="no-logo">
                            <i class="fas fa-building"></i>
                            <span>No Logo</span>
                        </div>
                    @endif
                </div>

                <!-- Partner Details -->
                <div class="partner-details">
                    <h3 class="partner-name">{{ $partner->name }}</h3>

                    <div class="partner-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Added by: {{ $partner->addedBy->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>Created: {{ $partner->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Featured Badge -->
                    @if($partner->is_featured)
                        <div class="featured-badge">
                            <i class="fas fa-star"></i>
                            Featured
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="partner-actions">
                    <a href="{{ route('admin.partners.show', $partner) }}" class="btn-action view" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.partners.edit', $partner) }}" class="btn-action edit" title="Edit Partner">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action delete" title="Delete Partner" onclick="return confirm('Are you sure you want to delete this partner?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="no-partners">
                <i class="fas fa-users"></i>
                <h3>No Partners Found</h3>
                <p>Get started by adding your first partner.</p>
                <a href="{{ route('admin.partners.create') }}" class="add-partner-btn">
                    <i class="fas fa-plus"></i>
                    Add New Partner
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($partners->hasPages())
        <div class="pagination-container">
            {{ $partners->links() }}
        </div>
    @endif
</div>

<style>
/* Base Styles */
.partners-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.partners-header {
    margin-bottom: 30px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.add-partner-btn {
    background-color: #3498db;
    color: white;
    padding: 10px 16px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: background-color 0.2s;
}

.add-partner-btn:hover {
    background-color: #2980b9;
}

/* Alert Styles */
.alert {
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

/* Grid Layout */
.partners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* Partner Card Styles */
.partner-card {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.partner-logo {
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.partner-logo img {
    max-width: 80%;
    max-height: 80%;
    object-fit: contain;
}

.no-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #6c757d;
}

.no-logo i {
    font-size: 40px;
    margin-bottom: 8px;
}

.partner-details {
    padding: 16px;
    flex-grow: 1;
}

.partner-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0 0 12px 0;
}

.partner-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
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
    width: 16px;
    text-align: center;
}

.featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background-color: #fff3cd;
    color: #856404;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

/* Action Buttons */
.partner-actions {
    display: flex;
    border-top: 1px solid #e0e0e0;
    background-color: #f8f9fa;
}

.btn-action {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    color: #6c757d;
    text-decoration: none;
    transition: background-color 0.2s;
}

.btn-action:hover {
    background-color: #e9ecef;
}

.btn-action.view:hover {
    color: #17a2b8;
}

.btn-action.edit:hover {
    color: #ffc107;
}

.btn-action.delete:hover {
    color: #dc3545;
}

.delete-form {
    flex: 1;
    margin: 0;
}

.delete-form button {
    width: 100%;
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    color: #6c757d;
}

/* No Partners State */
.no-partners {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.no-partners i {
    font-size: 64px;
    margin-bottom: 20px;
    color: #dee2e6;
}

.no-partners h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #495057;
}

.no-partners p {
    margin-bottom: 20px;
}

/* Pagination Styles */
.pagination-container {
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 5px;
}

.pagination li {
    display: inline-block;
}

.pagination li a,
.pagination li span {
    display: inline-block;
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
}

.pagination li.active span {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination li.disabled span {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .partners-container {
        padding: 15px;
    }

    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .partners-grid {
        grid-template-columns: 1fr;
    }
}
</style>


@endsection

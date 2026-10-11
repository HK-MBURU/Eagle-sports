@extends('layouts.admin')

@section('title', $partner->name . ' - Partner Details')
@section('page-title', 'Partner Details')

@section('content')
<div class="partners-show-container">
    <!-- Header with Actions -->
    <div class="show-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('admin.partners.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Partners
                </a>
                <h1 class="page-title">{{ $partner->name }}</h1>
                @if($partner->is_featured)
                    <div class="featured-badge">
                        <i class="fas fa-star"></i>
                        Featured Partner
                    </div>
                @endif
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.partners.edit', $partner) }}" class="action-btn edit">
                    <i class="fas fa-edit"></i>
                    Edit Partner
                </a>
                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this partner?')">
                        <i class="fas fa-trash"></i>
                        Delete Partner
                    </button>
                </form>
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

    <!-- Partner Details -->
    <div class="partner-details-grid">
        <!-- Logo Card -->
        <div class="detail-card logo-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-image"></i>
                    Partner Logo
                </h3>
            </div>
            <div class="card-body">
                @if($partner->logo)
                    <div class="logo-preview">
                        <img src="{{ Storage::disk('public')->url($partner->logo) }}" alt="{{ $partner->name }}" class="partner-logo">
                    </div>
                    <div class="logo-path">
                        <small>
                            <i class="fas fa-folder"></i>
                            {{ $partner->logo }}
                        </small>
                    </div>
                @else
                    <div class="no-logo">
                        <i class="fas fa-building"></i>
                        <h4>No Logo Uploaded</h4>
                        <p>This partner doesn't have a logo yet.</p>
                        <a href="{{ route('admin.partners.edit', $partner) }}" class="upload-link">
                            <i class="fas fa-upload"></i>
                            Upload Logo
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Basic Information Card -->
        <div class="detail-card info-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Basic Information
                </h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">Partner Name</label>
                        <div class="info-value">{{ $partner->name }}</div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Featured Status</label>
                        <div class="info-value">
                            @if($partner->is_featured)
                                <span class="status-badge featured">
                                    <i class="fas fa-star"></i>
                                    Featured
                                </span>
                            @else
                                <span class="status-badge regular">
                                    <i class="fas fa-circle"></i>
                                    Regular
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Created By</label>
                        <div class="info-value">
                            <div class="user-info">
                                <i class="fas fa-user"></i>
                                {{ $partner->addedBy->name ?? 'Unknown' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metadata Card -->
        <div class="detail-card meta-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database"></i>
                    Metadata
                </h3>
            </div>
            <div class="card-body">
                <div class="meta-grid">
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="meta-content">
                            <label class="meta-label">Created</label>
                            <div class="meta-value">{{ $partner->created_at->format('F j, Y') }}</div>
                            <div class="meta-time">{{ $partner->created_at->format('g:i A') }}</div>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="meta-content">
                            <label class="meta-label">Last Updated</label>
                            <div class="meta-value">{{ $partner->updated_at->format('F j, Y') }}</div>
                            <div class="meta-time">{{ $partner->updated_at->format('g:i A') }}</div>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="meta-content">
                            <label class="meta-label">Age</label>
                            <div class="meta-value">{{ $partner->created_at->diffForHumans() }}</div>
                            <div class="meta-time">{{ $partner->created_at->diffInDays(now()) }} days</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="detail-card actions-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="actions-grid">
                    <a href="{{ route('admin.partners.edit', $partner) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Edit Partner</div>
                            <div class="action-desc">Update details and logo</div>
                        </div>
                    </a>

                    @if($partner->is_featured)
                        <form action="{{ route('admin.partners.update', $partner) }}" method="POST" class="quick-action-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="is_featured" value="0">
                            <button type="submit" class="quick-action">
                                <div class="action-icon remove-featured">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Remove Featured</div>
                                    <div class="action-desc">Make this a regular partner</div>
                                </div>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.partners.update', $partner) }}" method="POST" class="quick-action-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="is_featured" value="1">
                            <button type="submit" class="quick-action">
                                <div class="action-icon add-featured">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Make Featured</div>
                                    <div class="action-desc">Highlight this partner</div>
                                </div>
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.partners.create') }}" class="quick-action">
                        <div class="action-icon create">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Add New Partner</div>
                            <div class="action-desc">Create another partner</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="danger-zone">
        <div class="danger-header">
            <h3 class="danger-title">
                <i class="fas fa-exclamation-triangle"></i>
                Danger Zone
            </h3>
        </div>
        <div class="danger-content">
            <div class="danger-warning">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="warning-content">
                    <h4>Delete this partner</h4>
                    <p>Once you delete a partner, there is no going back. Please be certain.</p>
                </div>
            </div>
            <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="danger-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="danger-btn" onclick="return confirm('Are you absolutely sure you want to delete this partner? This action cannot be undone.')">
                    <i class="fas fa-trash"></i>
                    Delete this Partner
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Base Styles */
.partners-show-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.show-header {
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
    flex-wrap: wrap;
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

.action-btn.edit {
    background-color: #ffc107;
    color: #212529;
    border: 1px solid #ffc107;
}

.action-btn.edit:hover {
    background-color: #e0a800;
    border-color: #e0a800;
}

.action-btn.delete {
    background-color: #dc3545;
    color: white;
    border: 1px solid #dc3545;
}

.action-btn.delete:hover {
    background-color: #c82333;
    border-color: #c82333;
}

.delete-form {
    margin: 0;
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

/* Details Grid */
.partner-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* Detail Card Styles */
.detail-card {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

.card-header {
    background-color: #f8f9fa;
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

/* Logo Card Styles */
.logo-preview {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 15px;
}

.partner-logo {
    max-width: 100%;
    max-height: 200px;
    object-fit: contain;
}

.logo-path {
    text-align: center;
    color: #6c757d;
}

.no-logo {
    text-align: center;
    color: #6c757d;
    padding: 30px 0;
}

.no-logo i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #dee2e6;
}

.no-logo h4 {
    margin: 0 0 8px 0;
    color: #495057;
}

.no-logo p {
    margin: 0 0 15px 0;
}

.upload-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.upload-link:hover {
    text-decoration: underline;
}

/* Info Card Styles */
.info-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-weight: 500;
    color: #6c757d;
    font-size: 14px;
}

.info-value {
    color: #333;
    font-size: 16px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.featured {
    background-color: #fff3cd;
    color: #856404;
}

.status-badge.regular {
    background-color: #e9ecef;
    color: #495057;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Meta Card Styles */
.meta-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.meta-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.meta-icon {
    width: 32px;
    height: 32px;
    background-color: #f8f9fa;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    flex-shrink: 0;
}

.meta-content {
    flex: 1;
}

.meta-label {
    display: block;
    font-weight: 500;
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 2px;
}

.meta-value {
    color: #333;
    font-size: 14px;
    font-weight: 500;
}

.meta-time {
    color: #6c757d;
    font-size: 12px;
}

/* Actions Card Styles */
.actions-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.quick-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}

.quick-action:hover {
    background-color: #f8f9fa;
    border-color: #3498db;
}

.quick-action-form {
    margin: 0;
}

.quick-action-form button {
    width: 100%;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
}

.action-icon {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.action-icon.edit {
    background-color: #fff3cd;
    color: #856404;
}

.action-icon.add-featured {
    background-color: #d4edda;
    color: #155724;
}

.action-icon.remove-featured {
    background-color: #f8d7da;
    color: #721c24;
}

.action-icon.create {
    background-color: #d1ecf1;
    color: #0c5460;
}

.action-content {
    flex: 1;
}

.action-title {
    font-weight: 500;
    color: #333;
    margin-bottom: 2px;
}

.action-desc {
    font-size: 12px;
    color: #6c757d;
}

/* Danger Zone */
.danger-zone {
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    overflow: hidden;
}

.danger-header {
    background-color: #f8d7da;
    padding: 16px 20px;
    border-bottom: 1px solid #f5c6cb;
}

.danger-title {
    font-size: 16px;
    font-weight: 600;
    color: #721c24;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.danger-content {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.danger-warning {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    flex: 1;
}

.warning-icon {
    color: #dc3545;
    font-size: 20px;
    margin-top: 2px;
}

.warning-content h4 {
    margin: 0 0 4px 0;
    color: #721c24;
}

.warning-content p {
    margin: 0;
    color: #856404;
    font-size: 14px;
}

.danger-form {
    margin: 0;
}

.danger-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
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

/* Responsive Adjustments */
@media (max-width: 768px) {
    .partners-show-container {
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

    .header-actions {
        width: 100%;
    }

    .action-btn {
        flex: 1;
        justify-content: center;
    }

    .partner-details-grid {
        grid-template-columns: 1fr;
    }

    .danger-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .danger-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>


@endsection

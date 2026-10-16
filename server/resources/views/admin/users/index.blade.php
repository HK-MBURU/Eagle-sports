@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Users</h2>
        <p class="text-muted mb-0">{{ $users->total() }} total users</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-es-primary">
        <i class="bi bi-person-plus me-2"></i>Add User
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filters --}}
<div class="card border mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       value="{{ request('search') }}" placeholder="Name or email…">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Role</label>
                <select name="role" class="form-select form-select-sm">
                    <option value="">All Roles</option>
                    <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                    <option value="editor"  {{ request('role') === 'editor'  ? 'selected' : '' }}>Editor</option>
                    <option value="general" {{ request('role') === 'general' ? 'selected' : '' }}>General</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-es-primary btn-sm w-100">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
            </div>
            @if(request()->hasAny(['search','role']))
            <div class="col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-x-circle me-1"></i>Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4" style="width:40px;">#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Joined</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-4 text-muted small">{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="fw-semibold">{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span class="badge bg-secondary" style="font-size:0.65rem;">You</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-muted small">{{ $user->email }}</td>
                    <td>
                        @php
                            $role = $user->role ?? 'general';
                            $roleMap = ['admin' => 'danger', 'editor' => 'primary', 'general' => 'secondary'];
                            $badge = $roleMap[$role] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($role) }}</span>
                    </td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Verified</span>
                        @else
                            <span class="text-warning small"><i class="bi bi-clock me-1"></i>Pending</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="btn btn-sm btn-outline-secondary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#del{{ $user->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>

                {{-- Delete modal --}}
                <div class="modal fade" id="del{{ $user->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0">
                                <h6 class="modal-title fw-bold">Delete User</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body pt-2">
                                <p class="small mb-0">Remove <strong>{{ $user->name }}</strong>? This cannot be undone.</p>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-2 d-block mb-2"></i>
                        No users found.
                        @if(request()->hasAny(['search','role']))
                            <a href="{{ route('admin.users.index') }}" class="d-block mt-2 small">Clear filters</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
        <p class="text-muted small mb-0">
            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
        </p>
        <div class="es-pagination">
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>

@endsection

@push('styles')
<style>
    .btn-es-primary {
        background: var(--es-red);
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: 0.82rem;
    }
    .btn-es-primary:hover { background: #b51e2a; color: #fff; }

    .user-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--es-navy);
        color: #fff;
        font-size: 0.78rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .table thead th {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #667085;
        border-bottom: 1px solid #e2e7ef;
    }
    .table tbody tr:hover { background: #f9fafb; }

    .form-control:focus, .form-select:focus {
        border-color: var(--es-red);
        box-shadow: 0 0 0 3px rgba(214,40,53,.08);
    }

    /* Pagination */
    .es-pagination .pagination { margin: 0; gap: 3px; }
    .es-pagination .page-item .page-link {
        border-radius: 5px !important;
        border: 1px solid var(--es-line);
        color: var(--es-navy);
        font-size: 0.8rem; font-weight: 600;
        padding: 5px 11px; min-width: 34px; text-align: center;
        transition: background .12s, border-color .12s, color .12s;
    }
    .es-pagination .page-item.active .page-link {
        background: var(--es-red); border-color: var(--es-red); color: #fff;
    }
    .es-pagination .page-item .page-link:hover:not(.disabled) {
        background: var(--es-paper); border-color: var(--es-red); color: var(--es-red);
    }
    .es-pagination .page-item.disabled .page-link {
        color: #b0b8c4; border-color: var(--es-line); background: transparent;
    }
</style>
@endpush

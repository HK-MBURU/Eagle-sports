@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h2 class="mb-0 fs-4 fw-bold">Users</h2>
        <p class="text-muted mb-0 small">{{ $users->total() }} total user{{ $users->total() !== 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-es-primary flex-shrink-0">
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

{{-- ── Filters ── --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-sm-5">
                    <label class="form-label fw-semibold small mb-1">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           value="{{ request('search') }}" placeholder="Name or email…">
                </div>
                <div class="col-7 col-sm-3">
                    <label class="form-label fw-semibold small mb-1">Role</label>
                    <select name="role" class="form-select form-select-sm">
                        <option value="">All Roles</option>
                        <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                        <option value="editor"  {{ request('role') === 'editor'  ? 'selected' : '' }}>Editor</option>
                        <option value="general" {{ request('role') === 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
                <div class="col-5 col-sm-2">
                    <button type="submit" class="btn btn-es-primary btn-sm w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
                @if(request()->hasAny(['search','role']))
                <div class="col-12 col-sm-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

@if($users->count() > 0)

    {{-- ══ MOBILE CARDS (< md) ══ --}}
    <div class="d-md-none d-flex flex-column gap-3 mb-3">
        @foreach($users as $user)
        @php
            $role    = $user->role ?? 'general';
            $roleMap = ['admin' => ['#dc2626','Admin'], 'editor' => ['#2563eb','Editor'], 'general' => ['#64748b','General']];
            [$roleColor, $roleLabel] = $roleMap[$role] ?? ['#64748b','General'];
        @endphp
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3">

                {{-- Avatar + name + badges --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="user-avatar flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold" style="color:#10284a;">{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span class="badge" style="background:#64748b;font-size:10px;">You</span>
                            @endif
                            <span class="badge ms-auto" style="background:{{ $roleColor }};font-size:11px;">{{ $roleLabel }}</span>
                        </div>
                        <div class="text-muted small text-truncate">{{ $user->email }}</div>
                    </div>
                </div>

                {{-- Meta row: verified + joined --}}
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        @if($user->email_verified_at)
                            <span class="small text-success fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i>Verified
                            </span>
                        @else
                            <span class="small text-warning fw-semibold">
                                <i class="bi bi-clock me-1"></i>Pending
                            </span>
                        @endif
                    </div>
                    <div class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i>{{ $user->created_at->format('M d, Y') }}
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="btn btn-sm flex-fill fw-semibold text-white"
                       style="background:#d97706;border:none;">
                        <i class="bi bi-pencil-fill me-1"></i>Edit
                    </a>
                    @if($user->id !== auth()->id())
                    <button type="button"
                            class="btn btn-sm flex-fill fw-semibold text-white"
                            style="background:#dc2626;border:none;"
                            data-bs-toggle="modal"
                            data-bs-target="#del{{ $user->id }}">
                        <i class="bi bi-trash-fill me-1"></i>Delete
                    </button>
                    @else
                    <button class="btn btn-sm flex-fill" style="background:#f1f5f9;color:#94a3b8;border:none;" disabled>
                        <i class="bi bi-shield-lock me-1"></i>Can't delete self
                    </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Delete modal --}}
        @if($user->id !== auth()->id())
        <div class="modal fade" id="del{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h6 class="modal-title fw-bold text-danger">
                            <i class="bi bi-exclamation-triangle me-1"></i>Delete User
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <p class="small mb-0">Remove <strong>{{ $user->name }}</strong>? This cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    {{-- ══ DESKTOP TABLE (≥ md) ══ --}}
    <div class="d-none d-md-block">
        <div class="card border-0 shadow-sm">
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
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        @php
                            $role    = $user->role ?? 'general';
                            $roleMap = ['admin' => 'danger', 'editor' => 'primary', 'general' => 'secondary'];
                            $badge   = $roleMap[$role] ?? 'secondary';
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted small">{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-secondary" style="font-size:0.65rem;">You</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-muted small">{{ $user->email }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ ucfirst($role) }}</span></td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Verified</span>
                                @else
                                    <span class="text-warning small"><i class="bi bi-clock me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td class="text-muted small text-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn btn-sm fw-semibold text-white px-3"
                                       style="background:#d97706;border:none;min-width:68px;">
                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <button type="button"
                                            class="btn btn-sm fw-semibold text-white px-3"
                                            style="background:#dc2626;border:none;min-width:76px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#del{{ $user->id }}">
                                        <i class="bi bi-trash-fill me-1"></i>Delete
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Delete modal --}}
                        @if($user->id !== auth()->id())
                        <div class="modal fade" id="del{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h6 class="modal-title fw-bold text-danger">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Delete User
                                        </h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-2">
                                        <p class="small mb-0">Remove <strong>{{ $user->name }}</strong>? This cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 px-4 py-3 border-top">
                <p class="text-muted small mb-0">
                    Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
                </p>
                <div class="es-pagination">{{ $users->links() }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Mobile pagination --}}
    @if($users->hasPages())
    <div class="d-md-none mt-3">
        <div class="d-flex flex-column align-items-center gap-2">
            <p class="text-muted small mb-0">
                Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
            </p>
            <div class="es-pagination">{{ $users->links() }}</div>
        </div>
    </div>
    @endif

@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-people" style="font-size:2.5rem;color:#c8d0dc;display:block;margin-bottom:12px;"></i>
            <h5 class="fw-bold mb-2" style="color:#10284a;">No users found</h5>
            <p class="text-muted small mb-4">
                @if(request()->hasAny(['search','role']))
                    No users match your filters.
                    <a href="{{ route('admin.users.index') }}">Clear filters</a>
                @else
                    No users have been added yet.
                @endif
            </p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-es-primary btn-sm">
                <i class="bi bi-person-plus me-2"></i>Add User
            </a>
        </div>
    </div>
@endif

@endsection

@push('styles')
<style>
    .btn-es-primary { background:#d62835; border:none; color:#fff; font-weight:700; font-size:0.82rem; }
    .btn-es-primary:hover { background:#b51e2a; color:#fff; }

    .user-avatar {
        width:36px; height:36px; border-radius:50%;
        background:#10284a; color:#fff;
        font-size:0.8rem; font-weight:800;
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
    }

    .table thead th {
        font-size:0.72rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.4px;
        color:#667085; border-bottom:1px solid #e2e7ef;
    }
    .table tbody tr:hover { background:#f9fafb; }

    .form-control:focus, .form-select:focus {
        border-color:#d62835;
        box-shadow:0 0 0 3px rgba(214,40,53,.08);
    }

    .es-pagination .pagination { margin:0; gap:3px; flex-wrap:wrap; justify-content:center; }
    .es-pagination .page-item .page-link {
        border-radius:5px !important; border:1px solid #e2e8f0;
        color:#10284a; font-size:0.8rem; font-weight:600;
        padding:5px 11px; min-width:34px; text-align:center;
        transition:background .12s, border-color .12s, color .12s;
    }
    .es-pagination .page-item.active .page-link { background:#d62835; border-color:#d62835; color:#fff; }
    .es-pagination .page-item .page-link:hover:not(.disabled) { background:#f4f5f7; border-color:#d62835; color:#d62835; }
    .es-pagination .page-item.disabled .page-link { color:#b0b8c4; background:transparent; }
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Edit User</h2>
        <p class="text-muted mb-0">Update account details for <strong>{{ $user->name }}</strong></p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card border" style="max-width:560px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold small">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    <option value="general" {{ old('role', $user->role) === 'general' ? 'selected' : '' }}>General</option>
                    <option value="editor"  {{ old('role', $user->role) === 'editor'  ? 'selected' : '' }}>Editor</option>
                    <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <hr class="my-4">

            <p class="small text-muted mb-3">Leave password fields blank to keep the current password.</p>

            <div class="mb-3">
                <label class="form-label fw-semibold small">New Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Min 8 characters">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repeat new password">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-es-primary">
                    <i class="bi bi-check-lg me-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
    .btn-es-primary { background: var(--es-red); border: none; color: #fff; font-weight: 700; }
    .btn-es-primary:hover { background: #b51e2a; color: #fff; }
    .form-control:focus, .form-select:focus {
        border-color: var(--es-red);
        box-shadow: 0 0 0 3px rgba(214,40,53,.08);
    }
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')

<div class="mb-4">
    <h2 class="mb-1">Settings</h2>
    <p class="text-muted mb-0">Manage site configuration and your account</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Tabs --}}
@php $activeTab = session('active_tab', 'site'); @endphp

<ul class="nav nav-tabs mb-4" id="settingsTabs">
    <li class="nav-item">
        <button class="nav-link {{ $activeTab === 'site' ? 'active' : '' }}"
                data-bs-toggle="tab" data-bs-target="#tab-site">
            <i class="bi bi-globe2 me-1"></i>Site
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ $activeTab === 'social' ? 'active' : '' }}"
                data-bs-toggle="tab" data-bs-target="#tab-social">
            <i class="bi bi-share me-1"></i>Social
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ $activeTab === 'content' ? 'active' : '' }}"
                data-bs-toggle="tab" data-bs-target="#tab-content">
            <i class="bi bi-sliders me-1"></i>Content
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ $activeTab === 'account' ? 'active' : '' }}"
                data-bs-toggle="tab" data-bs-target="#tab-account">
            <i class="bi bi-person-circle me-1"></i>My Account
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- ── Site Settings ── --}}
    <div class="tab-pane fade {{ $activeTab === 'site' ? 'show active' : '' }}" id="tab-site">
        <div class="card border" style="max-width:640px;">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    <input type="hidden" name="tab" value="site">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Site Name</label>
                        <input type="text" name="site_name" class="form-control"
                               value="{{ $settings['site_name']->value ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tagline</label>
                        <input type="text" name="site_tagline" class="form-control"
                               value="{{ $settings['site_tagline']->value ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">About Blurb</label>
                        <textarea name="about_blurb" class="form-control" rows="3">{{ $settings['about_blurb']->value ?? '' }}</textarea>
                    </div>
                    <hr class="my-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control"
                                   value="{{ $settings['contact_email']->value ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control"
                                   value="{{ $settings['contact_phone']->value ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Address</label>
                            <input type="text" name="contact_address" class="form-control"
                                   value="{{ $settings['contact_address']->value ?? '' }}">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-es-primary">
                            <i class="bi bi-check-lg me-2"></i>Save Site Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Social Settings ── --}}
    <div class="tab-pane fade {{ $activeTab === 'social' ? 'show active' : '' }}" id="tab-social">
        <div class="card border" style="max-width:640px;">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    <input type="hidden" name="tab" value="social">

                    @foreach([
                        'facebook_url'  => ['Facebook',    'bi-facebook'],
                        'twitter_url'   => ['Twitter / X', 'bi-twitter-x'],
                        'instagram_url' => ['Instagram',   'bi-instagram'],
                        'youtube_url'   => ['YouTube',     'bi-youtube'],
                    ] as $key => [$label, $icon])
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            <i class="bi {{ $icon }} me-1"></i>{{ $label }} URL
                        </label>
                        <input type="url" name="{{ $key }}" class="form-control"
                               value="{{ $settings[$key]->value ?? '' }}"
                               placeholder="https://…">
                    </div>
                    @endforeach

                    <div class="mt-4">
                        <button type="submit" class="btn btn-es-primary">
                            <i class="bi bi-check-lg me-2"></i>Save Social Links
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Content Settings ── --}}
    <div class="tab-pane fade {{ $activeTab === 'content' ? 'show active' : '' }}" id="tab-content">
        <div class="card border" style="max-width:640px;">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    <input type="hidden" name="tab" value="content">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Stories Per Page</label>
                        <input type="number" name="stories_per_page" class="form-control"
                               value="{{ $settings['stories_per_page']->value ?? 12 }}"
                               min="1" max="100" style="max-width:120px;">
                        <div class="form-text">How many stories to show per page on the public site.</div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-es-primary">
                            <i class="bi bi-check-lg me-2"></i>Save Content Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── My Account ── --}}
    <div class="tab-pane fade {{ $activeTab === 'account' ? 'show active' : '' }}" id="tab-account">
        <div class="card border" style="max-width:560px;">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    <input type="hidden" name="tab" value="account">

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

                    <hr class="my-4">
                    <p class="small text-muted mb-3">Leave password fields blank to keep your current password.</p>

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

                    <button type="submit" class="btn btn-es-primary">
                        <i class="bi bi-check-lg me-2"></i>Save Account
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>{{-- /.tab-content --}}

@endsection

@push('styles')
<style>
    .btn-es-primary { background: var(--es-red); border: none; color: #fff; font-weight: 700; font-size: 0.85rem; }
    .btn-es-primary:hover { background: #b51e2a; color: #fff; }

    .nav-tabs .nav-link {
        color: #667085;
        font-size: 0.82rem;
        font-weight: 600;
        border-color: transparent;
        padding: 8px 16px;
    }
    .nav-tabs .nav-link.active {
        color: var(--es-navy);
        border-bottom-color: var(--es-red);
        font-weight: 800;
    }
    .nav-tabs .nav-link:hover:not(.active) { color: var(--es-navy); }

    .form-control:focus, .form-select:focus {
        border-color: var(--es-red);
        box-shadow: 0 0 0 3px rgba(214,40,53,.08);
    }
</style>
@endpush

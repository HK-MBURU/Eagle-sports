@extends('layouts.admin')

@section('title', 'Edit Performance Entry')
@section('page-title', 'Edit Performance Entry')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Edit Performance Entry</h2>
        <p class="text-muted mb-0">{{ $entry->competition }} — {{ $entry->event_discipline }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.result-entries.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger mb-4">
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.result-entries.update', $entry) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- Main fields --}}
        <div class="col-lg-8">
            <div class="card border">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-800" style="color:var(--es-navy);">Performance Details</h6>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label fw-700">Competition <span class="text-danger">*</span></label>
                        <input type="text" name="competition" class="form-control @error('competition') is-invalid @enderror"
                               value="{{ old('competition', $entry->competition) }}"
                               placeholder="e.g. Nairobi City Marathon, Diamond League — Eugene"
                               required>
                        @error('competition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-700">Event / Discipline <span class="text-danger">*</span></label>
                            <input type="text" name="event_discipline"
                                   class="form-control @error('event_discipline') is-invalid @enderror"
                                   value="{{ old('event_discipline', $entry->event_discipline) }}"
                                   placeholder="e.g. Men's Marathon, Women's 10,000m"
                                   list="discipline-suggestions" required>
                            <datalist id="discipline-suggestions">
                                <option value="Men's Marathon">
                                <option value="Women's Marathon">
                                <option value="Men's Half Marathon">
                                <option value="Women's Half Marathon">
                                <option value="Men's 10,000m">
                                <option value="Women's 10,000m">
                                <option value="Men's 5,000m">
                                <option value="Women's 5,000m">
                                <option value="Men's 3,000m Steeplechase">
                                <option value="Women's 3,000m Steeplechase">
                                <option value="Men's 1,500m">
                                <option value="Women's 1,500m">
                                <option value="Men's 800m">
                                <option value="Women's 800m">
                                <option value="Men's 10K Road">
                                <option value="Women's 10K Road">
                            </datalist>
                            @error('event_discipline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-700">Performance <span class="text-danger">*</span></label>
                            <input type="text" name="performance"
                                   class="form-control @error('performance') is-invalid @enderror"
                                   value="{{ old('performance', $entry->performance) }}"
                                   placeholder="e.g. 2:05:48 or 28:45.32 or 3:31.45"
                                   required>
                            @error('performance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Time, distance, or score.</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-700">Athlete Name</label>
                            <input type="text" name="athlete_name"
                                   class="form-control @error('athlete_name') is-invalid @enderror"
                                   value="{{ old('athlete_name', $entry->athlete_name) }}"
                                   placeholder="e.g. Eliud Kipchoge">
                            @error('athlete_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-700">Country Code</label>
                            <input type="text" name="athlete_country"
                                   class="form-control @error('athlete_country') is-invalid @enderror"
                                   value="{{ old('athlete_country', $entry->athlete_country) }}"
                                   placeholder="KEN" maxlength="10">
                            @error('athlete_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">3-letter IOC code, e.g. KEN, ETH, UGA</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-700">Race Date</label>
                            <input type="date" name="race_date"
                                   class="form-control @error('race_date') is-invalid @enderror"
                                   value="{{ old('race_date', $entry->race_date?->format('Y-m-d')) }}">
                            @error('race_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-700">Location</label>
                            <input type="text" name="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   value="{{ old('location', $entry->location) }}"
                                   placeholder="e.g. Nairobi, Kenya">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Sidebar: classification --}}
        <div class="col-lg-4">

            {{-- Series & Status --}}
            <div class="card border mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-800" style="color:var(--es-navy);">Classification</h6>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label fw-700">Series</label>
                        <select name="series" class="form-select @error('series') is-invalid @enderror">
                            <option value="">— None —</option>
                            @foreach($seriesOptions as $s)
                            <option value="{{ $s }}" {{ old('series', $entry->series) === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('series')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-700">Result Status <span class="text-danger">*</span></label>
                        <select name="result_status" class="form-select @error('result_status') is-invalid @enderror" required>
                            @foreach($statusOptions as $val => $label)
                            <option value="{{ $val }}" {{ old('result_status', $entry->result_status) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('result_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

            {{-- Visibility --}}
            <div class="card border mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-800" style="color:var(--es-navy);">Visibility</h6>
                </div>
                <div class="card-body">

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_published"
                               id="is_published" value="1"
                               {{ old('is_published', $entry->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label fw-700" for="is_published">Published</label>
                        <div class="form-text mt-0">Show on frontend Results Centre.</div>
                    </div>

                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="is_featured"
                               id="is_featured" value="1"
                               {{ old('is_featured', $entry->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label fw-700" for="is_featured">Featured</label>
                        <div class="form-text mt-0">Highlight as a top result.</div>
                    </div>

                </div>
            </div>

            {{-- Meta info --}}
            <div class="card border mb-3">
                <div class="card-body py-3">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-clock me-1"></i>
                        Created {{ $entry->created_at->format('M d, Y') }}
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-pencil me-1"></i>
                        Updated {{ $entry->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-re-primary">
                    <i class="bi bi-check-circle me-2"></i>Update Entry
                </button>
                <a href="{{ route('admin.result-entries.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>

            {{-- Danger zone --}}
            <div class="card border border-danger mt-3">
                <div class="card-body py-3">
                    <h6 class="text-danger fw-800 mb-2" style="font-size:0.8rem;">Danger Zone</h6>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i>Delete This Entry
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Delete Entry
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-1">
                <p class="small mb-1">Delete <strong>{{ $entry->competition }}</strong> — {{ $entry->event_discipline }}?</p>
                <p class="small text-muted mb-0">This cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.result-entries.destroy', $entry) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .btn-re-primary { background:var(--es-red); border:none; color:#fff; font-weight:700; font-size:0.85rem; }
    .btn-re-primary:hover { background:#b51e2a; color:#fff; }
    .fw-700 { font-weight:700; }
    .fw-800 { font-weight:800; }
    .card-header h6 { font-size:0.85rem; }
    .form-label { font-size:0.82rem; margin-bottom:4px; }
    .form-control, .form-select {
        font-size:0.85rem;
        border-color: var(--es-line);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--es-red);
        box-shadow: 0 0 0 .2rem rgba(214,40,53,.12);
    }
    .form-text { font-size:0.72rem; color:#8a97aa; }
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Performance Entries')
@section('page-title', 'Performance Entries')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h2 class="mb-1 fs-4 fw-bold">Performance Entries</h2>
        <p class="text-muted mb-0 small d-none d-sm-block">
            Individual athlete performance records shown on the homepage Results widget
        </p>
    </div>
    <a href="{{ route('admin.result-entries.create') }}" class="btn btn-re-primary flex-shrink-0">
        <i class="bi bi-plus-circle me-2"></i>Add Entry
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
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Stats ── --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total',     'value'=>$stats['total'],     'icon'=>'bi-list-columns', 'color'=>'#10284a'],
        ['label'=>'Published', 'value'=>$stats['published'], 'icon'=>'bi-eye',          'color'=>'#16a34a'],
        ['label'=>'Featured',  'value'=>$stats['featured'],  'icon'=>'bi-star-fill',    'color'=>'#d97706'],
        ['label'=>'Official',  'value'=>$stats['official'],  'icon'=>'bi-patch-check',  'color'=>'#2563eb'],
    ] as $s)
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="re-stat-icon" style="color:{{ $s['color'] }}">
                    <i class="bi {{ $s['icon'] }} fs-4"></i>
                </div>
                <div>
                    <div class="fw-800 fs-5 lh-1">{{ $s['value'] }}</div>
                    <div class="text-muted small">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Filters + Search ── --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 px-3">

        {{-- Filter pills --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            @foreach([
                'all'       => ['All Entries', 'bi-list-ul'],
                'published' => ['Published',   'bi-eye'],
                'draft'     => ['Draft',       'bi-eye-slash'],
                'featured'  => ['Featured',    'bi-star'],
            ] as $key => [$label, $icon])
            <a href="{{ route('admin.result-entries.index', array_merge(request()->except('filter','page'), ['filter'=>$key])) }}"
               class="re-filter-pill {{ $filter === $key ? 'active' : '' }}">
                <i class="bi {{ $icon }} me-1"></i>{{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Search + series --}}
        <form method="GET" action="{{ route('admin.result-entries.index') }}">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="d-flex flex-column flex-sm-row gap-2">
                <select name="series" class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    <option value="">All Series</option>
                    @foreach($seriesList as $s => $count)
                    <option value="{{ $s }}" {{ $series === $s ? 'selected' : '' }}>
                        {{ $s }} ({{ $count }})
                    </option>
                    @endforeach
                </select>

                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0 bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           value="{{ $search }}" placeholder="Competition, event, athlete…">
                    @if($search)
                    <a href="{{ route('admin.result-entries.index', ['filter'=>$filter,'series'=>$series]) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@if($entries->count() > 0)

    {{-- ══ MOBILE CARDS (< md) ══ --}}
    <div class="d-md-none d-flex flex-column gap-3 mb-3">
        @foreach($entries as $entry)
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3">

                {{-- Top row: competition + status badge --}}
                <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                    <div class="fw-bold lh-sm" style="color:#10284a;font-size:14px;">
                        {{ $entry->competition }}
                    </div>
                    @php $statusColors = ['official'=>'success','pending'=>'warning','unofficial'=>'secondary']; @endphp
                    <span class="badge bg-{{ $statusColors[$entry->result_status] ?? 'secondary' }} flex-shrink-0">
                        {{ ucfirst($entry->result_status) }}
                    </span>
                </div>

                {{-- Athlete --}}
                @if($entry->athlete_name)
                <div class="text-muted mb-2" style="font-size:12px;">
                    <i class="bi bi-person-fill me-1"></i>{{ $entry->athlete_name }}
                    @if($entry->athlete_country)
                        <span class="badge bg-light text-secondary ms-1">{{ strtoupper($entry->athlete_country) }}</span>
                    @endif
                </div>
                @endif

                {{-- Performance (hero number) --}}
                <div class="d-flex align-items-baseline gap-2 mb-2">
                    <span class="fw-bold" style="font-size:22px;color:#10284a;font-variant-numeric:tabular-nums;">
                        {{ $entry->performance }}
                    </span>
                    <span class="text-muted small">{{ $entry->event_discipline }}</span>
                </div>

                {{-- Meta row: series + date + flags --}}
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    @if($entry->series)
                    <span class="re-series-badge">{{ $entry->series }}</span>
                    @endif
                    @if($entry->race_date)
                    <span class="text-muted" style="font-size:12px;">
                        <i class="bi bi-calendar3 me-1" style="color:#d62835;"></i>{{ $entry->race_date->format('M d, Y') }}
                    </span>
                    @endif
                    {{-- Published + Featured flags --}}
                    <span class="ms-auto d-flex gap-2">
                        @if($entry->is_published)
                            <span class="text-success small fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Live</span>
                        @else
                            <span class="text-muted small"><i class="bi bi-circle me-1"></i>Draft</span>
                        @endif
                        @if($entry->is_featured)
                            <span class="text-warning small fw-semibold"><i class="bi bi-star-fill me-1"></i>Featured</span>
                        @endif
                    </span>
                </div>

                {{-- Action buttons --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.result-entries.edit', $entry) }}"
                       class="btn btn-sm flex-fill fw-semibold text-white"
                       style="background:#d97706;border:none;">
                        <i class="bi bi-pencil-fill me-1"></i>Edit
                    </a>
                    <button type="button"
                            class="btn btn-sm flex-fill fw-semibold text-white"
                            style="background:#dc2626;border:none;"
                            data-bs-toggle="modal"
                            data-bs-target="#del{{ $entry->id }}">
                        <i class="bi bi-trash-fill me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>

        {{-- Delete modal --}}
        <div class="modal fade" id="del{{ $entry->id }}" tabindex="-1" aria-hidden="true">
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
        @endforeach
    </div>

    {{-- ══ DESKTOP TABLE (≥ md) ══ --}}
    <div class="d-none d-md-block">
        <div class="card border-0 shadow-sm mb-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="table-light" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.4px;color:#667085;">
                            <th class="ps-4">Competition</th>
                            <th>Event</th>
                            <th>Performance</th>
                            <th>Series</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Pub.</th>
                            <th class="text-center">Feat.</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $entry)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-800" style="color:#10284a;font-size:0.82rem;max-width:200px;">
                                    {{ $entry->competition }}
                                </div>
                                @if($entry->athlete_name)
                                <div class="text-muted" style="font-size:0.72rem;">
                                    <i class="bi bi-person me-1"></i>{{ $entry->athlete_name }}
                                    @if($entry->athlete_country)
                                        <span class="ms-1 badge bg-light text-secondary">{{ strtoupper($entry->athlete_country) }}</span>
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td style="font-size:0.82rem;color:#3d5275;">{{ $entry->event_discipline }}</td>
                            <td>
                                <span class="fw-800" style="font-size:0.9rem;color:#10284a;font-variant-numeric:tabular-nums;">
                                    {{ $entry->performance }}
                                </span>
                            </td>
                            <td>
                                @if($entry->series)
                                    <span class="re-series-badge">{{ $entry->series }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td style="font-size:0.78rem;color:#667085;white-space:nowrap;">
                                @if($entry->race_date)
                                    <i class="bi bi-calendar3 me-1" style="color:#d62835;"></i>
                                    {{ $entry->race_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php $statusColors = ['official'=>'success','pending'=>'warning','unofficial'=>'secondary']; @endphp
                                <span class="badge bg-{{ $statusColors[$entry->result_status] ?? 'secondary' }}">
                                    {{ ucfirst($entry->result_status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($entry->is_published)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-circle text-muted"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($entry->is_featured)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            </td>
                            <td class="pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.result-entries.edit', $entry) }}"
                                       class="btn btn-sm fw-semibold text-white px-3"
                                       style="background:#d97706;border:none;min-width:68px;">
                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm fw-semibold text-white px-3"
                                            style="background:#dc2626;border:none;min-width:76px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#del{{ $entry->id }}">
                                        <i class="bi bi-trash-fill me-1"></i>Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Delete modal (desktop) --}}
                        <div class="modal fade" id="del{{ $entry->id }}" tabindex="-1" aria-hidden="true">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 pt-1">
        <p class="text-muted small mb-0">
            Showing {{ $entries->firstItem() }}–{{ $entries->lastItem() }} of {{ $entries->total() }} entries
        </p>
        <div class="es-pagination">{{ $entries->links() }}</div>
    </div>

@else
{{-- Empty state --}}
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-table" style="font-size:2.5rem;color:#c8d0dc;display:block;margin-bottom:12px;"></i>
        <h5 class="fw-800 mb-2" style="color:#10284a;">No result entries yet</h5>
        <p class="text-muted small mb-4">
            @if($search)
                No results for "{{ $search }}".
                <a href="{{ route('admin.result-entries.index', ['filter'=>$filter]) }}">Clear search</a>
            @elseif($filter !== 'all')
                No {{ $filter }} entries.
                <a href="{{ route('admin.result-entries.index') }}">View all</a>
            @else
                Start adding performance results to display in the Results Centre on the homepage.
            @endif
        </p>
        <a href="{{ route('admin.result-entries.create') }}" class="btn btn-re-primary btn-sm">
            <i class="bi bi-plus-circle me-2"></i>Add First Entry
        </a>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .btn-re-primary { background:#d62835; border:none; color:#fff; font-weight:700; font-size:0.82rem; }
    .btn-re-primary:hover { background:#b51e2a; color:#fff; }

    .fw-800 { font-weight:800; }

    .re-filter-pill {
        display:inline-flex; align-items:center;
        padding:5px 13px; border-radius:20px;
        font-size:0.78rem; font-weight:700;
        border:1px solid #e2e8f0;
        color:#667085; background:#fff;
        text-decoration:none; transition:all .12s;
        white-space:nowrap;
    }
    .re-filter-pill:hover { border-color:#d62835; color:#d62835; }
    .re-filter-pill.active { background:#10284a; border-color:#10284a; color:#fff; }

    .re-series-badge {
        display:inline-block;
        background:#f4f5f7; color:#10284a;
        border:1px solid #e2e8f0; border-radius:4px;
        padding:2px 7px; font-size:0.7rem; font-weight:700;
    }

    .re-stat-icon {
        width:42px; height:42px; border-radius:50%;
        background:#f4f5f7;
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
    }

    .table th { font-weight:700; }
    .table td { border-color:#e9ecef; }
    .table tr:hover td { background:#f9fafc; }

    .es-pagination .pagination { margin:0; gap:3px; }
    .es-pagination .page-item .page-link {
        border-radius:5px !important; border:1px solid #e2e8f0;
        color:#10284a; font-size:0.8rem; font-weight:600;
        padding:5px 11px; min-width:34px; text-align:center;
        transition:background .12s, border-color .12s, color .12s;
    }
    .es-pagination .page-item.active .page-link { background:#d62835; border-color:#d62835; color:#fff; }
    .es-pagination .page-item .page-link:hover:not(.disabled) { background:#f4f5f7; border-color:#d62835; color:#d62835; }
    .es-pagination .page-item.disabled .page-link { color:#b0b8c4; background:transparent; }

    @media (max-width: 575px) {
        .es-pagination .pagination { flex-wrap:wrap; justify-content:center; }
    }
</style>
@endpush

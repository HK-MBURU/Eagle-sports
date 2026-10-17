@extends('layouts.admin')

@section('title', 'Performance Entries')
@section('page-title', 'Performance Entries')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Performance Entries</h2>
        <p class="text-muted mb-0">Individual athlete performance records (times, positions, series) shown on the homepage Results widget</p>
    </div>
    <a href="{{ route('admin.result-entries.create') }}" class="btn btn-re-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Performance Entry
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

{{-- Stats row --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Entries', 'value'=>$stats['total'],     'icon'=>'bi-list-columns', 'color'=>'es-navy'],
        ['label'=>'Published',     'value'=>$stats['published'], 'icon'=>'bi-eye',          'color'=>'success'],
        ['label'=>'Featured',      'value'=>$stats['featured'],  'icon'=>'bi-star-fill',    'color'=>'warning'],
        ['label'=>'Official',      'value'=>$stats['official'],  'icon'=>'bi-patch-check',  'color'=>'primary'],
    ] as $s)
    <div class="col-6 col-md-3">
        <div class="card border h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="re-stat-icon text-{{ $s['color'] }}">
                    <i class="bi {{ $s['icon'] }} fs-4"></i>
                </div>
                <div>
                    <div class="fw-900 fs-5 lh-1">{{ $s['value'] }}</div>
                    <div class="text-muted small">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters + Search --}}
<div class="card border mb-4">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            {{-- Filter pills --}}
            <div class="d-flex flex-wrap gap-2">
                @foreach([
                    'all'       => ['All Entries', 'bi-list-ul'],
                    'published' => ['Published',   'bi-eye'],
                    'draft'     => ['Draft',        'bi-eye-slash'],
                    'featured'  => ['Featured',     'bi-star'],
                ] as $key => [$label, $icon])
                <a href="{{ route('admin.result-entries.index', array_merge(request()->except('filter','page'), ['filter'=>$key])) }}"
                   class="re-filter-pill {{ $filter === $key ? 'active' : '' }}">
                    <i class="bi {{ $icon }} me-1"></i>{{ $label }}
                </a>
                @endforeach
            </div>

            {{-- Search + Series filter --}}
            <form method="GET" action="{{ route('admin.result-entries.index') }}" class="d-flex gap-2 flex-wrap">
                <input type="hidden" name="filter" value="{{ $filter }}">

                {{-- Series select --}}
                <select name="series" class="form-select form-select-sm" style="min-width:180px;"
                        onchange="this.form.submit()">
                    <option value="">All Series</option>
                    @foreach($seriesList as $s => $count)
                    <option value="{{ $s }}" {{ $series === $s ? 'selected' : '' }}>
                        {{ $s }} ({{ $count }})
                    </option>
                    @endforeach
                </select>

                {{-- Search --}}
                <div class="input-group input-group-sm" style="min-width:220px;">
                    <span class="input-group-text border-end-0 bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           value="{{ $search }}" placeholder="Competition, event, athlete…">
                    @if($search)
                    <a href="{{ route('admin.result-entries.index', ['filter'=>$filter, 'series'=>$series]) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Table --}}
@if($entries->count() > 0)
<div class="card border mb-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="table-light" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.4px;color:#667085;">
                    <th class="ps-3">Competition</th>
                    <th>Event / Discipline</th>
                    <th>Performance</th>
                    <th>Series</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-center">Pub.</th>
                    <th class="text-center">Feat.</th>
                    <th class="pe-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                <tr>
                    <td class="ps-3">
                        <div class="fw-800" style="color:var(--es-navy);font-size:0.82rem;max-width:200px;">
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
                        <span class="fw-800" style="font-size:0.9rem;color:var(--es-navy);font-variant-numeric:tabular-nums;">
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
                            <i class="bi bi-calendar3 me-1" style="color:var(--es-red);"></i>
                            {{ $entry->race_date->format('M d, Y') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'official'   => 'success',
                                'pending'    => 'warning',
                                'unofficial' => 'secondary',
                            ];
                        @endphp
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
                    <td class="pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.result-entries.edit', $entry) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#del{{ $entry->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

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
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center pt-1">
    <p class="text-muted small mb-0">
        Showing {{ $entries->firstItem() }}–{{ $entries->lastItem() }} of {{ $entries->total() }} entries
    </p>
    <div class="es-pagination">{{ $entries->links() }}</div>
</div>

@else
{{-- Empty state --}}
<div class="card border">
    <div class="card-body text-center py-5">
        <i class="bi bi-table" style="font-size:2.5rem;color:#c8d0dc;display:block;margin-bottom:12px;"></i>
        <h5 class="fw-800 mb-2" style="color:var(--es-navy);">No result entries yet</h5>
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
    .btn-re-primary { background:var(--es-red); border:none; color:#fff; font-weight:700; font-size:0.82rem; }
    .btn-re-primary:hover { background:#b51e2a; color:#fff; }

    .fw-800 { font-weight:800; }
    .fw-900 { font-weight:900; }

    .re-filter-pill {
        display: inline-flex; align-items: center;
        padding: 5px 13px; border-radius: 20px;
        font-size: 0.78rem; font-weight: 700;
        border: 1px solid var(--es-line);
        color: #667085; background: #fff;
        text-decoration: none; transition: all .12s;
    }
    .re-filter-pill:hover { border-color: var(--es-red); color: var(--es-red); }
    .re-filter-pill.active { background: var(--es-navy); border-color: var(--es-navy); color: #fff; }

    .re-series-badge {
        display: inline-block;
        background: var(--es-paper);
        color: var(--es-navy);
        border: 1px solid var(--es-line);
        border-radius: 4px;
        padding: 2px 7px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .re-stat-icon {
        width:42px; height:42px; border-radius:50%;
        background:var(--es-paper);
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
    }

    .table th { font-weight: 700; font-size: 0.72rem; }
    .table td { border-color: var(--es-line); }
    .table tr:hover td { background: #f9fafc; }

    .es-pagination .pagination { margin:0; gap:3px; }
    .es-pagination .page-item .page-link {
        border-radius:5px !important; border:1px solid var(--es-line);
        color:var(--es-navy); font-size:0.8rem; font-weight:600;
        padding:5px 11px; min-width:34px; text-align:center;
        transition: background .12s, border-color .12s, color .12s;
    }
    .es-pagination .page-item.active .page-link { background:var(--es-red); border-color:var(--es-red); color:#fff; }
    .es-pagination .page-item .page-link:hover:not(.disabled) { background:var(--es-paper); border-color:var(--es-red); color:var(--es-red); }
    .es-pagination .page-item.disabled .page-link { color:#b0b8c4; background:transparent; }
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Race Events')
@section('page-title', 'Race Events')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Race Events</h2>
        <p class="text-muted mb-0">Manage competitions, flagship coverage and calendar events</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-es-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Event
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stats row --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Events',  'value'=>$stats['total'],    'icon'=>'bi-trophy',       'color'=>'es-navy'],
        ['label'=>'Upcoming',      'value'=>$stats['upcoming'], 'icon'=>'bi-calendar-check','color'=>'success'],
        ['label'=>'Past',          'value'=>$stats['past'],     'icon'=>'bi-clock-history', 'color'=>'secondary'],
        ['label'=>'Flagship',      'value'=>$stats['flagship'], 'icon'=>'bi-star-fill',     'color'=>'warning'],
    ] as $s)
    <div class="col-6 col-md-3">
        <div class="card border h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon text-{{ $s['color'] }}">
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

{{-- Filter tabs + search --}}
<div class="card border mb-4">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            {{-- Filter pills --}}
            <div class="d-flex flex-wrap gap-2">
                @foreach([
                    'all'      => ['All Events',       'bi-list-ul'],
                    'upcoming' => ['Upcoming',          'bi-calendar-check'],
                    'past'     => ['Past',              'bi-clock-history'],
                    'featured' => ['Featured',          'bi-star'],
                    'flagship' => ['Flagship Coverage', 'bi-broadcast'],
                ] as $key => [$label, $icon])
                <a href="{{ route('admin.events.index', array_merge(request()->except('filter','page'), ['filter'=>$key])) }}"
                   class="filter-pill {{ $filter === $key ? 'active' : '' }}">
                    <i class="bi {{ $icon }} me-1"></i>{{ $label }}
                </a>
                @endforeach
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.events.index') }}" class="d-flex gap-2">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="input-group input-group-sm" style="min-width:220px;">
                    <span class="input-group-text border-end-0 bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           value="{{ $search }}" placeholder="Search title or location…">
                    @if($search)
                    <a href="{{ route('admin.events.index', ['filter'=>$filter]) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Events grid --}}
@if($events->count() > 0)

<div class="row g-3 mb-3">
    @foreach($events as $event)
    @php
        $isPast     = $event->event_date->isPast();
        $isFlagship = $event->is_flagship ?? false;
        $imgSrc     = $event->image
            ? asset('storage/'.$event->image)
            : ($event->art_image_url ?: null);
    @endphp
    <div class="col-12 col-md-6 col-xl-4">
        <div class="event-card card border h-100 {{ $isFlagship ? 'flagship-card' : '' }}">

            {{-- Image / placeholder --}}
            <div class="event-thumb" style="height:160px; overflow:hidden; position:relative; background:#f4f6f9;">
                @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $event->title }}"
                         style="width:100%;height:100%;object-fit:cover;display:block;">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-flag" style="font-size:2.5rem;color:#c8d0dc;"></i>
                    </div>
                @endif

                {{-- Flagship ribbon --}}
                @if($isFlagship)
                <div class="flagship-ribbon">
                    <i class="bi bi-broadcast me-1"></i>Flagship
                </div>
                @endif

                {{-- Time badge --}}
                <div style="position:absolute;bottom:10px;left:10px;">
                    <span class="badge {{ $isPast ? 'bg-secondary' : 'bg-success' }}">
                        {{ $isPast ? 'Past' : 'Upcoming' }}
                    </span>
                    @if($event->is_featured)
                    <span class="badge bg-warning text-dark ms-1">
                        <i class="bi bi-star-fill"></i> Featured
                    </span>
                    @endif
                    @if(!$event->is_active)
                    <span class="badge bg-danger ms-1">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="card-body d-flex flex-column pb-2">
                {{-- Title --}}
                <h6 class="fw-800 mb-1 lh-sm" style="color:var(--es-navy);">
                    {{ $event->title }}
                    @if($event->subtitle)
                        <span class="d-block fw-500 text-muted" style="font-size:0.75rem;">{{ $event->subtitle }}</span>
                    @endif
                </h6>

                {{-- Meta --}}
                <div class="d-flex flex-wrap gap-3 my-2" style="font-size:0.75rem;color:#667085;">
                    <span><i class="bi bi-calendar3 me-1" style="color:var(--es-red);"></i>
                        {{ $event->event_date->format('M d, Y') }}</span>
                    <span><i class="bi bi-clock me-1" style="color:var(--es-red);"></i>
                        {{ $event->start_time->format('g:i A') }}</span>
                    <span><i class="bi bi-geo-alt me-1" style="color:var(--es-red);"></i>
                        {{ Str::limit($event->location, 28) }}</span>
                </div>

                @if($event->distance_options)
                <p class="text-muted mb-2" style="font-size:0.72rem;">
                    <i class="bi bi-signpost-split me-1"></i>{{ Str::limit($event->distance_options, 50) }}
                </p>
                @endif

                {{-- Spacer --}}
                <div class="mt-auto pt-2 border-top d-flex gap-2">
                    <a href="{{ route('admin.events.show', $event) }}"
                       class="btn btn-sm btn-outline-secondary flex-fill"
                       title="View details">
                        <i class="bi bi-eye"></i>
                        <span class="d-none d-sm-inline ms-1">View</span>
                    </a>
                    <a href="{{ route('admin.events.edit', $event) }}"
                       class="btn btn-sm btn-outline-primary flex-fill"
                       title="Edit event">
                        <i class="bi bi-pencil"></i>
                        <span class="d-none d-sm-inline ms-1">Edit</span>
                    </a>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            title="Delete event"
                            data-bs-toggle="modal"
                            data-bs-target="#del{{ $event->id }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete modal --}}
    <div class="modal fade" id="del{{ $event->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>Delete Event
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-1">
                    <p class="small mb-1">Delete <strong>{{ $event->title }}</strong>?</p>
                    <p class="small text-muted mb-0">This cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
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

{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center pt-2">
    <p class="text-muted small mb-0">
        Showing {{ $events->firstItem() }}–{{ $events->lastItem() }} of {{ $events->total() }} events
    </p>
    <div class="es-pagination">{{ $events->links() }}</div>
</div>

@else
{{-- Empty state --}}
<div class="card border">
    <div class="card-body text-center py-5">
        <i class="bi bi-calendar-x" style="font-size:2.5rem;color:#c8d0dc;display:block;margin-bottom:12px;"></i>
        <h5 class="fw-800 mb-2" style="color:var(--es-navy);">No events found</h5>
        <p class="text-muted small mb-4">
            @if($search)
                No results for "{{ $search }}".
                <a href="{{ route('admin.events.index', ['filter'=>$filter]) }}">Clear search</a>
            @elseif($filter !== 'all')
                No {{ $filter }} events yet.
                <a href="{{ route('admin.events.index') }}">View all events</a>
            @else
                Get started by adding your first race event.
            @endif
        </p>
        <a href="{{ route('admin.events.create') }}" class="btn btn-es-primary btn-sm">
            <i class="bi bi-plus-circle me-2"></i>Add Event
        </a>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .btn-es-primary { background:var(--es-red); border:none; color:#fff; font-weight:700; font-size:0.82rem; }
    .btn-es-primary:hover { background:#b51e2a; color:#fff; }

    .fw-800 { font-weight:800; }
    .fw-900 { font-weight:900; }
    .fw-500 { font-weight:500; }

    /* Filter pills */
    .filter-pill {
        display: inline-flex; align-items: center;
        padding: 5px 13px; border-radius: 20px;
        font-size: 0.78rem; font-weight: 700;
        border: 1px solid var(--es-line);
        color: #667085; background: #fff;
        text-decoration: none;
        transition: all .12s;
    }
    .filter-pill:hover { border-color: var(--es-red); color: var(--es-red); }
    .filter-pill.active { background: var(--es-navy); border-color: var(--es-navy); color: #fff; }

    /* Event cards */
    .event-card { border-radius: 8px; transition: border-color .15s; }
    .event-card:hover { border-color: var(--es-red) !important; }

    /* Flagship card accent */
    .flagship-card { border-left: 3px solid var(--es-red) !important; }
    .flagship-ribbon {
        position: absolute; top: 10px; left: 10px;
        background: var(--es-red); color: #fff;
        font-size: 0.68rem; font-weight: 800;
        padding: 3px 9px; border-radius: 20px;
        letter-spacing: .3px; text-transform: uppercase;
    }

    /* Stat icon bg circle */
    .stat-icon { width:42px;height:42px; border-radius:50%; background:var(--es-paper);
        display:flex;align-items:center;justify-content:center; flex-shrink:0; }
    .stat-icon .text-es-navy { color: var(--es-navy); }

    /* Search input */
    .input-group .form-control:focus { border-color: var(--es-red); box-shadow: none; }
    .input-group-text { color: #aaa; }

    /* Pagination */
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

    @media (max-width:576px) {
        .filter-pill span { display:none; }
        .filter-pill { padding:5px 10px; }
    }
</style>
@endpush

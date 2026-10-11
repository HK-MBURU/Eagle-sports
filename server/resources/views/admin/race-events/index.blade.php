@extends('layouts.admin')

@section('title', 'Race Events')
@section('page-title', 'Race Events')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>All Race Events</h4>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Event
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-primary active">
                    <i class="fas fa-list"></i> All Events
                </a>
                <a href="{{ route('admin.events.index', ['filter' => 'upcoming']) }}" class="btn btn-outline-primary">
                    <i class="fas fa-calendar-alt"></i> Upcoming
                </a>
                <a href="{{ route('admin.events.index', ['filter' => 'past']) }}" class="btn btn-outline-primary">
                    <i class="fas fa-history"></i> Past Events
                </a>
                <a href="{{ route('admin.events.index', ['filter' => 'featured']) }}" class="btn btn-outline-primary">
                    <i class="fas fa-star"></i> Featured
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($events->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Event Title</th>
                                        <th>Date & Time</th>
                                        <th>Location</th>
                                        <th>Distance Options</th>
                                        <th>Runners</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>
                                                @if($event->image)
                                                    <img src="{{ asset('storage/' . $event->image) }}"
                                                         alt="{{ $event->title }}"
                                                         class="img-thumbnail"
                                                         style="width: 80px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                                         style="width: 80px; height: 60px;">
                                                        <i class="fas fa-running fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $event->title }}</strong>
                                                @if($event->is_featured)
                                                    <span class="badge bg-warning text-dark ms-1">
                                                        <i class="fas fa-star"></i> Featured
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="fas fa-calendar"></i>
                                                    {{ $event->event_date->format('M d, Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i>
                                                    {{ $event->start_time->format('g:i A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-danger"></i>
                                                {{ $event->location }}
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $event->distance_options }}</small>
                                            </td>
                                            <td>
                                                @if($event->expected_runners)
                                                    <span class="badge bg-info">
                                                        {{ $event->expected_runners }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($event->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                                @if($event->event_date < now())
                                                    <span class="badge bg-dark">Past</span>
                                                @else
                                                    <span class="badge bg-primary">Upcoming</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.events.show', $event) }}"
                                                       class="btn btn-sm btn-info"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.events.edit', $event) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.events.destroy', $event) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this race event?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-danger"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-running fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No race events found. Add your first event!</p>
                            <a href="{{ route('admin.events.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Add Race Event
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'View Race Event')
@section('page-title', 'View Race Event')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Events
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-md-12 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $event->title }}</h5>
                    <div>
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.events.destroy', $event) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this race event?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($event->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $event->image) }}"
                                 alt="{{ $event->title }}"
                                 class="img-fluid rounded"
                                 style="max-height: 400px; width: 100%; object-fit: cover;">
                        </div>
                    @endif

                    <div class="d-flex gap-2 mb-4">
                        @if($event->is_featured)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> Featured Event
                            </span>
                        @endif
                        @if($event->is_active)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-times-circle"></i> Inactive
                            </span>
                        @endif
                        @if($event->event_date < now())
                            <span class="badge bg-dark">
                                <i class="fas fa-history"></i> Past Event
                            </span>
                        @else
                            <span class="badge bg-primary">
                                <i class="fas fa-calendar-check"></i> Upcoming
                            </span>
                        @endif
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-calendar text-primary"></i> Event Date
                                    </h6>
                                    <p class="mb-0 fw-bold fs-5">
                                        {{ $event->event_date->format('F j, Y') }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $event->event_date->format('l') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-clock text-primary"></i> Start Time
                                    </h6>
                                    <p class="mb-0 fw-bold fs-5">
                                        {{ $event->start_time->format('g:i A') }} EAT
                                    </p>
                                    <small class="text-muted">East Africa Time</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-map-marker-alt text-danger"></i> Location
                                    </h6>
                                    <p class="mb-0 fw-bold">{{ $event->location }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-users text-info"></i> Expected Runners
                                    </h6>
                                    <p class="mb-0 fw-bold">
                                        @if($event->expected_runners)
                                            {{ $event->expected_runners }}
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-route text-success"></i> Distance Options
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(explode(', ', $event->distance_options) as $distance)
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        {{ trim($distance) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if($event->description)
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-info-circle text-primary"></i> Event Description
                                </h6>
                                <p class="mb-0" style="white-space: pre-line;">{{ $event->description }}</p>
                            </div>
                        </div>
                    @endif

                    @if($event->registration_link)
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-link text-primary"></i> Registration
                                </h6>
                                <a href="{{ $event->registration_link }}"
                                   class="btn btn-primary"
                                   target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Register for Event
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-user-plus"></i> Created By
                                    </h6>
                                    <p class="mb-0 fw-bold">
                                        @if($event->creator)
                                            {{ $event->creator->name }}
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </p>
                                    @if($event->creator)
                                        <small class="text-muted">{{ $event->creator->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-calendar-plus"></i> Date Created
                                    </h6>
                                    <p class="mb-0 fw-bold">
                                        {{ $event->created_at->format('F j, Y') }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $event->created_at->format('g:i A') }} • {{ $event->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        @if($event->created_at != $event->updated_at)
                            <div class="col-12 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-clock"></i> Last Updated
                                        </h6>
                                        <p class="mb-0 fw-bold">
                                            {{ $event->updated_at->format('F j, Y') }} at {{ $event->updated_at->format('g:i A') }}
                                        </p>
                                        <small class="text-muted">{{ $event->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> All Events
                        </a>
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

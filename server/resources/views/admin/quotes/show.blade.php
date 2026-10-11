@extends('layouts.admin')

@section('title', 'View Quote')
@section('page-title', 'View Quote')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.quotes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Quotes
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Quote Details</h5>
                    <div>
                        <a href="{{ route('admin.quotes.edit', $quote) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.quotes.destroy', $quote) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this quote?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="quote-display mb-4 p-4 bg-light rounded">
                        <i class="fas fa-quote-left text-muted fa-2x mb-3"></i>
                        <p class="lead mb-3" style="font-style: italic; line-height: 1.8;">
                            {{ $quote->content }}
                        </p>
                        <i class="fas fa-quote-right text-muted fa-2x float-end"></i>
                        <div class="clearfix"></div>
                        <div class="mt-3 pt-3 border-top">
                            <p class="mb-0 fw-bold">— {{ $quote->owner }}</p>
                            @if($quote->owner_title)
                                <p class="text-muted mb-0">
                                    <small>{{ $quote->owner_title }}</small>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-user"></i> Quote Owner
                                    </h6>
                                    <p class="mb-0 fw-bold">{{ $quote->owner }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-crown"></i> Owner Title
                                    </h6>
                                    <p class="mb-0 fw-bold">
                                        @if($quote->owner_title)
                                            {{ $quote->owner_title }}
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-user-plus"></i> Added By
                                    </h6>
                                    <p class="mb-0 fw-bold">{{ $quote->addedBy->name }}</p>
                                    <small class="text-muted">{{ $quote->addedBy->email }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-calendar"></i> Date Added
                                    </h6>
                                    <p class="mb-0 fw-bold">{{ $quote->created_at->format('F d, Y') }}</p>
                                    <small class="text-muted">{{ $quote->created_at->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>

                        @if($quote->created_at != $quote->updated_at)
                            <div class="col-12 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-clock"></i> Last Updated
                                        </h6>
                                        <p class="mb-0 fw-bold">
                                            {{ $quote->updated_at->format('F d, Y') }} at {{ $quote->updated_at->format('h:i A') }}
                                        </p>
                                        <small class="text-muted">{{ $quote->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.quotes.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> All Quotes
                        </a>
                        <a href="{{ route('admin.quotes.edit', $quote) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

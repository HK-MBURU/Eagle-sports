@extends('layouts.admin')

@section('title', 'Edit Quote')
@section('page-title', 'Edit Quote')

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
                    <h5 class="mb-0">Edit Quote</h5>
                    <a href="{{ route('admin.quotes.show', $quote) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quotes.update', $quote) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="content" class="form-label">
                                Quote Content <span class="text-danger">*</span>
                            </label>
                            <textarea
                                name="content"
                                id="content"
                                class="form-control @error('content') is-invalid @enderror"
                                rows="5"
                                placeholder="Enter the quote content..."
                                required>{{ old('content', $quote->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                The main content of the quote
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="owner" class="form-label">
                                Quote Owner <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="owner"
                                id="owner"
                                class="form-control @error('owner') is-invalid @enderror"
                                value="{{ old('owner', $quote->owner) }}"
                                placeholder="e.g., Nelson Mandela"
                                required>
                            @error('owner')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                The person who said or wrote this quote
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="owner_title" class="form-label">
                                Owner Title
                            </label>
                            <input
                                type="text"
                                name="owner_title"
                                id="owner_title"
                                class="form-control @error('owner_title') is-invalid @enderror"
                                value="{{ old('owner_title', $quote->owner_title) }}"
                                placeholder="e.g., Marathon Champion, Author, CEO">
                            @error('owner_title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                Optional title or designation of the quote owner
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Added by:</strong> {{ $quote->addedBy->name }} on {{ $quote->created_at->format('M d, Y') }}
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.quotes.show', $quote) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Quote
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Danger Zone
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        Once you delete this quote, there is no going back. Please be certain.
                    </p>
                    <form action="{{ route('admin.quotes.destroy', $quote) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to permanently delete this quote? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Quote Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Add New Quote')
@section('page-title', 'Add New Quote')

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
                <div class="card-header">
                    <h5 class="mb-0">Create New Quote</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quotes.store') }}" method="POST">
                        @csrf

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
                                required>{{ old('content') }}</textarea>
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
                                value="{{ old('owner') }}"
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
                                value="{{ old('owner_title') }}"
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

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.quotes.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Quote
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

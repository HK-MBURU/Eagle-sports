@extends('layouts.admin')

@section('title', 'Quotes')
@section('page-title', 'Quotes')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>All Quotes</h4>
                <a href="{{ route('admin.quotes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Quote
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($quotes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Quote</th>
                                        <th>Owner</th>
                                        <th>Title</th>
                                        <th>Added By</th>
                                        <th>Date Added</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quotes as $quote)
                                        <tr>
                                            <td>{{ $quote->id }}</td>
                                            <td>
                                                <div class="quote-content" style="max-width: 400px;">
                                                    "{{ Str::limit($quote->content, 100) }}"
                                                </div>
                                            </td>
                                            <td>{{ $quote->owner }}</td>
                                            <td>
                                                @if($quote->owner_title)
                                                    <span class="badge bg-info">{{ $quote->owner_title }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $quote->addedBy->name }}</td>
                                            <td>{{ $quote->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.quotes.show', $quote) }}"
                                                       class="btn btn-sm btn-info"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.quotes.edit', $quote) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.quotes.destroy', $quote) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this quote?');">
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
                            {{ $quotes->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-quote-left fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No quotes found. Add your first quote!</p>
                            <a href="{{ route('admin.quotes.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Add Quote
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

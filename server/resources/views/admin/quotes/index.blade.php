@extends('layouts.admin')

@section('title', 'Quotes')
@section('page-title', 'Quotes')

@section('content')
<div class="container-fluid px-3 px-md-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">All Quotes</h4>
            <small class="text-muted">{{ $quotes->total() }} quote{{ $quotes->total() !== 1 ? 's' : '' }}</small>
        </div>
        <a href="{{ route('admin.quotes.create') }}" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-plus-lg me-1"></i> Add New Quote
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($quotes->count() > 0)

        {{-- ── MOBILE CARDS (< md) ── --}}
        <div class="d-md-none">
            <div class="d-flex flex-column gap-3">
                @foreach($quotes as $quote)
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-3">

                        {{-- Top row: ID + badge --}}
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="text-muted small">#{{ $quote->id }}</span>
                            @if($quote->owner_title)
                                <span class="badge rounded-pill text-white fw-semibold"
                                      style="background:#06b6d4;font-size:11px;">
                                    {{ Str::limit($quote->owner_title, 30) }}
                                </span>
                            @endif
                        </div>

                        {{-- Quote text --}}
                        <p class="mb-2 fw-medium" style="font-style:italic;color:#172235;line-height:1.5;font-size:15px;">
                            &ldquo;{{ Str::limit($quote->content, 120) }}&rdquo;
                        </p>

                        {{-- Owner + date --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold text-dark" style="font-size:13px;">
                                <i class="bi bi-person-fill me-1 text-primary"></i>{{ $quote->owner }}
                            </span>
                            <span class="text-muted" style="font-size:12px;">
                                <i class="bi bi-calendar3 me-1"></i>{{ $quote->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-flex gap-2 pt-1">
                            <a href="{{ route('admin.quotes.show', $quote) }}"
                               class="btn btn-sm flex-fill fw-semibold text-white"
                               style="background:#0891b2;border:none;">
                                <i class="bi bi-eye-fill me-1"></i>View
                            </a>
                            <a href="{{ route('admin.quotes.edit', $quote) }}"
                               class="btn btn-sm flex-fill fw-semibold text-white"
                               style="background:#d97706;border:none;">
                                <i class="bi bi-pencil-fill me-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.quotes.destroy', $quote) }}"
                                  method="POST" class="flex-fill"
                                  onsubmit="return confirm('Delete this quote?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm w-100 fw-semibold text-white"
                                        style="background:#dc2626;border:none;">
                                    <i class="bi bi-trash-fill me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── DESKTOP TABLE (≥ md) ── --}}
        <div class="d-none d-md-block">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width:48px">#</th>
                                    <th>Quote</th>
                                    <th>Owner</th>
                                    <th>Title</th>
                                    <th>Added By</th>
                                    <th>Date</th>
                                    <th class="pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotes as $quote)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $quote->id }}</td>
                                    <td style="max-width:360px">
                                        <span class="fst-italic">&ldquo;{{ Str::limit($quote->content, 100) }}&rdquo;</span>
                                    </td>
                                    <td class="fw-semibold">{{ $quote->owner }}</td>
                                    <td>
                                        @if($quote->owner_title)
                                            <span class="badge rounded-pill text-white" style="background:#06b6d4">
                                                {{ Str::limit($quote->owner_title, 25) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $quote->addedBy->name }}</td>
                                    <td class="text-muted text-nowrap">{{ $quote->created_at->format('M d, Y') }}</td>
                                    <td class="pe-4">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('admin.quotes.show', $quote) }}"
                                               class="btn btn-sm fw-semibold text-white px-3"
                                               style="background:#0891b2;border:none;min-width:72px;">
                                                <i class="bi bi-eye-fill me-1"></i>View
                                            </a>
                                            <a href="{{ route('admin.quotes.edit', $quote) }}"
                                               class="btn btn-sm fw-semibold text-white px-3"
                                               style="background:#d97706;border:none;min-width:72px;">
                                                <i class="bi bi-pencil-fill me-1"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.quotes.destroy', $quote) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete this quote?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm fw-semibold text-white px-3"
                                                        style="background:#dc2626;border:none;min-width:80px;">
                                                    <i class="bi bi-trash-fill me-1"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $quotes->links() }}
        </div>

    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-quote display-4 text-muted mb-3 d-block"></i>
                <p class="text-muted mb-3">No quotes yet. Add your first one!</p>
                <a href="{{ route('admin.quotes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Add Quote
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

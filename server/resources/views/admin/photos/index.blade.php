@extends('layouts.admin')

@section('title', 'Photo Gallery Management')
@section('page-title', 'Photo Gallery')

@section('content')
<div class="photos-admin-wrap">

    {{-- Header --}}
    <div class="pg-header">
        <div class="pg-header-left">
            <h1 class="pg-title">Photo Gallery</h1>
            <span class="pg-count">{{ $photos->total() }} photo{{ $photos->total() !== 1 ? 's' : '' }}</span>
        </div>
        <a href="{{ route('admin.photos.create') }}" class="pg-add-btn">
            <i class="bi bi-plus-lg"></i> Add Photos
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="pg-alert pg-alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="pg-alert pg-alert-error"><i class="bi bi-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.photos.index') }}" class="pg-filters">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search photos…" class="pg-filter-input">

        <select name="category" class="pg-filter-select">
            <option value="">All Categories</option>
            @foreach($categories as $key => $label)
                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>

        <select name="source_type" class="pg-filter-select">
            <option value="">All Sources</option>
            <option value="upload"        {{ request('source_type') === 'upload'        ? 'selected' : '' }}>Direct Upload</option>
            <option value="story"         {{ request('source_type') === 'story'         ? 'selected' : '' }}>From Stories</option>
            <option value="women_running" {{ request('source_type') === 'women_running' ? 'selected' : '' }}>Women Athletes</option>
            <option value="race_event"    {{ request('source_type') === 'race_event'    ? 'selected' : '' }}>Race Events</option>
        </select>

        <select name="status" class="pg-filter-select">
            <option value="">All Statuses</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
        </select>

        <button type="submit" class="pg-filter-btn"><i class="bi bi-search"></i> Filter</button>
        @if(request()->hasAny(['search','category','source_type','status']))
            <a href="{{ route('admin.photos.index') }}" class="pg-clear-btn">Clear</a>
        @endif
    </form>

    {{-- Grid --}}
    @if($photos->isEmpty())
        <div class="pg-empty">
            <i class="bi bi-images" style="font-size:48px;color:#b0bec5;"></i>
            <p>No photos yet. <a href="{{ route('admin.photos.create') }}">Add some photos</a> to get started.</p>
        </div>
    @else
        <div class="pg-grid">
            @foreach($photos as $photo)
            <div class="pg-card {{ $photo->status === 'draft' ? 'pg-card-draft' : '' }}">
                <div class="pg-card-img-wrap">
                    <img src="{{ $photo->display_url }}" alt="{{ $photo->alt_text ?? $photo->title }}"
                         loading="lazy" onerror="this.style.display='none'">
                    @if($photo->is_featured)
                        <span class="pg-badge-featured"><i class="bi bi-star-fill"></i></span>
                    @endif
                    @if($photo->status === 'draft')
                        <span class="pg-badge-draft">Draft</span>
                    @endif
                    <div class="pg-card-overlay">
                        <a href="{{ route('admin.photos.edit', $photo) }}" class="pg-ov-btn" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.photos.toggleFeatured', $photo) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="pg-ov-btn {{ $photo->is_featured ? 'pg-ov-active' : '' }}" title="{{ $photo->is_featured ? 'Unfeature' : 'Feature' }}">
                                <i class="bi bi-star{{ $photo->is_featured ? '-fill' : '' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}"
                              onsubmit="return confirm('Remove this photo from the gallery?')" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="pg-ov-btn pg-ov-danger" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div class="pg-card-body">
                    <p class="pg-card-title" title="{{ $photo->title }}">{{ Str::limit($photo->title, 50) }}</p>
                    <span class="pg-card-cat">{{ $photo->category_name }}</span>
                    <span class="pg-card-source">
                        @if($photo->source_type === 'upload') <i class="bi bi-upload"></i>
                        @elseif($photo->source_type === 'story') <i class="bi bi-newspaper"></i>
                        @elseif($photo->source_type === 'women_running') <i class="bi bi-person"></i>
                        @else <i class="bi bi-calendar-event"></i>
                        @endif
                        {{ ucfirst(str_replace('_', ' ', $photo->source_type)) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pg-pagination">
            {{ $photos->links() }}
        </div>
    @endif

</div>

<style>
.photos-admin-wrap { padding: 24px; }
.pg-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; gap:12px; flex-wrap:wrap; }
.pg-header-left { display:flex; align-items:center; gap:12px; }
.pg-title { margin:0; font-size:22px; font-weight:800; color:#10284a; }
.pg-count { background:#e8edf5; color:#5a6a7e; font-size:13px; font-weight:600; padding:4px 10px; border-radius:20px; }
.pg-add-btn { display:inline-flex; align-items:center; gap:6px; background:#10284a; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700; }
.pg-add-btn:hover { background:#d62835; color:#fff; }

.pg-alert { padding:10px 16px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px; }
.pg-alert-success { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
.pg-alert-error { background:#ffebee; color:#c62828; border:1px solid #ef9a9a; }

.pg-filters { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:24px; align-items:center; }
.pg-filter-input { padding:8px 14px; border:1.5px solid #dde3eb; border-radius:8px; font-size:13px; min-width:200px; flex:1 1 180px; }
.pg-filter-select { padding:8px 14px; border:1.5px solid #dde3eb; border-radius:8px; font-size:13px; background:#fff; cursor:pointer; }
.pg-filter-btn { padding:8px 18px; background:#10284a; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.pg-clear-btn { padding:8px 14px; background:#f4f6f9; color:#5a6a7e; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600; }

.pg-empty { text-align:center; padding:64px 24px; color:#667085; }
.pg-empty p { margin:12px 0 0; font-size:14px; }
.pg-empty a { color:#d62835; font-weight:700; }

.pg-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:16px; }
.pg-card { border-radius:10px; overflow:hidden; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.08); border:1.5px solid #e8edf5; }
.pg-card-draft { opacity:.75; }
.pg-card-img-wrap { position:relative; aspect-ratio:4/3; overflow:hidden; background:#dde3eb; }
.pg-card-img-wrap img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .2s; }
.pg-card:hover .pg-card-img-wrap img { transform:scale(1.04); }
.pg-badge-featured { position:absolute; top:8px; right:8px; background:#f59e0b; color:#fff; border-radius:50%; width:26px; height:26px; display:grid; place-items:center; font-size:12px; z-index:2; }
.pg-badge-draft { position:absolute; top:8px; left:8px; background:#64748b; color:#fff; border-radius:4px; font-size:10px; font-weight:700; padding:2px 6px; z-index:2; }
.pg-card-overlay { position:absolute; inset:0; background:rgba(10,25,47,.65); display:flex; align-items:center; justify-content:center; gap:8px; opacity:0; transition:opacity .15s; z-index:3; }
.pg-card:hover .pg-card-overlay { opacity:1; }
.pg-ov-btn { background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.4); color:#fff; width:36px; height:36px; border-radius:8px; display:grid; place-items:center; cursor:pointer; font-size:14px; transition:background .15s; }
.pg-ov-btn:hover { background:rgba(255,255,255,.3); }
.pg-ov-active { background:#f59e0b !important; border-color:#f59e0b !important; }
.pg-ov-danger:hover { background:#ef4444 !important; border-color:#ef4444 !important; }
.pg-card-body { padding:10px 12px; }
.pg-card-title { margin:0 0 5px; font-size:12px; font-weight:700; color:#10284a; line-height:1.3; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pg-card-cat { display:inline-block; font-size:10px; background:#e8edf5; color:#5a6a7e; border-radius:4px; padding:2px 7px; font-weight:600; margin-right:4px; }
.pg-card-source { display:inline-flex; align-items:center; gap:3px; font-size:10px; color:#9aa5b4; }
.pg-pagination { margin-top:28px; display:flex; justify-content:center; }
</style>
@endsection

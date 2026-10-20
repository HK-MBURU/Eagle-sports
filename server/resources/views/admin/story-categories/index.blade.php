@extends('layouts.admin')

@section('title', 'Story Categories')
@section('page-title', 'Story Categories')

@push('styles')
<style>
/* ── stat cards ── */
.cat-stat {
    background:#fff; border:1px solid var(--es-line); border-radius:10px;
    padding:14px 18px; display:flex; align-items:center; gap:14px;
}
.cat-stat-icon {
    width:42px; height:42px; border-radius:9px;
    display:flex; align-items:center; justify-content:center;
    font-size:1.1rem; color:#fff; flex-shrink:0;
}
.cat-stat-body .num { font-size:1.45rem; font-weight:900; color:var(--es-navy); line-height:1; }
.cat-stat-body .lbl { font-size:.72rem; color:var(--es-muted); font-weight:600;
                      text-transform:uppercase; letter-spacing:.4px; margin-top:3px; }

/* ── tree table ── */
.cat-table { width:100%; border-collapse:collapse; }
.cat-table th {
    font-size:.72rem; font-weight:800; text-transform:uppercase;
    letter-spacing:.5px; color:var(--es-muted); background:#f8fafc;
    border-bottom:2px solid var(--es-line); padding:9px 12px;
}
.cat-table td { vertical-align:middle; padding:10px 12px; border-bottom:1px solid #f1f4f8; }

/* parent rows */
.cat-parent-row { background:#fff; cursor:default; }
.cat-parent-row:hover td { background:#fafbfc; }
.cat-parent-row td:first-child { padding-left:14px; }

/* sub rows — hidden by default */
.cat-sub-rows { display:none; }
.cat-sub-rows.open { display:table-row-group; }
.cat-sub-row td { background:#f9fafb; border-bottom:1px solid #eef1f6; }
.cat-sub-row:hover td { background:#f3f5f9; }
.cat-sub-row td:first-child { padding-left:14px; }

/* orphan section */
.cat-orphan-row td { background:#fffbeb; border-bottom:1px solid #fde68a; }

/* sub indent indicator */
.sub-indent {
    display:inline-flex; align-items:center; gap:6px;
    padding-left:22px; position:relative;
}
.sub-indent::before {
    content:''; position:absolute; left:10px; top:50%;
    width:10px; height:1px; background:#d1d5db;
}

/* expand toggle */
.expand-btn {
    width:22px; height:22px; border:1px solid #d1d5db; border-radius:4px;
    background:#fff; display:inline-flex; align-items:center; justify-content:center;
    cursor:pointer; font-size:.65rem; color:#6b7280; flex-shrink:0;
    transition:background .12s, border-color .12s;
}
.expand-btn:hover { background:#f1f5f9; border-color:#9ca3af; }
.expand-btn.open  { background:var(--es-red); border-color:var(--es-red); color:#fff; }

/* color swatch */
.color-swatch {
    width:22px; height:22px; border-radius:5px; display:inline-block;
    border:2px solid rgba(0,0,0,.08); flex-shrink:0; vertical-align:middle;
}

/* sub-count chip */
.sub-chip {
    display:inline-flex; align-items:center; gap:4px; font-size:.7rem;
    font-weight:700; padding:2px 7px; border-radius:100px;
    background:#eff6ff; color:#2563eb; margin-left:6px;
}

/* type badge */
.type-badge {
    font-size:.68rem; font-weight:800; padding:2px 8px; border-radius:100px;
    text-transform:uppercase; letter-spacing:.4px;
}
.type-parent { background:#fef3c7; color:#b45309; }
.type-sub    { background:#ede9fe; color:#6d28d9; }
.type-orphan { background:#fef9c3; color:#92400e; }

/* story count badge */
.story-badge {
    display:inline-flex; align-items:center; gap:3px; font-size:.7rem;
    font-weight:700; padding:2px 7px; border-radius:100px;
}

/* active toggle */
.es-toggle {
    position:relative; width:38px; height:20px; cursor:pointer; display:inline-block;
}
.es-toggle input { opacity:0; width:0; height:0; }
.es-slider {
    position:absolute; inset:0; background:#d1d5db; border-radius:10px; transition:background .2s;
}
.es-slider:before {
    content:''; position:absolute;
    width:14px; height:14px; border-radius:50%; background:#fff;
    left:3px; top:3px; transition:transform .2s; box-shadow:0 1px 3px rgba(0,0,0,.18);
}
input:checked + .es-slider { background:var(--es-red); }
input:checked + .es-slider:before { transform:translateX(18px); }

/* action buttons */
.btn-act {
    width:30px; height:30px; border-radius:6px; border:1px solid var(--es-line);
    background:#fff; display:inline-flex; align-items:center; justify-content:center;
    font-size:.8rem; cursor:pointer; transition:border-color .12s, background .12s;
    text-decoration:none; color:var(--es-navy);
}
.btn-act:hover         { background:#f1f4f8; border-color:#b0bcc8; color:var(--es-navy); }
.btn-act.add:hover     { border-color:#16a34a; color:#16a34a; background:#f0fdf4; }
.btn-act.edit:hover    { border-color:#2563eb; color:#2563eb; background:#eff6ff; }
.btn-act.del:hover     { border-color:var(--es-red); color:var(--es-red); background:#fff5f5; }

/* color picker */
.color-pick-wrap { display:flex; align-items:center; gap:8px; }
.color-pick-wrap input[type="color"] {
    width:38px; height:34px; border:1px solid #d1d5db; border-radius:5px;
    padding:2px; cursor:pointer; background:#fff;
}

/* empty / orphan heading */
.section-heading {
    font-size:.75rem; font-weight:900; text-transform:uppercase; letter-spacing:.6px;
    color:var(--es-muted); padding:9px 14px 7px; background:#f8fafc;
    border-bottom:1px solid var(--es-line);
}

/* no-items */
.cat-empty { text-align:center; padding:52px 20px; }
.cat-empty i { font-size:2.6rem; color:#d0d8e4; display:block; margin-bottom:12px; }
.cat-empty p { font-size:.84rem; color:var(--es-muted); margin-bottom:18px; }
</style>
@endpush

@section('content')

{{-- Header ---------------------------------------------------------------- --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h2 class="mb-0 fs-4 fw-bold">Story Categories</h2>
        <p class="text-muted mb-0 small">{{ $stats['top_level'] }} top-level categories with {{ $stats['sub'] }} subcategories</p>
    </div>
    <button class="btn btn-primary flex-shrink-0" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle me-2"></i>Add Category
    </button>
</div>

{{-- Flash alerts ----------------------------------------------------------- --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="bi bi-check-circle-fill flex-shrink-0"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
    <span>{{ session('error') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Fix the following errors:</strong>
    <ul class="mb-0 mt-1 ps-3">
        @foreach($errors->all() as $e)
        <li style="font-size:.84rem">{{ $e }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stats strip ------------------------------------------------------------ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="cat-stat">
            <div class="cat-stat-icon" style="background:#d62835"><i class="bi bi-tags-fill"></i></div>
            <div class="cat-stat-body"><div class="num">{{ $stats['top_level'] }}</div><div class="lbl">Categories</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="cat-stat">
            <div class="cat-stat-icon" style="background:#7c3aed"><i class="bi bi-diagram-2-fill"></i></div>
            <div class="cat-stat-body"><div class="num">{{ $stats['sub'] }}</div><div class="lbl">Subcategories</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="cat-stat">
            <div class="cat-stat-icon" style="background:#16a34a"><i class="bi bi-check-circle-fill"></i></div>
            <div class="cat-stat-body"><div class="num">{{ $stats['active'] }}</div><div class="lbl">Active</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="cat-stat">
            <div class="cat-stat-icon" style="background:#0891b2"><i class="bi bi-newspaper"></i></div>
            <div class="cat-stat-body"><div class="num">{{ $stats['covered'] }}</div><div class="lbl">Stories Tagged</div></div>
        </div>
    </div>
</div>

{{-- Category tree ---------------------------------------------------------- --}}
<div class="card border-0 shadow-sm" style="overflow:hidden">

    @if($parents->isEmpty() && $orphans->isEmpty())
    <div class="cat-empty">
        <i class="bi bi-tags"></i>
        <p>No categories yet. Click "Add Category" to get started.</p>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus me-1"></i>Add Category
        </button>
    </div>
    @else
    <div class="table-responsive">
    <table class="cat-table">
        <thead>
            <tr>
                <th style="width:50px"></th>
                <th>Category / Subcategory</th>
                <th class="d-none d-md-table-cell">Description</th>
                <th style="width:90px">Active</th>
                <th style="width:140px">Stories</th>
                <th style="width:70px" class="d-none d-sm-table-cell">Order</th>
                <th style="width:110px" class="text-end">Actions</th>
            </tr>
        </thead>

        {{-- Top-level categories + children -------------------------------- --}}
        @foreach($parents as $cat)
        @php $hasChildren = $cat->children->isNotEmpty(); @endphp

        {{-- Parent row --}}
        <tbody class="cat-parent-body">
        <tr class="cat-parent-row">
            {{-- Expand toggle + color --}}
            <td>
                <div style="display:flex;align-items:center;gap:7px">
                    @if($hasChildren)
                    <button class="expand-btn" data-target="subs-{{ $cat->id }}" title="Toggle subcategories">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    @else
                    <span style="width:22px"></span>
                    @endif
                    <span class="color-swatch" style="background:{{ $cat->color }}"></span>
                </div>
            </td>

            {{-- Name --}}
            <td>
                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                    <span class="fw-bold" style="font-size:.88rem;color:var(--es-navy)">{{ $cat->name }}</span>
                    @if($hasChildren)
                    <span class="sub-chip"><i class="bi bi-diagram-2" style="font-size:.65rem"></i>{{ $cat->children->count() }} sub</span>
                    @endif
                </div>
                <div class="text-muted" style="font-size:.7rem">{{ $cat->slug }}</div>
            </td>

            {{-- Description --}}
            <td class="d-none d-md-table-cell">
                @if($cat->description)
                <span style="font-size:.81rem;color:#4b5563">{{ Str::limit($cat->description, 70) }}</span>
                @else
                <span class="text-muted fst-italic" style="font-size:.76rem">—</span>
                @endif
            </td>

            {{-- Active --}}
            <td>
                <label class="es-toggle" title="{{ $cat->is_active ? 'Deactivate' : 'Activate' }}">
                    <input type="checkbox" class="active-toggle" data-id="{{ $cat->id }}" {{ $cat->is_active ? 'checked' : '' }}>
                    <span class="es-slider"></span>
                </label>
            </td>

            {{-- Stories --}}
            <td>
                <div style="display:flex;gap:4px;flex-wrap:wrap;align-items:center">
                    <span class="story-badge" style="background:#f1f5f9;color:#334155">
                        <i class="bi bi-file-text" style="font-size:.62rem"></i>{{ $cat->stories_total }}
                    </span>
                    @if($cat->stories_published > 0)
                    <span class="story-badge" style="background:#dcfce7;color:#15803d">
                        <i class="bi bi-check2" style="font-size:.62rem"></i>{{ $cat->stories_published }}
                    </span>
                    @endif
                </div>
            </td>

            {{-- Sort --}}
            <td class="d-none d-sm-table-cell">
                <span style="font-size:.78rem;color:var(--es-muted);font-weight:600">{{ $cat->sort_order }}</span>
            </td>

            {{-- Actions --}}
            <td class="text-end">
                <div style="display:flex;gap:4px;justify-content:flex-end">
                    {{-- Add sub --}}
                    <button type="button" class="btn-act add add-sub-btn" title="Add subcategory"
                        data-parent-id="{{ $cat->id }}" data-parent-name="{{ $cat->name }}">
                        <i class="bi bi-diagram-2"></i>
                    </button>
                    {{-- Edit --}}
                    <button type="button" class="btn-act edit" title="Edit"
                        data-bs-toggle="modal" data-bs-target="#editModal"
                        data-id="{{ $cat->id }}"
                        data-name="{{ $cat->name }}"
                        data-description="{{ $cat->description }}"
                        data-color="{{ $cat->color }}"
                        data-sort="{{ $cat->sort_order }}"
                        data-active="{{ $cat->is_active ? '1' : '0' }}"
                        data-parent-id=""
                        data-url="{{ route('admin.story-categories.update', $cat->id) }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.story-categories.destroy', $cat->id) }}"
                          class="del-form d-inline"
                          data-name="{{ $cat->name }}"
                          data-own-count="{{ $cat->stories_total }}"
                          data-child-count="{{ $cat->children->count() }}"
                          data-child-stories="{{ $cat->children->sum('stories_total') }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-act del" title="Delete"><i class="bi bi-trash3"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        </tbody>

        {{-- Subcategory rows (collapsed by default) ------------------------ --}}
        @if($hasChildren)
        <tbody class="cat-sub-rows" id="subs-{{ $cat->id }}">
            @foreach($cat->children as $child)
            <tr class="cat-sub-row">
                {{-- Indent + color --}}
                <td>
                    <div class="sub-indent" style="padding-left:32px;position:relative">
                        <span class="color-swatch" style="background:{{ $child->color }}"></span>
                    </div>
                </td>

                {{-- Name --}}
                <td>
                    <div style="padding-left:20px">
                        <div style="display:flex;align-items:center;gap:5px">
                            <span style="color:#9ca3af;font-size:.8rem">└</span>
                            <span class="fw-semibold" style="font-size:.86rem;color:var(--es-navy)">{{ $child->name }}</span>
                        </div>
                        <div class="text-muted" style="font-size:.68rem;padding-left:14px">{{ $child->slug }}</div>
                    </div>
                </td>

                {{-- Description --}}
                <td class="d-none d-md-table-cell">
                    @if($child->description)
                    <span style="font-size:.79rem;color:#4b5563">{{ Str::limit($child->description, 70) }}</span>
                    @else
                    <span class="text-muted fst-italic" style="font-size:.74rem">—</span>
                    @endif
                </td>

                {{-- Active --}}
                <td>
                    <label class="es-toggle" title="{{ $child->is_active ? 'Deactivate' : 'Activate' }}">
                        <input type="checkbox" class="active-toggle" data-id="{{ $child->id }}" {{ $child->is_active ? 'checked' : '' }}>
                        <span class="es-slider"></span>
                    </label>
                </td>

                {{-- Stories --}}
                <td>
                    <div style="display:flex;gap:4px;flex-wrap:wrap;align-items:center">
                        <span class="story-badge" style="background:#f1f5f9;color:#334155">
                            <i class="bi bi-file-text" style="font-size:.62rem"></i>{{ $child->stories_total }}
                        </span>
                        @if($child->stories_published > 0)
                        <span class="story-badge" style="background:#dcfce7;color:#15803d">
                            <i class="bi bi-check2" style="font-size:.62rem"></i>{{ $child->stories_published }}
                        </span>
                        @endif
                    </div>
                </td>

                {{-- Sort --}}
                <td class="d-none d-sm-table-cell">
                    <span style="font-size:.78rem;color:var(--es-muted);font-weight:600">{{ $child->sort_order }}</span>
                </td>

                {{-- Actions --}}
                <td class="text-end">
                    <div style="display:flex;gap:4px;justify-content:flex-end">
                        <button type="button" class="btn-act edit" title="Edit"
                            data-bs-toggle="modal" data-bs-target="#editModal"
                            data-id="{{ $child->id }}"
                            data-name="{{ $child->name }}"
                            data-description="{{ $child->description }}"
                            data-color="{{ $child->color }}"
                            data-sort="{{ $child->sort_order }}"
                            data-active="{{ $child->is_active ? '1' : '0' }}"
                            data-parent-id="{{ $child->parent_id }}"
                            data-url="{{ route('admin.story-categories.update', $child->id) }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.story-categories.destroy', $child->id) }}"
                              class="del-form d-inline"
                              data-name="{{ $child->name }}"
                              data-own-count="{{ $child->stories_total }}"
                              data-child-count="0"
                              data-child-stories="0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-act del" title="Delete"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach

            {{-- Add subcategory inline row --}}
            <tr>
                <td colspan="7" style="padding:8px 12px 8px 58px;background:#f0f4ff;border-bottom:1px solid #dbe4ff">
                    <button type="button" class="btn btn-outline-primary btn-sm add-sub-btn py-1"
                        style="font-size:.75rem;border-style:dashed"
                        data-parent-id="{{ $cat->id }}" data-parent-name="{{ $cat->name }}">
                        <i class="bi bi-plus me-1"></i>Add subcategory to <strong>{{ $cat->name }}</strong>
                    </button>
                </td>
            </tr>
        </tbody>
        @endif
        @endforeach

        {{-- Orphaned subcategories ----------------------------------------- --}}
        @if($orphans->isNotEmpty())
        <tbody>
            <tr><td colspan="7" class="section-heading">
                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Orphaned Subcategories
                <span style="font-size:.7rem;font-weight:400;color:var(--es-muted)">(parent was deleted — edit to re-assign)</span>
            </td></tr>
            @foreach($orphans as $cat)
            <tr class="cat-orphan-row">
                <td><span class="color-swatch" style="background:{{ $cat->color }}"></span></td>
                <td>
                    <div class="fw-semibold" style="font-size:.86rem;color:var(--es-navy)">{{ $cat->name }}</div>
                    <div class="text-muted" style="font-size:.7rem">{{ $cat->slug }}</div>
                </td>
                <td class="d-none d-md-table-cell">
                    @if($cat->description)
                    <span style="font-size:.79rem;color:#4b5563">{{ Str::limit($cat->description, 70) }}</span>
                    @else<span class="text-muted fst-italic" style="font-size:.74rem">—</span>@endif
                </td>
                <td>
                    <label class="es-toggle">
                        <input type="checkbox" class="active-toggle" data-id="{{ $cat->id }}" {{ $cat->is_active ? 'checked' : '' }}>
                        <span class="es-slider"></span>
                    </label>
                </td>
                <td>
                    <span class="story-badge" style="background:#f1f5f9;color:#334155">
                        <i class="bi bi-file-text" style="font-size:.62rem"></i>{{ $cat->stories_total }}
                    </span>
                </td>
                <td class="d-none d-sm-table-cell"><span style="font-size:.78rem;color:var(--es-muted);font-weight:600">{{ $cat->sort_order }}</span></td>
                <td class="text-end">
                    <div style="display:flex;gap:4px;justify-content:flex-end">
                        <button type="button" class="btn-act edit" title="Edit"
                            data-bs-toggle="modal" data-bs-target="#editModal"
                            data-id="{{ $cat->id }}"
                            data-name="{{ $cat->name }}"
                            data-description="{{ $cat->description }}"
                            data-color="{{ $cat->color }}"
                            data-sort="{{ $cat->sort_order }}"
                            data-active="{{ $cat->is_active ? '1' : '0' }}"
                            data-parent-id="{{ $cat->parent_id }}"
                            data-url="{{ route('admin.story-categories.update', $cat->id) }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.story-categories.destroy', $cat->id) }}"
                              class="del-form d-inline"
                              data-name="{{ $cat->name }}"
                              data-own-count="{{ $cat->stories_total }}"
                              data-child-count="0"
                              data-child-stories="0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-act del" title="Delete"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        @endif

    </table>
    </div>
    @endif
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- ADD MODAL                                                            --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('admin.story-categories.store') }}">
                @csrf
                <div class="modal-header" style="border-bottom:1px solid var(--es-line)">
                    <h5 class="modal-title fw-bold" id="addModalLabel">
                        <i class="bi bi-plus-circle me-2" style="color:var(--es-red)"></i>
                        <span id="addModalTitle">Add Category</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding:20px 22px">

                    {{-- Parent Category --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Parent Category</label>
                        <select name="parent_id" id="addParentId" class="form-select form-select-sm">
                            <option value="">None — top-level category</option>
                            @foreach($topLevelOptions as $opt)
                            <option value="{{ $opt->id }}" data-color="{{ $opt->color }}">{{ $opt->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Leave blank to create a top-level category.</div>
                    </div>

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" maxlength="100"
                               placeholder="e.g. Road Running" required autofocus>
                        <div class="form-text">Renaming it later automatically re-tags all stories.</div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Description</label>
                        <textarea name="description" class="form-control" rows="2" maxlength="500"
                                  placeholder="Short description…"></textarea>
                    </div>

                    {{-- Color + Sort --}}
                    <div class="row g-3">
                        <div class="col-7">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Colour</label>
                            <div class="color-pick-wrap">
                                <input type="color" name="color" id="addColor" value="#6366f1">
                                <input type="text" id="addColorText" class="form-control"
                                       value="#6366f1" maxlength="7" placeholder="#6366f1"
                                       style="font-family:monospace;font-size:.83rem">
                            </div>
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0" max="9999">
                            <div class="form-text">Lower = higher up</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top:1px solid var(--es-line);gap:8px">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-check2 me-1"></i>Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- EDIT MODAL                                                           --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="editForm">
                @csrf @method('PUT')
                <div class="modal-header" style="border-bottom:1px solid var(--es-line)">
                    <h5 class="modal-title fw-bold" id="editModalLabel">
                        <i class="bi bi-pencil-square me-2" style="color:#2563eb"></i>Edit Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding:20px 22px">

                    {{-- Parent --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Parent Category</label>
                        <select name="parent_id" id="editParentId" class="form-select form-select-sm">
                            <option value="">None — top-level category</option>
                            @foreach($topLevelOptions as $opt)
                            <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="editName" class="form-control" maxlength="100" required>
                        <div class="form-text text-warning-emphasis" id="editRenameNote" style="display:none">
                            <i class="bi bi-exclamation-triangle me-1"></i>Renaming will re-tag all stories using this category.
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem">Description</label>
                        <textarea name="description" id="editDescription" class="form-control" rows="2" maxlength="500"></textarea>
                    </div>

                    {{-- Color + Sort --}}
                    <div class="row g-3 mb-3">
                        <div class="col-7">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Colour</label>
                            <div class="color-pick-wrap">
                                <input type="color" name="color" id="editColor">
                                <input type="text" id="editColorText" class="form-control"
                                       maxlength="7" placeholder="#6366f1"
                                       style="font-family:monospace;font-size:.83rem">
                            </div>
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold" style="font-size:.82rem">Sort Order</label>
                            <input type="number" name="sort_order" id="editSort" class="form-control" min="0" max="9999">
                        </div>
                    </div>

                    {{-- Active --}}
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="editActive" value="1">
                        <label class="form-check-label fw-semibold" for="editActive" style="font-size:.82rem">
                            Active (visible to story editors)
                        </label>
                    </div>
                </div>

                <div class="modal-footer" style="border-top:1px solid var(--es-line);gap:8px">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-check2 me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ── Color pickers ─────────────────────────────────────────────────────── */
function syncColor(pickerId, textId) {
    const picker = document.getElementById(pickerId);
    const text   = document.getElementById(textId);
    if (!picker || !text) return;
    picker.addEventListener('input', ()   => { text.value = picker.value; });
    text.addEventListener('input',   ()   => {
        if (/^#[0-9a-fA-F]{6}$/.test(text.value)) picker.value = text.value;
    });
}
syncColor('addColor', 'addColorText');
syncColor('editColor', 'editColorText');

/* ── "Add Subcategory" buttons ─────────────────────────────────────────── */
document.querySelectorAll('.add-sub-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const parentId   = btn.dataset.parentId;
        const parentName = btn.dataset.parentName;

        document.getElementById('addParentId').value = parentId;
        document.getElementById('addModalTitle').textContent =
            'Add Subcategory to "' + parentName + '"';

        new bootstrap.Modal(document.getElementById('addModal')).show();
    });
});

/* Reset title when add modal is opened normally */
document.getElementById('addModal').addEventListener('show.bs.modal', function (e) {
    if (!e.relatedTarget) return; // triggered by JS (add-sub-btn) — keep custom title
    // triggered by the main Add button — reset
    if (e.relatedTarget.classList.contains('add-sub-btn')) return;
    document.getElementById('addModalTitle').textContent = 'Add Category';
    document.getElementById('addParentId').value = '';
});

/* ── Edit modal population ─────────────────────────────────────────────── */
document.getElementById('editModal').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    if (!btn) return;

    const origName = btn.dataset.name;
    document.getElementById('editForm').action      = btn.dataset.url;
    document.getElementById('editName').value       = origName;
    document.getElementById('editName').dataset.orig = origName;
    document.getElementById('editDescription').value = btn.dataset.description || '';
    document.getElementById('editSort').value        = btn.dataset.sort || '0';
    document.getElementById('editActive').checked   = btn.dataset.active === '1';

    const parentSelect = document.getElementById('editParentId');
    parentSelect.value = btn.dataset.parentId || '';

    // Remove self from parent options (can't be own parent)
    const selfId = btn.dataset.id;
    Array.from(parentSelect.options).forEach(opt => {
        opt.disabled = opt.value === selfId;
    });

    const color = btn.dataset.color || '#6366f1';
    document.getElementById('editColor').value     = color;
    document.getElementById('editColorText').value = color;

    document.getElementById('editRenameNote').style.display = 'none';
});

document.getElementById('editName').addEventListener('input', function () {
    document.getElementById('editRenameNote').style.display =
        (this.value.trim() !== this.dataset.orig) ? '' : 'none';
});

/* ── Expand / collapse subcategory rows ────────────────────────────────── */
document.querySelectorAll('.expand-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const targetId = btn.dataset.target;
        const tbody    = document.getElementById(targetId);
        const icon     = btn.querySelector('i');
        if (!tbody) return;

        const isOpen = tbody.classList.toggle('open');
        btn.classList.toggle('open', isOpen);
        icon.className = isOpen ? 'bi bi-chevron-down' : 'bi bi-chevron-right';
    });
});

/* Auto-expand if there's a flash success that mentions a subcategory */
@if(session('success') && str_contains(session('success'), 'sub'))
document.querySelectorAll('.cat-sub-rows').forEach(tb => {
    tb.classList.add('open');
    const id  = tb.id.replace('subs-', '');
    const btn = document.querySelector('[data-target="subs-' + id + '"]');
    if (btn) {
        btn.classList.add('open');
        const icon = btn.querySelector('i');
        if (icon) icon.className = 'bi bi-chevron-down';
    }
});
@endif

/* ── Active toggle (AJAX) ──────────────────────────────────────────────── */
document.querySelectorAll('.active-toggle').forEach(toggle => {
    toggle.addEventListener('change', function () {
        const id = this.dataset.id;
        fetch(`/admin/story-categories/${id}/toggle-active`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(d => { if (!d.success) this.checked = !this.checked; })
        .catch(() => { this.checked = !this.checked; });
    });
});

/* ── Delete confirmation ───────────────────────────────────────────────── */
document.querySelectorAll('.del-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        const name        = this.dataset.name;
        const ownCount    = parseInt(this.dataset.ownCount,    10) || 0;
        const childCount  = parseInt(this.dataset.childCount,  10) || 0;
        const childStories= parseInt(this.dataset.childStories,10) || 0;

        if (ownCount > 0) {
            e.preventDefault();
            alert(`Cannot delete "${name}" — it has ${ownCount} ${ownCount===1?'story':'stories'} assigned.\nReassign or delete those stories first.`);
            return;
        }
        if (childStories > 0) {
            e.preventDefault();
            alert(`Cannot delete "${name}" — its subcategories have ${childStories} ${childStories===1?'story':'stories'} assigned.\nReassign them first.`);
            return;
        }

        let msg = `Delete category "${name}"?`;
        if (childCount > 0) {
            msg += `\n\nThis will promote its ${childCount} subcategor${childCount===1?'y':'ies'} to top-level (no stories will be lost).`;
        }
        msg += '\n\nThis cannot be undone.';

        if (!confirm(msg)) e.preventDefault();
    });
});
</script>
@endpush

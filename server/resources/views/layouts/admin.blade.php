<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Block admin from all search-engine crawlers --}}
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <title>@yield('title', 'Dashboard') — Eagle Sports Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --es-navy:   #10284a;
            --es-navy2:  #0d1e3b;
            --es-red:    #d62835;
            --es-red2:   #b51e2a;
            --es-line:   #e2e7ef;
            --es-paper:  #f4f6f9;
            --es-muted:  #667085;
            --sidebar-w: 250px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--es-paper);
            color: #1a2b45;
            overflow-x: hidden;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-w);
            background: var(--es-navy);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: left .25s ease;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 2px; }

        /* Logo area */
        .sidebar-header {
            padding: 22px 18px 18px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .sidebar-logo-wrap {
            width: 40px; height: 40px;
            border-radius: 8px;
            background: rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-logo-wrap img {
            width: 34px; height: 34px;
            object-fit: contain;
        }

        .sidebar-brand { line-height: 1.2; }
        .sidebar-brand-name {
            font-size: .9rem; font-weight: 900;
            color: #fff; letter-spacing: -.2px;
        }
        .sidebar-brand-name span { color: var(--es-red); }
        .sidebar-brand-sub {
            font-size: .7rem; color: rgba(255,255,255,.45);
            text-transform: uppercase; letter-spacing: .8px; font-weight: 700;
        }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 16px 0; }

        .nav-section-label {
            font-size: .65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,.3);
            padding: 14px 18px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 18px;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: .83rem;
            font-weight: 600;
            border-left: 3px solid transparent;
            transition: background .15s, color .15s, border-color .15s;
        }

        .sidebar-link i { font-size: 1rem; width: 18px; flex-shrink: 0; }

        .sidebar-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }

        .sidebar-link.active {
            background: rgba(214,40,53,.15);
            color: #fff;
            border-left-color: var(--es-red);
            font-weight: 700;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 16px 18px;
            border-top: 1px solid rgba(255,255,255,.08);
            flex-shrink: 0;
        }

        .btn-logout {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 9px 14px;
            border-radius: 6px;
            background: rgba(214,40,53,.12);
            border: 1px solid rgba(214,40,53,.35);
            color: rgba(255,255,255,.8);
            font-size: .83rem; font-weight: 700;
            cursor: pointer;
            transition: background .15s, color .15s;
        }

        .btn-logout:hover {
            background: var(--es-red);
            border-color: var(--es-red);
            color: #fff;
        }

        /* ── Main content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .25s ease;
        }

        /* Top bar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--es-line);
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 900;
        }

        .topbar-left { display: flex; align-items: center; gap: 14px; }

        .menu-toggle {
            display: none;
            background: none; border: none;
            font-size: 1.3rem; color: var(--es-navy); cursor: pointer; line-height: 1;
        }

        .page-title {
            font-size: 1.05rem; font-weight: 800;
            color: var(--es-navy); margin: 0;
        }

        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .site-link {
            font-size: .75rem; font-weight: 700;
            color: var(--es-muted);
            text-decoration: none;
            display: flex; align-items: center; gap: 5px;
            transition: color .15s;
        }
        .site-link:hover { color: var(--es-red); }

        .user-chip {
            display: flex; align-items: center; gap: 9px;
        }

        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--es-navy);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: .85rem;
            flex-shrink: 0;
        }

        .user-name {
            font-size: .83rem; font-weight: 700; color: var(--es-navy);
        }

        /* ── Content area ── */
        .content-area { padding: 28px; flex: 1; }

        /* ── Utility card / stat helpers ── */
        .stat-card {
            background: #fff;
            border: 1px solid var(--es-line);
            border-radius: 10px;
            padding: 22px;
            border-left: 4px solid var(--es-red);
        }

        .stat-card h6 {
            color: var(--es-muted);
            font-size: .75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px;
        }

        .stat-card h2 {
            color: var(--es-navy); font-size: 1.9rem; font-weight: 900; margin: 0;
        }

        .stat-card .icon {
            width: 44px; height: 44px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 12px;
        }

        .action-btn {
            background: #fff;
            border: 1px solid var(--es-line);
            border-radius: 10px;
            padding: 20px 16px;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: border-color .15s;
        }

        .action-btn:hover { border-color: var(--es-red); }

        .action-btn .icon {
            width: 52px; height: 52px; border-radius: 10px;
            margin: 0 auto 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #fff;
        }

        .action-btn h5 { color: var(--es-navy); font-size: .9rem; font-weight: 800; margin: 0; }
        .action-btn p  { color: var(--es-muted); font-size: .78rem; margin: 4px 0 0; }

        .section-title {
            font-size: 1rem; font-weight: 800; color: var(--es-navy);
            margin-bottom: 16px; padding-bottom: 8px;
            border-bottom: 2px solid var(--es-red);
            display: inline-block;
        }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .sidebar { left: calc(var(--sidebar-w) * -1); }
            .sidebar.open { left: 0; }
            .main-content { margin-left: 0; }
            .menu-toggle { display: block; }
            .user-name { display: none; }
            .content-area { padding: 18px; }
        }

        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.45); z-index: 999;
        }
        .sidebar-overlay.open { display: block; }

        /* ── Notification Bell ── */
        .notif-wrap { position: relative; }

        .notif-btn {
            position: relative;
            background: none; border: none; cursor: pointer;
            width: 38px; height: 38px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--es-muted); font-size: 1.15rem;
            transition: background .15s, color .15s;
        }
        .notif-btn:hover { background: var(--es-line); color: var(--es-navy); }
        .notif-btn.has-unread { color: var(--es-navy); }

        .notif-badge {
            position: absolute; top: 4px; right: 4px;
            min-width: 16px; height: 16px;
            background: var(--es-red); color: #fff;
            border-radius: 8px; font-size: .6rem; font-weight: 800;
            display: none; align-items: center; justify-content: center;
            padding: 0 3px; line-height: 1;
            border: 1.5px solid #fff;
        }

        .notif-dropdown {
            position: absolute; top: calc(100% + 8px); right: 0;
            width: 360px; max-width: calc(100vw - 20px);
            background: #fff; border: 1px solid var(--es-line);
            border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,.12);
            z-index: 2000; display: none; flex-direction: column;
            max-height: 480px; overflow: hidden;
        }
        .notif-dropdown.open { display: flex; }

        .notif-hdr {
            display: flex; align-items: center; justify-content: space-between;
            padding: 13px 16px 11px; border-bottom: 1px solid var(--es-line);
            flex-shrink: 0;
        }
        .notif-hdr-title { font-size: .88rem; font-weight: 800; color: var(--es-navy); }

        .notif-clear-btn {
            background: none; border: none; cursor: pointer;
            font-size: .75rem; font-weight: 700; color: var(--es-muted);
            display: flex; align-items: center; gap: 4px;
            padding: 4px 8px; border-radius: 5px;
            transition: background .15s, color .15s;
        }
        .notif-clear-btn:hover { background: var(--es-line); color: var(--es-navy); }

        .notif-body { flex: 1; overflow-y: auto; }
        .notif-body::-webkit-scrollbar { width: 3px; }
        .notif-body::-webkit-scrollbar-thumb { background: var(--es-line); border-radius: 2px; }

        .notif-item {
            display: flex; align-items: flex-start; gap: 11px;
            padding: 11px 14px; border-bottom: 1px solid #f4f6f9;
            cursor: pointer; transition: background .12s;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #f0f5ff; }
        .notif-item.unread:hover { background: #e7effe; }

        .notif-icon {
            width: 34px; height: 34px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem; flex-shrink: 0;
        }

        .notif-text { flex: 1; min-width: 0; }
        .notif-title-t { font-size: .81rem; font-weight: 700; color: var(--es-navy); line-height: 1.3; }
        .notif-msg-t {
            font-size: .77rem; color: var(--es-muted); line-height: 1.35;
            margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .notif-time-t { font-size: .7rem; color: #9ba6b7; margin-top: 3px; }

        .notif-del {
            background: none; border: none; cursor: pointer;
            color: #ccc; font-size: .95rem;
            padding: 2px 4px; border-radius: 4px;
            transition: color .12s, background .12s;
            flex-shrink: 0; align-self: center; opacity: 0;
        }
        .notif-item:hover .notif-del { opacity: 1; }
        .notif-del:hover { color: var(--es-red); background: #fff0f1; }

        .notif-empty {
            text-align: center; padding: 36px 16px; color: var(--es-muted);
        }
        .notif-empty i { font-size: 2.2rem; display: block; margin-bottom: 10px; color: #d0d8e4; }
        .notif-empty p { font-size: .83rem; margin: 0; }

        .notif-loading {
            display: flex; align-items: center; justify-content: center;
            padding: 36px; color: var(--es-muted); gap: 8px; font-size: .83rem;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo-wrap">
            <img src="{{ asset('images/es-logo.png') }}" alt="Eagle Sports">
        </div>
        <div class="sidebar-brand">
            <div class="sidebar-brand-name">EAGLE <span>SPORTS</span></div>
            <div class="sidebar-brand-sub">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>

        <div class="nav-section-label">Content</div>
        <a href="{{ route('admin.stories.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.stories.*') ? 'active' : '' }}">
            <i class="bi bi-newspaper"></i><span>Stories</span>
        </a>
        <a href="{{ route('admin.athletes.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.athletes.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i><span>Athletes</span>
        </a>
        <a href="{{ route('admin.women-running.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.women-running.*') ? 'active' : '' }}">
            <i class="bi bi-gender-female"></i><span>Women Running</span>
        </a>
        <a href="{{ route('admin.climate.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.climate.*') ? 'active' : '' }}">
            <i class="bi bi-tree"></i><span>Climate</span>
        </a>
        <a href="{{ route('admin.quotes.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.quotes.*') ? 'active' : '' }}">
            <i class="bi bi-chat-quote"></i><span>Quotes</span>
        </a>

        <div class="nav-section-label">Media</div>
        <a href="{{ route('admin.videos.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
            <i class="bi bi-play-circle"></i><span>Videos</span>
        </a>

        <div class="nav-section-label">Events & Results</div>
        <a href="{{ route('admin.events.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i><span>Race Events</span>
        </a>
        <a href="{{ route('admin.result-entries.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.result-entries.*') ? 'active' : '' }}">
            <i class="bi bi-stopwatch"></i><span>Performance Entries</span>
        </a>
        <a href="{{ route('admin.results.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-arrow-down"></i><span>Result Documents</span>
        </a>
        <a href="{{ route('admin.partners.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
            <i class="bi bi-briefcase"></i><span>Partners</span>
        </a>

        <div class="nav-section-label">System</div>
        <a href="{{ route('admin.users.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i><span>Users</span>
        </a>
        <a href="{{ route('admin.settings') }}"
           class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="bi bi-gear"></i><span>Settings</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ── Main ── --}}
<main class="main-content">
    {{-- Top bar --}}
    <nav class="topbar">
        <div class="topbar-left">
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <i class="bi bi-list"></i>
            </button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="topbar-right">
            <a href="{{ config('app.frontend_url', 'https://eaglesports.co.ke') }}"
               target="_blank" rel="noopener" class="site-link">
                <i class="bi bi-box-arrow-up-right"></i> View Site
            </a>

            {{-- Notification Bell --}}
            <div class="notif-wrap" id="notifWrap">
                <button class="notif-btn" id="notifBtn" aria-label="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="notif-badge" id="notifBadge"></span>
                </button>
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-hdr">
                        <span class="notif-hdr-title">Notifications</span>
                        <button class="notif-clear-btn" id="notifMarkAll">
                            <i class="bi bi-check2-all"></i> Mark all read
                        </button>
                    </div>
                    <div class="notif-body" id="notifList">
                        <div class="notif-loading">
                            <span class="spinner-border spinner-border-sm"></span> Loading…
                        </div>
                    </div>
                </div>
            </div>

            <div class="user-chip">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    <div class="content-area">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
                 style="border:none;border-left:4px solid #1c7847;background:#e8f5ed;color:#1c7847;border-radius:8px;">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
                 style="border:none;border-left:4px solid var(--es-red);background:#fcecee;color:var(--es-red);border-radius:8px;">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const menuToggle     = document.getElementById('menuToggle');
    const sidebar        = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    menuToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('open');
    });

    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('open');
    });

    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            try { new bootstrap.Alert(el).close(); } catch {}
        });
    }, 5000);
</script>

@stack('scripts')

<script>
// ── Notification Bell ─────────────────────────────────────────────────────────
(function () {
    const btn      = document.getElementById('notifBtn');
    const badge    = document.getElementById('notifBadge');
    const dropdown = document.getElementById('notifDropdown');
    const list     = document.getElementById('notifList');
    const markAll  = document.getElementById('notifMarkAll');
    const csrf     = document.querySelector('meta[name="csrf-token"]').content;
    let open       = false;

    function esc(s) {
        if (s == null) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function setBadge(n) {
        if (n > 0) {
            badge.style.display = 'flex';
            badge.textContent   = n > 99 ? '99+' : n;
            btn.classList.add('has-unread');
        } else {
            badge.style.display = 'none';
            btn.classList.remove('has-unread');
        }
    }

    function render(data) {
        setBadge(data.unread_count || 0);
        const items = data.notifications || [];
        if (!items.length) {
            list.innerHTML = `<div class="notif-empty"><i class="bi bi-bell-slash"></i><p>All caught up!</p></div>`;
            return;
        }
        list.innerHTML = items.map(n => `
            <div class="notif-item ${n.unread ? 'unread' : ''}" data-id="${n.id}"
                 onclick="window._notifClick(event,${n.id},'${esc(n.link)}')">
                <div class="notif-icon" style="background:${esc(n.bg||'#eff6ff')};color:${esc(n.color||'#2563eb')}">
                    <i class="bi bi-${esc(n.icon||'bell')}"></i>
                </div>
                <div class="notif-text">
                    <div class="notif-title-t">${esc(n.title)}</div>
                    <div class="notif-msg-t" title="${esc(n.message)}">${esc(n.message)}</div>
                    <div class="notif-time-t">${esc(n.time)}</div>
                </div>
                <button class="notif-del" title="Dismiss"
                        onclick="window._notifDel(event,${n.id})">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `).join('');
    }

    async function poll() {
        try {
            const r = await fetch('{{ route("admin.notifications.poll") }}',
                { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!r.ok) return;
            render(await r.json());
        } catch {}
    }

    function toggle() {
        open = !open;
        dropdown.classList.toggle('open', open);
        if (open) poll();
    }

    btn.addEventListener('click', e => { e.stopPropagation(); toggle(); });
    document.addEventListener('click', e => {
        if (open && !dropdown.contains(e.target) && e.target !== btn) {
            open = false; dropdown.classList.remove('open');
        }
    });

    markAll.addEventListener('click', async () => {
        await fetch('{{ route("admin.notifications.read-all") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' }
        });
        poll();
    });

    window._notifClick = async (e, id, link) => {
        e.stopPropagation();
        await fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' }
        }).catch(() => {});
        if (link && link !== 'null' && link !== '') window.location.href = link;
        else poll();
    };

    window._notifDel = async (e, id) => {
        e.stopPropagation();
        const el = document.querySelector(`.notif-item[data-id="${id}"]`);
        if (el) el.remove();
        const rem = document.querySelectorAll('.notif-item').length;
        if (!rem) list.innerHTML = `<div class="notif-empty"><i class="bi bi-bell-slash"></i><p>All caught up!</p></div>`;
        setBadge(document.querySelectorAll('.notif-item.unread').length);
        fetch(`/admin/notifications/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' }
        }).catch(() => {});
    };

    // Initial load + poll every 30 s
    poll();
    setInterval(poll, 30000);
})();
</script>
</body>
</html>

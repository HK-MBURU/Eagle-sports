@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
:root {
    --good:  #16a34a; --good-bg:  #dcfce7; --good-muted:  #86efac;
    --warn:  #d97706; --warn-bg:  #fef3c7; --warn-muted:  #fcd34d;
    --alert: #dc2626; --alert-bg: #fee2e2; --alert-muted: #fca5a5;
}

/* ── Welcome strip ── */
.dash-welcome {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
}
.dash-greeting h2 {
    font-size: 1.35rem; font-weight: 900; color: var(--es-navy); margin: 0;
}
.dash-greeting p  { font-size: .82rem; color: var(--es-muted); margin: 2px 0 0; }

.dash-notif-pill {
    display: inline-flex; align-items: center; gap: 7px;
    background: #fff0f1; border: 1px solid #fca5a5; color: var(--es-red);
    font-size: .78rem; font-weight: 800; padding: 6px 14px; border-radius: 100px;
    text-decoration: none; transition: background .15s;
    cursor: pointer; border: none;
}
.dash-notif-pill:hover { background: #fee2e2; color: var(--es-red); }

/* ── KPI row ── */
.kpi-row {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 28px;
}
@media (max-width: 1100px) { .kpi-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px)  { .kpi-row { grid-template-columns: 1fr; } }

.kpi-card {
    background: #fff; border: 1px solid var(--es-line); border-radius: 12px;
    padding: 20px 22px; display: flex; align-items: center; gap: 16px;
    text-decoration: none; color: inherit;
    transition: border-color .15s, box-shadow .15s;
    border-top: 3px solid transparent;
}
.kpi-card:hover { border-color: var(--es-red); box-shadow: 0 4px 16px rgba(0,0,0,.07); }
.kpi-card:hover .kpi-icon { transform: scale(1.06); }

.kpi-icon {
    width: 52px; height: 52px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #fff; flex-shrink: 0;
    transition: transform .2s;
}
.kpi-body { flex: 1; min-width: 0; }
.kpi-val  { font-size: 2rem; font-weight: 900; color: var(--es-navy); line-height: 1; }
.kpi-lbl  { font-size: .77rem; color: var(--es-muted); font-weight: 700; margin-top: 4px;
            text-transform: uppercase; letter-spacing: .4px; }
.kpi-sub  { font-size: .72rem; color: #a0aec0; margin-top: 2px; }

/* ── Content counts grid ── */
.counts-grid {
    display: grid; grid-template-columns: repeat(5, 1fr);
    gap: 12px; margin-bottom: 28px;
}
@media (max-width: 1100px) { .counts-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 680px)  { .counts-grid { grid-template-columns: repeat(2, 1fr); } }

.count-card {
    background: #fff; border: 1px solid var(--es-line); border-radius: 10px;
    padding: 16px; display: flex; align-items: center; gap: 12px;
    text-decoration: none; color: inherit;
    transition: border-color .15s, box-shadow .15s;
}
.count-card:hover { border-color: var(--es-red); box-shadow: 0 2px 10px rgba(0,0,0,.06); }

.count-icon {
    width: 40px; height: 40px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #fff; flex-shrink: 0;
}
.count-body { flex: 1; min-width: 0; }
.count-num { font-size: 1.5rem; font-weight: 900; color: var(--es-navy); line-height: 1; }
.count-lbl { font-size: .73rem; color: var(--es-muted); font-weight: 600;
             white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* ── Section title ── */
.dash-sh {
    display: flex; align-items: baseline; gap: 10px; margin-bottom: 14px;
}
.dash-sh h3 {
    font-size: 1rem; font-weight: 800; color: var(--es-navy);
    padding-bottom: 4px; border-bottom: 2px solid var(--es-red);
}
.dash-sh small { font-size: .75rem; color: var(--es-muted); }

/* ── Health grid ── */
.health-grid {
    display: grid; grid-template-columns: repeat(5, 1fr);
    gap: 12px; margin-bottom: 28px;
}
@media (max-width: 1100px) { .health-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 680px)  { .health-grid { grid-template-columns: repeat(2, 1fr); } }

.health-card {
    background: #fff; border: 1px solid var(--es-line); border-radius: 10px;
    padding: 15px 14px; text-decoration: none; color: inherit;
    transition: box-shadow .15s, border-color .15s;
    position: relative; overflow: hidden;
}
.health-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); border-color: #c0cee0; }
.health-card .hc-stripe {
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
}
.health-card.good  .hc-stripe { background: var(--good); }
.health-card.warn  .hc-stripe { background: var(--warn); }
.health-card.alert .hc-stripe { background: var(--alert); }

.hc-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.hc-icon {
    width: 34px; height: 34px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem;
}
.health-card.good  .hc-icon { background: var(--good-bg);  color: var(--good); }
.health-card.warn  .hc-icon { background: var(--warn-bg);  color: var(--warn); }
.health-card.alert .hc-icon { background: var(--alert-bg); color: var(--alert); }

.hc-badge {
    font-size: .65rem; font-weight: 800; padding: 3px 7px; border-radius: 100px;
    text-transform: uppercase; letter-spacing: .5px;
}
.health-card.good  .hc-badge { background: var(--good-bg);  color: var(--good); }
.health-card.warn  .hc-badge { background: var(--warn-bg);  color: var(--warn); }
.health-card.alert .hc-badge { background: var(--alert-bg); color: var(--alert); }

.hc-label { font-size: .8rem; font-weight: 700; color: var(--es-navy); margin-bottom: 2px; }
.hc-count { font-size: 1.25rem; font-weight: 900; color: var(--es-navy); line-height: 1; }
.hc-days  { font-size: .7rem; color: var(--es-muted); margin-top: 3px; }
.health-card.alert .hc-days { color: var(--alert); font-weight: 700; }
.health-card.warn  .hc-days { color: var(--warn);  font-weight: 700; }

/* ── Bottom two columns ── */
.dash-bottom {
    display: grid; grid-template-columns: 1fr 360px;
    gap: 20px; align-items: start;
}
@media (max-width: 1000px) { .dash-bottom { grid-template-columns: 1fr; } }

/* Activity feed */
.activity-panel {
    background: #fff; border: 1px solid var(--es-line); border-radius: 12px;
    overflow: hidden;
}
.panel-hdr {
    padding: 15px 18px 13px; border-bottom: 1px solid var(--es-line);
    display: flex; align-items: center; justify-content: space-between;
}
.panel-hdr h4 { font-size: .9rem; font-weight: 800; color: var(--es-navy); margin: 0; }
.panel-hdr small { font-size: .73rem; color: var(--es-muted); }

.activity-list { max-height: 420px; overflow-y: auto; }
.activity-list::-webkit-scrollbar { width: 3px; }
.activity-list::-webkit-scrollbar-thumb { background: var(--es-line); border-radius: 2px; }

.activity-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 11px 18px; border-bottom: 1px solid #f4f6f9;
    text-decoration: none; color: inherit;
    transition: background .12s;
}
.activity-item:last-child { border-bottom: none; }
.activity-item:hover { background: #f8fafc; }

.act-icon {
    width: 32px; height: 32px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .88rem; flex-shrink: 0; margin-top: 1px;
}
.act-body { flex: 1; min-width: 0; }
.act-msg  { font-size: .81rem; color: var(--es-navy); line-height: 1.4;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.act-time { font-size: .7rem; color: #9ba6b7; margin-top: 3px; }

.act-empty { text-align: center; padding: 48px 20px; }
.act-empty i { font-size: 2.4rem; color: #d0d8e4; display: block; margin-bottom: 10px; }
.act-empty p { font-size: .82rem; color: var(--es-muted); margin: 0; }

/* Quick Actions */
.actions-panel {
    background: #fff; border: 1px solid var(--es-line); border-radius: 12px;
    overflow: hidden;
}
.qa-grid { padding: 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.qa-btn {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 8px; padding: 16px 10px; border-radius: 8px;
    border: 1px solid var(--es-line); background: #fff;
    text-decoration: none; color: var(--es-navy);
    transition: border-color .15s, background .15s;
    text-align: center;
}
.qa-btn:hover { border-color: var(--es-red); background: #fff8f8; color: var(--es-navy); }
.qa-btn:hover .qa-icon { transform: scale(1.08); }

.qa-icon {
    width: 42px; height: 42px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #fff; transition: transform .2s;
}
.qa-label { font-size: .75rem; font-weight: 700; line-height: 1.2; }
</style>
@endpush

@section('content')

{{-- Welcome strip --}}
<div class="dash-welcome">
    <div class="dash-greeting">
        <h2>{{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }}, {{ Str::words(Auth::user()->name ?? 'Admin', 1, '') }}!</h2>
        <p>{{ now()->format('l, d F Y') }} &mdash; Eagle Sports Admin Panel</p>
    </div>
    @if($unreadNotifications > 0)
    <button class="dash-notif-pill" onclick="document.getElementById('notifBtn').click()">
        <i class="bi bi-bell-fill"></i>
        {{ $unreadNotifications }} unread notification{{ $unreadNotifications !== 1 ? 's' : '' }}
    </button>
    @endif
</div>

{{-- KPI row --}}
<div class="kpi-row">
    <a href="{{ route('admin.stories.index') }}" class="kpi-card" style="border-top-color:#d62835">
        <div class="kpi-icon" style="background:#d62835"><i class="bi bi-newspaper"></i></div>
        <div class="kpi-body">
            <div class="kpi-val">{{ $publishedStories }}</div>
            <div class="kpi-lbl">Published Stories</div>
            <div class="kpi-sub">{{ $storiesCount }} total</div>
        </div>
    </a>
    <a href="{{ route('admin.events.index') }}" class="kpi-card" style="border-top-color:#7c3aed">
        <div class="kpi-icon" style="background:#7c3aed"><i class="bi bi-calendar-event"></i></div>
        <div class="kpi-body">
            <div class="kpi-val">{{ $upcomingEvents }}</div>
            <div class="kpi-lbl">Upcoming Events</div>
            <div class="kpi-sub">{{ $eventsCount }} total</div>
        </div>
    </a>
    <a href="{{ route('admin.users.index') }}" class="kpi-card" style="border-top-color:#16a34a">
        <div class="kpi-icon" style="background:#16a34a"><i class="bi bi-people"></i></div>
        <div class="kpi-body">
            <div class="kpi-val">{{ $usersCount }}</div>
            <div class="kpi-lbl">Registered Users</div>
            <div class="kpi-sub">{{ $commentsCount }} comment{{ $commentsCount !== 1 ? 's' : '' }}</div>
        </div>
    </a>
    <a href="{{ route('admin.result-entries.index') }}" class="kpi-card" style="border-top-color:#0891b2">
        <div class="kpi-icon" style="background:#0891b2"><i class="bi bi-stopwatch"></i></div>
        <div class="kpi-body">
            <div class="kpi-val">{{ $performanceCount }}</div>
            <div class="kpi-lbl">Performance Entries</div>
            <div class="kpi-sub">{{ $resultDocsCount }} result doc{{ $resultDocsCount !== 1 ? 's' : '' }}</div>
        </div>
    </a>
</div>

{{-- Content counts --}}
<div class="dash-sh">
    <h3>All Content</h3>
    <small>Complete inventory across all site sections</small>
</div>
<div class="counts-grid">
    @php
    $contentCards = [
        ['route' => 'admin.athletes.index',         'icon' => 'person-badge',             'bg' => '#2563eb', 'count' => $athletesCount,     'label' => 'Athletes'],
        ['route' => 'admin.videos.index',           'icon' => 'play-circle',              'bg' => '#0891b2', 'count' => $videosCount,        'label' => 'Videos'],
        ['route' => 'admin.women-running.index',    'icon' => 'gender-female',            'bg' => '#db2777', 'count' => $womenRunningCount,  'label' => 'Women Running'],
        ['route' => 'admin.climate.index',          'icon' => 'tree',                     'bg' => '#15803d', 'count' => $climateCount,       'label' => 'Climate'],
        ['route' => 'admin.partners.index',         'icon' => 'briefcase',                'bg' => '#7c3aed', 'count' => $partnersCount,      'label' => 'Partners'],
        ['route' => 'admin.quotes.index',           'icon' => 'chat-quote',               'bg' => '#d97706', 'count' => $quotesCount,        'label' => 'Quotes'],
        ['route' => 'admin.results.index',          'icon' => 'file-earmark-arrow-down',  'bg' => '#6366f1', 'count' => $resultDocsCount,    'label' => 'Result Docs'],
        ['route' => 'admin.stories.index',          'icon' => 'newspaper',                'bg' => '#d62835', 'count' => $storiesCount,       'label' => 'All Stories'],
        ['route' => 'admin.story-categories.index', 'icon' => 'tags-fill',                'bg' => '#f97316', 'count' => $categoriesCount,    'label' => 'Categories'],
        ['route' => 'admin.events.index',           'icon' => 'calendar-event',           'bg' => '#7c3aed', 'count' => $eventsCount,        'label' => 'Race Events'],
        ['route' => 'admin.users.index',            'icon' => 'person-gear',              'bg' => '#16a34a', 'count' => $usersCount,         'label' => 'Users'],
    ];
    @endphp
    @foreach($contentCards as $c)
    <a href="{{ route($c['route']) }}" class="count-card">
        <div class="count-icon" style="background:{{ $c['bg'] }}">
            <i class="bi bi-{{ $c['icon'] }}"></i>
        </div>
        <div class="count-body">
            <div class="count-num">{{ $c['count'] }}</div>
            <div class="count-lbl">{{ $c['label'] }}</div>
        </div>
    </a>
    @endforeach
</div>

{{-- Section health --}}
<div class="dash-sh">
    <h3>Content Health</h3>
    <small>Days since last item added &mdash; <span style="color:var(--good)">&#9679;</span> Good &nbsp;<span style="color:var(--warn)">&#9679;</span> Due &nbsp;<span style="color:var(--alert)">&#9679;</span> Overdue</small>
</div>
<div class="health-grid">
    @foreach($health as $s)
    <a href="{{ route($s['route']) }}" class="health-card {{ $s['status'] }}">
        <div class="hc-stripe"></div>
        <div class="hc-top">
            <div class="hc-icon"><i class="bi bi-{{ $s['icon'] }}"></i></div>
            <span class="hc-badge">
                @if($s['status'] === 'good') Good
                @elseif($s['status'] === 'warn') Due
                @else Overdue @endif
            </span>
        </div>
        <div class="hc-label">{{ $s['label'] }}</div>
        <div class="hc-count">{{ $s['count'] }}</div>
        <div class="hc-days">
            @if($s['days_since'] === null)
                No content yet
            @elseif($s['days_since'] === 0)
                Updated today
            @elseif($s['days_since'] === 1)
                Yesterday
            @else
                {{ $s['days_since'] }}d ago
            @endif
        </div>
    </a>
    @endforeach
</div>

{{-- Bottom: Activity + Quick Actions --}}
<div class="dash-bottom">

    {{-- Recent Activity --}}
    <div class="activity-panel">
        <div class="panel-hdr">
            <h4><i class="bi bi-activity me-2" style="color:var(--es-red)"></i>Recent Activity</h4>
            <small>Last 15 events across the site</small>
        </div>
        <div class="activity-list">
            @forelse($recentActivity as $act)
            @if(!empty($act['link']))
            <a href="{{ $act['link'] }}" class="activity-item">
            @else
            <div class="activity-item">
            @endif
                <div class="act-icon" style="background:{{ $act['color'] }}22;color:{{ $act['color'] }}">
                    <i class="bi bi-{{ $act['icon'] }}"></i>
                </div>
                <div class="act-body">
                    <div class="act-msg">{{ $act['message'] }}</div>
                    <div class="act-time">{{ \Carbon\Carbon::parse($act['time'])->diffForHumans() }}</div>
                </div>
            @if(!empty($act['link']))
            </a>
            @else
            </div>
            @endif
            @empty
            <div class="act-empty">
                <i class="bi bi-inbox"></i>
                <p>No recent activity yet</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="actions-panel">
        <div class="panel-hdr">
            <h4><i class="bi bi-lightning-charge me-2" style="color:var(--es-red)"></i>Quick Actions</h4>
        </div>
        <div class="qa-grid">
            @php
            $actions = [
                ['href' => route('admin.stories.create'),         'icon' => 'newspaper',              'bg' => '#d62835', 'label' => 'New Story'],
                ['href' => route('admin.story-categories.index'),'icon' => 'tags-fill',              'bg' => '#f97316', 'label' => 'Categories'],
                ['href' => route('admin.athletes.create'),       'icon' => 'person-plus',            'bg' => '#2563eb', 'label' => 'Add Athlete'],
                ['href' => route('admin.events.create'),        'icon' => 'calendar-plus',          'bg' => '#7c3aed', 'label' => 'Add Event'],
                ['href' => route('admin.videos.create'),        'icon' => 'camera-video',           'bg' => '#0891b2', 'label' => 'Add Video'],
                ['href' => route('admin.women-running.create'), 'icon' => 'gender-female',          'bg' => '#db2777', 'label' => 'Women Running'],
                ['href' => route('admin.climate.create'),       'icon' => 'tree',                   'bg' => '#15803d', 'label' => 'Climate'],
                ['href' => route('admin.quotes.create'),        'icon' => 'chat-quote',             'bg' => '#d97706', 'label' => 'Add Quote'],
                ['href' => route('admin.partners.create'),      'icon' => 'briefcase',              'bg' => '#7c3aed', 'label' => 'Add Partner'],
                ['href' => route('admin.result-entries.create'),'icon' => 'stopwatch',              'bg' => '#0891b2', 'label' => 'Add Result'],
                ['href' => route('admin.results.create'),       'icon' => 'file-earmark-plus',      'bg' => '#6366f1', 'label' => 'Upload Doc'],
            ];
            @endphp
            @foreach($actions as $a)
            <a href="{{ $a['href'] }}" class="qa-btn">
                <div class="qa-icon" style="background:{{ $a['bg'] }}">
                    <i class="bi bi-{{ $a['icon'] }}"></i>
                </div>
                <span class="qa-label">{{ $a['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection

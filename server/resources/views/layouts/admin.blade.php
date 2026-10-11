<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Beyond Finish Line</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --bfl-gold: #E2B34A;
            --bfl-brown: #997557;
            --bfl-orange: #FF6600;
            --bfl-dark: #333A12;
            --bfl-yellow: #FFBF00;
            --bfl-green: #0F3D22;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--bfl-dark) 0%, var(--bfl-green) 100%);
            padding: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            width: 80px;
            height: 80px;
            background: var(--bfl-green);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .sidebar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar-title {
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .sidebar-subtitle {
            color: var(--bfl-gold);
            font-size: 0.85rem;
            margin: 5px 0 0;
        }

        /* Navigation Menu */
        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-section-title {
            color: var(--bfl-gold);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px 10px;
            margin-top: 10px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: var(--bfl-orange);
        }

        .nav-link.active {
            background: rgba(255, 102, 0, 0.2);
            color: white;
            border-left-color: var(--bfl-orange);
            font-weight: 600;
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 30px;
            margin-right: 12px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navigation Bar */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--bfl-dark);
            cursor: pointer;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--bfl-dark);
            margin: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            position: relative;
            font-size: 1.3rem;
            color: var(--bfl-dark);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .notification-icon:hover {
            color: var(--bfl-orange);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--bfl-orange);
            color: white;
            font-size: 0.7rem;
            padding: 2px 5px;
            border-radius: 10px;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--bfl-orange), var(--bfl-yellow));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--bfl-dark);
            font-size: 0.95rem;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Logout Button in Sidebar */
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .btn-logout {
            width: 100%;
            background: rgba(255, 102, 0, 0.2);
            color: white;
            border: 1px solid var(--bfl-orange);
            padding: 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: var(--bfl-orange);
            color: white;
        }

        /* Dashboard Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--bfl-orange);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-card h6 {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card h2 {
            color: var(--bfl-dark);
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        /* Quick Action Buttons */
        .action-btn {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            height: 100%;
        }

        .action-btn:hover {
            border-color: var(--bfl-orange);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 102, 0, 0.15);
        }

        .action-btn .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .action-btn h5 {
            color: var(--bfl-dark);
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .action-btn p {
            color: #6c757d;
            font-size: 0.85rem;
            margin: 5px 0 0;
        }

        /* Section Titles */
        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--bfl-dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--bfl-orange);
            display: inline-block;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: calc(var(--sidebar-width) * -1);
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .content-area {
                padding: 20px;
            }

            .user-name {
                display: none;
            }
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo4.png') }}" alt="Beyond Finish Line Logo">
            </div>
            <h5 class="sidebar-title">Beyond Finish Line</h5>
            <p class="sidebar-subtitle">Admin Panel</p>
        </div>

        <nav class="sidebar-nav">
            <!-- Main Menu -->
            <div class="nav-section-title">Main</div>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <!-- Content Management -->
            <div class="nav-section-title">Content</div>
            <a href="{{ route('admin.athletes.index') }}"
                class="nav-link {{ request()->routeIs('admin.athletes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Athletes</span>
            </a>
            <a href="{{ route('admin.women-running.index') }}"
                class="nav-link {{ request()->routeIs('admin.women-running.*') ? 'active' : '' }}">
                <i class="bi bi-gender-female"></i>
                <span>Women Running</span>
            </a>
            <a href="{{ route('admin.climate.index') }}"
                class="nav-link {{ request()->routeIs('admin.climate.*') ? 'active' : '' }}">
                <i class="bi bi-tree"></i>
                <span>Climate</span>
            </a>

            <a href="{{ route('admin.results.index') }}"
                class="nav-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                <i class="bi bi-trophy"></i>

                <span>Race Results</span>
            </a>

            <!-- Media -->
            <div class="nav-section-title">Media</div>
            <a href="{{ route('admin.videos.index') }}"
                class="nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                <i class="bi bi-play-circle"></i>
                <span>Videos</span>
            </a>

            <a href="{{ route('admin.stories.index') }}"
                class="nav-link {{ request()->routeIs('admin.stories.*') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span>Stories</span>
            </a>
            <a href="{{ route('admin.quotes.index') }}"
                class="nav-link {{ request()->routeIs('admin.quotes.*') ? 'active' : '' }}">
                <i class="bi bi-chat-quote"></i>
                <span>Quotes</span>
            </a>

            <!-- Events & Partners -->
            <div class="nav-section-title">Events & Partners</div>
            <a href="{{ route('admin.events.index') }}"
                class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i>
                <span> Race Events</span>
            </a>
            <a href="{{ route('admin.partners.index') }}"
                class="nav-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Partners</span>
            </a>

            <!-- Settings -->
            <div class="nav-section-title">System</div>
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.settings') }}"
                class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </nav>

        <!-- Sidebar Footer with Logout -->
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle" id="menuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="user-menu">
                <div class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">3</span>
                </div>

                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>

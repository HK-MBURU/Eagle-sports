@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Statistics Overview -->
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Statistics Overview</h2>
            <div class="section-subtitle">Quick overview of your content</div>
        </div>
        <div class="stats-grid">
            <!-- Stories -->
            <a href="{{ route('admin.stories.index') }}" class="stat-card">
                <div class="stat-icon" style="background-color: #997557;">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $storiesCount ?? 0 }}</div>
                    <div class="stat-label">Stories Published</div>
                </div>
                <div class="stat-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- Athletes -->
            <a href="{{ route('admin.athletes.index') }}" class="stat-card">
                <div class="stat-icon" style="background-color: #FF6600;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $athletesCount ?? 0 }}</div>
                    <div class="stat-label">Featured Athletes</div>
                </div>
                <div class="stat-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- Events -->
            <a href="{{ route('admin.events.index') }}" class="stat-card">
                <div class="stat-icon" style="background-color: #0F3D22;">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $eventsCount ?? 0 }}</div>
                    <div class="stat-label">Active Events</div>
                </div>
                <div class="stat-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- Videos -->
            <a href="{{ route('admin.videos.index') }}" class="stat-card">
                <div class="stat-icon" style="background-color: #FF6600;">
                    <i class="bi bi-play-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $videosCount ?? 0 }}</div>
                    <div class="stat-label">Total Videos</div>
                </div>
                <div class="stat-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- Women Running -->
            <a href="{{ route('admin.women-running.index') }}" class="stat-card">
                <div class="stat-icon" style="background-color: #997557;">
                    <i class="bi bi-gender-female"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $womenRunningCount ?? 0 }}</div>
                    <div class="stat-label">Women Running Posts</div>
                </div>
                <div class="stat-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- Climate -->
            <a href="{{ route('admin.climate.index') }}" class="stat-card">
                <div class="stat-icon" style="background-color: #0F3D22;">
                    <i class="bi bi-tree-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $climateCount ?? 0 }}</div>
                    <div class="stat-label">Climate Articles</div>
                </div>
                <div class="stat-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- Partners -->
            <a href="{{ route('admin.partners.index') }}" class="stat-card">
                <div class="stat-icon" style="background-color: #333A12;">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $partnersCount ?? 0 }}</div>
                    <div class="stat-label">Active Partners</div>
                </div>
                <div class="stat-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- Quotes -->
            <a href="{{ route('admin.quotes.index') }}" class="stat-card">
                <div class="stat-icon" style="background-color: #E2B34A;">
                    <i class="bi bi-chat-quote-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $quotesCount ?? 0 }}</div>
                    <div class="stat-label">Inspiring Quotes</div>
                </div>
                <div class="stat-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Quick Actions</h2>
            <div class="section-subtitle">Common management tasks</div>
        </div>
        <div class="actions-grid">
            <!-- Add Story -->
            <a href="{{ route('admin.stories.create') }}" class="action-card">
                <div class="action-icon" style="background-color: #997557;">
                    <i class="bi bi-book-half"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Share Story</h3>
                    <p class="action-description">Add athlete story</p>
                </div>
            </a>

            <!-- Add Athlete -->
            <a href="{{ route('admin.athletes.create') }}" class="action-card">
                <div class="action-icon" style="background-color: #FF6600;">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Add Athlete</h3>
                    <p class="action-description">Register new athlete</p>
                </div>
            </a>

            <!-- Add Event -->
            <a href="{{ route('admin.events.create') }}" class="action-card">
                <div class="action-icon" style="background-color: #0F3D22;">
                    <i class="bi bi-calendar-plus-fill"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Create Event</h3>
                    <p class="action-description">Schedule new event</p>
                </div>
            </a>

            <!-- Add Video -->
            <a href="{{ route('admin.videos.create') }}" class="action-card">
                <div class="action-icon" style="background-color: #FF6600;">
                    <i class="bi bi-camera-video-fill"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Upload Video</h3>
                    <p class="action-description">Add new video</p>
                </div>
            </a>

            <!-- Add Women Running -->
            <a href="{{ route('admin.women-running.create') }}" class="action-card">
                <div class="action-icon" style="background-color: #997557;">
                    <i class="bi bi-gender-female"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Women Running</h3>
                    <p class="action-description">Add new content</p>
                </div>
            </a>

            <!-- Add Climate -->
            <a href="{{ route('admin.climate.create') }}" class="action-card">
                <div class="action-icon" style="background-color: #0F3D22;">
                    <i class="bi bi-tree-fill"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Climate Action</h3>
                    <p class="action-description">Add climate content</p>
                </div>
            </a>

            <!-- Add Quote -->
            <a href="{{ route('admin.quotes.create') }}" class="action-card">
                <div class="action-icon" style="background-color: #E2B34A;">
                    <i class="bi bi-chat-left-quote-fill"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Add Quote</h3>
                    <p class="action-description">Inspirational quote</p>
                </div>
            </a>

            <!-- Add Partner -->
            <a href="{{ route('admin.partners.create') }}" class="action-card">
                <div class="action-icon" style="background-color: #333A12;">
                    <i class="bi bi-building-fill-add"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Add Partner</h3>
                    <p class="action-description">Register new partner</p>
                </div>
            </a>

            <!-- Manage Users -->
            <a href="{{ route('admin.users.index') }}" class="action-card">
                <div class="action-icon" style="background-color: #333A12;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Manage Users</h3>
                    <p class="action-description">View all users</p>
                </div>
            </a>

            <!-- Settings -->
            <a href="{{ route('admin.settings') }}" class="action-card">
                <div class="action-icon" style="background-color: #0F3D22;">
                    <i class="bi bi-gear-fill"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Settings</h3>
                    <p class="action-description">System configuration</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Recent Activity</h2>
            <div class="section-subtitle">Latest system activities</div>
        </div>
        <div class="activity-card">
            <div class="activity-list">
                @forelse($recentActivities ?? [] as $activity)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-{{ $activity['icon'] ?? 'circle' }}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-message">{{ $activity['message'] ?? 'Activity' }}</div>
                            <div class="activity-time">{{ $activity['time'] ?? 'Just now' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
/* Base Styles */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Section Styles */
.section-container {
    margin-bottom: 40px;
}

.section-header {
    margin-bottom: 25px;
}

.section-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
}

.section-subtitle {
    font-size: 14px;
    color: #6c757d;
    margin: 0;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 10px;
}

.stat-card {
    display: flex;
    align-items: center;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background-color: currentColor;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.stat-card:hover {
    border-color: #3498db;
    transform: translateY(-2px);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card:hover .stat-arrow {
    transform: translateX(3px);
    color: #3498db;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.stat-icon i {
    font-size: 20px;
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.stat-arrow {
    color: #6c757d;
    transition: all 0.2s ease;
}

.stat-arrow i {
    font-size: 16px;
}

/* Actions Grid */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
}

.action-card {
    display: flex;
    align-items: center;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
}

.action-card:hover {
    border-color: #3498db;
    transform: translateY(-2px);
}

.action-card:hover .action-icon {
    transform: scale(1.05);
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.action-icon i {
    font-size: 20px;
    color: white;
}

.action-content {
    flex: 1;
}

.action-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.action-description {
    font-size: 13px;
    color: #6c757d;
    margin: 0;
    line-height: 1.4;
}

/* Activity Section */
.activity-card {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 16px 20px;
    border-bottom: 1px solid #f8f9fa;
    transition: background-color 0.2s ease;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background-color: #f8f9fa;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background-color: #e3f2fd;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
    color: #1976d2;
}

.activity-icon i {
    font-size: 14px;
}

.activity-content {
    flex: 1;
}

.activity-message {
    font-size: 14px;
    color: #333;
    margin-bottom: 4px;
    line-height: 1.4;
}

.activity-time {
    font-size: 12px;
    color: #6c757d;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    color: #dee2e6;
    display: block;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 15px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .stat-card {
        padding: 15px;
    }

    .action-card {
        padding: 15px;
    }

    .activity-item {
        padding: 12px 15px;
    }

    .section-title {
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .stat-card {
        flex-direction: column;
        text-align: center;
    }

    .stat-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }

    .stat-arrow {
        display: none;
    }

    .action-card {
        flex-direction: column;
        text-align: center;
    }

    .action-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }
}

/* Scrollbar Styling */
.activity-list::-webkit-scrollbar {
    width: 6px;
}

.activity-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.activity-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.activity-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection

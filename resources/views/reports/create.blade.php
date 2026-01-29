@extends('layouts.app')

@section('title', 'Reports - EcoScanner Pro')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-card: #ffffff;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --border-color: #e2e8f0;
        --accent-blue: #1e3a8a;
        --accent-blue-light: #3b82f6;
        --accent-red: #dc2626;
        --accent-red-light: #ef4444;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.15);
        --success: #10b981;
        --warning: #f59e0b;
        --info: #06b6d4;
    }

    [data-theme="dark"] {
        --bg-primary: #0a0e1a;
        --bg-secondary: #111827;
        --bg-card: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #cbd5e1;
        --text-muted: #94a3b8;
        --border-color: #334155;
        --accent-blue: #3b82f6;
        --accent-blue-light: #60a5fa;
        --accent-red: #ef4444;
        --accent-red-light: #f87171;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.3);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.4);
        --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Raleway', -apple-system, sans-serif;
        background: var(--bg-primary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Header */
    .reports-header {
        background: var(--bg-card);
        border-bottom: 2px solid var(--border-color);
        padding: 2rem 0;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }

    .header-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--accent-blue), var(--accent-red));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
        animation: slideInLeft 0.6s ease;
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    /* Theme Toggle */
    .theme-toggle {
        position: relative;
        width: 70px;
        height: 36px;
        background: var(--border-color);
        border-radius: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
    }

    .theme-toggle:hover {
        transform: scale(1.05);
    }

    .toggle-slider {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, var(--accent-blue), var(--accent-blue-light));
        border-radius: 50%;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    [data-theme="dark"] .toggle-slider {
        transform: translateX(34px);
        background: linear-gradient(135deg, var(--accent-red), var(--accent-red-light));
    }

    /* Create Button */
    .btn-create {
        background: linear-gradient(135deg, var(--accent-red), var(--accent-red-light));
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
        text-decoration: none;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(220, 38, 38, 0.4);
    }

    [data-theme="dark"] .btn-create {
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    /* Container */
    .reports-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem 4rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
        animation: fadeInUp 0.6s ease 0.2s both;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card {
        background: var(--bg-card);
        padding: 2rem;
        border-radius: 20px;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--accent-blue), var(--accent-red));
        transition: width 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent-blue-light);
    }

    .stat-card:hover::before {
        width: 100%;
        opacity: 0.05;
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 900;
        font-family: 'JetBrains Mono', monospace;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.95rem;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Filters */
    .filters-section {
        background: var(--bg-card);
        padding: 1.5rem;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease 0.3s both;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        font-weight: 700;
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-select {
        padding: 0.875rem;
        border-radius: 10px;
        border: 2px solid var(--border-color);
        background: var(--bg-secondary);
        color: var(--text-primary);
        font-family: 'Raleway', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Reports Grid */
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        animation: fadeInUp 0.6s ease 0.4s both;
    }

    .report-card {
        background: var(--bg-card);
        border-radius: 20px;
        border: 2px solid var(--border-color);
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .report-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent-blue);
    }

    .report-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--accent-blue), var(--accent-red));
    }

    .report-content {
        padding: 1.75rem;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .report-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: var(--warning);
        border: 1px solid var(--warning);
    }

    .status-verified {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .status-investigating {
        background: rgba(6, 182, 212, 0.15);
        color: var(--info);
        border: 1px solid var(--info);
    }

    .status-resolved {
        background: rgba(59, 130, 246, 0.15);
        color: var(--accent-blue);
        border: 1px solid var(--accent-blue);
    }

    .report-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .report-description {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .report-meta {
        display: flex;
        gap: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .meta-icon {
        font-size: 1.1rem;
    }

    /* Priority Badge */
    .priority-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        animation: pulse 2s ease-in-out infinite;
    }

    [data-theme="dark"] .priority-badge {
        background: rgba(30, 41, 59, 0.95);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .priority-high {
        border: 3px solid var(--accent-red);
    }

    .priority-medium {
        border: 3px solid var(--warning);
    }

    .priority-low {
        border: 3px solid var(--accent-blue);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-card);
        border-radius: 20px;
        border: 2px dashed var(--border-color);
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
    }

    .empty-text {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    /* Loading State */
    .loading-skeleton {
        animation: shimmer 2s infinite;
        background: linear-gradient(
            90deg,
            var(--bg-secondary) 0%,
            var(--border-color) 50%,
            var(--bg-secondary) 100%
        );
        background-size: 200% 100%;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 3rem;
    }

    .page-btn {
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        border: 2px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-primary);
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .page-btn:hover {
        border-color: var(--accent-blue);
        background: var(--accent-blue);
        color: white;
        transform: translateY(-2px);
    }

    .page-btn.active {
        background: linear-gradient(135deg, var(--accent-blue), var(--accent-blue-light));
        color: white;
        border-color: transparent;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }

        .page-title {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .reports-grid {
            grid-template-columns: 1fr;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Floating Action */
    .floating-action {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-red), var(--accent-red-light));
        color: white;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(220, 38, 38, 0.4);
        transition: all 0.3s ease;
        display: none;
    }

    .floating-action:hover {
        transform: scale(1.1) rotate(90deg);
    }

    @media (max-width: 768px) {
        .floating-action {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-create {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="reports-header">
    <div class="header-container">
        <h1 class="page-title">📍 Reports</h1>
        <div class="header-actions">
            <!-- Theme Toggle -->
            <div class="theme-toggle" id="themeToggle">
                <div class="toggle-slider">
                    <span id="themeIcon">☀️</span>
                </div>
            </div>
            
            <!-- Create Report Button -->
            <a href="{{ route('reports.create') }}" class="btn-create">
                <span>➕</span>
                <span>New Report</span>
            </a>
        </div>
    </div>
</div>

<div class="reports-container">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon">📊</span>
            <div class="stat-value">{{ $totalReports ?? 0 }}</div>
            <div class="stat-label">Total Reports</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-icon">⏳</span>
            <div class="stat-value">{{ $pendingReports ?? 0 }}</div>
            <div class="stat-label">Pending</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-icon">✅</span>
            <div class="stat-value">{{ $verifiedReports ?? 0 }}</div>
            <div class="stat-label">Verified</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-icon">🎯</span>
            <div class="stat-value">{{ $myReports ?? 0 }}</div>
            <div class="stat-label">My Reports</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select class="filter-select" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="investigating">Investigating</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Priority</label>
                <select class="filter-select" id="filterPriority">
                    <option value="">All Priorities</option>
                    <option value="high">High Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Type</label>
                <select class="filter-select" id="filterType">
                    <option value="">All Types</option>
                    <option value="ewaste">E-Waste</option>
                    <option value="plastic">Plastic</option>
                    <option value="organic">Organic</option>
                    <option value="hazardous">Hazardous</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Sort By</label>
                <select class="filter-select" id="filterSort">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest First</option>
                    <option value="priority">Highest Priority</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="reports-grid" id="reportsGrid">
        @if(isset($reports) && $reports->count() > 0)
            @foreach($reports as $report)
                <div class="report-card" onclick="window.location.href='{{ route('reports.show', $report->id) }}'">
                    @if($report->priority === 'high')
                        <div class="priority-badge priority-high">🔥</div>
                    @elseif($report->priority === 'medium')
                        <div class="priority-badge priority-medium">⚠️</div>
                    @else
                        <div class="priority-badge priority-low">ℹ️</div>
                    @endif

                    @if($report->image_path)
                        <img src="{{ Storage::url($report->image_path) }}" alt="{{ $report->title }}" class="report-image">
                    @else
                        <div class="report-image"></div>
                    @endif

                    <div class="report-content">
                        <div class="report-header">
                            <span class="report-status status-{{ $report->status }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>

                        <h3 class="report-title">{{ $report->title }}</h3>
                        <p class="report-description">{{ $report->description }}</p>

                        <div class="report-meta">
                            <div class="meta-item">
                                <span class="meta-icon">👤</span>
                                <span>{{ $report->user->name ?? 'Anonymous' }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-icon">📅</span>
                                <span>{{ $report->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state" style="grid-column: 1 / -1;">
                <div class="empty-icon">📭</div>
                <h3 class="empty-title">No Reports Found</h3>
                <p class="empty-text">Be the first to report waste in your area!</p>
                <a href="{{ route('reports.create') }}" class="btn-create" style="display: inline-flex;">
                    <span>➕</span>
                    <span>Create First Report</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($reports) && $reports->hasPages())
        <div class="pagination">
            @if($reports->onFirstPage())
                <button class="page-btn" disabled>« Previous</button>
            @else
                <a href="{{ $reports->previousPageUrl() }}" class="page-btn">« Previous</a>
            @endif

            @foreach(range(1, $reports->lastPage()) as $page)
                @if($page == $reports->currentPage())
                    <button class="page-btn active">{{ $page }}</button>
                @else
                    <a href="{{ $reports->url($page) }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($reports->hasMorePages())
                <a href="{{ $reports->nextPageUrl() }}" class="page-btn">Next »</a>
            @else
                <button class="page-btn" disabled>Next »</button>
            @endif
        </div>
    @endif
</div>

<!-- Floating Action Button (Mobile) -->
<a href="{{ route('reports.create') }}" class="floating-action">
    ➕
</a>
@endsection

@push('scripts')
<script>
    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const html = document.documentElement;

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    themeToggle.addEventListener('click', function() {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });

    function updateThemeIcon(theme) {
        themeIcon.textContent = theme === 'light' ? '☀️' : '🌙';
    }

    // Filters
    const filterStatus = document.getElementById('filterStatus');
    const filterPriority = document.getElementById('filterPriority');
    const filterType = document.getElementById('filterType');
    const filterSort = document.getElementById('filterSort');

    [filterStatus, filterPriority, filterType, filterSort].forEach(filter => {
        filter.addEventListener('change', applyFilters);
    });

    function applyFilters() {
        const status = filterStatus.value;
        const priority = filterPriority.value;
        const type = filterType.value;
        const sort = filterSort.value;

        // Build query string
        const params = new URLSearchParams();
        if (status) params.append('status', status);
        if (priority) params.append('priority', priority);
        if (type) params.append('type', type);
        if (sort) params.append('sort', sort);

        // Reload page with filters
        const url = params.toString() ? `{{ route('reports.index') }}?${params.toString()}` : '{{ route('reports.index') }}';
        window.location.href = url;
    }

    // Auto-apply filters from URL on load
    window.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.has('status')) filterStatus.value = urlParams.get('status');
        if (urlParams.has('priority')) filterPriority.value = urlParams.get('priority');
        if (urlParams.has('type')) filterType.value = urlParams.get('type');
        if (urlParams.has('sort')) filterSort.value = urlParams.get('sort');
    });

    // Card hover effect with parallax
    document.querySelectorAll('.report-card').forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
        });
        
        card.addEventListener('mouseleave', function() {
            card.style.transform = '';
        });
    });
</script>
@endpush
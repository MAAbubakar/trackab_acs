@php
    $user = auth()->user();

    $isSuperAdmin = $user?->hasRole('super-admin');
    $isProgrammeCoordinator = $user?->hasRole('programme-coordinator');
    $isAttendanceOfficer = $user?->hasRole('attendance-officer');
    $isMEOfficer = $user?->hasRole('m&e-officer');

    $canManageSetup = $isSuperAdmin || $isProgrammeCoordinator || $isMEOfficer;
    $canManageParticipants = $isSuperAdmin || $isProgrammeCoordinator || $isMEOfficer;
    $canManageReports = $isSuperAdmin || $isProgrammeCoordinator || $isMEOfficer;
    $canManageEvaluation = $isSuperAdmin || $isMEOfficer;
    $canManageSiwes = $isSuperAdmin || $isProgrammeCoordinator || $isMEOfficer;
    $canManageUsers = $isSuperAdmin;
    $canManageAttendance = $isSuperAdmin || $isProgrammeCoordinator || $isAttendanceOfficer || $isMEOfficer;
@endphp

<style>
    .admin-sidebar {
        width: 300px;
        min-width: 300px;
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(255,255,255,0.10), transparent 26%),
            linear-gradient(165deg, #07533f 0%, #064532 42%, #043525 100%);
        color: #ffffff;
        padding: 26px 22px;
        position: sticky;
        top: 0;
        overflow-y: auto;
        transition: width .25s ease, min-width .25s ease, padding .25s ease;
        box-shadow:
            12px 0 28px rgba(15, 23, 42, .18),
            inset -1px 0 0 rgba(255,255,255,.08);
        border-right: 1px solid rgba(255,255,255,.08);
        z-index: 20;
    }

    .admin-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .admin-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,.25);
        border-radius: 999px;
    }

    .admin-sidebar-top {
        display: block;
        margin-bottom: 24px;
        position: relative;
        padding: 8px 0 26px;
        border-bottom: 1px solid rgba(255,255,255,.14);
    }

    .admin-sidebar-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        min-width: 0;
        text-align: center;
        padding: 0 8px;
    }

    .admin-sidebar-logo {
        width: 100px;
        height: 100px;
        border-radius: 24px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 14px 30px rgba(0,0,0,.20),
            inset 0 0 0 1px rgba(15, 23, 42, .05);
        overflow: hidden;
        flex-shrink: 0;
        margin: 0 auto;
    }

    .admin-sidebar-logo img {
        width: 95px;
        height: 95px;
        object-fit: contain;
        display: block;
    }

    .admin-sidebar-title {
        font-size: 1.55rem;
        font-weight: 950;
        line-height: 1.08;
        letter-spacing: .01em;
        white-space: normal;
        text-align: center;
        color: #ffffff;
        width: 100%;
    }

    .admin-sidebar-subtitle {
        margin-top: 8px;
        font-size: .98rem;
        color: rgba(255,255,255,.78);
        line-height: 1.25;
        font-weight: 800;
        text-align: center;
        width: 100%;
    }

    .sidebar-toggle {
        position: absolute;
        top: -10px;
        right: -17px;
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,.24);
        background: linear-gradient(135deg, #0b6b57, #0f8f73);
        color: #ffffff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        font-weight: 900;
        transition: all .2s ease;
        box-shadow: 0 10px 24px rgba(0,0,0,.24);
        flex-shrink: 0;
        z-index: 50;
    }

    .sidebar-toggle:hover {
        background: rgba(255,255,255,.18);
        transform: translateY(-1px);
    }

    .admin-nav {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .admin-nav-section {
        margin: 18px 8px 8px;
        font-size: .78rem;
        font-weight: 900;
        color: rgba(255,255,255,.82);
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .admin-nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255,255,255,.88);
        text-decoration: none;
        padding: 14px 14px;
        border-radius: 18px;
        font-weight: 800;
        line-height: 1.2;
        background: rgba(255,255,255,.045);
        border: 1px solid rgba(255,255,255,.07);
        box-shadow:
            0 8px 18px rgba(0,0,0,.08),
            inset 0 1px 0 rgba(255,255,255,.08);
        transition: all .2s ease;
    }

    .admin-nav-link:hover {
        color: #ffffff;
        background: rgba(255,255,255,.12);
        transform: translateX(4px);
        border-color: rgba(255,255,255,.16);
    }

    .admin-nav-link.active {
        color: #ffffff;
        background:
            linear-gradient(135deg, rgba(255,255,255,.20), rgba(255,255,255,.10));
        border-color: rgba(255,255,255,.22);
        box-shadow:
            0 16px 28px rgba(0,0,0,.16),
            inset 4px 0 0 #f59e0b,
            inset 0 1px 0 rgba(255,255,255,.16);
    }

    .admin-nav-icon {
        width: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.05rem;
    }

    .admin-nav-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    body.sidebar-collapsed .admin-sidebar {
        width: 96px;
        min-width: 96px;
        padding: 24px 14px;
    }

    body.sidebar-collapsed .admin-sidebar-title,
    body.sidebar-collapsed .admin-sidebar-subtitle,
    body.sidebar-collapsed .admin-nav-text,
    body.sidebar-collapsed .admin-nav-section {
        display: none;
    }

    body.sidebar-collapsed .admin-sidebar-top {
        justify-content: center;
        flex-direction: column;
        padding: 0 0 18px;
        margin-bottom: 18px;
    }

    body.sidebar-collapsed .sidebar-toggle {
        right: -17px;
        top: -10px;
    }

    body.sidebar-collapsed .admin-sidebar-brand {
        justify-content: center;
    }

    body.sidebar-collapsed .admin-sidebar-logo {
        width: 58px;
        height: 58px;
        border-radius: 18px;
    }

    body.sidebar-collapsed .admin-sidebar-logo img {
        width: 48px;
        height: 48px;
    }

    body.sidebar-collapsed .admin-nav-link {
        justify-content: center;
        padding: 14px 10px;
    }

    body.sidebar-collapsed .admin-nav-link:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 900px) {
        .admin-sidebar {
            width: 100%;
            min-width: 100%;
            min-height: auto;
            position: relative;
            border-radius: 0;
        }

        body.sidebar-collapsed .admin-sidebar {
            width: 100%;
            min-width: 100%;
        }

        body.sidebar-collapsed .admin-sidebar-title,
        body.sidebar-collapsed .admin-sidebar-subtitle,
        body.sidebar-collapsed .admin-nav-text,
        body.sidebar-collapsed .admin-nav-section {
            display: block;
        }

        body.sidebar-collapsed .admin-nav-link {
            justify-content: flex-start;
        }
    }
</style>


<button type="button" class="mobile-sidebar-toggle" onclick="toggleMobileSidebar()" aria-label="Open menu">
    ☰
</button>

<div class="mobile-sidebar-overlay" onclick="closeMobileSidebar()"></div>

<aside class="admin-sidebar">

    <div class="admin-sidebar-top">
        <div class="admin-sidebar-brand">
            <div class="admin-sidebar-logo">
                <img src="{{ asset('assets/images/centre-logo.png') }}" alt="SPESSE-CE ABU"
                     onerror="this.style.display='none'; this.parentNode.innerHTML='SP';">
            </div>

            <div>
                <div class="admin-sidebar-title">SPESSE-CE ABU</div>
                <div class="admin-sidebar-subtitle">Track A & B Attendance System</div>
            </div>
        </div>

        <button type="button" class="sidebar-toggle" onclick="toggleAdminSidebar()" title="Collapse sidebar">
            ☰
        </button>
    </div>

    <nav class="admin-nav">

        <a href="{{ route('admin.dashboard') }}"
           class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="admin-nav-icon">🏠</span>
            <span class="admin-nav-text">Dashboard</span>
        </a>

        @if($canManageSetup)
            <div class="admin-nav-section">Setup</div>

            @if(Route::has('admin.courses.index'))
                <a href="{{ route('admin.courses.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">📘</span>
                    <span class="admin-nav-text">Available Courses</span>
                </a>
            @endif

            @if(Route::has('admin.venues.index'))
                <a href="{{ route('admin.venues.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.venues.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">📍</span>
                    <span class="admin-nav-text">Training Venues</span>
                </a>
            @endif

            @if(Route::has('admin.batches.index'))
                <a href="{{ route('admin.batches.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.batches.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">🗂️</span>
                    <span class="admin-nav-text">Training Batches</span>
                </a>
            @endif
        @endif

        @if($canManageParticipants)
            <div class="admin-nav-section">Participants</div>

            @if(Route::has('admin.participants.index'))
                <a href="{{ route('admin.participants.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">👥</span>
                    <span class="admin-nav-text">List of Participants</span>
                </a>
            @endif

            @if(Route::has('admin.profile-corrections.index'))
                <a href="{{ route('admin.profile-corrections.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.profile-corrections.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">📝</span>
                    <span class="admin-nav-text">Profile Corrections</span>
                </a>
            @endif
        @endif

        @if($canManageAttendance)
            <div class="admin-nav-section">Attendance</div>

            @if(Route::has('admin.sessions.index'))
                <a href="{{ route('admin.sessions.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">🕒</span>
                    <span class="admin-nav-text">Available Sessions</span>
                </a>
            @endif

            @if(Route::has('admin.attendance-records.index'))
                <a href="{{ route('admin.attendance-records.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.attendance-records.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">✅</span>
                    <span class="admin-nav-text">Attendance Records</span>
                </a>
            @endif

            @if(Route::has('admin.daily-summaries.index'))
                <a href="{{ route('admin.daily-summaries.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.daily-summaries.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">📅</span>
                    <span class="admin-nav-text">Daily Summaries</span>
                </a>
            @endif

            @if(Route::has('admin.attendance-flags.index'))
                <a href="{{ route('admin.attendance-flags.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.attendance-flags.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">🚩</span>
                    <span class="admin-nav-text">Attendance Flags</span>
                </a>
            @endif
        @endif

        @if($canManageReports)
            <div class="admin-nav-section">Reports</div>

            @if(Route::has('admin.reports.index'))
                <a href="{{ route('admin.reports.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">📈</span>
                    <span class="admin-nav-text">Generate Reports</span>
                </a>
            @endif

            @if(Route::has('admin.certificate-eligibilities.index'))
                <a href="{{ route('admin.certificate-eligibilities.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.certificate-eligibilities.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">🎓</span>
                    <span class="admin-nav-text">Certificate Eligibility</span>
                </a>
            @endif
        @endif

        @if($canManageEvaluation)
            <div class="admin-nav-section">Evaluation</div>

            @if(Route::has('admin.evaluation-forms.index'))
                <a href="{{ route('admin.evaluation-forms.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.evaluation-forms.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">🧾</span>
                    <span class="admin-nav-text">Evaluation Forms</span>
                </a>
            @endif

            @if(Route::has('admin.evaluation-responses.index'))
                <a href="{{ route('admin.evaluation-responses.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.evaluation-responses.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">📊</span>
                    <span class="admin-nav-text">Evaluation Responses</span>
                </a>
            @endif

            @if(Route::has('admin.evaluation-reminders.index'))
                <a href="{{ route('admin.evaluation-reminders.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.evaluation-reminders.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">🔔</span>
                    <span class="admin-nav-text">Evaluation Reminders</span>
                </a>
            @endif
        @endif

        @if($canManageSiwes)
            <div class="admin-nav-section">SIWES</div>

            @if(Route::has('admin.siwes.eligible'))
                <a href="{{ route('admin.siwes.eligible') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.siwes.eligible') ? 'active' : '' }}">
                    <span class="admin-nav-icon">🏢</span>
                    <span class="admin-nav-text">SIWES Eligible</span>
                </a>
            @endif

            @if(Route::has('admin.siwes.issued'))
                <a href="{{ route('admin.siwes.issued') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.siwes.issued') ? 'active' : '' }}">
                    <span class="admin-nav-icon">📄</span>
                    <span class="admin-nav-text">Issued SIWES Letters</span>
                </a>
            @endif
        @endif

        <div class="admin-nav-section">Communication</div>

        @if(Route::has('admin.notifications.index'))
            <a href="{{ route('admin.notifications.index') }}"
               class="admin-nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <span class="admin-nav-icon">🔔</span>
                <span class="admin-nav-text">Notifications</span>
            </a>
        @endif

        @if(!$isAttendanceOfficer && Route::has('admin.messages.index'))
            <a href="{{ route('admin.messages.index') }}"
               class="admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <span class="admin-nav-icon">💬</span>
                <span class="admin-nav-text">Messages</span>
            </a>
        @endif

        @if($canManageUsers)
            <div class="admin-nav-section">Administration</div>

            @if(Route::has('admin.users.index'))
                <a href="{{ route('admin.users.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">👤</span>
                    <span class="admin-nav-text">Users</span>
                </a>
            @endif

            @if(Route::has('admin.activity-logs.index'))
                <a href="{{ route('admin.activity-logs.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">🧭</span>
                    <span class="admin-nav-text">Activity Logs</span>
                </a>
            @endif

            @if(Route::has('admin.automation.index'))
                <a href="{{ route('admin.automation.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.automation.*') ? 'active' : '' }}">
                    <span class="admin-nav-icon">⚙️</span>
                    <span class="admin-nav-text">Automation</span>
                </a>
            @endif
        @endif

    </nav>
</aside>

<script>
    function toggleAdminSidebar() {
        document.body.classList.toggle('sidebar-collapsed');

        if (document.body.classList.contains('sidebar-collapsed')) {
            localStorage.setItem('adminSidebarCollapsed', '1');
        } else {
            localStorage.removeItem('adminSidebarCollapsed');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('adminSidebarCollapsed') === '1') {
            document.body.classList.add('sidebar-collapsed');
        }
    });
</script>


<style>
    .mobile-sidebar-toggle {
        display: none;
    }

    .mobile-sidebar-overlay {
        display: none;
    }

    @media (max-width: 900px) {
        body {
            overflow-x: hidden;
        }

        .mobile-sidebar-toggle {
            display: flex;
            position: fixed;
            top: 14px;
            left: 14px;
            width: 46px;
            height: 46px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,.22);
            background: linear-gradient(135deg, #0b6b57, #0f8f73);
            color: #ffffff;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 900;
            z-index: 1001;
            box-shadow: 0 14px 32px rgba(0,0,0,.24);
            cursor: pointer;
        }

        .mobile-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.48);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        body.mobile-sidebar-open .mobile-sidebar-overlay {
            display: block;
        }

        .admin-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 84vw !important;
            max-width: 330px !important;
            min-width: 0 !important;
            height: 100vh !important;
            min-height: 100vh !important;
            z-index: 1000 !important;
            transform: translateX(-105%);
            transition: transform .25s ease !important;
            border-radius: 0 28px 28px 0;
            padding-top: 76px !important;
            overflow-y: auto !important;
        }

        body.mobile-sidebar-open .admin-sidebar {
            transform: translateX(0);
        }

        .sidebar-toggle {
            display: none !important;
        }

        body.sidebar-collapsed .admin-sidebar {
            width: 84vw !important;
            max-width: 330px !important;
            min-width: 0 !important;
        }

        body.sidebar-collapsed .admin-sidebar-title,
        body.sidebar-collapsed .admin-sidebar-subtitle,
        body.sidebar-collapsed .admin-nav-text,
        body.sidebar-collapsed .admin-nav-section {
            display: block !important;
        }

        body.sidebar-collapsed .admin-nav-link {
            justify-content: flex-start !important;
        }

        body.sidebar-collapsed .admin-sidebar-logo {
            width: 100px !important;
            height: 100px !important;
        }

        body.sidebar-collapsed .admin-sidebar-logo img {
            width: 95px !important;
            height: 95px !important;
        }

        .admin-main,
        .admin-content,
        main {
            width: 100% !important;
            max-width: 100% !important;
            margin-left: 0 !important;
        }

        .admin-topbar,
        header {
            padding-left: 76px !important;
        }

        .dashboard-grid,
        .stats-grid,
        .charts-grid,
        .quick-grid,
        .reports-grid,
        .users-grid,
        .cards-grid {
            grid-template-columns: 1fr !important;
        }

        .dashboard-card,
        .card,
        .admin-card,
        .chart-card {
            width: 100% !important;
            max-width: 100% !important;
        }

        table {
            display: block;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        input,
        select,
        textarea,
        button {
            max-width: 100%;
        }

        .form-grid,
        .details-grid,
        .roles-grid {
            grid-template-columns: 1fr !important;
        }

        .page-header,
        .users-header,
        .qr-page-header {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        .page-actions,
        .users-actions,
        .qr-page-actions {
            width: 100%;
            justify-content: flex-start;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 520px) {
        .admin-sidebar {
            width: 88vw !important;
            max-width: 310px !important;
        }

        .admin-sidebar-title {
            font-size: 1.35rem !important;
        }

        .admin-sidebar-subtitle {
            font-size: .88rem !important;
        }

        .admin-nav-link {
            padding: 13px 12px !important;
            border-radius: 16px !important;
        }

        .mobile-sidebar-toggle {
            width: 42px;
            height: 42px;
            border-radius: 14px;
        }

        .admin-topbar,
        header {
            padding-left: 68px !important;
        }

        .dashboard-stat,
        .stat-card,
        .summary-card {
            padding: 18px !important;
        }

        h1,
        .page-title,
        .dashboard-title {
            font-size: 1.55rem !important;
        }

        h2 {
            font-size: 1.25rem !important;
        }
    }
</style>


<script>
    function toggleMobileSidebar() {
        document.body.classList.toggle('mobile-sidebar-open');
    }

    function closeMobileSidebar() {
        document.body.classList.remove('mobile-sidebar-open');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMobileSidebar();
        }
    });

    document.addEventListener('click', function (event) {
        const link = event.target.closest('.admin-nav-link');

        if (link && window.innerWidth <= 900) {
            closeMobileSidebar();
        }
    });
</script>

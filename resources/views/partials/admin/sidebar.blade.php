<style>
    .admin-sidebar .admin-nav-link {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        margin-bottom: 8px;
        border-radius: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        text-shadow: 0 1px 0 rgba(255,255,255,0.08), 0 1.5px 2px rgba(0,0,0,0.18);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.06), 0 2px 6px rgba(0,0,0,0.10);
        transition: all 0.2s ease;
    }

    .admin-sidebar .admin-nav-link:hover {
        transform: translateY(-1px);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.08), 0 4px 10px rgba(0,0,0,0.14);
    }

    .admin-sidebar .admin-nav-link.active {
        font-weight: 800;
        text-shadow: 0 1px 0 rgba(255,255,255,0.10), 0 2px 3px rgba(0,0,0,0.22);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.08), 0 5px 12px rgba(0,0,0,0.18);
    }
</style>

<aside class="admin-sidebar">
    <div class="admin-brand admin-brand-stacked">
        <img src="{{ asset('assets/images/centre-logo.png') }}" alt="Centre Logo" class="admin-sidebar-logo">

        <div>
            <div class="admin-brand-title">SPESSE-CE ABU</div>
            <div class="admin-brand-subtitle">Track A & B Attendance and Compliance System</div>
        </div>
    </div>

    <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">🏠 <span style="margin-left:8px;">Dashboard</span></a>

        @if(Route::has('admin.courses.index'))
            <a href="{{ route('admin.courses.index') }}" class="admin-nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">📘 <span style="margin-left:8px;">Available Courses</span></a>
        @endif

        @if(Route::has('admin.venues.index'))
            <a href="{{ route('admin.venues.index') }}" class="admin-nav-link {{ request()->routeIs('admin.venues.*') ? 'active' : '' }}">📍 <span style="margin-left:8px;">Training Venues</span></a>
        @endif

        @if(Route::has('admin.batches.index'))
            <a href="{{ route('admin.batches.index') }}" class="admin-nav-link {{ request()->routeIs('admin.batches.*') ? 'active' : '' }}">🗂️ <span style="margin-left:8px;">Training Batches</span></a>
        @endif

        @if(Route::has('admin.participants.index'))
            <a href="{{ route('admin.participants.index') }}" class="admin-nav-link {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">👥 <span style="margin-left:8px;">List of Participants</span></a>
        @endif

        @if(Route::has('admin.sessions.index'))
            <a href="{{ route('admin.sessions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">🕒 <span style="margin-left:8px;">Available Sessions</span></a>
        @endif

        @if(Route::has('admin.reports.index'))
            <a href="{{ route('admin.reports.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">📈 <span style="margin-left:8px;">Generate Reports</span></a>
        @endif

        @if(Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👤 <span style="margin-left:8px;">User Management</span></a>
        @endif

        @if(Route::has('admin.evaluation-responses.index'))
            <a href="{{ route('admin.evaluation-responses.index') }}" class="admin-nav-link {{ request()->routeIs('admin.evaluation-responses.*') ? 'active' : '' }}">🧾 <span style="margin-left:8px;">Evaluation Responses</span></a>
        @endif

        @if(Route::has('admin.evaluation-reminders.index'))
            <a href="{{ route('admin.evaluation-reminders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.evaluation-reminders.*') ? 'active' : '' }}">⏰ <span style="margin-left:8px;">Evaluation Reminders</span></a>
        @endif

        @if(Route::has('admin.activity-logs.index'))
            <a href="{{ route('admin.activity-logs.index') }}" class="admin-nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">📚 <span style="margin-left:8px;">Activity Logs</span></a>
        @endif

        @if(Route::has('admin.automation.index'))
            <a href="{{ route('admin.automation.index') }}" class="admin-nav-link {{ request()->routeIs('admin.automation.*') ? 'active' : '' }}">⚙️ <span style="margin-left:8px;">Automation</span></a>
        @endif

        @if(Route::has('admin.notifications.index'))
            <a href="{{ route('admin.notifications.index') }}" class="admin-nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">🔔 <span style="margin-left:8px;">Notifications</span></a>
        @endif

        @if(Route::has('admin.messages.index'))
            <a href="{{ route('admin.messages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">✉️ <span style="margin-left:8px;">Messages</span></a>
        @endif
    </nav>
</aside>

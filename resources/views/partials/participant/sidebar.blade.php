<style>
    .participant-sidebar .admin-nav-link {
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

    .participant-sidebar .admin-nav-link:hover {
        transform: translateY(-1px);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.08), 0 4px 10px rgba(0,0,0,0.14);
    }

    .participant-sidebar .admin-nav-link.active {
        font-weight: 800;
        text-shadow: 0 1px 0 rgba(255,255,255,0.10), 0 2px 3px rgba(0,0,0,0.22);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.08), 0 5px 12px rgba(0,0,0,0.18);
    }

    .participant-sidebar .nav-separator {
        height: 1px;
        background: rgba(255,255,255,0.16);
        margin: 12px 6px 14px;
        border-radius: 999px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);
    }

    .participant-sidebar .nav-separator.soft {
        background: rgba(255,255,255,0.10);
        margin-top: 10px;
        margin-bottom: 12px;
    }
</style>

<aside class="admin-sidebar participant-sidebar">
    <div class="admin-brand admin-brand-stacked">
        <img src="{{ asset('assets/images/centre-logo.png') }}" alt="Centre Logo" class="admin-sidebar-logo">

        <div>
            <div class="admin-brand-title">SPESSE-CE ABU</div>
            <div class="admin-brand-subtitle">Track A & B Attendance and Compliance System</div>
        </div>
    </div>

    <nav class="admin-nav">
        <a href="{{ route('participant.dashboard') }}" class="admin-nav-link {{ request()->routeIs('participant.dashboard') ? 'active' : '' }}">🏠 <span style="margin-left:8px;">Dashboard</span></a>

        <div class="nav-separator"></div>

        @if(Route::has('participant.profile'))
            <a href="{{ route('participant.profile') }}" class="admin-nav-link {{ request()->routeIs('participant.profile') ? 'active' : '' }}">👤 <span style="margin-left:8px;">My Profile</span></a>
        @endif

        @if(Route::has('participant.qr.show'))
            <a href="{{ route('participant.qr.show') }}" class="admin-nav-link {{ request()->routeIs('participant.qr.*') ? 'active' : '' }}">🪪 <span style="margin-left:8px;">My QR Code</span></a>
        @endif

        <div class="nav-separator soft"></div>

        @if(Route::has('participant.summaries'))
            <a href="{{ route('participant.summaries') }}" class="admin-nav-link {{ request()->routeIs('participant.summaries') ? 'active' : '' }}">📊 <span style="margin-left:8px;">My Summaries</span></a>
        @endif

        @if(Route::has('participant.eligibility'))
            <a href="{{ route('participant.eligibility') }}" class="admin-nav-link {{ request()->routeIs('participant.eligibility') ? 'active' : '' }}">🎓 <span style="margin-left:8px;">Certificate Status</span></a>
        @endif

        @if(Route::has('participant.siwes.index'))
            <a href="{{ route('participant.siwes.index') }}" class="admin-nav-link {{ request()->routeIs('participant.siwes.*') ? 'active' : '' }}">🏢 <span style="margin-left:8px;">SIWES Letter</span></a>
        @endif

        @if(Route::has('participant.evaluation.show'))
            <a href="{{ route('participant.evaluation.show') }}" class="admin-nav-link {{ request()->routeIs('participant.evaluation.*') ? 'active' : '' }}">🧾 <span style="margin-left:8px;">Evaluation</span></a>
        @endif

        <div class="nav-separator soft"></div>

        @if(Route::has('participant.notifications.index'))
            <a href="{{ route('participant.notifications.index') }}" class="admin-nav-link {{ request()->routeIs('participant.notifications.*') ? 'active' : '' }}">🔔 <span style="margin-left:8px;">Notifications</span></a>
        @endif

        @if(Route::has('participant.profile-corrections.history'))
            <a href="{{ route('participant.profile-corrections.history') }}" class="admin-nav-link {{ request()->routeIs('participant.profile-corrections.history') ? 'active' : '' }}">🛠️ <span style="margin-left:8px;">Correction History</span></a>
        @endif
    </nav>
</aside>

@extends('layouts.participant')

@section('title', 'My Profile')

@section('content')

@php
    function formatParticipantProfileValue($field, $value) {
        if ($value === null || $value === '') {
            return 'Not provided';
        }

        if ($field === 'employment_status') {
            return match ($value) {
                'employed' => 'Employed',
                'unemployed' => 'Unemployed',
                'self-employed' => 'Self-Employed',
                default => ucfirst(str_replace('-', ' ', $value)),
            };
        }

        return $value;
    }
@endphp

<style>
    .profile-page-wrap {
        padding: 1.5rem;
    }

    .profile-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .profile-title {
        font-size: 1.85rem;
        font-weight: 900;
        color: #102033;
        margin-bottom: .35rem;
        letter-spacing: -0.02em;
    }

    .profile-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .btn-profile-primary {
        background: #0b6b3a;
        border: 1px solid #0b6b3a;
        color: #fff;
        border-radius: .9rem;
        padding: .8rem 1.15rem;
        font-weight: 850;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        box-shadow: 0 10px 20px rgba(11, 107, 58, .16);
    }

    .btn-profile-primary:hover {
        background: #095c32;
        border-color: #095c32;
        color: #fff;
    }

    .profile-alert {
        border-radius: 1rem;
        border: 0;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
    }

    .profile-hero {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .profile-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.35rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.07);
        overflow: hidden;
    }

    .profile-card-body {
        padding: 1.35rem;
    }

    .profile-identity {
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 32%),
            linear-gradient(135deg, #064d31 0%, #0b6b3a 58%, #0f8a4d 100%);
        color: #fff;
        padding: 1.5rem;
        min-height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .avatar-wrap {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .profile-avatar {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        background: rgba(255,255,255,.16);
        border: 3px solid rgba(255,255,255,.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.1rem;
        font-weight: 900;
        color: #fff;
        flex-shrink: 0;
    }

    .identity-name {
        font-size: 1.25rem;
        line-height: 1.25;
        font-weight: 900;
        margin-bottom: .3rem;
    }

    .identity-email {
        font-size: .92rem;
        opacity: .9;
        word-break: break-word;
    }

    .identity-meta {
        display: grid;
        gap: .75rem;
        margin-top: 1.25rem;
    }

    .identity-pill {
        background: rgba(255,255,255,.13);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 1rem;
        padding: .85rem .95rem;
    }

    .identity-pill small {
        display: block;
        opacity: .78;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: .25rem;
    }

    .identity-pill strong {
        display: block;
        font-size: .98rem;
        line-height: 1.35;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .summary-card {
        background: #f8fbfa;
        border: 1px solid #e3eeea;
        border-radius: 1.15rem;
        padding: 1.1rem;
        min-height: 118px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        border-radius: .9rem;
        background: rgba(11,107,58,.1);
        color: #0b6b3a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: .8rem;
    }

    .summary-label {
        display: block;
        font-size: .78rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .06em;
        font-weight: 800;
        margin-bottom: .25rem;
    }

    .summary-value {
        color: #0f172a;
        font-weight: 900;
        font-size: 1.02rem;
        line-height: 1.35;
        word-break: break-word;
    }

    .section-card {
        margin-bottom: 1.5rem;
    }

    .section-header {
        padding: 1.15rem 1.35rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: .75rem;
        background: #fbfdfc;
    }

    .section-header-icon {
        width: 42px;
        height: 42px;
        border-radius: .9rem;
        background: #0b6b3a;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .section-heading {
        margin: 0;
        font-size: 1.06rem;
        font-weight: 900;
        color: #102033;
    }

    .section-subheading {
        margin: .1rem 0 0;
        color: #64748b;
        font-size: .9rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .info-item {
        border: 1px solid #e3eeea;
        background: #ffffff;
        border-radius: 1rem;
        padding: 1rem 1.05rem;
        min-height: 90px;
    }

    .info-item.full {
        grid-column: 1 / -1;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: .45rem;
        color: #64748b;
        font-size: .82rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 850;
        margin-bottom: .45rem;
    }

    .info-label .label-icon {
        color: #0b6b3a;
        font-size: 1rem;
    }

    .info-value {
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 800;
        line-height: 1.45;
        word-break: break-word;
    }

    .info-value.muted {
        color: #94a3b8;
        font-weight: 700;
    }

    @media (max-width: 1100px) {
        .profile-hero {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-page-wrap {
            padding: 1rem;
        }

        .profile-page-header {
            flex-direction: column;
        }

        .btn-profile-primary {
            width: 100%;
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item.full {
            grid-column: auto;
        }
    }
</style>

@php
    $display = fn ($value) => filled($value) ? $value : '—';

    $fullName = $participant->full_name ?? auth()->user()->name ?? 'Participant';
    $initials = collect(explode(' ', trim($fullName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->join('');

    $personalFields = [
        'full_name' => ['Full Name', '👤'],
        'gender' => ['Gender', '⚧'],
        'state_of_origin' => ['State of Origin', '📍'],
        'lga' => ['Local Government Area', '🗺️'],
        'nationality' => ['Nationality', '🌍'],
        'age' => ['Age', '🎂'],
    ];

    $contactFields = [
        'email' => ['Email Address', '✉️'],
        'phone' => ['Phone Number', '📞'],
        'address' => ['Contact Address', '🏠', 'full'],
    ];

    $workFields = [
        'organization' => ['Organization', '🏢'],
        'organisation' => ['Organisation', '🏢'],
        'department' => ['Department', '🏬'],
        'designation' => ['Designation', '💼'],
        'employment_sector' => ['Employment Sector', '🧾'],
        'employment_status' => ['Employment Status', '📌'],
        'highest_qualification' => ['Highest Qualification', '🎓'],
        'academic_background' => ['Academic Background', '📚'],
    ];

    $fieldExists = function ($field) use ($participant) {
        return array_key_exists($field, $participant->getAttributes());
    };

    $renderFields = function ($fields) use ($participant, $display, $fieldExists) {
        $html = '';

        foreach ($fields as $field => $meta) {
            if (! $fieldExists($field)) {
                continue;
            }

            $label = $meta[0] ?? $field;
            $icon = $meta[1] ?? '•';
            $full = ($meta[2] ?? '') === 'full';
            $value = $participant->{$field} ?? null;

            $html .= '<div class="info-item '.($full ? 'full' : '').'">';
            $html .= '<div class="info-label"><span class="label-icon">'.$icon.'</span><span>'.e($label).'</span></div>';
            $html .= '<div class="info-value '.(filled($value) ? '' : 'muted').'">'.e($display($value)).'</div>';
            $html .= '</div>';
        }

        return $html;
    };
@endphp

<div class="profile-page-wrap">

    <div class="profile-page-header">
        <div>
            <h1 class="profile-title">My Profile</h1>
            <p class="profile-subtitle">
                Review your biodata, contact details, work information, and training profile.
            </p>
        </div>

        <a href="{{ route('participant.profile.edit') }}" class="btn-profile-primary">
            <span>✏️</span>
            <span>Edit Profile</span>
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success profile-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-hero">
        <div class="profile-card">
            <div class="profile-identity">
                <div>
                    <div class="avatar-wrap">
                        <div class="profile-avatar">{{ $initials ?: 'P' }}</div>
                        <div>
                            <div class="identity-name">{{ $display($fullName) }}</div>
                            <div class="identity-email">{{ $display($participant->email ?? null) }}</div>
                        </div>
                    </div>

                    <div class="identity-meta">
                        <div class="identity-pill">
                            <small>Participant Number</small>
                            <strong>{{ $display($participant->participant_no ?? null) }}</strong>
                        </div>

                        <div class="identity-pill">
                            <small>Current Batch</small>
                            <strong>{{ $display($participant->batch->name ?? null) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="identity-pill" style="margin-top: 1rem;">
                    <small>Training Course</small>
                    <strong>{{ $display($participant->course->title ?? $participant->course->name ?? null) }}</strong>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <div class="profile-card-body">
                <div class="summary-grid">
                    <div class="summary-card">
                        <div>
                            <div class="summary-icon">🆔</div>
                            <span class="summary-label">Participant No.</span>
                            <div class="summary-value">{{ $display($participant->participant_no ?? null) }}</div>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div>
                            <div class="summary-icon">📦</div>
                            <span class="summary-label">Batch</span>
                            <div class="summary-value">{{ $display($participant->batch->name ?? null) }}</div>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div>
                            <div class="summary-icon">🎓</div>
                            <span class="summary-label">Course</span>
                            <div class="summary-value">
                                {{ $display($participant->course->title ?? $participant->course->name ?? null) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-card section-card">
        <div class="section-header">
            <div class="section-header-icon">👤</div>
            <div>
                <h2 class="section-heading">Personal Information</h2>
                <p class="section-subheading">Basic biodata captured for your programme record.</p>
            </div>
        </div>
        <div class="profile-card-body">
            <div class="info-grid">
                {!! $renderFields($personalFields) !!}
            </div>
        </div>
    </div>

    <div class="profile-card section-card">
        <div class="section-header">
            <div class="section-header-icon">☎️</div>
            <div>
                <h2 class="section-heading">Contact Information</h2>
                <p class="section-subheading">Your email, phone number, and contact address.</p>
            </div>
        </div>
        <div class="profile-card-body">
            <div class="info-grid">
                {!! $renderFields($contactFields) !!}
            </div>
        </div>
    </div>

    <div class="profile-card section-card">
        <div class="section-header">
            <div class="section-header-icon">🏢</div>
            <div>
                <h2 class="section-heading">Work and Academic Information</h2>
                <p class="section-subheading">Employment, organization, designation, and academic details.</p>
            </div>
        </div>
        <div class="profile-card-body">
            <div class="info-grid">
                {!! $renderFields($workFields) !!}
            </div>
        </div>
    </div>

    <div class="profile-card section-card">
        <div class="section-header">
            <div class="section-header-icon">🎓</div>
            <div>
                <h2 class="section-heading">Training Information</h2>
                <p class="section-subheading">Programme, batch, and course details assigned by the Centre.</p>
            </div>
        </div>
        <div class="profile-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label"><span class="label-icon">🆔</span><span>Participant Number</span></div>
                    <div class="info-value">{{ $display($participant->participant_no ?? null) }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label"><span class="label-icon">📦</span><span>Batch</span></div>
                    <div class="info-value">{{ $display($participant->batch->name ?? null) }}</div>
                </div>

                <div class="info-item full">
                    <div class="info-label"><span class="label-icon">🎓</span><span>Course</span></div>
                    <div class="info-value">
                        {{ $display($participant->course->title ?? $participant->course->name ?? null) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

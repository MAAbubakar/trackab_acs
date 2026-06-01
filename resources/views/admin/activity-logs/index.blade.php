@extends('layouts.admin')

@section('content')
    <style>
        .activity-log-card {
            background: #ffffff;
            border: 1px solid #dbe7e2;
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
            overflow: hidden;
        }

        .activity-log-body {
            padding: 24px;
        }

        .activity-user-group {
            border: 1px solid #dbe7e2;
            border-radius: 20px;
            margin-bottom: 16px;
            background: #ffffff;
            overflow: hidden;
        }

        .activity-user-group summary {
            list-style: none;
            cursor: pointer;
            padding: 18px 20px;
            background: linear-gradient(135deg, #f8fafc, #ecfdf5);
            display: grid;
            grid-template-columns: 1.4fr .8fr .8fr .9fr auto;
            gap: 14px;
            align-items: center;
        }

        .activity-user-group summary::-webkit-details-marker {
            display: none;
        }

        .activity-user-name {
            font-size: 1rem;
            font-weight: 950;
            color: #0f172a;
            line-height: 1.3;
        }

        .activity-user-meta {
            color: #64748b;
            font-size: .82rem;
            font-weight: 750;
            margin-top: 4px;
        }

        .activity-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            padding: 7px 11px;
            border-radius: 999px;
            background: #ecfdf5;
            border: 1px solid #b7e4d2;
            color: #0b6b57;
            font-size: .78rem;
            font-weight: 950;
        }

        .activity-pill-muted {
            background: #f8fafc;
            border-color: #dbe3ea;
            color: #475569;
        }

        .activity-toggle {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            background: #0b6b57;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 950;
            transition: transform .2s ease;
        }

        details[open] .activity-toggle {
            transform: rotate(180deg);
        }

        .activity-details {
            padding: 0 20px 20px;
            background: #ffffff;
        }

        .activity-table-wrap {
            width: 100%;
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
        }

        .activity-table {
            width: 100%;
            min-width: 920px;
            border-collapse: collapse;
        }

        .activity-table th {
            background: #ecfdf5;
            color: #065f46;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 950;
            padding: 13px 14px;
            text-align: left;
            border-bottom: 1px solid #cdeee2;
        }

        .activity-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #e5e7eb;
            color: #0f172a;
            font-weight: 650;
            vertical-align: top;
        }

        .activity-table tr:last-child td {
            border-bottom: none;
        }

        .activity-route {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: .84rem;
            color: #334155;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
            border-radius: 10px;
            display: inline-block;
        }

        .activity-method {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 54px;
            padding: 6px 9px;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            font-size: .78rem;
            font-weight: 950;
        }

        .activity-empty {
            padding: 28px;
            text-align: center;
            color: #64748b;
            font-weight: 800;
            background: #f8fafc;
            border-radius: 18px;
        }

        .activity-pagination {
            margin-top: 22px;
        }

        @media (max-width: 900px) {
            .activity-user-group summary {
                grid-template-columns: 1fr;
            }

            .activity-toggle {
                justify-self: start;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <h3 class="page-title">Activity Logs</h3>
            <div class="page-subtitle">Grouped audit trail of user and system activity.</div>
        </div>
    </div>

    @php
        $groupedLogs = $logs->getCollection()->groupBy(function ($log) {
            if ($log->user) {
                return 'user-'.$log->user->id;
            }

            if ($log->participant) {
                return 'participant-'.$log->participant->id;
            }

            return 'system-'.($log->ip_address ?? $log->ip ?? 'unknown');
        });
    @endphp

    <div class="activity-log-card">
        <div class="activity-log-body">
            @forelse($groupedLogs as $group)
                @php
                    $first = $group->first();

                    $userName = $first->user?->name
                        ?? $first->participant?->full_name
                        ?? 'System / Guest';

                    $participantName = $first->participant?->full_name ?? 'N/A';

                    $latestTime = $group->max('created_at');
                    $latestFormatted = $latestTime ? \Carbon\Carbon::parse($latestTime)->format('d M Y h:i:s A') : 'N/A';

                    $ips = $group
                        ->map(fn ($item) => $item->ip_address ?? $item->ip ?? null)
                        ->filter()
                        ->unique()
                        ->values();

                    $routes = $group
                        ->map(fn ($item) => $item->route_name ?? $item->route ?? null)
                        ->filter()
                        ->unique()
                        ->values();

                    $methods = $group
                        ->map(fn ($item) => $item->method ?? null)
                        ->filter()
                        ->unique()
                        ->values();
                @endphp

                <details class="activity-user-group">
                    <summary>
                        <div>
                            <div class="activity-user-name">{{ $userName }}</div>
                            <div class="activity-user-meta">
                                Participant: {{ $participantName }}
                            </div>
                        </div>

                        <div>
                            <span class="activity-pill">{{ $group->count() }} activities</span>
                        </div>

                        <div>
                            <span class="activity-pill activity-pill-muted">
                                {{ $methods->implode(', ') ?: 'N/A' }}
                            </span>
                        </div>

                        <div>
                            <div class="activity-user-meta">Latest activity</div>
                            <strong>{{ $latestFormatted }}</strong>
                        </div>

                        <div class="activity-toggle">⌄</div>
                    </summary>

                    <div class="activity-details">
                        <div style="margin: 16px 0; display:flex; gap:10px; flex-wrap:wrap;">
                            <span class="activity-pill activity-pill-muted">
                                IP: {{ $ips->take(3)->implode(', ') ?: 'N/A' }}
                            </span>

                            <span class="activity-pill activity-pill-muted">
                                Routes: {{ $routes->count() }}
                            </span>
                        </div>

                        <div class="activity-table-wrap">
                            <table class="activity-table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Route</th>
                                        <th>Method</th>
                                        <th>IP</th>
                                        <th>Participant</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($group as $log)
                                        <tr>
                                            <td>{{ $log->action ?? 'request.hit' }}</td>
                                            <td>
                                                <span class="activity-route">
                                                    {{ $log->route_name ?? $log->route ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="activity-method">
                                                    {{ $log->method ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ $log->ip_address ?? $log->ip ?? 'N/A' }}</td>
                                            <td>{{ $log->participant?->full_name ?? 'N/A' }}</td>
                                            <td>{{ $log->created_at?->format('d M Y h:i:s A') ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </details>
            @empty
                <div class="activity-empty">
                    No activity logs found.
                </div>
            @endforelse

            @if(method_exists($logs, 'links'))
                <div class="activity-pagination">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

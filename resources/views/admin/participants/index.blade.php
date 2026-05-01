@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">List of Participants</h3>
        <div class="page-subtitle">Participants grouped by batch. Click a batch to expand or collapse its list.</div>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('admin.participants.create') }}" class="btn btn-primary">Add Participant</a>
        <a href="{{ route('admin.participants.import-form') }}" class="btn btn-secondary">Import Participants</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($participants->isEmpty())
    <div class="card">
        <div class="card-body">
            No participants found.
        </div>
    </div>
@else
    <style>
        .participant-toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 18px;
            align-items: center;
            justify-content: space-between;
        }

        .participant-toolbar-left,
        .participant-toolbar-right {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .participant-search-box {
            min-width: 280px;
            flex: 1 1 320px;
            max-width: 420px;
        }

        .batch-group-card {
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
            margin-bottom: 18px;
            overflow: hidden;
        }

        .batch-group-toggle {
            width: 100%;
            border: 0;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding: 18px 20px;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            font: inherit;
        }

        .batch-group-left {
            min-width: 0;
        }

        .batch-group-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 4px;
        }

        .batch-group-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .batch-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #075985;
            font-size: 12px;
            font-weight: 800;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
        }

        .batch-group-meta {
            font-size: 13px;
            color: #64748b;
        }

        .batch-group-icon {
            font-size: 18px;
            transition: transform 0.2s ease;
            color: #334155;
            flex-shrink: 0;
        }

        .batch-group-card.open .batch-group-icon {
            transform: rotate(90deg);
        }

        .batch-group-body {
            display: none;
            padding: 0 20px 20px;
        }

        .batch-group-card.open .batch-group-body {
            display: block;
        }

        .participant-table-wrap {
            overflow-x: auto;
        }

        .participant-row-hidden,
        .batch-group-hidden {
            display: none !important;
        }

        .search-result-note {
            font-size: 13px;
            color: #64748b;
            margin-left: 4px;
        }

        .participant-search-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        @media (max-width: 992px) {
            .participant-toolbar {
                align-items: stretch;
            }

            .participant-toolbar-left,
            .participant-toolbar-right {
                width: 100%;
            }

            .participant-search-box {
                max-width: none;
                width: 100%;
            }
        }
    </style>

    <div class="participant-toolbar">
        <div class="participant-toolbar-left">
            <button type="button" class="btn btn-secondary" onclick="expandAllBatchGroups()">Expand All</button>
            <button type="button" class="btn btn-secondary" onclick="collapseAllBatchGroups()">Collapse All</button>
        </div>

        <div class="participant-toolbar-right">
            <input
                type="text"
                id="participantSearch"
                class="input participant-search-box"
                placeholder="Search by participant no, name, batch, course, email, phone, gender, status..."
                oninput="filterParticipants()"
            >
            <div class="participant-search-actions">
                <button type="button" class="btn btn-secondary" onclick="clearParticipantSearch()">Clear Search</button>
                <div id="searchResultNote" class="search-result-note"></div>
            </div>
        </div>
    </div>

    @foreach($participants as $batchId => $batchParticipants)
        @php
            $first = collect($batchParticipants)->first();
            $batchName = $first?->batch?->name ?? 'Unassigned Batch';
            $courseTitle = $first?->batch?->course?->title ?? $first?->course?->title ?? 'No Course';
            $count = collect($batchParticipants)->count();
            $collapseId = 'batch-group-' . $loop->index;
        @endphp

        <div
            class="batch-group-card {{ $loop->first ? 'open' : '' }}"
            id="{{ $collapseId }}"
            data-batch-group
            data-original-count="{{ $count }}"
        >
            <button type="button" class="batch-group-toggle" onclick="toggleBatchGroup('{{ $collapseId }}')">
                <div class="batch-group-left">
                    <div class="batch-group-title-row">
                        <div class="batch-group-title">{{ $batchName }}</div>
                        <span class="batch-count-badge" data-batch-count>{{ $count }}</span>
                    </div>
                    <div class="batch-group-meta">
                        {{ $courseTitle }} · <span data-batch-meta-count>{{ $count }}</span> participant{{ $count > 1 ? 's' : '' }}
                    </div>
                </div>
                <div class="batch-group-icon">▶</div>
            </button>

            <div class="batch-group-body">
                <div class="participant-table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Participant No</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <th>Evaluation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(collect($batchParticipants)->filter(fn($item) => is_object($item)) as $participant)
                                @php
                                    $evaluationStatus = !empty($participant->evaluation_completed) ? 'Completed' : 'Pending';
                                    $searchText = strtolower(implode(' ', [
                                        $participant->participant_no ?? '',
                                        $participant->full_name ?? '',
                                        $participant->email ?? '',
                                        $participant->phone ?? '',
                                        $participant->gender ?? '',
                                        $participant->status ?? '',
                                        $evaluationStatus,
                                        $participant->batch?->name ?? '',
                                        $participant->batch?->course?->title ?? '',
                                        $participant->course?->title ?? '',
                                    ]));
                                @endphp
                                <tr data-participant-row data-search="{{ $searchText }}">
                                    <td>{{ $participant->participant_no }}</td>
                                    <td>{{ $participant->full_name }}</td>
                                    <td>{{ $participant->email ?? '—' }}</td>
                                    <td>{{ $participant->phone ?? '—' }}</td>
                                    <td>{{ $participant->gender ?? '—' }}</td>
                                    <td>{{ $participant->status ?? '—' }}</td>
                                    <td>{{ $evaluationStatus }}</td>
                                    <td>
                                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                            <a href="{{ route('admin.participants.show', $participant) }}" class="btn btn-secondary">View</a>
                                            <a href="{{ route('admin.participants.edit', $participant) }}" class="btn btn-primary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function toggleBatchGroup(id) {
            const el = document.getElementById(id);
            if (el && !el.classList.contains('batch-group-hidden')) {
                el.classList.toggle('open');
            }
        }

        function expandAllBatchGroups() {
            document.querySelectorAll('.batch-group-card').forEach((el) => {
                if (!el.classList.contains('batch-group-hidden')) {
                    el.classList.add('open');
                }
            });
        }

        function collapseAllBatchGroups() {
            document.querySelectorAll('.batch-group-card').forEach((el) => {
                if (!el.classList.contains('batch-group-hidden')) {
                    el.classList.remove('open');
                }
            });
        }

        function clearParticipantSearch() {
            const input = document.getElementById('participantSearch');
            if (input) {
                input.value = '';
            }
            filterParticipants();
            if (input) {
                input.focus();
            }
        }

        function updateBatchCounts(group, visibleInGroup) {
            const countBadge = group.querySelector('[data-batch-count]');
            const metaCount = group.querySelector('[data-batch-meta-count]');
            const originalCount = parseInt(group.getAttribute('data-original-count') || '0', 10);
            const effectiveCount = visibleInGroup >= 0 ? visibleInGroup : originalCount;

            if (countBadge) countBadge.textContent = effectiveCount;
            if (metaCount) metaCount.textContent = effectiveCount;
        }

        function filterParticipants() {
            const input = document.getElementById('participantSearch');
            const query = (input?.value || '').trim().toLowerCase();
            const groups = document.querySelectorAll('[data-batch-group]');
            const note = document.getElementById('searchResultNote');

            let totalVisibleRows = 0;
            let totalVisibleGroups = 0;

            groups.forEach((group, index) => {
                const rows = group.querySelectorAll('[data-participant-row]');
                let visibleInGroup = 0;

                rows.forEach((row) => {
                    const haystack = row.getAttribute('data-search') || '';
                    const match = query === '' || haystack.includes(query);

                    row.classList.toggle('participant-row-hidden', !match);

                    if (match) {
                        visibleInGroup++;
                        totalVisibleRows++;
                    }
                });

                if (query === '') {
                    group.classList.remove('batch-group-hidden');
                    updateBatchCounts(group, parseInt(group.getAttribute('data-original-count') || '0', 10));

                    if (index === 0) {
                        group.classList.add('open');
                    }
                } else {
                    const hidden = visibleInGroup === 0;
                    group.classList.toggle('batch-group-hidden', hidden);
                    updateBatchCounts(group, visibleInGroup);

                    if (!hidden) {
                        totalVisibleGroups++;
                        group.classList.add('open');
                    }
                }
            });

            if (note) {
                if (query === '') {
                    note.textContent = '';
                } else {
                    note.textContent = `${totalVisibleRows} matching participant(s) in ${totalVisibleGroups} batch group(s).`;
                }
            }
        }
    </script>
@endif
@endsection

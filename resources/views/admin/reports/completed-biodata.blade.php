@extends('layouts.admin')

@section('content')
<style>
    .biodata-report-page {
        max-width: 1050px;
        margin: 0 auto;
    }

    .biodata-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
        overflow: hidden;
    }

    .biodata-card-body {
        padding: 24px;
    }

    .biodata-filter {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 14px;
        align-items: end;
    }

    .biodata-field label {
        display: block;
        font-size: .9rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .biodata-field select {
        width: 100%;
        border: 1px solid #cfe1dc;
        border-radius: 14px;
        padding: 13px 14px;
        outline: none;
        font-weight: 700;
        color: #0f172a;
    }

    .biodata-field select:focus {
        border-color: #0b6b57;
        box-shadow: 0 0 0 4px rgba(11,107,87,.08);
    }

    .biodata-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-green,
    .btn-soft {
        border-radius: 14px;
        padding: 13px 18px;
        font-weight: 900;
        text-decoration: none;
        border: 1px solid #dbe7e2;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .btn-green {
        background: #0b6b57;
        border-color: #0b6b57;
        color: #ffffff;
    }

    .btn-soft {
        background: #ffffff;
        color: #0f172a;
    }

    .biodata-summary {
        margin-top: 24px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .biodata-box {
        border: 1px solid #dbe7e2;
        border-radius: 18px;
        padding: 18px;
        background: #f8fafc;
    }

    .biodata-box small {
        display: block;
        color: #64748b;
        font-size: .78rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 8px;
    }

    .biodata-box strong {
        display: block;
        color: #0f172a;
        font-size: 1.6rem;
        font-weight: 950;
    }

    .biodata-note {
        margin-top: 22px;
        padding: 16px 18px;
        border-radius: 18px;
        background: #ecfdf5;
        border: 1px solid #b7e4d2;
        color: #065f46;
        font-weight: 750;
        line-height: 1.6;
    }

    @media (max-width: 760px) {
        .biodata-filter {
            grid-template-columns: 1fr;
        }

        .biodata-actions a,
        .biodata-actions button {
            width: 100%;
        }

        .biodata-summary {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="biodata-report-page">
    <div class="page-header">
        <div>
            <h3 class="page-title">Completed Biodata Export</h3>
            <div class="page-subtitle">Download participants who have completed their biodata by batch.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.reports.index') }}" class="btn-soft">Back to Reports</a>
        </div>
    </div>

    <div class="biodata-card">
        <div class="biodata-card-body">
            <form method="GET" action="{{ route('admin.reports.completed-biodata') }}" class="biodata-filter">
                <div class="biodata-field">
                    <label for="batch_id">Select Batch</label>
                    <select name="batch_id" id="batch_id" required>
                        <option value="">Choose batch</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" @selected(request('batch_id') == $batch->id)>
                                {{ $batch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="biodata-actions">
                    <button type="submit" class="btn-soft">Check Batch</button>

                    @if($selectedBatch)
                        <a href="{{ route('admin.reports.completed-biodata.export', $selectedBatch) }}" class="btn-green">
                            Download CSV
                        </a>
                    @endif
                </div>
            </form>

            @if($selectedBatch)
                @php
                    $pendingCount = max(($totalCount ?? 0) - ($completedCount ?? 0), 0);
                @endphp

                <div class="biodata-summary">
                    <div class="biodata-box">
                        <small>Selected Batch</small>
                        <strong style="font-size:1.05rem;">{{ $selectedBatch->name }}</strong>
                    </div>

                    <div class="biodata-box">
                        <small>Completed Biodata</small>
                        <strong>{{ number_format($completedCount ?? 0) }}</strong>
                    </div>

                    <div class="biodata-box">
                        <small>Pending / Incomplete</small>
                        <strong>{{ number_format($pendingCount) }}</strong>
                    </div>
                </div>

                <div class="biodata-note">
                    The CSV export will include only participants in this batch whose biodata fields are complete.
                </div>
            @else
                <div class="biodata-note">
                    Select a batch to view the completed biodata count and download the CSV file.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

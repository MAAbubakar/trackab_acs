@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Automation Console</h3>
            <div class="page-subtitle">Run and review scheduled attendance operations manually when needed.</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="dashboard-panel dashboard-panel-lg">
            <div class="dashboard-panel-header">
                <h3>Manual Task Runs</h3>
            </div>

            <div class="dashboard-actions-grid">
                <form action="{{ route('admin.automation.run', 'close-expired-checkpoints') }}" method="POST">
                    @csrf
                    <button type="submit" class="dashboard-action-card" style="width:100%; text-align:left; border:none; cursor:pointer;">
                        <span class="dashboard-action-title">Close Expired Checkpoints</span>
                        <span class="dashboard-action-meta">Manually close checkpoints whose time window has passed.</span>
                    </button>
                </form>

                <form action="{{ route('admin.automation.run', 'compute-daily-summaries') }}" method="POST">
                    @csrf
                    <button type="submit" class="dashboard-action-card" style="width:100%; text-align:left; border:none; cursor:pointer;">
                        <span class="dashboard-action-title">Compute Daily Summaries</span>
                        <span class="dashboard-action-meta">Generate attendance summaries for participants and sessions.</span>
                    </button>
                </form>

                <form action="{{ route('admin.automation.run', 'evaluate-certificate-eligibility') }}" method="POST">
                    @csrf
                    <button type="submit" class="dashboard-action-card" style="width:100%; text-align:left; border:none; cursor:pointer;">
                        <span class="dashboard-action-title">Evaluate Certificate Eligibility</span>
                        <span class="dashboard-action-meta">Recalculate participant eligibility for certificates.</span>
                    </button>
                </form>
            </div>
        </section>

        <section class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h3>Scheduler Notes</h3>
            </div>

            <div class="dashboard-summary-list">
                <div class="dashboard-summary-item">
                    <span>Close Expired Checkpoints</span>
                    <strong>Every Minute</strong>
                </div>

                <div class="dashboard-summary-item">
                    <span>Daily Summaries</span>
                    <strong>Hourly</strong>
                </div>

                <div class="dashboard-summary-item">
                    <span>Certificate Eligibility</span>
                    <strong>Twice Daily</strong>
                </div>
            </div>
        </section>
    </div>
@endsection

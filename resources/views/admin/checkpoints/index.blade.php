@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Session Checkpoints</h3>
        <div class="page-subtitle">{{ $session->title ?? 'Training Session' }}</div>
    </div>
    <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Back</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<style>
    .checkpoint-form-shell {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 28px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        margin-bottom: 22px;
    }

    .checkpoint-builder-grid {
        display: grid;
        grid-template-columns: 1.65fr 1fr;
        gap: 28px;
        align-items: start;
    }

    .checkpoint-left-stack {
        display: grid;
        gap: 16px;
    }

    .checkpoint-field-card {
        background: linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
        border: 1px solid #e8edf3;
        border-radius: 18px;
        padding: 18px 20px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .checkpoint-field-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    }

    .checkpoint-field-card label {
        display: block;
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .checkpoint-field-card .input {
        margin-bottom: 0;
        border-radius: 14px;
        width: 100%;
        display: block;
        box-sizing: border-box;
    }

    .checkpoint-datetime-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .checkpoint-options-card {
        background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    }

    .checkpoint-options-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 14px;
    }

    .checkpoint-options-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 18px;
    }

    .checkpoint-option-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 14px;
        border: 1px solid #e7ecf2;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.035);
        margin-bottom: 12px;
        font-weight: 600;
        color: #0f172a;
    }

    .checkpoint-option-row:last-child {
        margin-bottom: 0;
    }

    .checkpoint-option-row input[type="checkbox"] {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .checkpoint-submit-row {
        margin-top: 22px;
        display: flex;
        justify-content: flex-start;
    }

    .checkpoint-generator-card {
        background: linear-gradient(180deg, #f7fffb 0%, #ffffff 100%);
        border: 1px solid #d7efe7;
        border-radius: 20px;
        padding: 18px 20px;
        box-shadow: 0 8px 22px rgba(16, 185, 129, 0.08);
        margin-bottom: 22px;
    }

    .checkpoint-generator-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .checkpoint-generator-title {
        font-size: 16px;
        font-weight: 800;
        color: #064e3b;
        margin-bottom: 4px;
    }

    .checkpoint-generator-text {
        color: #4b5563;
        max-width: 760px;
    }

    @media (max-width: 992px) {
        .checkpoint-builder-grid,
        .checkpoint-datetime-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="checkpoint-form-shell">
    <form method="POST" action="{{ route('admin.checkpoints.store', $session) }}">
        @csrf

        <div class="checkpoint-builder-grid">
            <div class="checkpoint-left-stack">
                <div class="checkpoint-field-card">
                    <label>Checkpoint Title</label>
                    <input type="text" name="title" class="input" required placeholder="Enter checkpoint title" style="width: 100%;">
                </div>

                <div class="checkpoint-field-card">
                    <label>Checkpoint Type</label>
                    <select name="checkpoint_type" class="input" required>
                        <option value="checkin">Check-in</option>
                        <option value="session_validation">Session Validation</option>
                        <option value="checkout">Check-out</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>

                <div class="checkpoint-field-card">
                    <div class="checkpoint-datetime-grid">
                        <div>
                            <label>Opens At</label>
                            <input type="datetime-local" name="opens_at" class="input" required>
                        </div>
                        <div>
                            <label>Closes At</label>
                            <input type="datetime-local" name="closes_at" class="input" required>
                        </div>
                    </div>
                </div>

                <div class="checkpoint-field-card">
                    <label>Weight</label>
                    <input type="number" step="0.01" min="0" name="weight" class="input" value="0">
                </div>
            </div>

            <div class="checkpoint-options-card">
                <div class="checkpoint-options-title">Checkpoint Options</div>
                <div class="checkpoint-options-subtitle">
                    Choose any validations you want attached to this checkpoint.
                </div>

                <label class="checkpoint-option-row">
                    <input type="checkbox" name="is_random" value="1">
                    <span>Random checkpoint</span>
                </label>

                <label class="checkpoint-option-row">
                    <input type="checkbox" name="requires_photo" value="1">
                    <span>Requires photo</span>
                </label>

                <label class="checkpoint-option-row">
                    <input type="checkbox" name="requires_device_validation" value="1">
                    <span>Requires device validation</span>
                </label>

                <label class="checkpoint-option-row">
                    <input type="checkbox" name="requires_location_validation" value="1">
                    <span>Requires location validation</span>
                </label>
            </div>
        </div>

        <div class="checkpoint-submit-row">
            <button type="submit" class="btn btn-primary">Create Checkpoint</button>
        </div>
    </form>
</div>

<div class="checkpoint-generator-card">
    <div class="checkpoint-generator-inner">
        <div>
            <div class="checkpoint-generator-title">Standard Checkpoint Generator</div>
            <div class="checkpoint-generator-text">
                Create Check-in, Random Session Validation, and Check-out automatically. Manual creation remains available above.
            </div>
        </div>

        <form method="POST" action="{{ route('admin.checkpoints.generate-standard', $session) }}">
            @csrf
            <button type="submit" class="btn btn-primary"
                onclick="return confirm('Generate Check-in, Random Session Validation, and Check-out for this session? Existing checkpoint types will be skipped.')">
                Generate Standard Checkpoints
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 style="margin-bottom:12px;">Existing Checkpoints</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Random</th>
                    <th>Weight</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($checkpoints as $checkpoint)
                    <tr>
                        <td>{{ $checkpoint->title }}</td>
                        <td>{{ $checkpoint->checkpoint_type }}</td>
                        <td>{{ $checkpoint->opens_at }}</td>
                        <td>{{ $checkpoint->closes_at }}</td>
                        <td>{{ $checkpoint->is_random ? 'Yes' : 'No' }}</td>
                        <td>{{ $checkpoint->weight }}</td>
                        <td>{{ $checkpoint->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No checkpoints created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

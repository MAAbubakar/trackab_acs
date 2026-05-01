@extends('layouts.participant')

@section('content')
<div class="page-header">
    <div>
        <h3 class="page-title">Request Profile Correction</h3>
        <div class="page-subtitle">Submit a correction request for any wrong profile information.</div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('participant.profile-corrections.store') }}" class="form-grid content-narrow">
            @csrf

            <div class="form-group">
                <label>Field to Correct</label>
                <select name="field_name" class="input" required>
                    <option value="">Select a field</option>
                    @foreach($fields as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Requested Correct Value</label>
                <textarea name="requested_value" class="input" required></textarea>
            </div>

            <div class="form-group">
                <label>Reason / Note</label>
                <textarea name="reason" class="input"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit Request</button>
                <a href="{{ route('participant.profile') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

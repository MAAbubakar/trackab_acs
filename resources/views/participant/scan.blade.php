@extends('layouts.participant')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Submit Attendance</h3>
            <div class="page-subtitle">Submit your attendance using your QR identifier or participant number.</div>
        </div>
    </div>

    <div class="section-stack">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('participant.scan') }}" method="POST" class="form-grid content-narrow">
                    @csrf

                    <div>
                        <label for="qr_identifier">QR Identifier</label>
                        <input
                            type="text"
                            name="qr_identifier"
                            id="qr_identifier"
                            value="{{ old('qr_identifier') }}"
                            placeholder="Enter or paste your QR identifier"
                        >
                    </div>

                    <div>
                        <label for="participant_no">Participant Number</label>
                        <input
                            type="text"
                            name="participant_no"
                            id="participant_no"
                            value="{{ old('participant_no', $participant->participant_no ?? '') }}"
                            placeholder="Use this if your QR is not available"
                        >
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">Submit Attendance</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="mt-0">Tips</h4>
                <div class="section-stack">
                    <div>• Use your assigned QR identifier when available.</div>
                    <div>• If your QR card is damaged, use your participant number.</div>
                    <div>• Only submit attendance during an active checkpoint window.</div>
                </div>
            </div>
        </div>
    </div>
@endsection

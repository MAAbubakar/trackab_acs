@extends('layouts.participant')

@section('content')
    <h3>Participant Self-Service Dashboard</h3>

    <p>Select a participant to open the self-service dashboard.</p>

    <form method="GET" onsubmit="event.preventDefault(); goToDashboard();">
        <div>
            <div>
                <label for="participant_id">Participant</label>
                <select id="participant_id"  required>
                    <option value="">Select Participant</option>
                    @foreach($participants as $participant)
                        <option value="{{ $participant->id }}">
                            {{ $participant->full_name }} ({{ $participant->participant_no }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" style="padding:10px 18px;">Open Dashboard</button>
            </div>
        </div>
    </form>

    <script>
        function goToDashboard() {
            const participantId = document.getElementById('participant_id').value;
            if (!participantId) return;
            window.location.href = `/participant/dashboard/${participantId}`;
        }
    </script>
@endsection

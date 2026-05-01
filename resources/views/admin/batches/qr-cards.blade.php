@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Batch QR Cards</h3>
            <div class="page-subtitle">
                Printable participant QR cards for {{ $batch->name }}.
                Showing {{ $participants->firstItem() ?? 0 }} - {{ $participants->lastItem() ?? 0 }}
                of {{ $participants->total() }} participants.
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.batches.index') }}" class="btn btn-secondary">Back</a>
            <button type="button" onclick="window.print()" class="btn btn-primary">Print This Page</button>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:20px;">
        @forelse($participants as $participant)
            @php
                $participantName =
                    $participant->full_name
                    ?? $participant->name
                    ?? trim(($participant->first_name ?? '') . ' ' . ($participant->last_name ?? ''))
                    ?? $participant->participant_no
                    ?? 'Participant';
            @endphp

            <div class="card">
                <div class="card-body">
                    <div style="font-weight:700; font-size:1.05rem; margin-bottom:6px;">
                        {{ $participantName }}
                    </div>

                    <div style="font-size:0.95rem; margin-bottom:6px;">
                        {{ $participant->participant_no ?? 'Participant' }}
                    </div>

                    <div style="font-size:0.95rem; margin-bottom:6px;">
                        {{ $participant->organization ?? 'Participant' }}
                    </div>

                    <div style="font-size:0.9rem; color:#64748b; margin-bottom:14px;">
                        {{ $batch->course?->title ?? 'N/A' }} · {{ $batch->name }}
                    </div>

                    <div style="text-align:center; margin-bottom:12px;">
                        @if($participant->qr_code_path)
                            <img
                                src="{{ asset('storage/' . $participant->qr_code_path) }}"
                                alt="Participant QR Code"
                                style="width:200px; height:200px; object-fit:contain; margin:0 auto;"
                            >
                        @else
                            <div class="empty-state">QR code not available.</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">No participants found in this batch yet.</div>
                </div>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 24px;">
        {{ $participants->links() }}
    </div>
@endsection

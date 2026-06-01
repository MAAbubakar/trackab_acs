@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Edit Participant</h3>
            <div class="page-subtitle">Update participant biodata, contact details, course, and batch.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.participants.show', $participant) }}" class="btn btn-secondary">View</a>
            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.participants.update', $participant) }}">
                @csrf
                @method('PUT')

                @include('admin.participants._form', [
                    'participant' => $participant,
                    'courses' => $courses ?? collect(),
                    'batches' => $batches ?? collect(),
                ])
            </form>
        </div>
    </div>
@endsection

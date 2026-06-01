@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Create Participant</h3>
            <div class="page-subtitle">Add a participant directly into a course and batch.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.participants.store') }}">
                @csrf

                @include('admin.participants._form', [
                    'participant' => null,
                    'courses' => $courses ?? collect(),
                    'batches' => $batches ?? collect(),
                ])
            </form>
        </div>
    </div>
@endsection

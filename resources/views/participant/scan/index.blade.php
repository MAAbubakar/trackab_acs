@extends('layouts.participant')

@section('content')
    <h3>Submit Attendance Scan</h3>

    <div style="margin-bottom:20px;">
        <p><strong>Participant:</strong> {{ $participant->full_name }}</p>
        <p><strong>Participant No:</strong> {{ $participant->participant_no }}</p>
        <p><strong>Course:</strong> {{ $participant->course?->title ?? 'N/A' }}</p>
        <p><strong>Batch:</strong> {{ $participant->batch?->name ?? 'N/A' }}</p>
    </div>

    <form action="{{ route('participant.scan.submit') }}" method="POST" enctype="multipart/form-data" style="display:grid; gap:15px; max-width:700px;">
        @csrf

        <div>
            <label for="token">Checkpoint Token</label>
            <input type="text" name="token" id="token" value="{{ old('token') }}"  required>
        </div>

        <div>
            <label for="device_id">Device ID</label>
            <input type="text" name="device_id" id="device_id" value="{{ old('device_id') }}" >
        </div>

        <div>
            <label for="latitude">Latitude</label>
            <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" >
        </div>

        <div>
            <label for="longitude">Longitude</label>
            <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" >
        </div>

        <div>
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo" >
        </div>

        <div>
            <button type="submit" style="padding:10px 18px;">Submit Attendance</button>
        </div>
    </form>
@endsection

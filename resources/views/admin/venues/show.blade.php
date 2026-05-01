@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Venue Details</h3>
            <div class="page-subtitle">{{ $venue->name }}</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="two-col-grid">
                <div>
                    <div><strong>Name:</strong> {{ $venue->name }}</div>
                    <div><strong>Location:</strong> {{ $venue->location_description ?: 'N/A' }}</div>
                </div>

                <div>
                    <div><strong>IP Restriction:</strong> {{ $venue->ip_restriction ?: 'N/A' }}</div>
                    <div><strong>Device Restriction:</strong> {{ $venue->device_restriction ? 'Enabled' : 'Disabled' }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($venue->status ?? 'inactive') }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

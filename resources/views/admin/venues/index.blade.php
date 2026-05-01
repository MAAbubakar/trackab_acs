@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Venues</h3>
            <div class="page-subtitle">Manage training venues and checkpoint restrictions.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.venues.create') }}" class="btn btn-primary">Add Venue</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>IP Restriction</th>
                            <th>Device Restriction</th>
                            <th>Status</th>
                            <th width="260">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($venues as $venue)
                            <tr>
                                <td>{{ $venue->name }}</td>
                                <td>{{ $venue->location_description ?: 'N/A' }}</td>
                                <td>{{ $venue->ip_restriction ?: 'N/A' }}</td>
                                <td>{{ $venue->device_restriction ? 'Yes' : 'No' }}</td>
                                <td>
                                    <span class="badge {{ ($venue->status ?? '') === 'active' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($venue->status ?? 'inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-inline">
                                        <a href="{{ route('admin.venues.show', $venue) }}" class="btn btn-secondary">View</a>
                                        <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-primary">Edit</a>

                                        <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this venue?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">No venues found yet.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $venues->links() }}
            </div>
        </div>
    </div>
@endsection

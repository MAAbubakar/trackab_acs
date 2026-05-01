@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h3 class="page-title">Create Venue</h3>
            <div class="page-subtitle">Add a venue for training delivery and attendance operations.</div>
        </div>

        <div class="actions-inline">
            <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.venues.store') }}" method="POST" class="form-grid content-narrow">
                @csrf

                <div>
                    <label for="name">Venue Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                </div>

                <div>
                    <label for="location_description">Location Description</label>
                    <input type="text" name="location_description" id="location_description" value="{{ old('location_description') }}">
                </div>

                <div class="two-col-grid">
                    <div>
                        <label for="ip_restriction">IP Restriction</label>
                        <input type="text" name="ip_restriction" id="ip_restriction" value="{{ old('ip_restriction') }}" placeholder="Optional allowed IP or network">
                    </div>

                    <div>
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:flex; align-items:center; gap:10px;">
                        <input type="hidden" name="device_restriction" value="0">
                        <input type="checkbox" name="device_restriction" value="1" {{ old('device_restriction') ? 'checked' : '' }} style="width:auto;">
                        Enable device restriction
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Create Venue</button>
                </div>
            </form>
        </div>
    </div>
@endsection

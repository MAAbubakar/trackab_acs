@extends('layouts.participant')

@section('title', 'Edit Profile')

@section('content')
<style>
    .profile-edit-wrap {
        padding: 1.5rem;
    }

    .profile-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .profile-edit-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #102033;
        margin-bottom: .35rem;
    }

    .profile-edit-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .profile-card {
        background: #ffffff;
        border: 1px solid #dbe7e2;
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        padding: 1.5rem;
    }

    .profile-section-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 1rem;
        padding-bottom: .75rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .profile-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.15rem 1.25rem;
    }

    .profile-field-full {
        grid-column: 1 / -1;
    }

    .profile-label {
        display: block;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: .45rem;
        font-size: .95rem;
    }

    .profile-control {
        width: 100%;
        border: 1px solid #cfe1dc;
        border-radius: .8rem;
        padding: .9rem 1rem;
        color: #0f172a;
        background: #fff;
        font-size: 1rem;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .profile-control:focus {
        border-color: #0b6b3a;
        box-shadow: 0 0 0 .2rem rgba(11, 107, 58, .12);
    }

    .profile-control.is-invalid {
        border-color: #dc3545;
    }

    .profile-readonly-box {
        border: 1px solid #dbe7e2;
        border-radius: .8rem;
        padding: .9rem 1rem;
        background: #f8fafc;
        color: #334155;
        min-height: 52px;
        display: flex;
        align-items: center;
    }

    .profile-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-profile-primary {
        background: #0b6b3a;
        border: 1px solid #0b6b3a;
        color: #fff;
        border-radius: .85rem;
        padding: .75rem 1.15rem;
        font-weight: 800;
        text-decoration: none;
    }

    .btn-profile-primary:hover {
        background: #095c32;
        border-color: #095c32;
        color: #fff;
    }

    .btn-profile-light {
        background: #fff;
        border: 1px solid #dbe7e2;
        color: #0f172a;
        border-radius: .85rem;
        padding: .75rem 1.15rem;
        font-weight: 800;
        text-decoration: none;
    }

    .btn-profile-light:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .profile-alert {
        border-radius: 1rem;
        border: 0;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
    }

    @media (max-width: 768px) {
        .profile-edit-wrap {
            padding: 1rem;
        }

        .profile-edit-header {
            flex-direction: column;
        }

        .profile-form-grid {
            grid-template-columns: 1fr;
        }

        .profile-actions {
            flex-direction: column-reverse;
        }

        .profile-actions a,
        .profile-actions button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="profile-edit-wrap">

    <div class="profile-edit-header">
        <div>
            <h1 class="profile-edit-title">Edit Profile</h1>
            <p class="profile-edit-subtitle">Update your biodata and contact information.</p>
        </div>

        <a href="{{ route('participant.profile') }}" class="btn-profile-light">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger profile-alert">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('participant.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="profile-card">
            <div class="profile-section-title">Personal and Contact Information</div>

            <div class="profile-form-grid">

                <div>
                    <label class="profile-label">Participant Number</label>
                    <div class="profile-readonly-box">
                        {{ $participant->participant_no ?? '—' }}
                    </div>
                </div>

                @foreach ($editableFields as $field => $meta)
                    @php
                        $type = $meta['type'] ?? 'text';
                        $isFull = in_array($field, ['address'], true);
                    @endphp

                    <div class="{{ $isFull ? 'profile-field-full' : '' }}">
                        <label class="profile-label">{{ $meta['label'] }}</label>

                        @if ($type === 'textarea')
                            <textarea
                                name="{{ $field }}"
                                rows="4"
                                class="profile-control @error($field) is-invalid @enderror"
                            >{{ old($field, $participant->{$field} ?? '') }}</textarea>

                        @elseif ($type === 'select')
                            <select
                                name="{{ $field }}"
                                class="profile-control @error($field) is-invalid @enderror"
                            >
                                <option value="">Select {{ strtolower($meta['label']) }}</option>
                                @foreach (($meta['options'] ?? []) as $option)
                                    <option value="{{ $option }}" @selected(old($field, $participant->{$field} ?? '') === $option)>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>

                        @else
                            <input
                                type="{{ $type }}"
                                name="{{ $field }}"
                                value="{{ old($field, $participant->{$field} ?? '') }}"
                                class="profile-control @error($field) is-invalid @enderror"
                                placeholder="{{ $meta['placeholder'] ?? '' }}"
                            >
                        @endif

                        @error($field)
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

            </div>

            <div class="profile-actions">
                <a href="{{ route('participant.profile') }}" class="btn-profile-light">
                    Cancel
                </a>

                <button type="submit" class="btn-profile-primary">
                    Save Changes
                </button>
            </div>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const countryField = document.querySelector('[name="nationality"]');
    const stateField = document.querySelector('[name="state_of_origin"]');
    const lgaField = document.querySelector('[name="lga"]');

    if (!countryField || !stateField || !lgaField) {
        return;
    }

    const selectedState = @json(old('state_of_origin', $participant->state_of_origin ?? ''));
    const selectedLga = @json(old('lga', $participant->lga ?? ''));

    const clearSelect = (select, placeholder) => {
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);
    };

    const addOptions = (select, items, selectedValue) => {
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item;
            option.textContent = item;

            if (item === selectedValue) {
                option.selected = true;
            }

            select.appendChild(option);
        });
    };

    const loadStates = async () => {
        const country = countryField.value;

        clearSelect(stateField, 'Select State');
        clearSelect(lgaField, 'Select LGA');

        if (country !== 'Nigeria') {
            stateField.disabled = true;
            lgaField.disabled = true;
            return;
        }

        stateField.disabled = false;
        lgaField.disabled = false;

        const response = await fetch(`/locations/states?country=${encodeURIComponent(country)}`, {
            headers: { 'Accept': 'application/json' }
        });

        const states = await response.json();
        addOptions(stateField, states, selectedState);

        if (stateField.value) {
            await loadLgas();
        }
    };

    const loadLgas = async () => {
        const country = countryField.value;
        const state = stateField.value;

        clearSelect(lgaField, 'Select LGA');

        if (country !== 'Nigeria' || !state) {
            lgaField.disabled = true;
            return;
        }

        lgaField.disabled = false;

        const response = await fetch(`/locations/lgas?country=${encodeURIComponent(country)}&state=${encodeURIComponent(state)}`, {
            headers: { 'Accept': 'application/json' }
        });

        const lgas = await response.json();
        addOptions(lgaField, lgas, selectedLga);
    };

    countryField.addEventListener('change', loadStates);
    stateField.addEventListener('change', loadLgas);

    loadStates();
});
</script>

@endsection

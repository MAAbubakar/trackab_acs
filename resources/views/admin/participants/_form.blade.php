
<style>
    .participant-field-help {
        margin-top: 7px;
        font-size: .82rem;
        line-height: 1.45;
        color: #64748b;
        font-weight: 700;
    }

    .participant-field-help strong {
        color: #0b6b57;
        font-weight: 900;
    }
</style>

@php
    $participant = $participant ?? null;

    $countries = config('countries', []);

    if (! is_array($countries) || count($countries) === 0) {
        $countriesPath = base_path('config/countries.php');
        $countries = file_exists($countriesPath) ? require $countriesPath : [];
    }

    $countries = is_array($countries) ? $countries : [];

    $nigeriaLocations = config('nigeria_locations', []);
    $nigeriaLocations = is_array($nigeriaLocations) ? $nigeriaLocations : [];
    $nigeriaStates = array_values(array_keys($nigeriaLocations));
    $nigeriaLgas = collect($nigeriaLocations)->flatten()->unique()->sort()->values()->all();

    $genderOptions = [
        'Male' => 'Male',
        'Female' => 'Female',
    ];

    $academicBackgroundOptions = [
        'P.hD' => 'P.hD',
        'M.Sc/Masters' => 'M.Sc/Masters',
        'B.Sc' => 'B.Sc',
        'HND' => 'HND',
        'ND' => 'ND',
        'NECO/WAEC' => 'NECO/WAEC',
    ];

    $employmentStatusOptions = [
        'employed' => 'Employed',
        'unemployed' => 'Unemployed',
        'self-employed' => 'Self-Employed',
    ];

    $employmentSectorOptions = [
        'Public' => 'Public',
        'Private' => 'Private',
    ];

    $registrationStatusOptions = [
        'pending' => 'Pending',
        'registered' => 'Registered',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
    ];

    $statusOptions = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    $oldOrValue = function ($field, $default = '') use ($participant) {
        return old($field, $participant?->{$field} ?? $default);
    };
@endphp

<style>
    .participant-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .participant-form-section {
        grid-column: 1 / -1;
        margin-top: 10px;
        padding: 14px 16px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-weight: 900;
        color: #0f172a;
    }

    .participant-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 900;
        color: #334155;
        margin-bottom: 7px;
    }

    .participant-form-control {
        width: 100%;
        border: 1px solid #dbe3ea;
        border-radius: 14px;
        padding: 12px 13px;
        background: #ffffff;
        color: #0f172a;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
    }

    .participant-form-control:focus {
        border-color: #0b6b57;
        box-shadow: 0 0 0 4px rgba(11, 107, 87, .08);
    }

    .participant-form-error {
        margin-top: 6px;
        color: #b91c1c;
        font-size: 12px;
        font-weight: 700;
    }

    .participant-form-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 800px) {
        .participant-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="participant-form-grid">
    <div class="participant-form-section">Core Participant Information</div>

    <div class="participant-form-group">
        <label for="participant_no">Participant Number</label>
        <input type="text" name="participant_no" id="participant_no" class="participant-form-control" value="{{ $oldOrValue('participant_no') }}" required>
        @error('participant_no') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" id="full_name" class="participant-form-control" value="{{ $oldOrValue('full_name') }}" required>
        <div class="participant-field-help">
            Enter the participant’s full name <strong>exactly how it should appear on the certificate</strong>.
        </div>
        @error('full_name') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" class="participant-form-control" value="{{ $oldOrValue('email') }}">
        @error('email') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="phone">Phone Number</label>
        <input type="text" name="phone" id="phone" class="participant-form-control" value="{{ $oldOrValue('phone') }}">
        @error('phone') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-section">Personal Details</div>

    

    <div class="participant-form-group">
        <label for="gender">Gender</label>
        <select name="gender" id="gender" class="participant-form-control">
            <option value="">Select Gender</option>
            @foreach($genderOptions as $value => $label)
                <option value="{{ $value }}" @selected($oldOrValue('gender') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('gender') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="age">Age</label>
        <input type="number" name="age" id="age" class="participant-form-control" value="{{ $oldOrValue('age') }}" min="1" max="120">
        @error('age') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="nationality">Nationality</label>
        <select name="nationality" id="nationality" class="participant-form-control">
            <option value="">Select Nationality</option>
            @foreach($countries as $country)
                <option value="{{ $country }}" @selected($oldOrValue('nationality', 'Nigeria') === $country)>{{ $country }}</option>
            @endforeach
        </select>
        @error('nationality') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="state_of_origin">State of Origin</label>
        <select name="state_of_origin" id="state_of_origin" class="participant-form-control">
            <option value="">Select State</option>
            @foreach($nigeriaStates as $state)
                <option value="{{ $state }}" @selected($oldOrValue('state_of_origin') === $state)>{{ $state }}</option>
            @endforeach
        </select>
        @error('state_of_origin') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="lga">Local Government Area</label>
        <select name="lga" id="lga" class="participant-form-control">
            <option value="">Select LGA</option>
            @foreach($nigeriaLgas as $lga)
                <option value="{{ $lga }}" @selected($oldOrValue('lga') === $lga)>{{ $lga }}</option>
            @endforeach
        </select>
        @error('lga') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-section">Academic and Employment Details</div>

    <div class="participant-form-group">
        <label for="academic_background">Academic Background</label>
        <select name="academic_background" id="academic_background" class="participant-form-control">
            <option value="">Select Academic Background</option>
            @foreach($academicBackgroundOptions as $value => $label)
                <option value="{{ $value }}" @selected($oldOrValue('academic_background') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('academic_background') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="employment_status">Employment Status</label>
        <select name="employment_status" id="employment_status" class="participant-form-control">
            <option value="">Select Employment Status</option>
            @foreach($employmentStatusOptions as $value => $label)
                <option value="{{ $value }}" @selected($oldOrValue('employment_status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('employment_status') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="employment_sector">Employment Sector</label>
        <select name="employment_sector" id="employment_sector" class="participant-form-control">
            <option value="">Select Employment Sector</option>
            @foreach($employmentSectorOptions as $value => $label)
                <option value="{{ $value }}" @selected($oldOrValue('employment_sector') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('employment_sector') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="employer_name">Employer Name</label>
        <input type="text" name="employer_name" id="employer_name" class="participant-form-control" value="{{ $oldOrValue('employer_name') }}">
        @error('employer_name') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="organization">Organization</label>
        <input type="text" name="organization" id="organization" class="participant-form-control" value="{{ $oldOrValue('organization') }}">
        @error('organization') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="designation">Designation</label>
        <input type="text" name="designation" id="designation" class="participant-form-control" value="{{ $oldOrValue('designation') }}">
        @error('designation') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-section">Training Details</div>

    <div class="participant-form-group">
        <label for="course_id">Course</label>
        <select name="course_id" id="course_id" class="participant-form-control" required>
            <option value="">Select Course</option>
            @foreach(($courses ?? collect()) as $course)
                <option value="{{ $course->id }}" @selected((string)$oldOrValue('course_id') === (string)$course->id)>
                    {{ $course->title ?? $course->name ?? 'Course '.$course->id }}
                </option>
            @endforeach
        </select>
        @error('course_id') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="batch_id">Batch</label>
        <select name="batch_id" id="batch_id" class="participant-form-control" required>
            <option value="">Select Batch</option>
            @foreach(($batches ?? collect()) as $batch)
                <option value="{{ $batch->id }}" @selected((string)$oldOrValue('batch_id') === (string)$batch->id)>
                    {{ $batch->name ?? 'Batch '.$batch->id }}
                </option>
            @endforeach
        </select>
        @error('batch_id') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="registration_status">Registration Status</label>
        <select name="registration_status" id="registration_status" class="participant-form-control">
            @foreach($registrationStatusOptions as $value => $label)
                <option value="{{ $value }}" @selected($oldOrValue('registration_status', 'registered') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('registration_status') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-group">
        <label for="status">Account Status</label>
        <select name="status" id="status" class="participant-form-control">
            @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected($oldOrValue('status', 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="participant-form-error">{{ $message }}</div> @enderror
    </div>

    <div class="participant-form-actions">
        <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            {{ $participant ? 'Update Participant' : 'Create Participant' }}
        </button>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const countryField = document.querySelector('[name="nationality"]');
    const stateField = document.querySelector('[name="state_of_origin"]');
    const lgaField = document.querySelector('[name="lga"]');

    if (!countryField || !stateField || !lgaField) {
        return;
    }

    const selectedState = @json(old('state_of_origin', $participant?->state_of_origin ?? ''));
    const selectedLga = @json(old('lga', $participant?->lga ?? ''));

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


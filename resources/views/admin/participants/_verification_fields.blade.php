<div class="form-group">
    <label for="age">Age</label>
    <input type="number" name="age" id="age" class="input"
           value="{{ old('age', $participant->age ?? '') }}" min="1" max="100">
</div>

<div class="form-group">
    <label for="nationality">Nationality</label>
    <input type="text" name="nationality" id="nationality" class="input"
           value="{{ old('nationality', $participant->nationality ?? '') }}"
           placeholder="e.g. Nigerian">
</div>

<div class="form-group">
    <label for="academic_background">Academic Background</label>
    <select name="academic_background" id="academic_background" class="input">
        <option value="">Select academic background</option>
        @foreach([
            'Bachelor/Diploma',
            'Masters/PhD',
            'No tertiary qualification',
            'Other'
        ] as $option)
            <option value="{{ $option }}" @selected(old('academic_background', $participant->academic_background ?? '') === $option)>
                {{ $option }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="employment_status">Employment Status</label>
    <select name="employment_status" id="employment_status" class="input">
        <option value="">Select employment status</option>
        <option value="employed" @selected(old('employment_status', $participant->employment_status ?? '') === 'employed')>Employed</option>
        <option value="unemployed" @selected(old('employment_status', $participant->employment_status ?? '') === 'unemployed')>Unemployed</option>
    </select>
</div>

<div class="form-group">
    <label for="employment_sector">Employment Sector</label>
    <select name="employment_sector" id="employment_sector" class="input">
        <option value="">Select employment sector</option>
        <option value="public" @selected(old('employment_sector', $participant->employment_sector ?? '') === 'public')>Public</option>
        <option value="private" @selected(old('employment_sector', $participant->employment_sector ?? '') === 'private')>Private</option>
        <option value="other" @selected(old('employment_sector', $participant->employment_sector ?? '') === 'other')>Other</option>
    </select>
</div>

<div class="form-group">
    <label for="employer_name">Employer Name</label>
    <input type="text" name="employer_name" id="employer_name" class="input"
           value="{{ old('employer_name', $participant->employer_name ?? '') }}"
           placeholder="Employer / organisation name">
</div>

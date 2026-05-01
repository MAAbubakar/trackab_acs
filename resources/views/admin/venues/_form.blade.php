<div style="display: grid; gap: 15px;">
    <div>
        <label for="name">Venue Name</label>
        <input type="text" name="name" id="name"
               value="{{ old('name', $venue->name ?? '') }}"
                required>
    </div>

    <div>
        <label for="location_description">Location Description</label>
        <textarea name="location_description" id="location_description" rows="4"
                  >{{ old('location_description', $venue->location_description ?? '') }}</textarea>
    </div>

    <div>
        <label for="ip_restriction">IP Restriction</label>
        <input type="text" name="ip_restriction" id="ip_restriction"
               value="{{ old('ip_restriction', $venue->ip_restriction ?? '') }}"
               >
    </div>

    <div>
        <label>
            <input type="checkbox" name="device_restriction" value="1"
                   {{ old('device_restriction', $venue->device_restriction ?? false) ? 'checked' : '' }}>
            Device Restriction Enabled
        </label>
    </div>

    <div>
        <label for="status">Status</label>
        <select name="status" id="status"  required>
            <option value="active" {{ old('status', $venue->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $venue->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div>
        <button type="submit" style="padding: 10px 18px;">Save Venue</button>
    </div>
</div>

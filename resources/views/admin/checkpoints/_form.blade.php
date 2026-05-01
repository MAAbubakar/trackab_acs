<div>
    <div>
        <label for="title">Checkpoint Title</label>
        <input type="text" name="title" id="title"  required>
    </div>

    <div>
        <label for="checkpoint_type">Checkpoint Type</label>
        <select name="checkpoint_type" id="checkpoint_type"  required>
            <option value="checkin">Check-in</option>
            <option value="periodic">Periodic</option>
            <option value="post_lunch">Post Lunch</option>
            <option value="checkout">Check-out</option>
        </select>
    </div>

    <div>
        <label for="opens_at">Opens At</label>
        <input type="datetime-local" name="opens_at" id="opens_at"  required>
    </div>

    <div>
        <label for="closes_at">Closes At</label>
        <input type="datetime-local" name="closes_at" id="closes_at"  required>
    </div>

    <div>
        <label for="weight">Weight</label>
        <input type="number" name="weight" id="weight" value="20" min="1" max="100"  required>
    </div>

    <div>
        <label><input type="checkbox" name="is_random" value="1"> Random Checkpoint</label>
    </div>

    <div>
        <label><input type="checkbox" name="requires_photo" value="1"> Require Photo</label>
    </div>

    <div>
        <label><input type="checkbox" name="requires_device_validation" value="1"> Require Device Validation</label>
    </div>

    <div>
        <label><input type="checkbox" name="requires_location_validation" value="1"> Require Location Validation</label>
    </div>

    <div>
        <button type="submit" style="padding:10px 18px;">Create Checkpoint</button>
    </div>
</div>

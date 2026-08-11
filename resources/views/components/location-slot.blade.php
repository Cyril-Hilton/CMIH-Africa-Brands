@props([
    'index' => 0,
    'staff' => collect(),
])

<div class="location-slot" style="border:1px solid #3d202a; border-radius:16px; background:#231318; padding:14px; display:grid; gap:12px; margin-bottom:12px;">
    <div style="display:flex; justify-content:space-between; gap:12px; align-items:center;">
        <div>
            <strong style="display:block; color:#ffffff; font-size:13px; font-weight:900;">Location {{ ((int) $index) + 1 }}</strong>
            <small style="display:block; color:#bda7ad; font-size:10px;">Activation venue, target and assigned support staff</small>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:2fr 1fr 1fr; gap:10px;">
        <div class="field">
            <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">Location / Venue</label>
            <input
                name="locations[{{ $index }}][name]"
                value="{{ old('locations.'.$index.'.name') }}"
                placeholder="e.g. Accra Mall, Main Atrium"
                class="admin-input"
            >
        </div>
        <div class="field">
            <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">Total Target</label>
            <input
                name="locations[{{ $index }}][target]"
                type="number"
                min="0"
                value="{{ old('locations.'.$index.'.target') }}"
                placeholder="0"
                class="admin-input"
            >
        </div>
        <div class="field">
            <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">Daily Target</label>
            <input
                name="locations[{{ $index }}][daily_target]"
                type="number"
                min="0"
                value="{{ old('locations.'.$index.'.daily_target') }}"
                placeholder="0"
                class="admin-input"
            >
        </div>
    </div>

    <div class="field">
        <label style="font-size:9px; font-weight:900; color:#bda7ad; text-transform:uppercase;">Assigned Support Staff</label>
        <select
            name="locations[{{ $index }}][staff_ids][]"
            multiple
            class="admin-select"
            style="min-height:110px;"
        >
            @foreach($staff as $member)
                <option value="{{ $member->id }}" @selected(collect(old('locations.'.$index.'.staff_ids', []))->contains($member->id))>
                    {{ $member->name }}{{ $member->department ? ' - '.\App\Models\User::departmentLabel($member->department) : '' }}
                </option>
            @endforeach
        </select>
        <small style="display:block; color:#bda7ad; font-size:10px; margin-top:6px;">Hold Ctrl or Cmd to select more than one staff member.</small>
    </div>
</div>

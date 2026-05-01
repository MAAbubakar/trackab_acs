<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileCorrectionRequest;
use App\Notifications\SystemAlertNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileCorrectionRequestController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->string('filter')->toString();

        $requests = ProfileCorrectionRequest::with(['participant', 'user', 'reviewer', 'applier'])
            ->when($filter === 'pending', fn ($q) => $q->where('status', 'pending'))
            ->when($filter === 'approved_not_applied', fn ($q) => $q->where('status', 'approved')->where('is_applied', false))
            ->when($filter === 'applied', fn ($q) => $q->where('is_applied', true))
            ->when($filter === 'rejected', fn ($q) => $q->where('status', 'rejected'))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.profile_corrections.index', compact('requests', 'filter'));
    }

    public function update(Request $request, ProfileCorrectionRequest $profileCorrectionRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:reviewed,approved,rejected'],
            'admin_note' => ['nullable', 'string'],
        ]);

        $profileCorrectionRequest->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        if ($validated['status'] === 'approved') {
            $this->applyRequestIfAllowed($profileCorrectionRequest);
        }

        if ($validated['status'] !== 'approved') {
            $profileCorrectionRequest->update([
                'is_applied' => false,
                'applied_at' => null,
                'applied_by' => null,
            ]);
        }

        $participantUser = $profileCorrectionRequest->participant?->user;

        if ($participantUser) {
            $statusText = ucfirst($validated['status']);

            $participantUser->notify(new SystemAlertNotification(
                title: "Profile Correction Request {$statusText}",
                message: "Your correction request for {$profileCorrectionRequest->field_name} was {$validated['status']}.",
                url: route('participant.profile'),
                extra: [
                    'type' => 'profile_correction_status',
                    'request_id' => $profileCorrectionRequest->id,
                    'status' => $validated['status'],
                    'is_applied' => (bool) $profileCorrectionRequest->fresh()->is_applied,
                ]
            ));
        }

        return back()->with('success', 'Correction request updated.');
    }

    public function apply(ProfileCorrectionRequest $profileCorrectionRequest): RedirectResponse
    {
        if ($profileCorrectionRequest->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be applied manually.');
        }

        if ($profileCorrectionRequest->is_applied) {
            return back()->with('success', 'This request has already been applied.');
        }

        $applied = $this->applyRequestIfAllowed($profileCorrectionRequest);

        if (!$applied) {
            return back()->with('error', 'This correction request could not be applied. Check the field or participant record.');
        }

        $participantUser = $profileCorrectionRequest->participant?->user;

        if ($participantUser) {
            $participantUser->notify(new SystemAlertNotification(
                title: 'Profile Correction Applied',
                message: "Your approved correction request for {$profileCorrectionRequest->field_name} has now been applied to your profile.",
                url: route('participant.profile'),
                extra: [
                    'type' => 'profile_correction_applied',
                    'request_id' => $profileCorrectionRequest->id,
                    'status' => 'approved',
                    'is_applied' => true,
                ]
            ));
        }

        return back()->with('success', 'Approved correction request applied successfully.');
    }

    protected function applyRequestIfAllowed(ProfileCorrectionRequest $profileCorrectionRequest): bool
    {
        if (!$profileCorrectionRequest->participant) {
            return false;
        }

        $participant = $profileCorrectionRequest->participant;

        $allowedFields = [
            'full_name',
            'email',
            'phone',
            'alternate_phone',
            'gender',
            'age',
            'nationality',
            'academic_background',
            'state_of_origin',
            'organization',
            'designation',
            'sponsor_name',
            'employment_status',
            'employment_sector',
            'employer_name',
        ];

        $field = $profileCorrectionRequest->field_name;

        if (!in_array($field, $allowedFields, true)) {
            return false;
        }

        $participant->{$field} = $profileCorrectionRequest->requested_value;
        $participant->save();

        $profileCorrectionRequest->update([
            'current_value' => $profileCorrectionRequest->requested_value,
            'is_applied' => true,
            'applied_at' => now(),
            'applied_by' => auth()->id(),
        ]);

        return true;
    }
}

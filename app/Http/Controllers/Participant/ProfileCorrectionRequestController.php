<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\ProfileCorrectionRequest;
use App\Models\User;
use App\Notifications\SystemAlertNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileCorrectionRequestController extends Controller
{
    public function create(Request $request): View
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $fields = [
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'alternate_phone' => 'Alternate Phone',
            'gender' => 'Gender',
            'age' => 'Age',
            'nationality' => 'Nationality',
            'academic_background' => 'Academic Background',
            'state_of_origin' => 'State of Origin',
            'organization' => 'Organization',
            'designation' => 'Designation',
            'sponsor_name' => 'Sponsor Name',
            'employment_status' => 'Employment Status',
            'employment_sector' => 'Employment Sector',
            'employer_name' => 'Employer Name',
        ];

        return view('participant.profile_corrections.create', compact('participant', 'fields'));
    }

    public function store(Request $request): RedirectResponse
    {
        $participant = $request->user()->participant;
        abort_unless($participant, 403);

        $validated = $request->validate([
            'field_name' => ['required', 'string', 'max:100'],
            'requested_value' => ['required', 'string'],
            'reason' => ['nullable', 'string'],
        ]);

        $correction = ProfileCorrectionRequest::create([
            'participant_id' => $participant->id,
            'user_id' => $request->user()->id,
            'field_name' => $validated['field_name'],
            'current_value' => data_get($participant, $validated['field_name']),
            'requested_value' => $validated['requested_value'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        $admins = User::query()
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['super-admin', 'programme-coordinator', 'm&e-officer']);
            })
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new SystemAlertNotification(
                title: 'New Profile Correction Request',
                message: "{$participant->full_name} requested a correction for {$validated['field_name']}.",
                url: route('admin.profile-corrections.index'),
                extra: [
                    'type' => 'profile_correction_request',
                    'participant_id' => $participant->id,
                    'request_id' => $correction->id,
                ]
            ));
        }

        return redirect()
            ->route('participant.profile')
            ->with('success', 'Your correction request has been submitted.');
    }
}

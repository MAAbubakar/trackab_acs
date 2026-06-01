<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ParticipantProfileController extends Controller
{
    public function show(): View
    {
        $participant = $this->participant();

        return view('participant.profile', [
            'participant' => $participant,
            'editableFields' => $this->editableFields(),
        ]);
    }

    public function edit(): View
    {
        $participant = $this->participant();

        return view('participant.profile-edit', [
            'participant' => $participant,
            'editableFields' => $this->editableFields(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $participant = $this->participant();

        $rules = [];

        if (Schema::hasColumn('participants', 'full_name')) {
            $rules['full_name'] = ['required', 'string', 'max:255'];
        }

        if (Schema::hasColumn('participants', 'email')) {
            $rules['email'] = [
                'required',
                'email',
                'max:255',
                Rule::unique('participants', 'email')->ignore($participant->id),
            ];
        }

        foreach ($this->editableFields() as $field => $meta) {
            if (in_array($field, ['full_name', 'email'], true)) {
                continue;
            }

            if (! Schema::hasColumn('participants', $field)) {
                continue;
            }

            $rules[$field] = $meta['rules'];
        }

        $validated = $request->validate($rules);

        foreach ($validated as $field => $value) {
            if (Schema::hasColumn('participants', $field)) {
                $participant->{$field} = is_string($value) ? trim($value) : $value;
            }
        }

        $participant->save();

        if (
            Schema::hasColumn('participants', 'email') &&
            $participant->user &&
            ! empty($participant->email)
        ) {
            $participant->user->email = strtolower(trim($participant->email));
            $participant->user->name = $participant->full_name ?: $participant->user->name;
            $participant->user->save();
        }

        return redirect()
            ->route('participant.profile')
            ->with('success', 'Your profile information has been updated successfully.');
    }

    private function participant(): Participant
    {
        return Participant::where('user_id', auth()->id())->firstOrFail();
    }

    private function countries(): array
    {
        $countries = config('countries', []);

        if (! is_array($countries) || count($countries) === 0) {
            $countriesPath = base_path('config/countries.php');
            $countries = file_exists($countriesPath) ? require $countriesPath : [];
        }

        return is_array($countries) ? $countries : [];
    }


    private function nigeriaLocations(): array
    {
        $locations = config('nigeria_locations', []);

        return is_array($locations) ? $locations : [];
    }

    private function nigeriaStates(): array
    {
        return array_values(array_keys($this->nigeriaLocations()));
    }

    private function nigeriaLgas(): array
    {
        return collect($this->nigeriaLocations())
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    private function editableFields(): array
    {
        $fields = [
            'full_name' => [
                'label' => 'Full Name',
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255'],
            ],

            'email' => [
                'label' => 'Email Address',
                'type' => 'email',
                'rules' => ['required', 'email', 'max:255'],
            ],

            'phone' => [
                'label' => 'Phone Number',
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:50'],
            ],

            'gender' => [
                'label' => 'Gender',
                'type' => 'select',
                'options' => ['Male', 'Female'],
                'rules' => ['nullable', 'string', 'in:Male,Female'],
            ],


            'nationality' => [
                'label' => 'Nationality',
                'type' => 'select',
                'options' => $this->countries(),
                'rules' => ['nullable', 'string', 'max:100'],
            ],


            'state_of_origin' => [
                'label' => 'State of Origin',
                'type' => 'select',
                'options' => $this->nigeriaStates(),
                'rules' => ['nullable', 'string', 'max:100'],
            ],


            'lga' => [
                'label' => 'Local Government Area',
                'type' => 'select',
                'options' => $this->nigeriaLgas(),
                'rules' => ['nullable', 'string', 'max:150'],
            ],


            'age' => [
                'label' => 'Age',
                'type' => 'number',
                'rules' => ['nullable', 'integer', 'min:1', 'max:120'],
            ],

            'academic_background' => [
                'label' => 'Academic Background',
                'type' => 'select',
                'options' => [
                    'P.hD',
                    'M.Sc/Masters',
                    'B.Sc',
                    'HND',
                    'ND',
                    'NECO/WAEC',
                ],
                'rules' => ['nullable', 'string', 'in:P.hD,M.Sc/Masters,B.Sc,HND,ND,NECO/WAEC'],
            ],

            'employment_status' => [
                'label' => 'Employment Status',
                'type' => 'select',
                'options' => [
                    'employed',
                    'unemployed',
                    'self-employed',
                ],
                'rules' => ['nullable', 'string', 'in:employed,unemployed,self-employed'],
            ],

            'employment_sector' => [
                'label' => 'Employment Sector',
                'type' => 'select',
                'options' => [
                    'Public',
                    'Private',
                ],
                'rules' => ['nullable', 'string', 'in:Public,Private'],
            ],

            'organization' => [
                'label' => 'Organization',
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255'],
            ],

            'designation' => [
                'label' => 'Designation',
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255'],
            ],

            'employer_name' => [
                'label' => 'Employer Name',
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255'],
            ],
        ];

        return collect($fields)
            ->filter(fn ($meta, $field) => Schema::hasColumn('participants', $field))
            ->all();
    }
}

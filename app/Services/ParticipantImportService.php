<?php

namespace App\Services;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Spatie\Permission\Models\Role;

class ParticipantImportService
{
    public function import(string $filePath, int $courseId, int $batchId): array
    {
        if (! file_exists($filePath)) {
            return [
                'inserted' => 0,
                'updated' => 0,
                'users_created' => 0,
                'users_updated' => 0,
                'skipped' => 0,
                'errors' => ["Import file not found: {$filePath}"],
            ];
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (count($rows) < 2) {
            return [
                'inserted' => 0,
                'updated' => 0,
                'users_created' => 0,
                'users_updated' => 0,
                'skipped' => 0,
                'errors' => ['The uploaded sheet appears to be empty.'],
            ];
        }

        [$headerIndex, $columns] = $this->detectColumns($rows);

        if ($headerIndex === null || ! isset($columns['participant_no'], $columns['full_name'])) {
            return [
                'inserted' => 0,
                'updated' => 0,
                'users_created' => 0,
                'users_updated' => 0,
                'skipped' => 0,
                'errors' => [
                    'Could not find valid participant columns.',
                    'Required headers: participant_no and full_name.',
                    'Accepted alternatives include: participant no, registration no, reg no, name, full name.',
                ],
            ];
        }

        Role::firstOrCreate([
            'name' => 'participant',
            'guard_name' => 'web',
        ]);

        $inserted = 0;
        $updated = 0;
        $users_created = 0;
        $users_updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            if ($index <= $headerIndex) {
                continue;
            }

            $participantNo = $this->value($row, $columns, 'participant_no');
            $fullName = $this->value($row, $columns, 'full_name');

            if ($participantNo === '' || $fullName === '') {
                $skipped++;
                continue;
            }

            $email = $this->value($row, $columns, 'email');
            $phone = $this->value($row, $columns, 'phone');
            $plainPassword = $this->value($row, $columns, 'password');

            if ($plainPassword === '') {
                $plainPassword = 'Spesse@2026';
            }

            $data = [
                'course_id' => $courseId,
                'batch_id' => $batchId,
                'participant_no' => $participantNo,
                'full_name' => $fullName,
                'status' => 'active',
            ];

            $optionalFields = [
                'email' => 'email',
                'phone' => 'phone',
                'gender' => 'gender',
                'organization' => 'organization',
                'designation' => 'designation',
                'employment_sector' => 'employment_sector',
                'state_of_origin' => 'state_of_origin',
                'lga' => 'lga',
                'employer_name' => 'employer_name',
                'nationality' => 'nationality',
                'age' => 'age',
                'academic_background' => 'academic_background',
                'employment_status' => 'employment_status',
                'sponsor_name' => 'sponsor_name',
                'category' => 'category',
                'training_location' => 'training_location',
            ];

            foreach ($optionalFields as $sourceKey => $dbColumn) {
                if (Schema::hasColumn('participants', $dbColumn)) {
                    $value = $this->value($row, $columns, $sourceKey);

                    if ($value !== '') {
                        $data[$dbColumn] = $value;
                    }
                }
            }

            try {
                $participant = Participant::where('participant_no', $participantNo)->first();

                if ($participant) {
                    $participant->update($data);
                    $updated++;
                } else {
                    $data['user_id'] = null;
                    $participant = Participant::create($data);
                    $inserted++;
                }

                if ($email !== '') {
                    $user = User::where('email', $email)->first();

                    if ($user) {
                        $user->update([
                            'name' => $fullName,
                            'password' => Hash::make($plainPassword),
                            'status' => 'active',
                            'must_change_password' => true,
                        ]);

                        $users_updated++;
                    } else {
                        $user = User::create([
                            'name' => $fullName,
                            'email' => $email,
                            'password' => Hash::make($plainPassword),
                            'status' => 'active',
                            'must_change_password' => true,
                        ]);

                        $users_created++;
                    }

                    if (! $user->hasRole('participant')) {
                        $user->assignRole('participant');
                    }

                    if ((int) $participant->user_id !== (int) $user->id) {
                        $participant->user_id = $user->id;
                        $participant->save();
                    }
                }
            } catch (\Throwable $e) {
                $errors[] = 'Row ' . ($index + 1) . ': ' . $e->getMessage();
            }
        }

        return compact(
            'inserted',
            'updated',
            'users_created',
            'users_updated',
            'skipped',
            'errors'
        );
    }

    protected function detectColumns(array $rows): array
    {
        foreach ($rows as $index => $row) {
            $columns = [];

            foreach ($row as $colIndex => $value) {
                $key = $this->normalizeHeader((string) $value);

                if ($key !== '') {
                    $columns[$key] = $colIndex;
                }
            }

            if (isset($columns['participant_no'], $columns['full_name'])) {
                return [$index, $columns];
            }
        }

        return [null, []];
    }

    protected function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        $header = str_replace(['.', '-', '/', '\\'], ' ', $header);
        $header = preg_replace('/\s+/', ' ', $header);

        return match ($header) {
            'participant no', 'participant number', 'participant id',
            'reg no', 'registration no', 'registration number',
            'reg number', 'application no', 'application number',
            'participant_no' => 'participant_no',

            'name', 'full name', 'participant name', 'full_name' => 'full_name',

            'email', 'email address', 'e mail' => 'email',

            'phone', 'phone no', 'phone number', 'mobile',
            'mobile no', 'mobile number', 'telephone' => 'phone',

            'password', 'default password', 'login password' => 'password',

            'gender', 'sex' => 'gender',

            'organization', 'organisation', 'institution', 'agency', 'ministry',
            'mda' => 'organization',

            'designation', 'rank', 'position', 'job title' => 'designation',

            'employment sector', 'sector', 'employment_sector' => 'employment_sector',

            'state', 'state of origin', 'state_of_origin', 'state of_origin' => 'state_of_origin',

            'lga', 'local government', 'local government area' => 'lga',

            'employer', 'employer name', 'employer_name' => 'employer_name',

            'nationality' => 'nationality',
                'age' => 'age',

            'academic background', 'qualification', 'academic_background' => 'academic_background',

            'employment status', 'employment_status', 'self employed', 'self-employed' => 'employment_status',

            'sponsor', 'sponsor name', 'sponsor_name' => 'sponsor_name',

            'category' => 'category',

            'training location', 'location', 'training_location' => 'training_location',

            default => str_replace(' ', '_', $header),
        };
    }

    protected function value(array $row, array $columns, string $key): string
    {
        if (! isset($columns[$key])) {
            return '';
        }

        return trim((string) ($row[$columns[$key]] ?? ''));
    }
}

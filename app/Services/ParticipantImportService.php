<?php

namespace App\Services;

use App\Models\Participant;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ParticipantImportService
{
    public function import(string $filePath, int $courseId, int $batchId): array
    {
        if (!file_exists($filePath)) {
            return [
                'inserted' => 0,
                'updated' => 0,
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
                'skipped' => 0,
                'errors' => ['The uploaded sheet appears to be empty.'],
            ];
        }

        [$headerIndex, $nameCol, $regCol] = $this->detectColumns($rows);

        if ($headerIndex === null || $nameCol === null || $regCol === null) {
            return [
                'inserted' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => [
                    'Could not find valid participant columns.',
                    'Accepted headers include: full_name / participant_no or Name / reg no.',
                ],
            ];
        }

        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            if ($index <= $headerIndex) {
                continue;
            }

            $name = trim((string) ($row[$nameCol] ?? ''));
            $regNo = trim((string) ($row[$regCol] ?? ''));

            if ($name === '' || $regNo === '') {
                $skipped++;
                continue;
            }

            try {
                $participant = Participant::where('participant_no', $regNo)->first();

                if ($participant) {
                    $participant->update([
                        'course_id' => $courseId,
                        'batch_id' => $batchId,
                        'full_name' => $name,
                        'organization' => null,
                        'gender' => null,
                        'status' => 'active',
                    ]);
                    $updated++;
                } else {
                    Participant::create([
                        'user_id' => null,
                        'course_id' => $courseId,
                        'batch_id' => $batchId,
                        'participant_no' => $regNo,
                        'full_name' => $name,
                        'organization' => null,
                        'gender' => null,
                        'status' => 'active',
                    ]);
                    $inserted++;
                }
            } catch (\Throwable $e) {
                $errors[] = 'Row ' . ($index + 1) . ': ' . $e->getMessage();
            }
        }

        return compact('inserted', 'updated', 'skipped', 'errors');
    }

    protected function detectColumns(array $rows): array
    {
        $possibleNameHeaders = [
            'full_name',
            'full name',
            'name',
        ];

        $possibleRegHeaders = [
            'participant_no',
            'participant no',
            'participant number',
            'reg no',
            'reg no.',
            'registration no',
            'registration number',
            'reg number',
        ];

        foreach ($rows as $index => $row) {
            $normalized = array_map(
                fn ($value) => strtolower(trim((string) $value)),
                $row
            );

            $nameCol = null;
            $regCol = null;

            foreach ($normalized as $colIndex => $value) {
                if ($nameCol === null && in_array($value, $possibleNameHeaders, true)) {
                    $nameCol = $colIndex;
                }

                if ($regCol === null && in_array($value, $possibleRegHeaders, true)) {
                    $regCol = $colIndex;
                }
            }

            if ($nameCol !== null && $regCol !== null) {
                return [$index, $nameCol, $regCol];
            }
        }

        return [null, null, null];
    }
}

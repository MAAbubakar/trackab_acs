<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportAdvParticipants extends Command
{
    protected $signature = 'participants:import-adv {file} {course_id} {batch_id}';
    protected $description = 'Import ADV participant list from Excel into participants table';

    public function handle(): int
    {
        $file = base_path($this->argument('file'));
        $courseId = (int) $this->argument('course_id');
        $batchId = (int) $this->argument('batch_id');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (count($rows) < 2) {
            $this->error('The spreadsheet appears to be empty.');
            return self::FAILURE;
        }

        $header = array_map(fn ($v) => strtolower(trim((string) $v)), $rows[0] ?? []);

        $nameCol = array_search('full_name', $header, true);
        $regCol = array_search('participant_no', $header, true);

        if ($nameCol === false || $regCol === false) {
            $this->error('Could not find full_name and participant_no columns.');
            return self::FAILURE;
        }

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $name = trim((string) ($row[$nameCol] ?? ''));
            $regNo = trim((string) ($row[$regCol] ?? ''));

            if ($name === '' || $regNo === '') {
                $skipped++;
                continue;
            }

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
        }

        $this->info('Import completed.');
        $this->line("Inserted: {$inserted}");
        $this->line("Updated: {$updated}");
        $this->line("Skipped: {$skipped}");

        return self::SUCCESS;
    }
}

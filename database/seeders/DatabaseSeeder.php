<?php

namespace Database\Seeders;

use Database\Seeders\EvaluationFormSeeder;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EvaluationFormSeeder::class,
        ]);

$this->call([
            AdminUserSeeder::class,
        ]);
    }
}

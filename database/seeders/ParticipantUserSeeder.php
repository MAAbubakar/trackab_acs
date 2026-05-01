<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParticipantUserSeeder extends Seeder
{
    public function run(): void
    {
        $participants = Participant::whereNull('user_id')
            ->whereNotNull('email')
            ->get();

        foreach ($participants as $participant) {
            $user = User::firstOrCreate(
                ['email' => strtolower(trim($participant->email))],
                [
                    'name' => $participant->full_name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $participant->update([
                'user_id' => $user->id,
            ]);
        }
    }
}

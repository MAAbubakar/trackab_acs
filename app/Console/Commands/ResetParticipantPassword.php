<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetParticipantPassword extends Command
{
    protected $signature = 'participant:reset-password {email} {password=password}';
    protected $description = 'Reset a participant user password';

    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found.');
            return self::FAILURE;
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        $this->info('Password reset successfully.');
        return self::SUCCESS;
    }
}

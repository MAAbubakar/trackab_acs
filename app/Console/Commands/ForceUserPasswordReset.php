<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ForceUserPasswordReset extends Command
{
    protected $signature = 'user:force-password-reset {user_id}';
    protected $description = 'Require a user to change password at next login';

    public function handle(): int
    {
        $user = User::find($this->argument('user_id'));

        if (!$user) {
            $this->error('User not found.');
            return self::FAILURE;
        }

        $user->update([
            'must_change_password' => true,
        ]);

        $this->info("Password reset flag applied to {$user->name}.");
        return self::SUCCESS;
    }
}

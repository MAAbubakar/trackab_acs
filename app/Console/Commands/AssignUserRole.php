<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignUserRole extends Command
{
    protected $signature = 'user:assign-role {user_id} {role}';
    protected $description = 'Assign a role to a user';

    public function handle(): int
    {
        $user = User::find($this->argument('user_id'));
        $roleName = $this->argument('role');

        if (!$user) {
            $this->error('User not found.');
            return self::FAILURE;
        }

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error('Role not found.');
            return self::FAILURE;
        }

        $user->assignRole($roleName);

        $this->info("Assigned role '{$roleName}' to {$user->name}.");
        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AuditUserRoles extends Command
{
    protected $signature = 'audit:user-roles';
    protected $description = 'Print all users with roles and participant linkage';

    public function handle(): int
    {
        $users = User::with('participant')->orderBy('id')->get();

        if ($users->isEmpty()) {
            $this->warn('No users found.');
            return self::SUCCESS;
        }

        $rows = $users->map(function (User $user) {
            return [
                'ID' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Roles' => method_exists($user, 'getRoleNames')
                    ? ($user->getRoleNames()->join(', ') ?: '—')
                    : 'HasRoles not enabled',
                'Participant Linked' => $user->participant ? 'Yes' : 'No',
                'Participant ID' => $user->participant?->id ?? '—',
                'Participant No' => $user->participant?->participant_no ?? '—',
            ];
        })->toArray();

        $this->table(
            ['ID', 'Name', 'Email', 'Roles', 'Participant Linked', 'Participant ID', 'Participant No'],
            $rows
        );

        $adminCount = $users->filter(function (User $user) {
            return method_exists($user, 'hasRole') &&
                ($user->hasRole('super-admin') ||
                 $user->hasRole('attendance-officer') ||
                 $user->hasRole('programme-coordinator'));
        })->count();

        $participantRoleCount = $users->filter(function (User $user) {
            return method_exists($user, 'hasRole') && $user->hasRole('participant');
        })->count();

        $linkedParticipantCount = $users->filter(fn (User $user) => (bool) $user->participant)->count();

        $this->newLine();
        $this->info("Total users: {$users->count()}");
        $this->info("Admin-role users: {$adminCount}");
        $this->info("Users with participant role: {$participantRoleCount}");
        $this->info("Users linked to participant records: {$linkedParticipantCount}");

        return self::SUCCESS;
    }
}

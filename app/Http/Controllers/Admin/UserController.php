<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\User;
use App\Services\UserAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(private readonly UserAuditService $userAuditService)
    {
    }

    public function index(Request $request): View
    {
        $users = User::with(['participant', 'roles'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->role($request->role);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'in:active,inactive,locked'],
            'must_change_password' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'status' => $validated['status'],
            'must_change_password' => (bool) ($validated['must_change_password'] ?? false),
        ]);

        if (!empty($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        $this->userAuditService->log($user, 'user.created', 'User account created by admin.', [
            'roles' => $validated['roles'] ?? [],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $user->load(['participant.course', 'participant.batch', 'roles', 'audits.actor']);

        $availableParticipants = Participant::with(['course', 'batch'])
            ->whereNull('user_id')
            ->orderBy('full_name')
            ->get();

        return view('admin.users.show', compact('user', 'availableParticipants'));
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'in:active,inactive,locked'],
            'must_change_password' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
            'must_change_password' => (bool) ($validated['must_change_password'] ?? false),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }

        $user->update($updateData);
        $user->syncRoles($validated['roles'] ?? []);

        $this->userAuditService->log($user, 'user.updated', 'User account updated by admin.', [
            'roles' => $validated['roles'] ?? [],
            'status' => $validated['status'],
            'must_change_password' => (bool) ($validated['must_change_password'] ?? false),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ((int) auth()->id() === (int) $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->participant) {
            return back()->with('error', 'This user is linked to a participant record. Unlink first before deleting.');
        }

        $this->userAuditService->log($user, 'user.deleted', 'User account deleted by admin.');

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ((int) auth()->id() === (int) $user->id) {
            return back()->with('error', 'You cannot change your own account status from here.');
        }

        $next = match ($user->status) {
            'active' => 'inactive',
            'inactive' => 'active',
            'locked' => 'active',
            default => 'inactive',
        };

        $user->update(['status' => $next]);

        $this->userAuditService->log($user, 'user.status.toggled', 'User status toggled by admin.', [
            'new_status' => $next,
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function lock(User $user): RedirectResponse
    {
        if ((int) auth()->id() === (int) $user->id) {
            return back()->with('error', 'You cannot lock your own account.');
        }

        $user->update(['status' => 'locked']);

        $this->userAuditService->log($user, 'user.locked', 'User account locked by admin.');

        return back()->with('success', 'User locked successfully.');
    }

    public function linkParticipant(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'participant_id' => ['required', 'exists:participants,id'],
        ]);

        if ($user->participant) {
            return back()->with('error', 'This user is already linked to a participant.');
        }

        $participant = Participant::findOrFail($validated['participant_id']);

        if ($participant->user_id) {
            return back()->with('error', 'This participant is already linked to another user.');
        }

        $participant->update([
            'user_id' => $user->id,
        ]);

        if (!$user->hasRole('participant')) {
            $user->assignRole('participant');
        }

        $this->userAuditService->log($user, 'user.participant.linked', 'Participant linked to user.', [
            'participant_id' => $participant->id,
            'participant_no' => $participant->participant_no,
        ]);

        return back()->with('success', 'Participant linked successfully.');
    }

    public function unlinkParticipant(User $user): RedirectResponse
    {
        if (!$user->participant) {
            return back()->with('error', 'This user is not linked to any participant.');
        }

        $participant = $user->participant;

        $participant->update([
            'user_id' => null,
        ]);

        $this->userAuditService->log($user, 'user.participant.unlinked', 'Participant unlinked from user.', [
            'participant_id' => $participant->id,
            'participant_no' => $participant->participant_no,
        ]);

        return back()->with('success', 'Participant unlinked successfully.');
    }

    public function createFromParticipant(Participant $participant): RedirectResponse
    {
        if ($participant->user_id) {
            return back()->with('error', 'This participant already has a linked user account.');
        }

        $email = $participant->email ?: ('participant' . $participant->id . '@local.test');
        $baseEmail = $email;
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = preg_replace('/@/', '+' . $counter . '@', $baseEmail, 1);
            $counter++;
        }

        $temporaryPassword = Str::password(10);

        $user = User::create([
            'name' => $participant->full_name ?: ('Participant ' . $participant->id),
            'email' => $email,
            'password' => Hash::make($temporaryPassword),
            'status' => 'active',
            'must_change_password' => true,
        ]);

        $user->assignRole('participant');

        $participant->update([
            'user_id' => $user->id,
        ]);

        $this->userAuditService->log($user, 'user.created.from_participant', 'User account created from participant record.', [
            'participant_id' => $participant->id,
            'participant_no' => $participant->participant_no,
            'temporary_password' => $temporaryPassword,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Participant user created successfully. Temporary password: {$temporaryPassword}");
    }

    public function bulkCreateParticipantUsers(Request $request): RedirectResponse
    {
        $participants = Participant::whereNull('user_id')
            ->orderBy('id')
            ->limit((int) $request->integer('limit', 50))
            ->get();

        $created = 0;

        foreach ($participants as $participant) {
            $email = $participant->email ?: ('participant' . $participant->id . '@local.test');
            $baseEmail = $email;
            $counter = 1;

            while (User::where('email', $email)->exists()) {
                $email = preg_replace('/@/', '+' . $counter . '@', $baseEmail, 1);
                $counter++;
            }

            $user = User::create([
                'name' => $participant->full_name ?: ('Participant ' . $participant->id),
                'email' => $email,
                'password' => Hash::make(Str::password(10)),
                'status' => 'active',
                'must_change_password' => true,
            ]);

            $user->assignRole('participant');

            $participant->update([
                'user_id' => $user->id,
            ]);

            $this->userAuditService->log($user, 'user.bulk_created.from_participant', 'User account created in bulk from participant record.', [
                'participant_id' => $participant->id,
                'participant_no' => $participant->participant_no,
            ]);

            $created++;
        }

        return back()->with('success', "Bulk participant account creation completed. Created: {$created}");
    }

    public function sendInvitation(User $user): RedirectResponse
    {
        $this->userAuditService->log($user, 'user.invitation.sent', 'Invitation dispatch requested by admin.', [
            'email' => $user->email,
            'must_change_password' => $user->must_change_password,
        ]);

        return back()->with('success', 'Invitation recorded successfully. Configure mail delivery to send real invitations.');
    }

    public function resendReset(User $user): RedirectResponse
    {
        $user->update([
            'must_change_password' => true,
        ]);

        $this->userAuditService->log($user, 'user.reset.reissued', 'Password reset / onboarding reset reissued by admin.', [
            'email' => $user->email,
        ]);

        return back()->with('success', 'Password reset requirement reissued successfully.');
    }

    public function bulkStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'status' => ['required', 'in:active,inactive,locked'],
        ]);

        $count = 0;

        $users = User::whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            if ((int) auth()->id() === (int) $user->id) {
                continue;
            }

            $user->update([
                'status' => $validated['status'],
            ]);

            $this->userAuditService->log($user, 'user.bulk_status.updated', 'Bulk status update by admin.', [
                'new_status' => $validated['status'],
            ]);

            $count++;
        }

        return back()->with('success', "Bulk status update completed. Updated: {$count}");
    }
    public function grouped(\Illuminate\Http\Request $request): \Illuminate\View\View
    {
        $users = \App\Models\User::query()
            ->with(['roles', 'participant'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $role = $request->string('role')->toString();

                $query->whereHas('roles', fn ($q) => $q->where('name', $role));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status')->toString());
            })
            ->orderBy('name')
            ->get();

        $roles = class_exists(\App\Models\Role::class)
            ? \App\Models\Role::query()->orderBy('name')->get()
            : collect();

        return view('admin.users.grouped', compact('users', 'roles'));
    }


}

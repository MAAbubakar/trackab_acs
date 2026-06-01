<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\MessageLog;
use App\Models\Participant;
use App\Services\MessagingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = MessageLog::with(['user', 'participant'])
            ->when($request->filled('message_type'), fn ($q) => $q->where('message_type', $request->string('message_type')))
            ->when($request->filled('channel'), fn ($q) => $q->where('channel', $request->string('channel')))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $messageTypes = MessageLog::query()
            ->select('message_type')
            ->distinct()
            ->orderBy('message_type')
            ->pluck('message_type');

        $channels = MessageLog::query()
            ->select('channel')
            ->distinct()
            ->orderBy('channel')
            ->pluck('channel');

        return view('admin.messages.index', compact('logs', 'messageTypes', 'channels'));
    }

    public function create(): View
    {
        $batches = Batch::query()
            ->latest()
            ->get(['id', 'name']);

        $participants = Participant::query()
            ->orderBy('full_name')
            ->get(['id', 'participant_no', 'full_name', 'email', 'batch_id', 'user_id']);

        return view('admin.messages.create', compact('batches', 'participants'));
    }

    public function store(Request $request, MessagingService $messagingService): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_scope' => ['required', 'string', 'in:all,batch,participant'],
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'participant_id' => ['nullable', 'integer', 'exists:participants,id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $query = Participant::query()
            ->with('user')
            ->where('status', 'active');

        if ($validated['recipient_scope'] === 'batch') {
            if (empty($validated['batch_id'])) {
                return back()->withInput()->with('error', 'Please select a batch.');
            }

            $query->where('batch_id', $validated['batch_id']);
        }

        if ($validated['recipient_scope'] === 'participant') {
            if (empty($validated['participant_id'])) {
                return back()->withInput()->with('error', 'Please select a participant.');
            }

            $query->where('id', $validated['participant_id']);
        }

        $participants = $query->get();

        if ($participants->isEmpty()) {
            return back()->withInput()->with('error', 'No active participant found for the selected recipient option.');
        }

        $sent = 0;
        $skipped = 0;

        foreach ($participants as $participant) {
            if (! $participant->user) {
                $skipped++;
                continue;
            }

            $messagingService->sendParticipantReminder(
                $participant,
                $validated['subject'],
                $validated['body'],
                [
                    'recipient_scope' => $validated['recipient_scope'],
                    'batch_id' => $validated['batch_id'] ?? null,
                    'participant_id' => $validated['participant_id'] ?? null,
                    'sent_by' => auth()->id(),
                ]
            );

            $sent++;
        }

        return redirect()
            ->route('admin.messages.index')
            ->with('success', "Message sent successfully to {$sent} participant(s). Skipped {$skipped} participant(s) without linked user accounts.");
    }
}

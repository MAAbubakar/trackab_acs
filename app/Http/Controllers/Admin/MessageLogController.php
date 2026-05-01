<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageLog;
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

        $messageTypes = MessageLog::query()->select('message_type')->distinct()->orderBy('message_type')->pluck('message_type');
        $channels = MessageLog::query()->select('channel')->distinct()->orderBy('channel')->pluck('channel');

        return view('admin.messages.index', compact('logs', 'messageTypes', 'channels'));
    }
}

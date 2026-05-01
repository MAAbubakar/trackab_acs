<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParticipantNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = collect();

        if (method_exists($request->user(), 'notifications')) {
            $notifications = $request->user()->notifications()
                ->latest('id')
                ->paginate(20);
        }

        return view('participant.notifications.index', compact('notifications'));
    }
}

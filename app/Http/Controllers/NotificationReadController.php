<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationReadController extends Controller
{
    public function markAndRedirect(Request $request, string $notificationId): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user, 403);

        $notification = $user->notifications()->where('id', $notificationId)->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $payload = $notification->data ?? [];
        $url = $payload['url'] ?? null;

        if (!$url) {
            return redirect()->back()->with('success', 'Notification marked as read.');
        }

        return redirect()->to($url);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user, 403);

        if (method_exists($user, 'unreadNotifications')) {
            $user->unreadNotifications->markAsRead();
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth('customer')->user()
            ->notifications()
            ->paginate(10);

        return view('customer.notifications.index', compact('notifications'));
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead()
    {
        auth('customer')->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read');
    }

    public function preferences()
{
    $customer = auth('customer')->user();
    return view('customer.notifications.preferences', compact('customer'));
}

public function updatePreferences(Request $request)
{
    $validated = $request->validate([
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
    ]);

    auth('customer')->user()->update($validated);

    return back()->with('success', 'Notification preferences updated successfully');
}
}
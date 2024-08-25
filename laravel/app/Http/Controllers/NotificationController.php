<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications() {
        $notifications = Notification::select(['icon', 'title', 'description', 'read'])->currentUser()->orderBy('created_at', 'desc')->limit(15)->get();

        return $this->success([
            'notifications' => $notifications,
            'hasUnreadNotifications' => $notifications->where('read', false)->count() > 0,
        ]);
    }

    public function markAllAsRead(Request $request) {
        $notifications = Notification::currentUser()->where('hasAction', false)->update(['read' => true]);

        return $this->success();
    }
}

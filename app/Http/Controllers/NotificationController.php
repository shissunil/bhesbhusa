<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notifications()
    {
        $userId = Auth::user()->id;
        $notificationList = Notification::where('deleted_at', '')->where('is_read', '0')
            ->where('receiver_type', 'user')->where('receiver_id', $userId)
            ->where('created_at', '>', now()->subDays(30)->endOfDay())
            ->orderBy('id', 'DESC')->get();
        if (!empty($notificationList->toArray())) {
            foreach ($notificationList as $notification) {
                $notification->date = $notification->created_at->diffForHumans();
            }
        }
        // dd($notificationList);
        return view('front.notifications', compact('notificationList'));
    }

    public function deleteNotification(Request $request)
    {
        $userId = Auth::user()->id;

        $notificationId = $request->notification_id;

        if (isset($notificationId)) {
            $chekNotification = Notification::where('deleted_at', '')
                ->where('receiver_type', 'user')->where('receiver_id', $userId)
                ->where('is_read', '0')->find($notificationId);
            if ($chekNotification) {
                $chekNotification->is_read = '1';
                $chekNotification->save();
            }
            return redirect(route('notifications'))->with('success', 'Notification deleted.');
        } else {
            $checkNotification = Notification::where('deleted_at', '')
                ->where('receiver_type', 'user')
                ->where('receiver_id', $userId)
                ->where('is_read', '0')->get();

            if (!empty($checkNotification->toArray())) {
                $deleteNotification = Notification::where('deleted_at', '')
                    ->where('receiver_type', 'user')
                    ->where('receiver_id', $userId)
                    ->where('is_read', '0')
                    ->update(['is_read' => '1']);
            }

            return redirect(route('notifications'))->with('success', 'Notification cleared.');
        }
    }
}

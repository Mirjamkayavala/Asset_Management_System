<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asset;
use Notification;
use App\Notifications\AssetCreated;
use App\Notifications\AssetUpdated;
use App\Notifications\AssetDeleted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        // dd('Constructor called');
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user(); // Get the authenticated user
        // $notifications = Auth::user()->notifications;
        // $notifications = Auth::user()->notifications;

        $notifications = Auth::user()->notifications;

        $assets = Asset::first();

        $user->notify(new AssetCreated($assets));
        Auth::user()->notify(new AssetCreated($assets));
        
        $user->notify(new AssetUpdated($assets));
        Auth::user()->notify(new AssetUpdated($assets));

        // return view('notifications.index', compact('notifications'));
        return view('notifications.index', compact('notifications'));
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        Log::info('Deleting notification: ' . $id);
        $notification->delete();
        // dd('here');

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    public function markAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function markAsReadSingle($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        $unreadCount = auth()->user()->unreadNotifications->count();
        return response()->json(['message' => 'Notification marked as read', 'unreadCount' => $unreadCount]);
    }

    public function clearAll()
    {
        // Clear all notifications
        auth()->user()->notifications()->delete();
        return redirect()->back()->with('success', 'All notifications have been cleared.');
    }
}
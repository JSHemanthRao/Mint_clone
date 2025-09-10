<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    private function userId(): ?int
    {
        if (Auth::check()) return Auth::id();

        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $user ? $user->id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function index()
    {
        $userId = $this->userId();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(
            Notification::where('user_id', $userId)
                ->latest()
                ->limit(20)
                ->get()
        );
    }

    public function markAllRead()
    {
        $userId = $this->userId();
        Notification::where('user_id', $userId)->update(['read' => true]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}

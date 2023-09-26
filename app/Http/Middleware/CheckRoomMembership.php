<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CheckRoomMembership
{
    public function handle(Request $request, Closure $next)
    {


        $roomId = $request->route('room');
        $user = Auth::user();

        $room = $user->rooms()
            ->wherePivot('role', '<', 3)
            ->wherePivot('status', 1)
            ->find($roomId);

        if (!$room) {
            return new JsonResponse("You are not a member of this room", 403);
        }
        if (!($request->user_handling && $room->users()->where('user_id', $request->user_handling)->exists()))
            return new JsonResponse("User Handling has to be a member of the room", 403);

        return $next($request);
    }
}

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
        // dd($request->route('post'));
        $user = Auth::user();

        $room = $user->rooms()
            ->wherePivot('role', '<', 3)
            ->wherePivot('status', 1)
            ->find($roomId);

        if (!$room) {
            return new JsonResponse("You are not a member of this room", 403);
        }
        return $next($request);
    }
}

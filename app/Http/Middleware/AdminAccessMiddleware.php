<?php

namespace App\Http\Middleware;

use App\Enums\UserStatusEnum;
use App\Models\Room;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roomId = $request->route('room') ?? $request->input('room_id');

        if (!$roomId) {
            abort(422,"Provide ID of the room");
        }

        $room = Room::find($roomId);

        if (!$room) {
            abort(404);
        }

        $accesses = $room->accesses()->get();
        $userAccess = $accesses->where('user_id', Auth::id())->first();

        if (!$userAccess || !in_array($userAccess->role, [UserStatusEnum::SuperAdmin, UserStatusEnum::Admin])) {
            abort(403, 'Unauthorized');
        }
        $request->attributes->add(['user_access' => $userAccess,'accesses'=>$accesses]);
        return $next($request);
    }
}

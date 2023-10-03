<?php

namespace App\Http\Middleware;

use App\Enums\UserStatusEnum;
use App\Models\Room;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $roomId = $request->route('room') ?? $request->input('room_id');

        if (!$roomId) {
            return new JsonResponse('Provide ID of the room', 422);
        }

        $room = Room::find($roomId);

        if (!$room) {
            return new JsonResponse('Room not found', 404);
        }

        $accesses = $room->accesses()->get();
        $userAccess = $accesses->where('user_id', Auth::id())->first();

        if (!$userAccess || !in_array($userAccess->role, [UserStatusEnum::SuperAdmin, UserStatusEnum::Admin])) {
            return new JsonResponse('Unauthorized', 403);
        }
        $request->attributes->add(['user_access' => $userAccess, 'accesses' => $accesses]);
        return $next($request);
    }
}

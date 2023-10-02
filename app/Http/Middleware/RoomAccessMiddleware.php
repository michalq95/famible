<?php

namespace App\Http\Middleware;

use App\Enums\AccessStatusEnum;
use App\Enums\PostStatusEnum;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RoomAccessMiddleware
{
    public function handle($request, Closure $next)
    {

        $room = $request->route('room');
        if (!$room) {
            return new JsonResponse("Room not found", 404);
        }

        $access = $room->accesses()->where('user_id', Auth::id())->where("status", AccessStatusEnum::Accepted)->first();

        if (!$access) {
            return new JsonResponse("Unauthorized", 403);
        }

        return $next($request);
    }
}

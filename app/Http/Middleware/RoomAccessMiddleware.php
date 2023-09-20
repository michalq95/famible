<?php

namespace App\Http\Middleware;

use App\Enums\AccessStatusEnum;
use App\Enums\PostStatusEnum;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoomAccessMiddleware
{
    public function handle($request, Closure $next)
    {

        $room = $request->route('room');
        if (!$room) {
            abort(404);
        }

        $access = $room->accesses()->where('user_id', Auth::id())->where("status", AccessStatusEnum::Accepted)->first();

        if (!$access) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

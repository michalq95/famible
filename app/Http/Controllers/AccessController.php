<?php

namespace App\Http\Controllers;

use App\Enums\AccessStatusEnum;
use App\Enums\UserStatusEnum;
use App\Http\Requests\CreateAccessRequest;
use App\Http\Resources\AccessResource;
use App\Models\Access;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    public function store(CreateAccessRequest $request)
    {
        $data = $request->validated();
        $room = Room::findOrFail($request->room_id);
        $accesses = $room->accesses()->get();
        if ($accesses->where('user_id',$request->user_id)->first()){
            return new JsonResponse("User already has access to this room.", 403);
        }
        $adder_access = $accesses->where('user_id', Auth::user()->id)->first();
        if (!($adder_access && ($adder_access->role == UserStatusEnum::SuperAdmin or $adder_access->role == UserStatusEnum::Admin))) {
            return new JsonResponse("Unauthorized", 403);
        }
        if ($data['role'] === UserStatusEnum::SuperAdmin->value)
            return new JsonResponse("You cant add another superadmins.", 403);
        if ($adder_access->role != UserStatusEnum::SuperAdmin && $data['role'] === UserStatusEnum::Admin->value)
            return new JsonResponse("Only SuperAdmin can add Admins.", 403); 

        $data['status'] = AccessStatusEnum::Pending;
        $result = Access::create($data);
        return new AccessResource($result);
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\AccessStatusEnum;
use App\Enums\UserStatusEnum;
use App\Http\Requests\CreateAccessRequest;
use App\Http\Requests\UpdateAccessRequest;
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

        $accesses = $request->get('accesses');
        $adder_access = $request->get('user_access');
        if (!($adder_access && ($adder_access->role == UserStatusEnum::SuperAdmin or $adder_access->role == UserStatusEnum::Admin))) {
            return new JsonResponse("Unauthorized", 403);
        }
        if ($accesses->where('user_id', $request->user_id)->first()) {
            return new JsonResponse("User already has access to this room.", 403);
        }

        $data = $request->validated();
        if ($data['role'] === UserStatusEnum::SuperAdmin->value)
            return new JsonResponse("You cant add another superadmins.", 403);
        if ($adder_access->role != UserStatusEnum::SuperAdmin && $data['role'] === UserStatusEnum::Admin->value)
            return new JsonResponse("Only SuperAdmin can add Admins.", 403);

        $data['status'] = AccessStatusEnum::Pending;
        $result = Access::create($data);
        return new AccessResource($result);
    }

    public function update(UpdateAccessRequest $request, Access $access)
    {

        $userId = Auth::user()->id;
        $admin = Access::whereHas('room.accesses', function ($query) use ($userId) {
            $query->where('user_id', $userId)->whereIn('role', [0, 1]);
        })->first();

        $normalUser = $userId == $access->user_id;

        if (!($normalUser or $admin)) {
            return new JsonResponse("Unauthorized", 403);
        }

        $role = $access->role;

        $status = $access->status;
        if ($admin) {
            if ($request->role && $admin->role == UserStatusEnum::SuperAdmin) {
                if (
                    $request->role === UserStatusEnum::Admin ||
                    $request->role === UserStatusEnum::Member ||
                    $request->role === UserStatusEnum::Guest
                ) {
                    $role = $request->role;
                }
            } else if ($request->role && $admin->role == UserStatusEnum::Admin) {
                if (
                    $request->role === UserStatusEnum::Member ||
                    $request->role === UserStatusEnum::Guest
                ) {
                    $role = $request->role;
                }
            }
            if ($request->status && $request->status == 3) $status = 3;
        }

        if ($normalUser) {
            if ($request->status && $request->status !== 0) {
                $status = $request->status;
            }
        }
        // dd($status, $role);
        $access->update(["status" => $status, "role" => $role]);

        return $access;
    }
}

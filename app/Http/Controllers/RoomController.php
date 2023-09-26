<?php

namespace App\Http\Controllers;

use App\Enums\AccessStatusEnum;


use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\AccessResource;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = RoomResource::collection(Room::orderBy('created_at', 'desc')->paginate(25));
        return $rooms;
    }

    public function personal_index()
    {
        // return  AccessResource::collection(Auth::user()->accepted_accesses());
        $user = Auth::user()->load('accepted_accesses');
        return AccessResource::collection($user->accepted_accesses);
    }


    public function personal_to_accept()
    {

        $access = Auth::user()->accesses()->whereIn("status", [AccessStatusEnum::Pending, AccessStatusEnum::Rejected])->orderBy("status", "desc")->get();
        return  AccessResource::collection($access);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {

        $data = $request->validated();
        $room = Room::create($data);
        if ($room && $request->hasFile('image')) {
            $image = $room->addImage($request->file('image'));
        }
        return AccessResource::collection(Auth::user()->accepted_accesses);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return new RoomResource($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image->url);

                $room->image->delete();
            }
            $image = $room->addImage($request->file('image'));
        }
        $room->update($data);
        return new RoomResource($room);
    }


    public function destroy(Room $room)
    {
        //Do middleware dac, 3 powtorzenia
        //Tu, update i store w Access Controller
        // $accesses = $room->accesses()->get();
        // $user = $accesses->where('user_id', Auth::user()->id)->first();
        // if (!($user && ($user->role == UserStatusEnum::SuperAdmin or $user->role == UserStatusEnum::Admin))) {
        //     return new JsonResponse("Unauthorized", 403);
        // }
        $room->delete();
        return new JsonResponse('Deleted room', 204);
    }
}

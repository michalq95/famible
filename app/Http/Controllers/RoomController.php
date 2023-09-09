<?php

namespace App\Http\Controllers;

use App\Enums\UserRoomEnum;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Room::orderBy('created_at', 'desc')->paginate(25));
        $rooms = RoomResource::collection(Room::orderBy('created_at', 'desc')->paginate(25));
        return $rooms;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();
        $room = Room::create($data);

        return new RoomResource($room);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

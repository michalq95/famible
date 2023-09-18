<?php

namespace App\Providers;

use App\Models\Access;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class RoomProvider extends ServiceProvider{

    public function register()
    {
        //
    }

    public function boot()
    {
        Room::created(function ($room) {
            $user = Auth::user(); 
            Access::create(['role' => 0,'status'=>1,'user_id'=>$user->id,'room_id'=>$room->id]);

        });
    }


}
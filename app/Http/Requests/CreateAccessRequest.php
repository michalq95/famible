<?php

namespace App\Http\Requests;

use App\Enums\InvitationStatusEnum;
use App\Enums\UserRoomEnum;
use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateAccessRequest extends FormRequest
{
    public function authorize()
    {
        $room = Room::findOrFail($this->room_id);
        $access = $room->accesses()->where('user_id', Auth::user()->id)->first();
        return ($access && $access->role==UserRoomEnum::Owner);
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'status'=> 'required'
        ];
    }
}
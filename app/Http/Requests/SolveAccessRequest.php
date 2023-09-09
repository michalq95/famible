<?php

namespace App\Http\Requests;

use App\Enums\InvitationStatusEnum;
use App\Enums\UserRoomEnum;
use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class SolveAccessRequest extends FormRequest
{
    public function authorize()
    {
        $room = Room::findOrFail($this->room_id);
        return Auth::user()->rooms->contains($room) && $room->pivot->role === UserRoomEnum::Owner;

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
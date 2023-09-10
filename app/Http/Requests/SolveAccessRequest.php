<?php

namespace App\Http\Requests;

use App\Enums\AccessStatusEnum;
use App\Enums\UserStatusEnum;
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
        $access = $room->accesses()->where('user_id', Auth::user()->id)->first();
        return ($access && ($access->role==UserStatusEnum::SuperAdmin or $access->role==UserStatusEnum::Admin));

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
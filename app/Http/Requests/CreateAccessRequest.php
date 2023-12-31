<?php

namespace App\Http\Requests;

use App\Enums\AccessStatusEnum;
use App\Enums\UserStatusEnum;
use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class CreateAccessRequest extends FormRequest
{
    public function authorize()
    {
        //doing it in controller
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'role'=> [new Enum(AccessStatusEnum::class)]
        ];
    }
}
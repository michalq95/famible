<?php

namespace App\Http\Requests;

use App\Enums\AccessStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            'title' => 'required|string|max:255',
            // 'status' => [new Enum(PostStatusEnum::class), "nullable"],
            'status' => 'in:0,1,2,3|nullable',

            'expire_date' => 'nullable|date',
            'description' => 'nullable|string',

            'image' => 'nullable|mimes:jpeg,png,jpg,gif,dimensions:max_width=200,max_height=300,size:2000',
            'user_handling' => 'nullable|exists:users,id',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePostRequest extends FormRequest
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
        // dd($this);
        return [
            'title' => 'required|string|max:255',
            // 'status' => [new Enum(PostStatusEnum::class), "nullable"],
            'status' => 'in:0,1,2,3|nullable',
            'expire_date' => 'nullable|date',
            'description' => 'nullable|string',
            'user_handling' => 'exists:users,id',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = strtoupper(request()->method());
        if ($method == 'POST') {
            return [
                'username' => 'required|string|min:3|max:30|unique:user',
                'password' => 'required|string|min:4|max:20',
                'email' => 'required|string|email|unique:user',
                'nama' => 'required|string|min:3|max:120',
                'role' => 'required|in:0,1',
            ];
        } else if ($method == 'PUT' || $method == 'PATCH') {
            return [
                'password' => 'sometimes|required|string|min:4|max:20',
                'nama' => 'sometimes|required|string|min:3|max:120',
            ];
        } else {
            return [];
        }
    }
}
